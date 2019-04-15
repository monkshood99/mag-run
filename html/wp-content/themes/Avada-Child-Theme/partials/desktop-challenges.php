<div class = 'container mb-4'>

    <div class="row">
        <div class="col-md-6 offset-md-3 text-center ">
            <div class = 'beetle beetle-circle bkg-yellow mx-auto '><span class="icon-beetle"></span></div>
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
