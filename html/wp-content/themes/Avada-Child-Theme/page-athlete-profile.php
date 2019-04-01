<?php 
	/*
	Template Name:Member Profile Page
	*/
	get_header(); 
	wp_enqueue_script( 'moment',  TMPL_PATH . '/bower_components/moment/moment.js' , null , null  );
	wp_enqueue_script( 'fullcalendar-script',  TMPL_PATH . '/bower_components/fullcalendar/dist/fullcalendar.js' , null , null  );
	wp_enqueue_style( 'fullcalendar-style', TMPL_PATH . '/bower_components/fullcalendar/dist/fullcalendar.min.css');
	wp_enqueue_script( 'ng-run-tracker',  TMPL_PATH .'/assets/js/component.member-profile.js' , null , '24', true  );
	wp_enqueue_style( 'boostrap-grid' , TMPL_PATH . "/bower_components/bootstrap4-grid-only/dist/css/bootstrap-grid.min.css" );
// 	wp_enqueue_script( 'vue',  TMPL_PATH . '/bower_components/vue/dist/vue.min.js' , null , null, true  );
// 	wp_enqueue_script( 'member-page',  TMPL_PATH .'/assets/js/vue.member-profile.js' , null , null, true  );


	$userStats = Atw_app::getUserStats();
	$goal_options= Atw_app::getGoalOptions()
?>


<?php the_content();?>


<div class = "loading-container"  >
	<div class="loading">
		<span class="load-1"></span>
		<span class="load-2"></span>
		<span class="load-3"></span>
		<span class="load-4"></span>
	</div>
	<h1>Loading </h1>
</div> 


<div  athlete-calendar target="#calendar" user-stats='<?= json_encode_attr( $userStats);?>' goal-options='<?= json_encode_attr($goal_options);?>' >

	<div class = "app-container  bootstrap-wrapper full-width {{$ctrl.ready ? 'ready' : ''}}"  ng-cloak>
		<div class = 'container mb-4'>

			<div class="row">
				<div class="col-md-6 offset-md-3 text-center ">
					<div class = 'beetle beetle-circle bkg-yellow mx-auto '><span class="icon-beetle"></span></div>
					<?php $user_meta = get_user_meta($current_user->id);?>
						<h1 class = 'mg-h1'><?= $user_meta['first_name'][0] . ' ' .$user_meta['last_name'][0] ;?></h1>
					<!-- <h4 class = mg-h4><i class="glyphicon fa-map-marker fas text-yellow"></i>Boulder, Co</h4> -->
				</div>
			</div>
			<div class = 'row text-center'>
				<div class = 'col-sm-4'>
					<div class = 'mg-display-1' ng-cloak >{{$ctrl.MRS.userStats[$ctrl.runs_total_time.value].runs_total}}</div>
					<div class="mg-h2 text-upper">Total Runs</div>
					<h4 class = 'mg-h4' ng-cloak >
						{{$ctrl.runs_total_time.label}} 
						<span class = 'fa fas fa-caret-{{changing_runs_total_time ? "up" : "down"}}' ng-click="changing_runs_total_time = changing_runs_total_time ? false : true " ></span>
					</h4>
					<div class = 'toggle-drop' ng-show="changing_runs_total_time" ng-cloak >
						<button type = 'button' class = 'time-toggle' ng-repeat="o in $ctrl.run_time_options" ng-click="$ctrl.change_period( o )">{{o.label}}.</button>
					</div>
				</div>
				<div class = 'col-sm-4'>
					<div class = 'mg-display-1' ng-cloak >
						{{$ctrl.MRS.userStats[$ctrl.runs_total_time.value].mi_total}}
					</div>
					<div class="mg-h2 text-upper">Total Miles</div>
					<div class = 'relative'>
						<h4 class = 'mg-h4' ng-cloak >
							{{$ctrl.runs_total_time.label}} 
							<span class = 'fa fas fa-caret-{{changing_runs_miles_time ? "up" : "down"}}' ng-click="changing_runs_miles_time = changing_runs_miles_time ? false : true " ></span>
						</h4>
						<div class = 'toggle-drop' ng-show="changing_runs_miles_time" ng-cloak >
							<button type = 'button' class = 'time-toggle'ng-repeat="o in $ctrl.run_time_options" ng-click="$ctrl.change_period( o )">{{o.label}}.</button>
						</div>
					</div>
				</div>
				<div class = 'col-sm-4 this-week-goal {{$ctrl.goalSucceeded() ? "success" : ""}}'>
					<div class = 'success-check'>
						<span class = 'icon-check-large'></span>
					</div>
					<div class = 'success-text'>
						<div class = 'mg-display-1' ng-cloak >
							{{$ctrl.MRS.userStats.this_week[$ctrl.goalValueType]}}
						</div>
						<div class="mg-h2 text-upper" ng-cloak>{{$ctrl.goalLabel}}</div>
						<h4 class = 'mg-h4' ng-cloak >
							<span ng-if="$ctrl.currentGoal.type == 'week'">
								Shoot for {{$ctrl.currentGoal.value}}!
							</span>
							<span ng-if="$ctrl.currentGoal.type == 'miles'">
								Shoot for {{( $ctrl.currentGoal.value /  52   ) | number:2 }}!
							</span>
							<span class = 'fa fas fa-caret-{{chaging_goal ? "up" : "down"}}' ng-click="chaging_goal = chaging_goal ? false : true " ></span>
						</h4>
						<div class = 'toggle-drop' ng-show="chaging_goal" ng-cloak>
							<button type = 'button' class = 'time-toggle' ng-repeat="o in $ctrl.goalOptions" ng-if="o.option_value !== 'none'" ng-click="$ctrl.change_goal( o )">{{o.option_name}}.</button>
						</div>
					</div>
				</div>
			</div><!-- // row --> 
		</div><!-- // container --> 

		<div class = 'horizontal-section-bar'>
			<h1 class = 'horizontal-section-title text-white text-upper'>Run Tracker</h1>
			<button type = 'button' id = 'btn-add-to-home-screen' style="display:none" class ="mg-btn bkg-yellow btn ">Add The Run Tracker to Your Home Screen</button>
		</div>
		<div class = 'bkg-gray-light'>
			<div class='container '>
				<form ng-submit="$ctrl.addRun()" class = 'mb-5 '>
					<div class = 'row text-center'>
						<div class = 'col-md-4'>
							<h1 class = 'mg-h2 text-upper'>Date <sup>*</sup></h1>
							<div class="mg-h4 mb-2">What day did you run?</div>
							<input type = 'date' class = 'mg-input bkg-white'  ng-model="$ctrl.run_data.run_date" name="distance" />
						</div>
						<div class = 'col-md-4'>
							<h1 class = 'mg-h2 text-upper'>Distance <sup>*</sup> </h1>
							<div class="mg-h4 mb-2">How far did you run? (in miles)</div>
							<input type = 'number'  class = 'mg-input bkg-white  text-gray'  step="0.1" max="100" ng-model="$ctrl.run_data.distance" name="distance" />
						</div>
						<div class = 'col-md-4'>
							<h1 class = 'mg-h2 text-upper'>Time <sup>&nbsp;</sup></h1>
							<div class="mg-h4 mb-2">How long did you run?</div>
							<input type = 'hidden' class = 'mg-input bkg-white text-gray'   ng-model="$ctrl.run_data.seconds" name="seconds" />
							<mg-duration ng-model="$ctrl.run_data.seconds" ></mg-duration> 
						</div>
						<div class = 'col-md-8 offset-md-2 text-center mt-2'>
							<div class="mg-h4 mb-2 show-mobile">Comments </div>
							<textarea ng-model="$ctrl.run_data.comment" class = 'form-control mg-textarea' placeholder="Say Something about your Run!"></textarea>
						</div>
			
						<div class="col-md-6 offset-md-3 text-center mt-2">
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




		<div class = 'container calendar-container relative mt-5 {{ $ctrl.loading  ? "loading" : ""}}' >
			<div class = 'view-switcher  pb-3'>
				<a class = "{{$ctrl.currentCalView == 'month' ? 'active' : ''}}" ng-click="$ctrl.toggleCalendarView( 'month' )">Month</a>
				<a class = "{{$ctrl.currentCalView == 'list' ? 'active' : ''}}" ng-click="$ctrl.toggleCalendarView( 'list' )">List</a>
			</div>
			<div class = 'loader'><span class = "fa fa-spin fa-refresh"></span></div>
			<div id='calendar'></div>
			<div class = 'text-center mt-3'>
				<h4 class = 'mg-h4'>Week Starts on Sunday</h4>
			</div>
		</div>


		<div class = 'horizontal-section-bar d-none' >
			<h1 class = 'horizontal-section-title text-white text-upper'>Running Log</h1>
		</div>
		<div class = 'container d-none '>
			<div class = 'row run-log-header hide-mobile'>
				<div class = 'col-6 col-sm-3'> Date.</div>
				<div class = 'col-6 col-sm-3'> Miles.</div>
				<div class = 'col-6 col-sm-3'> Pace. ( min / mi ) </div>
				<div class = 'col-6 col-sm-3 '></div>
			</div>
			<div class = 'row-log-body'>
				<div class = 'row run-log-row' id="run-log-row-{{run.id}}" ng-repeat="run in $ctrl.log_runs">
					<div class = 'col-6 col-sm-3'> 
						<span class = 'hide-mobile'>{{ run.iso | date:'MMMM dd, yyyy'}}</span>
						<span class = 'show-mobile'>{{ run.iso | date:'MM.d.yy'}}</span>
					</div>
					<div class = 'col-6 col-sm-3'> 
						{{run.miles}}
						<span class='show-mobile'>miles</span>
					</div>
					<div class = 'col-6 col-sm-3'> 
						{{run.pace_mi == 0 ? 'n/a' : run.pace_mi | number : 2 | convertMinutes }} 
						<span class = 'show-mobile'>min/mi</span>
					</div>
				
					<div class = 'col-6 col-sm-3 '>
						<div ng-show="$ctrl.confirmingDelete !== run && $ctrl.deleting !== run ">
							<span class = 'icon-mg-edit clickable'  ng-click="$ctrl.startEdit( run )" ></span>
							<span class = 'fa fas fa-trash-o clickable '  ng-click="$ctrl.confirmDelete( run )" ></span>
						</div>
						<div ng-show="$ctrl.confirmingDelete == run">
							<span class = 'fa fas fa-trash-o clickable' ng-click="$ctrl.deleteRun( run )"></span>
							<span class = 'fa fas fa-times-circle-o clickable ' ng-click="$ctrl.cancelDelete()"></span>
						</div>
						<div ng-show='$ctrl.deleting == run'>
							<span class = 'fa fas fa-refresh fa-spin' ></span>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="run-edit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-body mg-modal-body">
					<div class="modal-content">
						<div class = 'container-fluid'>
							<form ng-submit="$ctrl.saveEdit( $ctrl.MRS.edit_run )">
								<input type = 'hidden' ng-model="$ctrl.MRS.edit_run.run_date"/>
								<div class="row">
									<div class="col text-right">
										<button type="button" class="close btn-modal-close" data-dismiss="modal" aria-label="Close"><span class = 'fa fa-close'></span></button/>
									</div>
								</div>
								<div class = 'row mb-2'>
									<div class = 'col'>
										<label>Date</label>
										<input type = 'date' class = 'mg-input bkg-white'  ng-model="$ctrl.MRS.edit_run.run_date" name="distance" />
									</div>
								</div>
								<div class = 'row mb-2' >
									<div class="col">
										<label>Distance</label>
										<input type = 'number'  class = 'mg-input bkg-white  text-gray'  step="0.1" ng-model="$ctrl.MRS.edit_run.distance" name="distance" />
									</div>
								</div>
								<div class = 'row mb-2'>
									<div class="col" ng-if="$ctrl.MRS.edit_run.id">
										<label>Time</label>
										<input type = 'hidden' class = 'mg-input bkg-white text-gray'   ng-model="$ctrl.MRS.edit_run.seconds" name="seconds" />
										<mg-duration ng-model="$ctrl.MRS.edit_run.seconds"  obj-id="$ctrl.MRS.edit_run.id"></mg-duration> 
									</div>
									<div class="col" ng-if="!$ctrl.MRS.edit_run.id">
										<label>Time</label>
										<input type = 'hidden' class = 'mg-input bkg-white text-gray'   ng-model="$ctrl.MRS.edit_run.seconds" name="seconds" />
										<mg-duration ng-model="$ctrl.MRS.edit_run.seconds" ></mg-duration> 
									</div>
								</div>
								<div class = 'row mb-2'>
									<div class = 'col'>
										<label></label>
										<textarea  ng-model="$ctrl.MRS.edit_run.comment" class = 'form-control mg-textarea text-left ' placeholder="Say Something about your Run!"></textarea>
									</div>
								</div>
								<div class = ''>
									<div class="text-center mt-2">
										<button type = 'submit' class = 'mg-btn bkg-yellow btn-xl'>
											<small><span ng-show="$ctrl.MRS.posting" class = 'fa-refresh fa-spin fas fa'></span></small>
											<span ng-show="$ctrl.MRS.success">Saved!</span>
											<span ng-hide="$ctrl.MRS.success || $ctrl.MRS.posting ">{{ $ctrl.MRS.edit_run.id ? 'Update!' : 'Post.'}}</span>
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
	</div>

	<div id = 'mobile-view-switch'>
		<a class = "$ctrl.currentView == 'challenges' ? 'active':''" ng-click="$ctrl.changeView( 'challenges' )" >Challenges</a>
		<a class = "$ctrl.currentView == 'add_run' ? 'active':''" ng-click="$ctrl.changeView( 'add_run' )">Add Run</a>
		<a class = "$ctrl.currentView == 'calendar' ? 'active':''" ng-click="$ctrl.changeView( 'calendar' )">Calendar</a>
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