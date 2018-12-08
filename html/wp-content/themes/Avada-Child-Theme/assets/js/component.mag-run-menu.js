( function(){
		
    var app = angular.module( 'WebsiteApp' );
    
    
    app.directive( 'magRunMenuComponent', [ '$http', '$timeout', '$rootScope', '$injector', '$q', 
    function( $http , $timeout, $rootScope, $injector , $q ){ 
        return {
            "scope" : { userId : "=" ,  userStats : "="},
            'template' : function(){},
            'link' : function($scope, $element, $attrs ){
                jQuery('[mag-run-menu-component]').appendTo('.fusion-main-menu').addClass( 'logged-in');
            },
            'controller' : function( $scope, $element , $attrs ){ 
                var $ctrl = $scope.$ctrl  = this
                
                $ctrl.$onInit = function(){
                    $ctrl.success = false;
                    $ctrl.error = false;
                    $ctrl.userStats = $scope.userStats;
                    $ctrl.run_data = {
                        run_date : new Date(),
                        distance : 0,
                        user : $scope.userStats.id,
                        user_id : $scope.userStats.id
                    }
                }
                $ctrl.addRun = function(){
                    $ctrl.posting = true;
                    $http.post( '/?mag::post-my-run', $ctrl.run_data).then(
                        function( response ){
                            $ctrl.posting = false;
                            if( response.data.success ){
                                $ctrl.userStats.distance_total = response.data.user_data.distance_total;
                                $ctrl.userStats.runs_total = response.data.user_data.runs_total;
                                $ctrl.success = true;
                            }else{
                                $ctrl.error = true;
                            }
                        },
                        function(){
                            $ctrl.error = true;
                            $ctrl.posting = false;
                        }
                    )
                }
                
            }

                
              
        }
    }]);
    
    
    
    
    
})();