( function(){
		
		var eba = angular.module( 'WebsiteApp' );
		
		
		eba.directive( 'athleteCalendar', [ '$http', '$timeout', '$rootScope', '$injector', '$q', 
		function( $http , $timeout, $rootScope, $injector , $q ){ 
			return {
				"scope" : { userId : "=" },
				'template' : function(){},
				'link' : function($scope, $element, $attrs ){
				},
				'controller' : function( $scope, $element , $attrs ){ 
					var $ctrl = $scope.$ctrl  = this
					
					$ctrl.$onInit = function(){
						
						$ctrl.run_data = {
							run_date : new Date(),
							distance : 0,
							user : $scope.userId,
							user_id : $scope.userId
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
/*
							defaultView: $ctrl.options.defaultView,
							header: $ctrl.options.header ,
							navLinks: true, // can click day/week names to navigate views
							eventLimit: true, // allow "more" link when too many events
			        loading: $ctrl.eventsLoding,

			        contentHeight: $ctrl.options.contentHeight  ,

							eventRender: $ctrl.eventRender ,
					    dayRender: $ctrl.dayRender,

					    eventClick: $ctrl.eventClick,
					    dayClick: $ctrl.dayClick,

*/
						});
					}// create calendar
					
					$ctrl.addRun = function(){
						$http.post( '/?mag::post-my-run', $ctrl.run_data).then(
							function( response ){
								$ctrl.cal.fullCalendar( 'refetchEvents')
							},
							function(){}
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
					
					
					
					/**
					 * render the events
					 * 
					 * @var mixed
					 * @access public
					 */
					$ctrl.eventRender = function(event, element, view) {
// 						console.log( 'event render');
						if( event.exclude  ) return false; 
					
						if( event.past ){
							$(element).addClass( 'past-event' );
							button ='<button  class="btn btn-xs pull-right btn-primary book-now" disabled > Past Event  </button>';
						}
					
						if( event.past_wait_time || event.unavailable ) button ='<button  class="btn btn-xs pull-right btn-primary book-now" disabled > Unavailable  </button>';
					
						if( event.bookable ) button ='<button  data-toggle="modal" href="#myModal"  class="btn btn-xs pull-right btn-primary book-now" > '+ event.remaining +' spots Left | <span class = "label label-info">Book Now </span></button>';
					
						if( event.full ) button ='<button   disabled   class="btn btn-xs pull-right btn-primary book-now" > Event Full</button>';
					
						$(element.find( '.fc-list-item-title')).append( button );
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
								console.log( response );
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


					/**
					 * category_changed
					 * 
					 * @var mixed
					 * @access public
					 */
					$ctrl.category_changed = function(){
						$scope.options.category = [$ctrl.category ];
						$ctrl.cal.fullCalendar( 'refetchEvents').then(function(){ }) ;
					}
										
					/**
					 * Book the Event and maybe add it to the cart
					 * 
					 * @var mixed
					 * @access public
					 */
					$ctrl.bookNow = function(  ){
						$ctrl.response = {};
						$ctrl.submitting = true;
						$http.post( window.location.origin + window.location.pathname  + '/?mxebs::book_now', {'data' : $ctrl.data , 'event' : $ctrl.bookingEvent , returnUrl : window.location.href })
						.then( 
							function( response ){
								$ctrl.submitting = false;
								$ctrl.response = response.data; 
								if( $ctrl.priorities ){
									$ctrl.cal.fullCalendar( 'destroy' );
									$ctrl.createCalendar();
								}else{
									$ctrl.cal.fullCalendar( 'refetchEvents');
								}

								// if this was successful, then get the prodduct and add it to the cart. 
								if( response.data.success && $ctrl.SHOP && ( typeof( $ctrl.bookingEvent.shopify_id ) !== 'undefined'  && $ctrl.bookingEvent.shopify_id !== '' )  ){
									// get the product then add it to the cart
									$ctrl.SHOP.client.fetchProduct( $ctrl.bookingEvent.shopify_id ).then( 
										function ( product ) {
											var reservation = response.data.reservation;
											$ctrl.bookingEvent.product = product;
											var $cart_item = { variant : product.selectedVariant , quantity : 1, properties : { 'mx_type' :  'mxebs', 'd' :  reservation.booking_date , 'r' : reservation.ID , 'e' : reservation.booking_event_id } } 
											$ctrl.SHOP.add_to_cart( $cart_item  )
											$ctrl.timeout = $timeout( function(){
												$('#myModal').modal('hide');
											}, 10000 )
										}, function ( error ){
											// error 
										}
									);
									
								}else{
									$ctrl.timeout = $timeout( function(){
										$('#myModal').modal('hide');
									}, 10000 )
								}
							}, 
							function( error ){
								$ctrl.success = false;
								$ctrl.message = 'Error';
// 								$ctrl.response = response.data
							} 
						);
					}// book now

				}
			}
		}]);
		
		
		
		
		
})();