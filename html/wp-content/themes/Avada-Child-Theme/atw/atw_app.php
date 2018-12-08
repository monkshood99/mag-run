<?php 


Atw_app::start();


	
class Atw_app{
	
	public static function start(){
		Atw_app::enqueue();
		Atw_app::add_actions();
		Atw_app::add_shortcodes();
	}	
	
	public static function extend_init(){
		if( trying_to( 'mag::post-my-run' , 'request' )) static::post_my_run();
		if( trying_to( 'mag::get-my-runs' , 'request' )) static::get_my_runs();
		if( trying_to( 'mag::get-total-runs' , 'request' )) static::get_total_runs(false);
		if( trying_to( 'mag::get-total-distance' , 'request' )) static::get_total_distance(false);
	}

	public static function enqueue(){
		if( !is_admin()){
			wp_enqueue_script( 'angular',  TMPL_PATH . '/bower_components/angular/angular.min.js' , null , null, true  );
			wp_enqueue_script( 'ng-app',  TMPL_PATH . '/assets/js/ng-app.js' , null , null, true  );
			wp_enqueue_style( 'mag-app-custom',  TMPL_PATH . '/assets/css/custom.css' );
		}
	}	

	public static function add_actions(){
		add_action( 'init', ['Atw_app', 'extend_init'] , 2, 100 );
		if( trying_to( 'test-menu' , 'get' )){
			add_action( 'avada_header',  ['Atw_app', 'add_menu_items'], 3, 999 );
		}
		/*
		add_action('pods_api_pre_save_pod_item_run', array('Atw_app', 'pods_api_pre_save_pod_item_run'),999, 3); 
		add_action('pods_api_pre_save_pod_item_user', array('Atw_app', 'pods_api_pre_save_pod_item_user'),999, 3); 
		*/
		
	}

	public static function add_shortcodes(){
		add_shortcode( 'total-distance', ['Atw_app', 'get_total_distance_shortcode']  );	
		add_shortcode( 'total-runs', ['Atw_app', 'get_total_runs_shortcode']  );	
	
	}


	public static function getUserStats(){
		$user = wp_get_current_user(  );
		$userStats = pods('user')->find( ['where'=> "`t`.`id` = '{$user->ID}'"])->data();
		if( $userStats = return_if( $userStats, 0 )){
			$userStats = (object )[
				'id'=> $userStats->ID,
				'distance_total'=> $userStats->distance_total,
				'runs_total'=> $userStats->runs_total,
			];
		}
		return $userStats;
	}

	public static function get_total_runs( $local = true ){
		global $wpdb;
		$total = $wpdb->get_var( "SELECT count( id ) as 'total' from `{$wpdb->base_prefix}pods_run`;");
		if( $local ) return $total;
		else return_json( compact( 'total'));
	}

	public static function get_total_distance( $local = true ){
		global $wpdb;
		$total = $wpdb->get_var( "SELECT sum( distance ) as 'total' from `{$wpdb->base_prefix}pods_run`;");
		if( $local ) return $total;
		else return_json( compact( 'total'));
	}
	
	public static function get_total_runs_shortcode( $atts ) {
		return static::get_total_runs( true );
	}

	public static function get_total_distance_shortcode( $atts ) {
		return static::get_total_distance( true );
	}

	
	
	public static function post_my_run(){
		$data = mx_POST();
		$success = false;
		$errors = [];
		if( !$user_id = return_if( $data, 'user_id' ))$errors[]= 'Invalid User';
		if( empty( $errors )){
			$data->run_date = date( 'Y-m-d' , strtotime( $data->run_date ));
			$success = pods('run')->save( $data );
			if( $success ){
				$user = pods('user')->find( ['where'=> "`t`.`id` = '{$user_id}'"])->data();
				if( $user = return_if( $user , 0 )  ){
					$user_data = [
						'id' => $user_id,
						'runs_total' => $user->runs_total += 1,
						'distance_total' => $user->distance_total += $data->distance
					];
					pods('user')->save( $user_data );
				}
				$new_run = [
					'title' => $data->distance . ' mi',
					'start' => date( 'Y-m-d 00:00:00', strtotime( $data->run_date)) ,
					'end' => date( 'Y-m-d 11:59:59', strtotime( $data->run_date)) 
				];
			}
		}
		return_json ( compact( 'save_data' , 'success' , 'user_data' , 'new_run'));
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
	
	public static function add_menu_items(){
		wp_enqueue_script( 'mag-run-menu-component',  TMPL_PATH .'/assets/js/component.mag-run-menu.js' , null , null, true  );
		echo Loader::partial( 'partials/component-mag-run-menu');
	}
		

	public static function get_var( $key ){
		return return_if( 
			[
				'link_login' =>"/mp-login",
				'link_register' =>"/join-now",
				'link_account' =>"/mp-account"
			], 
		$key );
	}

	
	
}