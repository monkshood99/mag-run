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
		if( trying_to( 'mag::get-log-runs' , 'request' )) static::get_my_log();
		if( trying_to( 'mag::get-total-runs' , 'request' )) static::get_total_runs(false);
		if( trying_to( 'mag::get-total-distance' , 'request' )) static::get_totals(false, return_if( $_REQUEST, 'unit'));
		if( trying_to( 'mag::post-to-facebook' , 'request' )) static::post_to_facebook();

		
	}

	public static function enqueue(){
		if( !is_admin()){
			add_action( 'wp_enqueue_scripts' , function(){
				wp_enqueue_script( 'angular',  TMPL_PATH . '/bower_components/angular/angular.min.js' , null , null, true  );
				wp_enqueue_script( 'ng-app',  TMPL_PATH . '/assets/js/ng-app.js' , null , null, true  );
				wp_enqueue_script( 'mag-run-service',  TMPL_PATH . '/assets/js/service.mag-run.js' , null , null, true  );
				wp_enqueue_style( 'mag-app-screen',  TMPL_PATH . '/assets/css/screen.css' );
			} , 99 , 99 );
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
		add_shortcode( 'mag-totals', ['Atw_app', 'get_totals_shortcode']  );	
	
	}


	public static function getUserStats($internal = true ){
		$user = wp_get_current_user(  );
		$userStats = false;
		if( $user ){
			$totals = Atw_app::get_user_totals( $user->ID );
			$totals->id = $user->ID;
			$userStats = $totals;
		}
		$userStats->user = $user->data;
		if( $internal ) return $userStats;
		else return_json( compact( 'userStats') );
	}

	public static function get_total_runs( $local = true ){
		global $wpdb;
		$total = $wpdb->get_var( "SELECT count( id ) as 'total' from `{$wpdb->base_prefix}pods_run`;");
		if( $local ) return $total;
		else return_json( compact( 'total'));
	}

	public static function get_totals( $local = true , $unit = 'mi' ){
		global $wpdb;
		$total = $wpdb->get_row( "SELECT COUNT(`id`) as `runs_total` ,FORMAT(SUM(kilometers),1) as `km_total` , FORMAT(SUM(miles),1) as `mi_total`  from `{$wpdb->base_prefix}pods_run`;");
		if( $local ) return $total;
		else return_json( compact( 'total'));
	}
	
	public static function get_user_totals( $user_id = false ){
		$default_totals = ( object) [ 'runs_total'=> 0, 'km_total' => 0, 'mi_total'=> 0 ];

		// get all runs
		$where=" `user`.`id` = '{$user_id}' OR `user_id` = '{$user_id}' ";
		$runs 	= pods( 'run')->find( [ 'select'=> 'COUNT(`t`.`id`) as `runs_total` ,FORMAT(SUM(kilometers),1) as `km_total` , FORMAT(SUM(miles),1) as `mi_total`' , 'where'=>  $where , 'limit'=> '-1' ]  )->data();
		$data = return_if( $runs, 0 , $default_totals );
		$data->km_total = is_numeric( $data->km_total ) ? $data->km_total : 0  ;
		$data->mi_total = is_numeric( $data->mi_total ) ? $data->mi_total : 0   ;
		

		// get runs of this week 
		$day = date('w');
		$start = date('Y-m-d', strtotime('-'.$day.' days'));
		$end = date('Y-m-d', strtotime('+'.(6-$day).' days'));
		$where = "`run_date` >= '{$start}' AND `run_date` <= '{$end}' AND ( `user`.`id` = '{$user_id}' OR `user_id` = '{$user_id}') ";
		$this_week 	= pods( 'run')->find( [ 'select'=> 'COUNT(`t`.`id`) as `runs_total` ,FORMAT(SUM(kilometers),1) as `km_total` , FORMAT(SUM(miles),1) as `mi_total` ' , 'where'=>  $where , 'limit'=> '-1' ]  )->data();
		$this_week = return_if( $this_week, 0  , $default_totals  );
		$this_week->km_total = is_numeric( $this_week->km_total ) ? $this_week->km_total : 0   ;
		$this_week->mi_total = is_numeric( $this_week->mi_total ) ? $this_week->mi_total : 0   ;
		
		// get runs of this year 
		$start = date('Y-m-d', strtotime('1/01'));
		$end = date('Y-m-d', strtotime('12/31'));
		$where = "`run_date` >= '{$start}' AND `run_date` <= '{$end}' AND ( `user`.`id` = '{$user_id}' OR `user_id` = '{$user_id}') ";
		$this_year 	= pods( 'run')->find( [ 'select'=> 'COUNT(`t`.`id`) as `runs_total` ,FORMAT(SUM(kilometers),1) as `km_total` , FORMAT(SUM(miles),1) as `mi_total` ' , 'where'=>  $where , 'limit'=> '-1' ]  )->data();
		$this_year = return_if( $this_year, 0  , $default_totals  );
		$this_year->km_total = is_numeric( $this_year->km_total ) ? $this_year->km_total : 0  ; 
		$this_year->mi_total = is_numeric( $this_year->mi_total ) ? $this_year->mi_total : 0  ;
		
		$data->all_time = json_decode( json_encode( $data )) ;
		$data->this_week = $this_week;
		$data->this_year = $this_year;
		
		return $data;
	}

	public static function get_total_runs_shortcode( $atts ) {
		$totals = static::get_totals( true );
		return $totals->runs_total;
	}

	public static function get_total_distance_shortcode( $atts ) {
		$unit = return_if( $atts, 'unit', 'mi'); 
		$totals = static::get_totals( true , $unit  );
		if( $unit == 'mi') return $totals->mi_total;
		if( $unit == 'km') return $totals->km_total;
	}


	public static function get_totals_shortcode( $atts ) {
		$unit = return_if( $atts, 'unit');
		$totals = static::get_totals( true  );
		if( $unit == 'mi') return $totals->mi_total;
		if( $unit == 'km') return $totals->km_total;
		if( $unit == 'runs') return $totals->runs_total;
	}


	
	public static function post_my_run(){
		$data = mx_POST();
		$success = false;
		$errors = [];
		if( !$user_id = return_if( $data, 'user_id' ))$errors[]= 'Invalid User';
		if( empty( $errors )){
			$data->run_date = date( 'Y-m-d' , strtotime( $data->run_date ));

			if( return_if( $data, 'unit') == 'mi') {
				$data->miles = $data->distance;
				$data->kilometers = $data->distance * 1.60934;	
			}
			if( return_if( $data, 'unit' == 'km')){
				$data->miles = $data->distance;
				$data->kilometers = $data->distance * .621371;
			}
			$data->pace_km = 0;
			$data->pace_mi = 0;
			if( return_if( $data, 'minutes')){
				$data->pace_km = $data->minutes / $data->kilometers;
				$data->pace_mi = $data->minutes / $data->miles;
			}
		
			$success = pods('run')->save( ( array ) $data );
			if( $success ){
				$new_run = [
					'title' => $data->distance . ' ' .$data->unit,
					'start' => date( 'Y-m-d 00:00:00', strtotime( $data->run_date)) ,
					'end' => date( 'Y-m-d 11:59:59', strtotime( $data->run_date)) 
				];
			}
			$userStats = static::get_user_totals( $user_id );
		}
		return_json ( compact( 'save_data' , 'success' , 'data' , 'new_run' , 'userStats'));
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
		ini_set( 'display_errors' , 'on');
			date_default_timezone_set('America/Denver');
			global $wpdb;
			$success 	= false;
			$data 		= mx_POST();
			$start = return_if( $data, 'start' );
			$end = return_if( $data , 'end' );
			$where= '';
			if( $start && $end )
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
					$e->iso = date(DATE_ISO8601, strtotime($e->start));
				}
			}else{
				$events = [];
			}
			$date_totals = ( object ) [];
			foreach( $events as $event  ){
				$date = date( 'Y-m-d' , strtotime( $event->start ));
				if( !property_exists( $date_totals  , $date )){
					$date_totals->$date= ( object ) [ 'miles' => 0 , 'kilometers' => 0 ];
				}
				 $date_totals->$date->miles += number_format($event->miles , 2);
				 $date_totals->$date->kilometers += number_format( $event->kilometers , 2 ) ;
			}
			return_json ( compact( 'events', 'success' , 'date_totals' ) );
			
	}
	
		/**
		 * get_events function.
		 * 
		 * @access public
		 * @static
		 * @return void
		 */
		public static function get_my_log( ){
			ini_set( 'display_errors' , 'on');
				date_default_timezone_set('America/Denver');
				global $wpdb;
				$success 	= false;
				$data 		= mx_POST();
				$start = return_if( $data, 'start' );
				$end = return_if( $data , 'end' );
				$where= '';
				if( $start && $end )
					$where = "`run_date` >= '{$start}' AND `run_date` <= '{$end}' ";
				if( $user_id = return_if( $data, 'user_id') ) {
					$where.="AND ( `user`.`id` = '{$data->user_id}' OR `user_id` = '{$user_id}') ";
				}
				$events 	= pods( 'run')->find( [ 'where'=>  $where , 'limit'=> '-1'  , 'orderby' => ' `run_date` DESC ']  )->data();
				// if there were events continue;
				if( $events ){
					$success = true;
					foreach( $events as &$e ){
						$e->title = $e->distance . $e->unit;
						$e->start = date( 'Y-m-d 00:00:00', strtotime( $e->run_date)) ;
						$e->end = date( 'Y-m-d 11:59:59', strtotime( $e->run_date)) ;
						$e->iso = date(DATE_ISO8601, strtotime($e->start));
					}
				}else{
					$events = [];
				}
				$date_totals = ( object ) [];
				foreach( $events as $event  ){
					$date = date( 'Y-m-d' , strtotime( $event->start ));
					if( !property_exists( $date_totals  , $date )){
						$date_totals->$date= ( object ) [ 'miles' => 0 , 'kilometers' => 0 ];
					}
					 $date_totals->$date->miles += number_format($event->miles , 2);
					 $date_totals->$date->kilometers += number_format( $event->kilometers , 2 ) ;
				}
				return_json ( compact( 'events', 'success' , 'date_totals' ) );
				
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

	public static function post_to_facebook(){
		require_once ATW_DIR . 'lib/Facebook/Facebook.php';

		$fb = new \Facebook\Facebook([
		  'app_id' => '257135764955242',
		  'app_secret' => 'EAAZAAkZCe8OCYBAJzzlwEZCLmwsWTJWn8igVnH9LtdjZAGSaFu0I7WJYmZB69DZClHXRzx8XZA0ZBDR5EfPV1gB1PeSwRu6P2iSlP9EZCyRSITsKpbcuqseHorJPysEkmQ7MWtnHdxNiMdD9fXZCntczsGMdhY7ZABBaj3P1lPoSzRka35sMqSHbNXvZAeUQO7IhWqIZD',
		  'default_graph_version' => 'v2.10',
		  //'default_access_token' => '{access-token}', // optional
		]);
		
		// Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
		//   $helper = $fb->getRedirectLoginHelper();
		//   $helper = $fb->getJavaScriptHelper();
		//   $helper = $fb->getCanvasHelper();
		//   $helper = $fb->getPageTabHelper();
		
		try {
		  // Get the \Facebook\GraphNodes\GraphUser object for the current user.
		  // If you provided a 'default_access_token', the '{access-token}' is optional.
		  $response = $fb->get('/me', '{access-token}');
		} catch(\Facebook\Exceptions\FacebookResponseException $e) {
		  // When Graph returns an error
		  echo 'Graph returned an error: ' . $e->getMessage();
		  exit;
		} catch(\Facebook\Exceptions\FacebookSDKException $e) {
		  // When validation fails or other local issues
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  exit;
		}
		
		$me = $response->getGraphUser();
		echo 'Logged in as ' . $me->getName();
		
	}
	
	
}