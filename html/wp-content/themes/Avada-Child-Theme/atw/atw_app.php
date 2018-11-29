<?php 
	
// after init 
add_action( 'init', ['Atw_app', 'init'] , 2, 100 );
	
	
add_action('pods_api_pre_save_pod_item_run', array('Atw_app', 'pods_api_pre_save_pod_item_run'),999, 3); 
add_action('pods_api_pre_save_pod_item_user', array('Atw_app', 'pods_api_pre_save_pod_item_user'),999, 3); 
	
	
	
class Atw_app{
	
	
	public static function init(){
		if( trying_to( 'mag::post-my-run' , 'request' )) static::post_my_run();
		if( trying_to( 'mag::get-my-runs' , 'request' )) static::get_my_runs();
		
	}

	
	public static function post_my_run(){
		$data = mx_POST();
		$success = false;
		$errors = [];
		if( !return_if( $data, 'user_id' ))$errors[]= 'Invalid User';
		if( empty( $errors )){
			$data->run_date = date( 'Y-m-d' , strtotime( $data->run_date ));
			$success = pods('run')->save( $data );
		}
		return_json ( compact( 'save_data' , 'success' ));
	}

	
	public static function pods_api_pre_save_pod_item_run($pieces, $is_new_item, $id){
/*
		// skip this if importing;
		$location = array(
			'address'		=> return_if($pieces['fields']['address'], 'value'),
			'city'			=> return_if($pieces['fields']['city'],'value'),
			'state'			=> return_if($pieces['fields']['state'], 'value'),
			'zip_code'	=> return_if($pieces['fields']['zip_code'], 'value'),
			'country'		=> return_if($pieces['fields']['country'], 'value'),
		);
	
		$results = static::geocode_address( $location , false );
	
		$pieces['fields']['latitude']['value'] = return_if($results, 'lat');
		$pieces['fields']['longitude']['value'] = return_if($results, 'lng');
			
*/
		return $pieces;
	}
	
		

	public static function pods_api_pre_save_pod_item_user($pieces, $is_new_item, $id){
/*
		// skip this if importing;
		$location = array(
			'address'		=> return_if($pieces['fields']['address'], 'value'),
			'city'			=> return_if($pieces['fields']['city'],'value'),
			'state'			=> return_if($pieces['fields']['state'], 'value'),
			'zip_code'	=> return_if($pieces['fields']['zip_code'], 'value'),
			'country'		=> return_if($pieces['fields']['country'], 'value'),
		);
	
		$results = static::geocode_address( $location , false );
	
		$pieces['fields']['latitude']['value'] = return_if($results, 'lat');
		$pieces['fields']['longitude']['value'] = return_if($results, 'lng');
*/
			
		return $pieces;
	}
	
				
	/**
		 * get_events function.
		 * 
		 * @access public
		 * @static
		 * @return void
		 */
	public static function get_my_runs( ){
			date_default_timezone_set('America/Denver');
			global $wpdb;
			$success 	= false;
			$data 		= mx_POST();
			$start = return_if( $data, 'start' );
			$end = return_if( $data , 'end' );
			$where = "`run_date` >= '{$start}' AND `run_date` <= '{$end}' ";
			if( $user_id = return_if( $data, 'user_id') ) {
				$where.="AND ( `user`.`id` = '{$data->user_id}' OR `user_id` = '{$user_id}') ";
			}
			$events 	= pods( 'run')->find( [ 'where'=>  $where , 'limit'=> '-1' ]  )->data();
			// if there were events continue;
			if( $events ){
				$success = true;
				foreach( $events as &$e ){
					$e->title = $e->distance . $e->unit;
					$e->start = date( 'Y-m-d 00:00:00', strtotime( $e->run_date)) ;
					$e->end = date( 'Y-m-d 11:59:59', strtotime( $e->run_date)) ;
				}
			}else{
				$events = [];
			}
			return_json ( compact( 'events', 'success' ) );
			
		}
	
		

	
	
}