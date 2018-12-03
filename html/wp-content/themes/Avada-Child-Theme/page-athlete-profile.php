<?php 
	/*
	Template Name:Member Profile Page
	*/
	get_header(); 
	wp_enqueue_script( 'angular',  TMPL_PATH . '/bower_components/angular/angular.min.js' , null , null, true  );
	wp_enqueue_script( 'ng-app',  TMPL_PATH . '/assets/js/ng-app.js' , null , null, true  );
	wp_enqueue_script( 'moment',  TMPL_PATH . '/bower_components/moment/min/moment.min.js' , null , null, true  );
	wp_enqueue_script( 'fullcalendar-script',  TMPL_PATH . '/bower_components/fullcalendar/dist/fullcalendar.min.js' , null , null, true  );
	wp_enqueue_style( 'fullcalendar-style', TMPL_PATH . '/bower_components/fullcalendar/dist/fullcalendar.min.css');
	wp_enqueue_script( 'ng-athlete-profile',  TMPL_PATH .'/assets/js/component.member-profile.js' , null , null, true  );
	wp_enqueue_style( 'boostrap-grid' , TMPL_PATH . "/bower_components/bootstrap4-grid-only/dist/css/bootstrap-grid.min.css" );
// 	wp_enqueue_script( 'vue',  TMPL_PATH . '/bower_components/vue/dist/vue.min.js' , null , null, true  );
// 	wp_enqueue_script( 'member-page',  TMPL_PATH .'/assets/js/vue.member-profile.js' , null , null, true  );


	$user = wp_get_current_user(  );
	$userStats = pods('user')->find( ['where'=> "`t`.`id` = '{$user->ID}'"])->data();
	if( $userStats = return_if( $userStats, 0 )){
		$userStats = (object )[
			'id'=> $userStats->ID,
			'distance_total'=> $userStats->distance_total,
			'runs_total'=> $userStats->runs_total,
		];
	}
?>

<pre>These are the global values : can be used on the homepage ( Will update on refresh )</pre>
<?= apply_filters( 'the_content', "[total-runs] Total Runs" );?>
<?= apply_filters( 'the_content', "[total-distance] Miles Total" );?>

<?php the_content();?>
	<div class="bootstrap-wrapper" ng-app="WebsiteApp">
		<div class="container" athlete-calendar target="#calendar" user-stats='<?= json_encode_attr( $userStats);?>' >
			<div class = 'row'>
				<div class= 'col'>
					<h3>My Stats</h3>
				</div>
				<div class = 'col'>
					<h4>Total Runs: <small>{{$ctrl.userStats.runs_total ? $ctrl.userStats.runs_total : 0}}</small></h4>
				</div>
				<div class = 'col'>
					<h4>Total Distance: <small>{{$ctrl.userStats.distance_total ? $ctrl.userStats.distance_total : 0 }} mi</small></h4>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div  user-id="<?= $user->ID;?>">
						
						<form ng-submit="$ctrl.addRun()">
							<h3>Post My Run</h3>
							<p>
								<label>Distance</label><br/>
								<input type = 'number' ng-model="$ctrl.run_data.distance" name="distance" />
							</p>
							<p>
								<label>Date</label><br/>
								<input type = 'date' ng-model="$ctrl.run_data.run_date" name="distance" />
							</p>
							<p>
							  <button type = 'submit' class = 'wpcf7-form-control wpcf7-submit'>Post It!</button>
							</p>
						</form>
					</div>
				</div>
				<div class="col-md-8">
					<div id='calendar'></div>
				</div>
			</div>
		</div>
	</div>

	
<script>
	var $page_data = {
		"today" : "<?= date( 'Y-m-d' , strtotime( 'today' ) ) ;?>" 
	}	
</script>
<?php get_footer(); ?>