<?php 
	/*
	Template Name:Member Profile Page
	*/
	get_header(); 
	wp_enqueue_script( 'moment',  TMPL_PATH . '/bower_components/moment/moment.js' , null , null  );
	wp_enqueue_script( 'fullcalendar-script',  TMPL_PATH . '/bower_components/fullcalendar/dist/fullcalendar.js' , null , null  );
	wp_enqueue_style( 'fullcalendar-style', TMPL_PATH . '/bower_components/fullcalendar/dist/fullcalendar.min.css');
	wp_enqueue_script( 'ng-athlete-profile',  TMPL_PATH .'/assets/js/component.member-profile.js' , null , null, true  );
	wp_enqueue_style( 'boostrap-grid' , TMPL_PATH . "/bower_components/bootstrap4-grid-only/dist/css/bootstrap-grid.min.css" );
// 	wp_enqueue_script( 'vue',  TMPL_PATH . '/bower_components/vue/dist/vue.min.js' , null , null, true  );
// 	wp_enqueue_script( 'member-page',  TMPL_PATH .'/assets/js/vue.member-profile.js' , null , null, true  );


	$userStats = Atw_app::getUserStats();
?>


<div class="bootstrap-wrapper full-width" athlete-calendar target="#calendar" user-stats='<?= json_encode_attr( $userStats);?>' >
	<div class = 'container mb-4'>
		<div class="row">
			<div class="col-6 offset-3 text-center ">
				<div class = 'beetle beetle-circle bkg-yellow mx-auto '><span class="icon-beetle"></span></div>
				<h1 class = 'mg-h1'><?= $userStats->user->display_name;?></h1>
				<!-- <h4 class = mg-h4><i class="glyphicon fa-map-marker fas text-yellow"></i>Boulder, Co</h4> -->
			</div>
		</div>
		<div class = 'row text-center'>
			<div class = 'col'>
				<h1 class = 'mg-display-1' ng-cloak >{{$ctrl.MRS.userStats[$ctrl.runs_total_time.value].runs_total}}</h1>
				<div class="mg-h2 text-upper">Total Runs</div>
				<h4 class = 'mg-h4' ng-cloak >
					{{$ctrl.runs_total_time.label}} 
					<span class = 'fa fas fa-caret-{{changing_runs_total_time ? "up" : "down"}}' ng-click="changing_runs_total_time = changing_runs_total_time ? false : true " ></span>
				</h4>
				<div class = 'toggle-drop' ng-show="changing_runs_total_time">
					<button type = 'button' class = 'time-toggle' ng-repeat="o in $ctrl.run_time_options" ng-click="$ctrl.runs_total_time = o">{{o.label}}.</button>
				</div>
			</div>
			<div class = 'col'>
				<h1 class = 'mg-display-1' ng-cloak >{{$ctrl.MRS.userStats[$ctrl.runs_total_time.value].mi_total}}</h1>
				<div class="mg-h2 text-upper">Total Miles</div>
				<div class = 'relative'>
					<h4 class = 'mg-h4' ng-cloak >
						{{$ctrl.runs_total_time.label}} 
						<span class = 'fa fas fa-caret-{{changing_runs_miles_time ? "up" : "down"}}' ng-click="changing_runs_miles_time = changing_runs_miles_time ? false : true " ></span>
					</h4>
					<div class = 'toggle-drop' ng-show="changing_runs_miles_time">
						<button type = 'button' class = 'time-toggle'ng-repeat="o in $ctrl.run_time_options" ng-click="$ctrl.runs_total_time = o">{{o.label}}.</button>
					</div>
				</div>
			</div>
			<div class = 'col this-week-goal {{ $ctrl.MRS.userStats.this_week.runs_total >= 4 ? "success" : ""}}'>
				<div class = 'success-check'>
					<span class = 'icon-check-large'></span>
				</div>
				<div class = 'success-text'>
					<h1 class = 'mg-display-1' ng-cloak >{{$ctrl.MRS.userStats.this_week.runs_total}}</h1>
					<div class="mg-h2 text-upper">Runs This Week</div>
					<h4 class="mg-h4">Shoot for 4!</h4>
				</div>
			</div>
		</div><!-- // row --> 
	</div><!-- // container --> 

	<div class = 'horizontal-section-bar'>
		<h1 class = 'horizontal-section-title text-white text-upper'>Run Tracker</h1>
	</div>
	<div class = 'bkg-gray-light'>
		<div class='container '>
			<form ng-submit="$ctrl.addRun()" class = 'mb-5 '>
				<div class = 'row text-center'>
					<div class = 'col'>
						<h1 class = 'mg-h2 text-upper'>Date <sup>*</sup></h1>
						<div class="mg-h4 mb-2">What day did you run?</div>
						<input type = 'date' class = 'mg-input bkg-white'  ng-model="$ctrl.run_data.run_date" name="distance" />
					</div>
					<div class = 'col'>
						<h1 class = 'mg-h2 text-upper'>Distance <sup>*</sup> </h1>
						<div class="mg-h4 mb-2">How far did you run? (in miles)</div>
						<input type = 'number'  class = 'mg-input bkg-white  text-gray'  step="0.1" ng-model="$ctrl.run_data.distance" name="distance" />
					</div>
					<div class = 'col'>
						<h1 class = 'mg-h2 text-upper'>Time <sup>&nbsp;</sup></h1>
						<div class="mg-h4 mb-2">Minutes you ran. </div>
						<input type = 'number' class = 'mg-input bkg-white text-gray'   ng-model="$ctrl.run_data.minutes" name="minutes" />
					</div>
		
					<div class="col-6 offset-3 text-center mt-2">
						<button type = 'submit' class = 'mg-btn bkg-yellow btn-xl'>
								<small><span ng-show="$ctrl.MRS.posting" class = 'fa-refresh fa-spin fas fa'></span></small>
								<span ng-show="$ctrl.MRS.success">Posted!</span>
								<span ng-hide="$ctrl.MRS.success || $ctrl.MRS.posting ">Post It!</span>
						</button>
						<h4 class = 'mg-h4'>* Required</h4>
					</div>
		
				</div>
			</form><!-- // form container --> 
				
		</div>
	</div>
	<div class = 'container mt-5'>
		<div id='calendar'></div>
		<div class = 'text-center mt-3'>
			<h4 class = 'mg-h4'>Week Starts on Sunday</h4>
		</div>
	</div>


	<div class = 'horizontal-section-bar'>
		<h1 class = 'horizontal-section-title text-white text-upper'>Running Log</h1>
	</div>
	<div class = 'container'>
		<div class = 'row run-log-header'>
			<div class = 'col-2'></div>
			<div class = 'col-3'> Date.</div>
			<div class = 'col-2'> Miles.</div>
			<div class = 'col-3'> Pace. ( min / mi ) </div>
			<div class = 'col-2'></div>
		</div>
		<div class = 'row-log-body'>
			<div class = 'row run-log-row' ng-repeat="run in $ctrl.log_runs">
				<div class = 'col-2'></div>
				<div class = 'col-3'> {{ run.iso | date:'MMMM dd, yyyy'}}</div>
				<div class = 'col-2'> {{run.miles}}</div>
				<div class = 'col-3'> {{run.pace_mi == 0 ? 'n/a' : run.pace_mi | number : 2}} /mi</div>
				<div class = 'col-2'>
					<span class = 'icon-mg-edit clickable'  ng-click="$ctrl.startEdit( run )" ></span>
				</div>
			</div>
		</div>
	</div>


	<!-- Modal -->
	<div class="modal fade" id="run-edit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<div class = 'container-fluid'>
						<form ng-submit="$ctrl.saveEdit( run )">
							<input type = 'hidden' ng-model="$ctrl.edit_run.run_date"/>
							<div class = 'row'>
								<div class = 'col'>
									<h1 class = 'mg-h2 text-upper'>Date <sup>*</sup></h1>
								</div>
								<div class = 'col'>
									<input type = 'date' class = 'mg-input bkg-white'  ng-model="$ctrl.edit_run.run_date" name="distance" />
								</div>
							</div>
							<div class = 'row' >
								<div class="col">
									<h1 class = 'mg-h2 text-upper'>Distance <sup>*</sup> </h1>
								</div>
								<div class="col">
									<input type = 'number'  class = 'mg-input bkg-white  text-gray'  step="0.1" ng-model="$ctrl.edit_run.distance" name="distance" />
								</div>
							</div>
							<div class = 'row'>
								<div class="col">
									<h1 class = 'mg-h2 text-upper'>Time <sup>&nbsp;</sup></h1>
								</div>
								<div class="col">
									<input type = 'number' class = 'mg-input bkg-white text-gray'   ng-model="$ctrl.edit_run.minutes" name="minutes" />
								</div>
							</div>
							<div class = 'row'>
								<div class="col-sm-6 offset-sm-	3 text-center mt-2">
									<button type = 'submit' class = 'mg-btn bkg-yellow btn-xl'>
										<small><span ng-show="$ctrl.MRS.posting" class = 'fa-refresh fa-spin fas fa'></span></small>
										<span ng-show="$ctrl.MRS.success">Updated!</span>
										<span ng-hide="$ctrl.MRS.success || $ctrl.MRS.posting ">Update!</span>
									</button>
									<h4 class = 'mg-h4'>* Required</h4>
								</div>		
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>



</div><!-- // athlete calendar --> 





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