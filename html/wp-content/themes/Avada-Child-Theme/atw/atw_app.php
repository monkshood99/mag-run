<?php


Atw_app::start();



class Atw_app{

	public static function start(){
		if( return_if( $_COOKIE , 'mg_timezone')) date_default_timezone_set( $_COOKIE['mg_timezone']);
		Atw_app::enqueue();
		Atw_app::add_actions();
		Atw_app::add_shortcodes();
		// prx( $_COOKIE );
	}

	public static function extend_init(){
		if( trying_to( 'mag::post-my-run' , 'request' )) static::post_my_run();
		if( trying_to( 'mag::edit-my-run' , 'request' )) static::edit_my_run();
		if( trying_to( 'mag::delete-my-run' , 'request' )) static::delete_my_run();
		if( trying_to( 'mag::get-my-runs' , 'request' )) static::get_my_runs();
		if( trying_to( 'mag::get-community-data' , 'request' )) static::get_community_data();
		if( trying_to( 'mag::get-log-runs' , 'request' )) static::get_my_log();
		if( trying_to( 'mag::get-total-runs' , 'request' )) static::get_total_runs(false);
		if( trying_to( 'mag::get-total-distance' , 'request' )) static::get_totals(false, return_if( $_REQUEST, 'unit'));
		if( trying_to( 'mag::post-to-facebook' , 'request' )) static::post_to_facebook();		
		if( trying_to( 'mg::setTZ' , 'request' )) static::setTZ();

	}

	
	public static function setTZ(){
		$success = false;
		if( $data = mx_POST() ) {
			$Dtz = new Helper_DateTimeZone(Helper_DateTimeZone::tzOffsetToName($data->timezone));
			date_default_timezone_set( $Dtz->getName() );
			setcookie("mg_timezone", $Dtz->getName() , 0 );  /* expire in 1 hour */
			$timezone = $Dtz->getName();
			$success = true;
		}
		return_json( compact( 'success' , 'timezone' ) );

		exit;		

	}

	public static function enqueue(){
		if( !is_admin()){
			add_action( 'wp_enqueue_scripts' , function(){
				wp_enqueue_script( 'angular',  TMPL_PATH . '/bower_components/angular/angular.min.js' , null , null, true  );
				wp_enqueue_script( 'ng-app',  TMPL_PATH . '/service-worker.js' , null , '3', true  );
				wp_enqueue_script( 'ng-app',  TMPL_PATH . '/assets/js/ng-app.js' , null , '1', true  );
				wp_enqueue_script( 'mag-run-service',  TMPL_PATH . '/assets/js/service.mag-run.js' , null , '4', true  );
				wp_enqueue_style( 'mag-app-screen',  TMPL_PATH . '/assets/css/screen.css' ,null, '16' );
			} , 99 , 99 );
		}
	}

	public static function add_actions(){
		add_action( 'init', ['Atw_app', 'extend_init'] , 2, 100 );
		add_action( 'wp_head', function(){ 
			echo '<link rel="manifest" href="/wp-content/themes/Avada-Child-Theme/site.webmanifest">';
		}, 3, 100 );
		
		add_action( 'avada_header',  ['Atw_app', 'add_menu_items'], 3, 999 );
		add_action( 'wp_footer', ['Atw_app', 'wp_footer'] );
		add_filter('body_class',function ($classes) {
			if (! ( is_user_logged_in() ) )$classes[] = 'logged-out';
			return $classes;
		});
		 add_action('mepr_account_nav', function( $user ){
			echo "<span class='mepr-nav-item mepr-home'><a href='/run-tracker'>Run Tracker</a></span>";
		 }, 10, 10 ); 
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

	public static function get_community_data( $internal = false ){
		global $wpdb;
		$default_totals = ( object) [ 'members'=> 0, 'mi_total'=> 0 , 'runs_total' => 0   ];
		$totals 	= pods( 'run')->find( [ 
			'select'=> 'COUNT(`t`.`id`) as `runs_total` ,SUM(miles) as `mi_total` ' , 
			]  )->data();	
		if( $totals = return_if( $totals, 0 )){
			$default_totals->mi_total = $totals->mi_total;
			$default_totals->runs_total = $totals->runs_total;
		}
		$default_totals->members = count_users()['total_users'];
		$totals = $default_totals;
		if( $internal ) return  compact( 'totals' ) ;
		return_json( compact( 'totals' ) );
	}


	public static function getUserStats($internal = true ){
		$user = wp_get_current_user(  );
		global $wpdb;
		if( $user ){
			
			$totals = Atw_app::get_user_totals( $user->ID );
			$totals->id = $user->ID;
			$userStats = $totals;
		}
		$userStats->user = $user;
		if( $internal ) return $userStats;
		else return_json( compact( 'userStats' ) );
	}
	public static function get_user_goals( $user_id ){
		$user_meta = ( object ) [];
		// $user_meta_ = $wpdb->get_results( "SELECT * FROM `{$wpdb->base_prefix}usermeta` WHERE `user_id` = '{$user_id}'");
		// foreach( $user_meta_ as $item ){
		// 	$user_meta->{$item->meta_key} = $item->meta_value;
		// }
		$goal = get_user_meta( $user_id, 'mepr_choose_your_running_challenge', true );
		$goal = explode(  '-' , $goal );

		$goal_ = ( object ) [ 'type' => 'week', 'value' => '4'];
		if( count ( $goal ) == 2 ){
			$goal_->type = $goal[0];
			$goal_->value = $goal[1];
		}
		return $goal_;
	
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
		//date_default_timezone_set ( 'America/Denver' ); 
		$default_totals = ( object) [ 'runs_total'=> 0, 'km_total' => 0, 'mi_total'=> 0 , 'longest_run'=> 0, 'fastest_pace' => 0  ];

		// get all run totals
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
		// get the longest run this year 
		$longest_run = pods( 'run')->find( [ 
			'select'=> '`miles`' , 
			'where'=>  $where , 
			'limit'=> '1',
			'orderby'=> '`miles` DESC'
		]  )->data();
		$fastest_pace = pods( 'run')->find( [ 
			'select'=> '`pace_mi`' , 
			'where'=>  $where , 
			'limit'=> '1',
			'orderby'=> '`pace_mi` DESC'
		]  )->data();
		$this_week->longest_run = return_if( $longest_run, 0 ) ?  $longest_run[0]->miles : 0;
		$this_week->fastest_pace = return_if( $fastest_pace, 0 ) ?  $fastest_pace[0]->pace_mi : 0;

		// get week data 
		$select = "CONCAT(YEAR(run_date), '/', WEEK(run_date)) AS week_name,
			GROUP_CONCAT( miles ) as all_miles,
			GROUP_CONCAT( pace_mi ) as all_paces,
			GROUP_CONCAT( run_date ) as all_dates,
			YEAR(run_date) as year,
			WEEK(run_date) as week,
			run_date,
			COUNT(`t`.`id`) as `runs_total`,
			FORMAT(SUM(kilometers),1) as `km_total`,
			SUM(miles) as `mi_total`";
		$where=" `user`.`id` = '{$user_id}' OR `user_id` = '{$user_id}' ";
		$weeks 	= pods( 'run')->find( [ 
			'select'=> $select , 
			'where'=>  $where , 
			'groupby'=>  'week_name', 
			'orderby'=> "YEAR(run_date) ASC, WEEK(run_date) ASC",
			'limit'=> '-1' ,
		] )->data();

		if( !empty( $weeks )){
			foreach( $weeks as $week ){
				$all_miles = explode( ',' , $week->all_miles );
				if( !empty( $all_miles)){
					rsort( $all_miles );
					$week->longest_run = $all_miles[0];
					unset( $week->all_miles);
				}
				$all_paces = explode( ',' , $week->all_paces );
				if( !empty( $all_paces)){
					sort( $all_paces );
					$week->fastest_pace = $all_paces[0];
					unset( $week->all_paces);
				}
				$data->weeks[$week->week_name]= $week;
			}
		}
		

		// get runs of this year
		$start = date('Y-m-d', strtotime('1/01'));
		$end = date('Y-m-d', strtotime('12/31'));
		$where = "`run_date` >= '{$start}' AND `run_date` <= '{$end}' AND ( `user`.`id` = '{$user_id}' OR `user_id` = '{$user_id}') ";
		$this_year 	= pods( 'run')->find( [ 
			'select'=> 'COUNT(`t`.`id`) as `runs_total` ,FORMAT(SUM(kilometers),1) as `km_total` , FORMAT(SUM(miles),1) as `mi_total` ' , 
			'where'=>  $where , 'limit'=> '-1' ]  )->data();

		// get the longest run this year 
		$longest_run = pods( 'run')->find( [ 
			'select'=> '`miles`' , 
			'where'=>  $where , 
			'limit'=> '1',
			'orderby'=> '`miles` DESC'
		]  )->data();
		$fastest_pace = pods( 'run')->find( [ 
			'select'=> '`pace_mi`' , 
			'where'=>  $where , 
			'limit'=> '1',
			'orderby'=> '`pace_mi` DESC'
		]  )->data();

		$this_year = return_if( $this_year, 0  , $default_totals  );
		$this_year->km_total = is_numeric( $this_year->km_total ) ? $this_year->km_total : 0  ;
		$this_year->mi_total = is_numeric( $this_year->mi_total ) ? $this_year->mi_total : 0  ;
		if( return_if( $longest_run, 0 ))
			$this_year->longest_run = $longest_run[0]->miles;
		if( return_if( $fastest_pace, 0 ))
			$this_year->fastest_pace = $fastest_pace[0]->pace_mi;
		$data->all_time = json_decode( json_encode( $data )) ;
		$data->this_week = $this_week;
		$data->this_year = $this_year;


		$data->goal = static::get_user_goals( $user_id );

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
			if( return_if( $data, 'seconds')){
				$data->pace_km = ( $data->seconds / 60 )  / $data->kilometers;
				$data->pace_mi = ( $data->seconds / 60 ) / $data->miles;
			}

			$success = pods('run')->save( ( array ) $data );
			if( $success ){
				$new_run 	= pods( 'run')->find( [
					 'where'=>  "`id` = '{$success}'" , 
					 'limit'=> '1' ]  
					)->data()[0];
			}
			$userStats = static::get_user_totals( $user_id );
		}
		return_json ( compact( 'save_data' , 'success' , 'data' , 'new_run' , 'userStats'));
	}

	public static function edit_my_run(){
		$data = mx_POST();
		$success = false;
		$errors = [];
		$user_id = wp_get_current_user()->ID;


		// if( !$user_id = return_if( $data, 'user_id' ))$errors[]= 'Invalid User';
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
			if( return_if( $data, 'seconds')){
				$data->pace_km = ( $data->seconds / 60  )  / $data->kilometers;
				$data->pace_mi = ( $data->seconds / 60 )  / $data->miles;
			}
			if( return_if( $data, 'id' )){
				$success = pods('run')->save( ( array ) $data , null , $data->id );
			}else{
				$data->user_id = $user_id;
				$data->user = $user_id;
				$success = pods('run')->save( ( array ) $data );
			}
			if( $success ){
				$new_run = [
					'title' => $data->distance . ' ' .$data->unit,
					'start' => date( 'Y-m-d 12:00:00', strtotime( $data->run_date)) ,
					'end' => date( 'Y-m-d 13:00:00', strtotime( $data->run_date))
				];
			}
			$userStats = static::get_user_totals( $user_id );
		}
		return_json ( compact( 'save_data' , 'success' , 'data' , 'new_run' , 'userStats'));
	}

	public static function delete_my_run(){
		$data = mx_POST();
		$success = false;
		$errors = [];
		if( !$user_id = return_if( $data, 'user_id' ))$errors[]= 'Invalid User';
		if( empty( $errors )){
			$success = pods('run')->delete( $data->id );
			if( $success ){
				$userStats = static::get_user_totals( $user_id );
			}
		}
		return_json ( compact(  'success' ,  'userStats'));
	}

	public static function getGoalOptions(){
		$mepr_options = get_option( 'mepr_options', true);
		$goal_options = [];
	
		foreach( $mepr_options['custom_fields'] as $field ){
			if( $field['field_key'] == 'mepr_choose_your_running_challenge'){
				foreach( $field['options'] as $o ){
					$goal = explode(  '-' , $o['option_value'] );
					if( count ( $goal ) == 2 ){
						$goal['type'] = $goal[0];
						$goal['value'] = $goal[1];
					}
					$o['goal'] = $goal;
					$goal_options[]= $o;
				}
			}
		}


		return $goal_options;
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
			//date_default_timezone_set('America/Denver');
			global $wpdb;
			$success 	= false;
			$data 		= mx_POST();
			$start = return_if( $data, 'start' );
			$end = return_if( $data , 'end' );
			$where= '';
			$where = "`run_date` >= '{$start}' AND `run_date` <= '{$end}' ";
			$user_id = return_if( $data, 'user_id');
			$where.="AND ( `user`.`id` = '{$user_id}' OR `user_id` = '{$user_id}') ";
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
				//date_default_timezone_set('America/Denver');
				global $wpdb;
				$success 	= false;
				$data 		= mx_POST();
				$start = return_if( $data, 'start' );
				$end = return_if( $data , 'end' );
				$user_id = return_if( $data, 'user_id');
				$where=" ( `user`.`id` = '{$data->user_id}' OR `user_id` = '{$user_id}') ";
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
		// wp_enqueue_script( 'mag-run-menu-component',  TMPL_PATH .'/assets/js/component.mag-run-menu.js' , null , null, true  );
		// echo Loader::partial( 'partials/component-mag-run-menu');
	}

	public static function wp_footer(){
		wp_enqueue_script( 'mg-post-my-run-modal',  TMPL_PATH .'/assets/js/component.mg-post-my-run-modal.js' , null , null, true  );
		echo Loader::partial( 'partials/component-post-run-modal');
		$timezone = return_if( $_COOKIE , 'mg_timezone' );
		if( !$timezone ){
			echo '<script type="text/javascript">
			var _d = new Date();

			var visitortime = new Date();
			var visitortimezone = -visitortime.getTimezoneOffset()/60 ;
			var offset = visitortime.getTimezoneOffset();

			document.cookie = "js_date=" + visitortime;
			document.cookie = "js_date_timezone=" + visitortimezone ;
			document.cookie = "js_date_offset=" + offset ;

			jQuery.ajax({
				url: "/?mg::setTZ"  ,
				method : "post",
				data :  { date : visitortime, timezone : visitortimezone  , offset : offset  },
				success: function(a, b, c, d){ 
					if( a.success ){
						document.cookie = "js_timezone_string=" + a.timezone ;
						//location.reload();
					}
				},
				error: function(xhr, error, msg){ 
					//console.log( xhr , error , msg );
				},
				dataType: "json"
			});				

			if ("serviceWorker" in navigator) {
			console.log("Will the service worker register?");
			navigator.serviceWorker.register("/service-worker.js")
				.then(function(reg){
				console.log("Yes, it did.");
				}).catch(function(err) {
				console.log("No. This happened:", err)
			});
			}
		</script>';
		}
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



/**
* Helps with timezones.
* @link http://us.php.net/manual/en/class.datetimezone.php
*
* @package  Date
*/
class Helper_DateTimeZone extends DateTimeZone
{
    /**
     * Converts a timezone hourly offset to its timezone's name.
     * @example $offset = -5, $isDst = 0 <=> return value = 'America/New_York'
     * 
     * @param float $offset The timezone's offset in hours.
     *                      Lowest value: -12 (Pacific/Kwajalein)
     *                      Highest value: 14 (Pacific/Kiritimati)
     * @param bool  $isDst  Is the offset for the timezone when it's in daylight
     *                      savings time?
     * 
     * @return string The name of the timezone: 'Asia/Tokyo', 'Europe/Paris', ...
     */
    final public static function tzOffsetToName($offset, $isDst = null)
    {
        if ($isDst === null)
        {
            $isDst = date('I');
        }

        $offset *= 3600;
        $zone    = timezone_name_from_abbr('', $offset, $isDst);

        if ($zone === false)
        {
            foreach (timezone_abbreviations_list() as $abbr)
            {
                foreach ($abbr as $city)
                {
                    if ((bool)$city['dst'] === (bool)$isDst &&
                        strlen($city['timezone_id']) > 0    &&
                        $city['offset'] == $offset)
                    {
                        $zone = $city['timezone_id'];
                        break;
                    }
                }

                if ($zone !== false)
                {
                    break;
                }
            }
        }
    
        return $zone;
    }
}
