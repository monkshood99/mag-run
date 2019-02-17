( function(){
		
		var eba = angular.module( 'WebsiteApp' );
		
		
		eba.directive( 'athleteCalendar', [ '$http', '$timeout', '$rootScope', '$injector', '$q', 'MagRunService', '$timeout',
		function( $http , $timeout, $rootScope, $injector , $q, MRS , $timeout ){ 
			return {
				"scope" : { userId : "=" ,  userStats : "=" , goalOptions : "=" },
				'template' : function(){},
				'link' : function($scope, $element, $attrs ){},
				'controller' : function( $scope, $element , $attrs ){ 
					var $ctrl = $scope.$ctrl  = this
					$ctrl.MRS = MRS;
					$ctrl.log_runs = [];
					$ctrl.deleting = false;
					$ctrl.confirmingDelete = false;
					$ctrl.goalOptions = $scope.goalOptions;
					$ctrl.goalLabel = 'Runs This Week';
					$ctrl.currentGoal = false;
					$ctrl.temp_goal = false
					$ctrl.ready = true;

					$ctrl.$onInit = function(){
						$timeout( function(){ 
							jQuery('.loading-container').remove();
							$ctrl.ready = true 
						} , 450)
						$ctrl.MRS.userStats = $scope.userStats;
						$ctrl.run_time_options = [
							{ 'label' : 'This Week' , 'value' : 'this_week'},
							{ 'label' : 'This Year' , 'value' : 'this_year'},
							{ 'label' : 'All Time' , 'value' : 'all_time'},
						]
						$ctrl.runs_total_time = $ctrl.run_time_options[0];
						$ctrl.run_data = {
							run_date : new Date(),
							distance : 0,
							user : $scope.userStats.id,
							user_id : $scope.userStats.id
						}
						$ctrl.edit_run = {};

						
						$ctrl.createCalendar();
						//$ctrl.getLogRuns();

						document.addEventListener('mg.reloadEvents', function( e ){ 
							$ctrl.cal.fullCalendar('refetchEvents');
							//$ctrl.getLogRuns();
						});
						$ctrl.getUserGoal();

					}
					$ctrl.getLogRuns = function(){
						// $ctrl.MRS.getLogRuns( $ctrl.run_data  ).then( 
						// 	function( runs ){
						// 		$ctrl.log_runs = runs;
						// 	},
						// 	function( response ){

						// 	}
						// );
					}

					$ctrl.getMoreLogRuns = function(){
						var $data = $ctrl.run_data;
						$data.offset = $ctrl.log_runs.length;
						$ctrl.MRS.getLogRuns( $data  ).then( 
							function( runs ){
								$ctrl.log_runs = runs;
							},
							function( response ){

							}
						);
					}
					$ctrl.change_goal = function( goal ){
						$ctrl.temp_goal = goal.goal;
						$ctrl.eventAfterAllRender();
					}
					$ctrl.getUserGoal = function(){
						$goal = false;
						if(  $ctrl.temp_goal ) $goal =  $ctrl.temp_goal
						else $goal = $ctrl.MRS.userStats.goal;

						if( $goal.type == 'week' ){
							$ctrl.goalLabel = 'Runs This Week';
							$ctrl.goalValueType = 'runs_total';
						}
						if( $goal.type == 'miles' ){
							$ctrl.goalLabel = 'Miles This Week';
							$ctrl.goalValueType = 'mi_total';
						}
						$ctrl.currentGoal = $goal;
						return $goal;
					}
					$ctrl.change_period = function( o ){
						$ctrl.runs_total_time = o
						$timeout();					
					}
					$ctrl.reset = function(){
						$timeout.cancel($ctrl.timeout);
						$ctrl.confirmed = false;
						$ctrl.success = false;
						$ctrl.response = {};
						$ctrl.submitting = false;
						$ctrl.data = { quantity : 1 }
						$ctrl.timeout = false;
					}
					$ctrl.createCalendar = function(){
	
						$ctrl.cal = jQuery( $attrs.target ).fullCalendar({
							events: $ctrl.eventSource,
							defaultView :'month',
// 							defaultView :'listWeek',
							header : { left: 'month',  center: 'prev title next' , right : 'listWeek' },
							loading: $ctrl.loading,
							eventRender: $ctrl.eventRender ,
							eventAfterAllRender: $ctrl.eventAfterAllRender ,
							// eventClick: $ctrl.eventClick,
							// dayRender: $ctrl.dayRender,
							dayClick: $ctrl.dayClick,
							// navLinks: true, // can click day/week names to navigate views
						});
						jQuery( 'body').on( 'click' , '.fc-day' , function(){
							var date = jQuery( this).data( 'date');
							if( jQuery(this).hasClass( 'has-event' )) {
								$ctrl.cal.fullCalendar('changeView', 'listDay');
								$ctrl.cal.fullCalendar('gotoDate', date );      
							}else{
								$ctrl.startAdd( date );
							}

						})
					}// create calendar

					$ctrl.eventAfterAllRender = function(){
						jQuery('.has-events').removeClass( 'has-events');
						jQuery( '.fc-day-total').remove();
						for( var $date in $ctrl.date_totals ){
							var $current = $ctrl.date_totals[$date];
							var total_div = jQuery( 
								'<div/>', 
								{
									'html' : '<h1 >'+ $current.miles +'</h1>',
									'class' : 'fc-day-total'
								}
							)
							total_div.appendTo( ".fc-day[data-date='"+$date+"']");

						}

						$ctrl.events.forEach( function( event ){
							jQuery(".fc-day[data-date='"+moment(event.start).format('YYYY-MM-DD')+"']").addClass( 'has-event')
						});

						$ctrl.processGoal();
						


					}
					$ctrl.processGoal = function(){
						$goal = $ctrl.getUserGoal();
						if( $goal.type == 'week' ){
							$ctrl.processRunsPerWeek(  $goal.value )
						}
						if( $goal.type == 'miles' ){
							$ctrl.processMilesPerWeek(  $goal.value )
						}
					}

					$ctrl.processRunsPerWeek = function( $goal ){
						
						$ctrl.week_events = {}
						$ctrl.events.forEach( function( event ){
							var start = moment( event.start ).day('Sunday');
							var start_of_week = start.format( 'YYYY-MM-DD');
							if( !$ctrl.week_events.hasOwnProperty( start_of_week ) ){
								$ctrl.week_events[start_of_week] = { count : 0 , complete : false };
							}
							$ctrl.week_events[start_of_week].count += 1;
							if( $ctrl.week_events[start_of_week].count >= $goal &&  $ctrl.week_events[start_of_week].complete  == false  ){
								jQuery(".fc-day[data-date='"+start_of_week+"']").addClass( 'has-events')
								$ctrl.week_events[start_of_week].complete = true;
								var i = 1;
								while( i < 7){
									var day = start.add( '1' , 'days').format( 'YYYY-MM-DD');
									jQuery('.fc-day[data-date="'+day+'"]').addClass( 'has-events')
									i++;
								}
							}
	
						})
					}
					
					$ctrl.processMilesPerWeek = function( $goal){

						$goal = parseFloat( ( $goal /  52  ) , 2 );
						$ctrl.week_events = {}
						$ctrl.events.forEach( function( event ){
							event.miles = parseFloat( event.miles );
							var start = moment( event.start ).day('Sunday');
							var start_of_week = start.format( 'YYYY-MM-DD');
							if( !$ctrl.week_events.hasOwnProperty( start_of_week ) ){
								$ctrl.week_events[start_of_week] = { count : 0 , complete : false , miles : 0 };
							}
							$ctrl.week_events[start_of_week].miles += event.miles;
							if( $ctrl.week_events[start_of_week].miles >= $goal &&  $ctrl.week_events[start_of_week].complete  == false  ){
								jQuery(".fc-day[data-date='"+start_of_week+"']").addClass( 'has-events')
								$ctrl.week_events[start_of_week].complete = true;
								var i = 1;
								while( i < 7){
									var day = start.add( '1' , 'days').format( 'YYYY-MM-DD');
									jQuery('.fc-day[data-date="'+day+'"]').addClass( 'has-events')
									i++;
								}
							}
	
						})
					}
					$ctrl.goalSucceeded = function(){
						var $success = false;
						if( $ctrl.currentGoal.type == 'week' ){
							if( $ctrl.MRS.userStats.this_week.runs_total >=  $ctrl.currentGoal.value  ) $success = true;
						}
						if( $ctrl.currentGoal.type == 'miles'){
							$weekGoal = parseFloat( ( $ctrl.currentGoal.value /  52 ) , 2 );
							if( $ctrl.MRS.userStats.this_week[$ctrl.goalValueType] >= $weekGoal ) $success = true; 
						}

						return $success;
					}
					
					$ctrl.addRun = function(){
						var $run_data = angular.copy( $ctrl.run_data );
						$run_data.run_date = moment($ctrl.run_data.run_date).format( 'YYYY-MM-DD');
						$ctrl.MRS.addRun( $run_data ).then( 
							function( response ){	
								if( response.success ){
									$ctrl.cal.fullCalendar('refetchEvents');
									//$ctrl.getLogRuns();
								}
							}, 
							function( response ){ }
						)
					}
					$ctrl.startEdit = function( run ){
						jQuery('#run-edit-modal').modal('show');
						$ctrl.MRS.edit_run = run;
						$ctrl.MRS.edit_run.run_date = moment( run.run_date ).toDate();
						$ctrl.MRS.edit_run.distance = parseFloat( run.distance );
						$ctrl.MRS.edit_run.seconds = parseInt( run.seconds );
						$timeout();
					}

					$ctrl.startAdd = function( date ){
						jQuery('#run-edit-modal').modal('show');
						$ctrl.MRS.edit_run = {};
						$ctrl.MRS.edit_run.run_date = moment( date  ).toDate();
						// $ctrl.MRS.edit_run.distance = parseFloat( run.distance );
						// $ctrl.MRS.edit_run.seconds = parseInt( run.seconds );
						$timeout();
					}

					$ctrl.saveEdit = function( $run ){
						$ctrl.MRS.saveEdit( $run ).then( 
							function( response ){	
								if( response.success ){
									$ctrl.cal.fullCalendar('refetchEvents');
									$ctrl.getLogRuns();
									$timeout( function(){
										jQuery('#run-edit-modal').modal('hide');
									} , 500 )
								}
							}, 
							function( response ){}
						)
					}

					$ctrl.confirmDelete = function( $run ){
						$ctrl.confirmingDelete = $run;
					}

					$ctrl.cancelDelete = function( $run ){
						$ctrl.confirmingDelete = false;
					}

					$ctrl.deleteRun = function( $run  ){
						$ctrl.deleting = $run;
						$ctrl.confirmingDelete = false;
						jQuery( '#run-log-row-' + $run.id ).fadeOut();
						$ctrl.MRS.deleteRun( $run ).then( 
							function( response ){	
								$ctrl.deleting = false;
								if( response.success ){
									$ctrl.cal.fullCalendar('refetchEvents');
									//$ctrl.getLogRuns();
								} 
							}, 
							function( response ){ }
						)
					}


					$ctrl.eventClick = function(calEvent, jsEvent, view) {
						if( view.type == 'month'){
				    		$ctrl.cal.fullCalendar('changeView', 'listDay');
						  $ctrl.cal.fullCalendar('gotoDate', calEvent.start );      
						}else{
							$ctrl.bookingEvent = calEvent;
							$timeout( function(){ $scope.$apply() })
						}
					}
			    
			    
				  $ctrl.dayRender = function( date, cell ){

					}

					$ctrl.dayClick = function(date) {
						$ctrl.cal.fullCalendar('changeView', 'listDay');
						$ctrl.cal.fullCalendar('gotoDate', date );      
					}


					
					
					/**
					 * Loading function
					 * 
					 * @var mixed
					 * @access public
					 */
					$ctrl.loading = function( isLoading, view ) {
						$ctrl.loading = isLoading;
						$timeout( function(){  })
	    		    }
					

					
					/**
					 * render the events
					 * 
					 * @var mixed
					 * @access public
					 */
					$ctrl.eventRender = function(event, element, view) {
		
						var fb_button = jQuery('<button/>', {
								type : 'button',
						    html: 'FB->',
						    click: $ctrl.postToFb
						})
						fb_button.data( {run_data : event });
						// jQuery(element.find( '.fc-list-item-title')).append( fb_button );
						jQuery( element.find( '.fc-list-item-marker')).remove();
						jQuery( element.find( '.fc-list-item-time')).remove();
						jQuery( element.find( '.fc-list-item-title')).html('');
						var run = angular.copy( event );
						delete( run.source );
						run = JSON.stringify( run ) ;
						$row = '<div class = "container-fluid" athlete-calendar-run-log-row run=\''+ run +'\'></div>'
						var $target= element.find( '.fc-list-item-title');
						var app = jQuery('[athlete-calendar]');
						angular.element(app).injector().invoke(function($compile) {
						  var $scope = angular.element(app).scope();
						  jQuery($target).append($compile($row)($scope));
						  // Finally, refresh the watch expressions in the new element
						});

					}
					
					/**
					 * function to get the events 
					 * 
					 * @var mixed
					 * @access public
					 */
					$ctrl.eventSource = function(start, end, timezone, callback) {
						var defer = $q.defer();	
						$data = { start : start, end : end , timezone : timezone  , user_id : $ctrl.run_data.user_id };
						$http.post( '/?mag::get-my-runs', $data  )
						.then( 
							function( response ){ 
								$ctrl.date_totals = {}
						    	$ctrl.start = $ctrl.cal.fullCalendar('getCalendar').view.start.format('MMM Do \'YY');
						    	$ctrl.end = $ctrl.cal.fullCalendar('getCalendar').view.start.format('MMM Do \'YY');
								if( response.data.success ){
									$ctrl.date_totals = response.data.date_totals;
									$ctrl.events = response.data.events;
									callback( response.data.events );
									$clientEvents = $ctrl.cal.fullCalendar('clientEvents');
									$timeout( function(){ $q.resolve( response ); $scope.$apply() })
								}else {
									callback([]);
								}
							}, 
							function(){  $q.reject( 'Failed to fetch events', response ); }
						);
						
					}

				}
			}
		}]);

		eba.filter( 'convertMinutes' , [ function(){
			return function( minutes , format ){
				if( typeof( minutes ) !== 'undefined' ){
					var sign = minutes < 0 ? "-" : "";
					var min = Math.floor(Math.abs(minutes));
					var sec = Math.floor((Math.abs(minutes) * 60) % 60);
					return sign + min + ":" + (sec < 10 ? "0" : "") + sec;
				}
				return minutes;
			}
		}]);

		eba.directive( 'athleteCalendarRunLogRow' , [ 'MagRunService','$timeout' ,  function( MRS , $timeout ){
			return {
				scope : { run : "=run"},
				template : '\
					<div class="row run-log-row">\
						<div class=" col-sm-3 ng-binding">\
							{{run.miles}} miles\
							<p style="margin:0"><small>{{run.comment}}</small></p>\
						</div>\
						<div class="col-sm-4 ng-binding">{{run.pace_mi == 0 ? \'n/a\' : run.pace_mi | number : 2 | convertMinutes }}  minutes per mile</div>\
						<div class="col-sm-3 text-right ">\
							<span ng-show="$ctrl.MRS.confirmingDelete !== run && $ctrl.MRS.deleting !== run"  >\
								<span class="icon-mg-edit clickable" ng-click="$ctrl.MRS.startEdit( run )"></span>\
								<span class="fa fas fa-trash-o clickable " ng-click="$ctrl.MRS.confirmDelete( run )"></span>\
							</span>\
							<span ng-show="$ctrl.MRS.confirmingDelete == run"  >\
								<span class="fa fas fa-times-circle-o clickable " ng-click="$ctrl.MRS.cancelDelete()"></span>\
								<span class="fa fas fa-trash-o clickable" ng-click="$ctrl.deleteRun( run )"></span>\
							</span>\
							<span ng-show="$ctrl.MRS.deleting == run" class = "">\
								<span class="fa fas fa-refresh fa-spin clickable"></span>\
							</span>\
							<span class="fa fa-facebook run-log-row-button  clickable " ng-click="$ctrl.MRS.postToFb( run ) "></span>\
						</div>\
					</div>\
				',
				link : function(scope, element, attrs){
				},
				controller : function( $scope , $element , $attrs ){ 
					var $ctrl = $scope.$ctrl = this;
					$ctrl.run = $scope.run;
					$ctrl.MRS = MRS;

					$ctrl.deleteRun = function( $run  ){
						$ctrl.deleting = $run;
						$ctrl.confirmingDelete = false;
						jQuery( '#run-log-row-' + $run.id ).fadeOut();
						$ctrl.MRS.deleteRun( $run ).then( 
							function( response ){	
								$ctrl.deleting = false;
								if( response.success ){
									$element.fadeOut();
									document.dispatchEvent(new CustomEvent('mg.reloadEvents' , {detail : response  } ) );
								} 
							}, 
							function( response ){ }
						)
					}

					$ctrl.startEdit = function( run ){
						jQuery('#run-edit-modal').modal('show');
						$ctrl.MRS.edit_run = run;
						$ctrl.MRS.edit_run.run_date = moment( run.run_date ).toDate();
						$ctrl.MRS.edit_run.distance = parseFloat( run.distance );
						$ctrl.MRS.edit_run.minutes = parseInt( run.minutes );
						$timeout();
					}



					$timeout();

				}
	
			}
		}]);
		
	

	eba.directive('mgDuration', ['$timeout' , function( $timeout ){
		return {
			require : 'ngModel',
			template : "<div>\
							<input class = 'mg-input bkg-white text-gray' ng-model=\"duration\" type = 'text' placeholder='HH:MM:SS'>\
						</div>",
			scope : {ngModel : '=' , objId : '='},
			link : function($scope, element, attrs, ngModel ){
				$scope.$watch('objId', function(newVal, oldVal){
					if(typeof(newVal) != 'undefined'){
						if( !isNaN(parseInt( ngModel.$viewValue ) ) ){
							$scope.duration  = seconds_to_toHHMMSS( ngModel.$viewValue );
						}
					}
				});

				$scope.$watch('duration', function(newVal, oldVal){
					if(typeof(newVal) == 'string'){
						var $seconds = hmsToSecondsOnly(newVal);
						ngModel.$setViewValue($seconds);
					}
				});

				function hmsToSecondsOnly(str) {
					var p = str.split(':'),
						s = 0, m = 1;
				
					while (p.length > 0) {
						s += m * parseInt(p.pop(), 10);
						m *= 60;
					}
				
					return s;
				}

				function seconds_to_toHHMMSS( seconds) {
					var sec_num = parseInt(seconds, 10); // don't forget the second param
					var hours   = Math.floor(sec_num / 3600);
					var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
					var seconds = sec_num - (hours * 3600) - (minutes * 60);
				
					if (hours   < 10) {hours   = "0"+hours;}
					if (minutes < 10) {minutes = "0"+minutes;}
					if (seconds < 10) {seconds = "0"+seconds;}
					return hours+':'+minutes+':'+seconds;
				}

				
			}
		}
	}])



		
})();