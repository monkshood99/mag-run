( function(){
		
		var eba = angular.module( 'WebsiteApp' );
		
		
		eba.directive( 'athleteCalendar', [ '$http', '$timeout', '$rootScope', '$injector', '$q', 'MagRunService',
		function( $http , $timeout, $rootScope, $injector , $q, MRS ){ 
			return {
				"scope" : { userId : "=" ,  userStats : "="},
				'template' : function(){},
				'link' : function($scope, $element, $attrs ){
				},
				'controller' : function( $scope, $element , $attrs ){ 
					var $ctrl = $scope.$ctrl  = this
					$ctrl.MRS = MRS;
					$ctrl.$onInit = function(){
						$ctrl.MRS.userStats = $scope.userStats;
						$ctrl.run_time_options = [
							{ 'label' : 'THis Week' , 'value' : 'this_week'},
							{ 'label' : 'THis Year' , 'value' : 'this_year'},
							{ 'label' : 'All Time' , 'value' : 'all_time'},
						]
						$ctrl.runs_total_time = $ctrl.run_time_options[0];
						$ctrl.run_data = {
							run_date : new Date(),
							distance : 0,
							user : $scope.userStats.id,
							user_id : $scope.userStats.id
						}
						$ctrl.set_run_option = function( o , k){
							console.log( o, k )
						}

/*
						$ctrl.default_options = {
							wait_time : 0,
							defaultView :'listWeek',
							header : { left: 'prev', center: 'title',right: 'today next' },
							contentHeight : 650
						}
						$ctrl.options = $.extend( $ctrl.default_options, $scope.options );
*/
					
	

	
/*

						$ctrl.reset();
*/
						
						$ctrl.createCalendar();
						
					}
					$ctrl.change_period = function(){
						$ctrl.runs_total_time = o						
					}
					
					/**
					 * reset form variables 
					 * 
					 * @var mixed
					 * @access public
					 */
					$ctrl.reset = function(){
						$timeout.cancel($ctrl.timeout);
						$ctrl.confirmed = false;
						$ctrl.success = false;
						$ctrl.response = {};
						$ctrl.submitting = false;
						$ctrl.data = { quantity : 1 }
						$ctrl.timeout = false;
					}
					
					
					
					/**
					 * Create the calendar with options
					 * 
					 * @var mixed
					 * @access public
					 */
					$ctrl.createCalendar = function(){
	
						$ctrl.cal = jQuery( $attrs.target ).fullCalendar({
							events: $ctrl.eventSource,
							defaultView :'month',
// 							defaultView :'listWeek',
							header : { left: '',  center: 'prev title next' , right : '' },
			        loading: $ctrl.eventsLoding,
					    dayClick: $ctrl.dayClick,
							eventRender: $ctrl.eventRender ,
/*
							defaultView: $ctrl.options.defaultView,
							header: $ctrl.options.header ,
							navLinks: true, // can click day/week names to navigate views
							eventLimit: true, // allow "more" link when too many events

			        contentHeight: $ctrl.options.contentHeight  ,

					    dayRender: $ctrl.dayRender,

					    eventClick: $ctrl.eventClick,

*/
						});
					}// create calendar

					
					$ctrl.addRun = function(){
						$ctrl.MRS.addRun( $ctrl.run_data ).then( 
							function( response ){	
								console.log( response  )
								if( response.success ){
									$ctrl.cal.fullCalendar('renderEvent', response.new_run, true);
								}
							}, 
							function( response ){console.log( response ) }
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
			    
			    
			    $ctrl.dayRender = function( date, cell ){}


					$ctrl.dayClick = function(date, jsEvent, view) {
			    	$ctrl.cal.fullCalendar('changeView', 'listDay');
					  $ctrl.cal.fullCalendar('gotoDate', date );      
			    }


					
					
					/**
					 * Loading function
					 * 
					 * @var mixed
					 * @access public
					 */
					$ctrl.eventsLoading = function( isLoading, view ) {
		        $ctrl.loading = isLoading;
						$timeout( function(){ $scope.$apply() })
	        }
					
					$ctrl.postToFb = function( event ){
						var data = jQuery( this ).data();
						var message = "Post This To Facebook (I ran : " +  data.run_data.distance + "mi on " + data.run_data.run_date + ")";

						var $post = {
							method: 'share_open_graph',
							action_type: 'og.shares',
							action_properties: JSON.stringify({
								object : {
									'og:url': "https://magnoliarunning.com", // your url to share
									'og:title': "I Ran Today",
									'og:site_name':'magnolia Running',
									'og:description':'I ran today',
									'og:image': 'https://magnoliarunning.com/wp-content/themes/Avada-Child-Theme/assets/img/run-more.jpg',
									'og:image:width':'1038',//size of image in pixel
									'og:image:height':'353'
								}
								})
							};
						FB.ui( $post , function(response){ 
							console.log("response is ",response);
						});


					}
					
					$ctrl.week_events = {
						
					}
					
					/**
					 * render the events
					 * 
					 * @var mixed
					 * @access public
					 */
					$ctrl.eventRender = function(event, element, view) {
						var date = moment( event.start ).format( 'YYYY-M-DD');
						var start_of_week = moment( event.start ).startOf('week').format( 'YYYY-M-DD');
						console.log( start_of_week );
						jQuery("[data-date='"+date+"']").addClass( 'has-events')
						element.addClass( 'has-event');
						var fb_button = jQuery('<button/>', {
								type : 'button',
						    html: 'FB->',
						    click: $ctrl.postToFb
						})
						fb_button.data( {run_data : event });
						jQuery(element.find( '.fc-list-item-title')).append( fb_button );
					}
					
					/**
					 * function to get the events 
					 * 
					 * @var mixed
					 * @access public
					 */
					$ctrl.eventSource = function(start, end, timezone, callback) {
						var defer = $q.defer();	
						$http.post( '/?mag::get-my-runs', { start : start, end : end , timezone : timezone  , user_id : $scope.userId })
						.then( 
							function( response ){ 
						    	$ctrl.start = $ctrl.cal.fullCalendar('getCalendar').view.start.format('MMM Do \'YY');
						    	$ctrl.end = $ctrl.cal.fullCalendar('getCalendar').view.start.format('MMM Do \'YY');
								if( response.data.success ){
// 									console.log( 'getting event source' )
									$ctrl.events = response.data.events;
									callback( response.data.events );
									$clientEvents = $ctrl.cal.fullCalendar('clientEvents');
// 									$ctrl.eventsPreRender( $clientEvents );
									$timeout( function(){ $q.resolve( response ); $scope.$apply() })
								}
							}, 
							function(){  $q.reject( 'Failed to fetch events', response ); }
						);
						
					}
					
					
/*
					$ctrl.setPriorities = function ( $events ){
						_.each( $events , function ( $e ){
							
						});
						console.log( $ctrl.priorities );
					}
*/
					
					$ctrl.eventsPreRender = function( $events ){
// 						console.log( 'pre render');
						$ctrl.priorities = {};

						_.each( $events , function( event ){
							if( event.priority > 0 ){
								if( typeof( $ctrl.priorities[event.start.format( 'YYYY-MM-DD')] ) == 'undefined' )
									$ctrl.priorities[event.start.format( 'YYYY-MM-DD')]= []
								$ctrl.priorities[event.start.format( 'YYYY-MM-DD')].push( { ID : event.ID , priority : event.priority} )
							}
						});
						_.each( $ctrl.priorities , function( $p ){
							$p.sort( function( a , b ){ return a.priority > b.priority})
							
						})							

						_.each( $events , function( event ){
							
							var $past = false;
							var exclude = false;
							var button = '';
							event.active = false;
							event.past = false;
							event.past_wait_time = false;
							event.bookable = false;
							event.full = false;
							event.unavailable = false;
	
	
							// if the event is in the past 
							if( moment().diff( event.start ) > 0 ) event.past = true;
							// if the event is before the wait time 
							else if( $ctrl.options.wait_time !== 0 && moment().diff( event.start ) > $ctrl.options.wait_time  )  event.past_wait_time = true;
							else{
								// continue 
								// if we are excluding this date from the event set 
								if( event.exclusions ){
									_.each( event.exclusions.rows, function( ex ){
										if( exclude ) return;
										var bt_start = moment(ex.start_date).format( 'YYYY-MM-DD')
										var between =  event.start.isBetween( ex.start_date, ex.end_date, moment(ex.end_date), 'days', '[]');
										var bt_date = event.start.format( 'YYYY-MM-DD')
										var bt_end = moment(ex.end_date).format( 'YYYY-MM-DD');
										exclude =  moment( bt_date ).isBetween( bt_start, bt_end, null, '[]');
									})
								}
								event.exclude = exclude;
								// if exluding 
								if( !exclude ){ 
									// if there are reservations 
									// check them and find if there are any left. 
									if( event.reservations ){
										if( typeof( event.reservations.counts[event.start.format( 'YYYY-MM-DD HH:mm:ss')]  ) !== 'undefined' )
											event.remaining = event.limit - event.reservations.counts[event.start.format( 'YYYY-MM-DD HH:mm:ss')];
									}
									// if there are remaining events 
									if( event.remaining > 0 ) event.bookable = true;
									else event.full = true;
								}
							}
						});

						_.each( $events, function( event ){
							if( $ctrl.priorities && typeof( $ctrl.priorities[event.start.format( 'YYYY-MM-DD')]) !== 'undefined' ){
								
								var $current = $ctrl.priorities[event.start.format( 'YYYY-MM-DD')];
								
								if( !event.bookable ){
									_.remove( $current, function( $c ){
										return $c.ID == event.ID;
									});
								}
/*
								if( event.bookable  && $current.indexOf( event.ID) !== 1 ){
									event.bookable = false 
									event.unavailable = true;
								}
*/
							}
						});
						
// 						console.log( $ctrl.priorities )
						
						_.each( $events, function ( event ){
							if( $ctrl.priorities && typeof( $ctrl.priorities[event.start.format( 'YYYY-MM-DD')]) !== 'undefined'  ){
								var $current = $ctrl.priorities[event.start.format( 'YYYY-MM-DD')];
								if( $current.length > 0 && event.bookable ){
									var $found = _.find( $current, function( $e ){ 
										return $e.ID == event.ID;
									});
									if( $found && $current.indexOf( $found ) !== 0  ){
										event.unavailable = true;			
										event.bookable = false;							
									}
								}	
							}
						})
						
					}
				}
			}
		}]);
		
		
		
		
		
})();