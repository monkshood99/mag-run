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


	$userStats = Atw_app::getUserStats();
?>

<pre>These are the global values : can be used on the homepage ( Will update on refresh )</pre>
<?= apply_filters( 'the_content', "[mag-totals unit='runs'] Runs Total" );?>
<?= apply_filters( 'the_content', "[mag-totals unit='km'] Kilometers Total" );?>
<?= apply_filters( 'the_content', "[mag-totals unit='mi'] Miles Total" );?>

<?php the_content();?>
	<div class="bootstrap-wrapper" >
		<div class="container" athlete-calendar target="#calendar" user-stats='<?= json_encode_attr( $userStats);?>' >
			<div class = 'row'>
				<div class= 'col'>
					<h3>My Stats</h3>
				</div>
				<div class = 'col'>
					<h4>Total Runs: <small>{{$ctrl.MRS.userStats.runs_total ? $ctrl.MRS.userStats.runs_total : 0}}</small></h4>
				</div>
				<div class = 'col'>
					<h4>Total Distance: <small>{{$ctrl.MRS.userStats.mi_total | number:1}} mi /{{$ctrl.MRS.userStats.km_total | number:1 }} km</small></h4>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div  user-id="<?= $userStats->ID;?>">
						
						<form ng-submit="$ctrl.addRun()">
							<h3>Post My Run</h3>
							<p>
								<label>Distance</label><br/>
								<input type="number" step="0.1" ng-model="$ctrl.run_data.distance" name="distance"  />
							</p>
							<p>
								<label>Date</label><br/>
								<input type = 'date' ng-model="$ctrl.run_data.run_date" name="distance" />
							</p>
							<p>
							  <button type = 'submit' class = 'wpcf7-form-control wpcf7-submit'>
								<span ng-show="$ctrl.MRS.posting">Posting</span>
    						   	<span ng-show="$ctrl.MRS.success">Posted!</span>
							    <span ng-hide="$ctrl.MRS.success || $ctrl.MRS.posting ">Post It!</span>
							  </button>
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

<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId            : '257135764955242',
      autoLogAppEvents : true,
      xfbml            : true,
      version          : 'v2.10'
    });
    FB.AppEvents.logPageView();
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>


<?php get_footer(); ?>