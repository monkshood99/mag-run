( function(){
    var app = angular.module( 'WebsiteApp' );

    app.service( "MagRunService", [ '$q', '$http','$timeout', function( $q , $http , $timeout ){
        var $s = this;

        $s.userStats = {}
        $s.userMeta = {};


        
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

        $s.startEdit = function( run ){
            jQuery('#modal-run-added').modal('hide');
            jQuery('#run-edit-modal').modal('show');
            $s.edit_run = run;
            $s.edit_run.run_date = moment( run.run_date ).toDate();
            $s.edit_run.distance = parseFloat( run.distance );
            $s.edit_run.minutes = parseInt( run.minutes );
            $s.edit_run.seconds = parseInt( run.seconds );
            $timeout();
        }


        $s.saveEdit = function( $run_data ){
            var defer = $q.defer();
            $s.posting = true;
            $run_data.unit = 'mi';
            $run_data.user_id = $s.userStats.id;
            console.log( $run_data );
            $http.post( '/?mag::edit-my-run', $run_data ).then(
                function( response ){
                    console.log( response )
                    $s.posting = false;
                    if( response.data.success ){
                        $s.userStats = response.data.userStats;
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

        $s.confirmDelete = function( $run ){
            $s.confirmingDelete = $run;
        }
        $s.cancelDelete = function( $run ){
            $s.confirmingDelete = false;
        }



        $s.deleteRun = function( $run_data ){
            var defer = $q.defer();
            $s.posting = true;
            $run_data.unit = 'mi';
            $s.deleting = $s.confirmingDelete;
            $http.post( '/?mag::delete-my-run', $run_data ).then(
                function( response ){
                    $s.confirmingDelete = false;
                    $s.deleting = false;
                    $s.posting = false;
                    if( response.data.success ){
                        $s.userStats = response.data.userStats;
                        $s.success = true;
                        $timeout( function() { $s.success = false }, 350 );
                        jQuery('#modal-run-added').modal('hide');
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

        $s.getUserStats = function(){

        }

        $s.getLogRuns = function( $params ){
            var defer = $q.defer();
            $http.post( '/?mag::get-log-runs', $params ).then( function( response ){
                if( response.data.success )
                    defer.resolve( response.data.events )
                else defer.reject( response.data )
            })
            return defer.promise;
        }

        $s.updateGoal = function( $goal   ){
            $s.userStats.goal = $goal;
        } 

        $s.postToFb = function( $run ){
            var $post = {
                method: 'share_open_graph',
                action_type: 'og.shares',
                action_properties: JSON.stringify({
                    object : {
                        'og:url': "https://magnoliarunning.com", // your url to share
                        'og:title': 'I ran ' +  $run.distance + ' miles today',
                        'og:site_name':'Magnolia Running',
                        'og:description':$run.comment,
                        'og:image': 'https://magnoliarunning.com/wp-content/themes/Avada-Child-Theme/assets/img/run-more.jpg',
                        'og:image:width':'1038',//size of image in pixel
                        'og:image:height':'353'
                    }
                    })
                };
            console.log( $post );
            FB.ui( $post , function(response){ 
                console.log("response is ",response);
            });

        }


        return $s;
    }]);

})()