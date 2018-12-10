( function(){
		
    var app = angular.module( 'WebsiteApp' );
    
    
    app.directive( 'magRunMenuComponent', [ '$http', '$timeout', '$rootScope', '$injector', '$q', 'MagRunService',
    function( $http , $timeout, $rootScope, $injector , $q  , MRS){ 
        return {
            "scope" : { userId : "=" ,  userStats : "="},
            'template' : function(){},
            'link' : function($scope, $element, $attrs ){
                jQuery('[mag-run-menu-component]').appendTo('.fusion-main-menu .fusion-menu').addClass( 'logged-in');
            },
            'controller' : function( $scope, $element , $attrs ){ 
                var $ctrl = $scope.$ctrl  = this
                $ctrl.MRS = MRS;
                
                $ctrl.$onInit = function(){
                    $ctrl.success = false;
                    $ctrl.error = false;
                    $ctrl.MRS.userStats = $scope.userStats;
                    $ctrl.run_data = {
                        run_date : new Date(),
                        distance : 0,
                        user : $scope.userStats.id,
                        user_id : $scope.userStats.id
                    }
                }

                $ctrl.addRun = function(){
                    $ctrl.MRS.addRun( $ctrl.run_data ).then( 
                        function( response ){
                            console.log( response )
                        }, 
                        function( response ){
                            console.log( response )
                        }
                    )
                }
                
            }

                
              
        }
    }]);
    
    
    
    
    
})();