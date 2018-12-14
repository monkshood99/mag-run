( function(){
    var app = angular.module( 'WebsiteApp' );

    app.service( "MagRunService", [ '$q', '$http','$timeout', function( $q , $http , $timeout ){
        var $s = this;

        this.userStats = {}
        $s.addRun = function( $run_data ){
            var defer = $q.defer();
            $s.posting = true;
            $run_data.unit = 'mi';
            $http.post( '/?mag::post-my-run', $run_data).then(
                function( response ){
                    $s.posting = false;
                    if( response.data.success ){
                        $s.userStats = response.data.userStats;
                        // $s.userStats.km_total =  parseFloat($s.userStats.km_total) + parseFloat(response.data.data.kilometers);
                        // $s.userStats.mi_total =  parseFloat($s.userStats.mi_total) + parseFloat(response.data.data.miles);
                        // $s.userStats.runs_total = parseInt($s.userStats.runs_total) + 1; 
                        $s.success = true;
                        $timeout( function() {
                            $s.success = false;
                        }, 350 );
                    }else{
                        $s.error = true;
                    }
                    defer.resolve( response.data )
                },
                function(){
                    $s.error = true;
                    $s.posting = false;
                    defer.resolve( response );
                }
            )
            return defer.promise;
        }
        this.getUserStats = function(){

        }

        return this;
    }]);

})()