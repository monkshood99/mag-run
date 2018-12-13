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


<div class="bootstrap-wrapper full-width" athlete-calendar target="#calendar" user-stats='<?= json_encode_attr( $userStats);?>' >
	<div class = 'container'>
		<div class="row">
			<div class="col-6 offset-3 text-center ">
				<div class = 'beetle beetle-circle bkg-yellow mx-auto '><span class="icon-beetle"></span></div>
				<h1 class = 'mg-h1'>Justin Case</h1>
				<h4 class = mg-h4><i class="glyphicon fa-map-marker fas text-yellow"></i>Boulder, Co</h4>
			</div>
		</div>
		<div class = 'row text-center'>
			<div class = 'col'>
				<h1 class = 'mg-display-1'>{{$ctrl.MRS.userStats.runs_total}}</h1>
				<div class="mg-h2 text-upper">Total Runs</div>
				<h4 class = 'mg-h4'>All Time. <span class = 'fa fas fa-caret-down'></span></h4>
			</div>
			<div class = 'col'>
				<h1 class = 'mg-display-1'>{{$ctrl.MRS.userStats.mi_total}}</h1>
				<div class="mg-h2 text-upper">Total Miles</div>
				<h4 class = 'mg-h4'>All Time.</h4>
			</div>
			<div class = 'col {{ $ctrl.MRS.userStats.this_week.runs_total >= 4 ? "success" : ""}}'>
				<h1 class = 'mg-display-1'>{{$ctrl.MRS.userStats.this_week.runs_total}}</h1>
				<div class="mg-h2 text-upper">Runs This Week</div>
				<h4 class="mg-h4">Shoot for 4!</h4>
			</div>
		</div><!-- // row --> 
	</div><!-- // container --> 

	<div class = 'horizontal-section-bar'>
		<h1 class = 'horizontal-section-title text-white text-upper'>Run Tracker</h1>
	</div>
	<div class='container'>
		<form ng-submit="$ctrl.addRun()" class = 'mb-5 '>
			<div class = 'row text-center'>
				<div class = 'col'>
					<h1 class = 'mg-h2 text-uppe'>Date</h1>
					<div class="mg-h4 mb-2">What day did you run?</div>
					<input type = 'date' ng-model="$ctrl.run_data.run_date" name="distance" />
				</div>
				<div class = 'col'>
					<h1 class = 'mg-h2 text-upper'>Distance</h1>
					<div class="mg-h4 mb-2">How far did you run? (in miles)</div>
					<input type = 'number' step="0.1" ng-model="$ctrl.run_data.distance" name="distance" />
				</div>
				<div class = 'col'>
					<h1 class = 'mg-h2 text-upper'>Pace</h1>
					<div class="mg-h4 mb-2">Minutes per mile.</div>
					<input type = 'date' ng-model="$ctrl.run_data.minutes" name="minutes" />
				</div>
	
				<div class="col-6 offset-3 text-center mt-2">
					<button type = 'submit' class = 'mg-btn bkg-yellow btn-xl'>
							<small><span ng-show="$ctrl.MRS.posting" class = 'fa-refresh fa-spin fas fa'></span></small>
							<span ng-show="$ctrl.MRS.success">Posted!</span>
							<span ng-hide="$ctrl.MRS.success || $ctrl.MRS.posting ">Post It!</span>
					</button>
				</div>
	
			</div>
		</form><!-- // form container --> 
			
		<div id='calendar'></div>
	</div>
	
	<div class = 'horizontal-section-bar'>
		<h1 class = 'horizontal-section-title text-white text-upper'>Run Tracker</h1>
	</div>
</div><!-- // athlete calendar --> 
			

<h1>Old Markup </h1>

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