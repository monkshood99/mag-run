<div class = 'container mb-4' ng-show="$ctrl.currentView == 'challenges'">


	<div class = 'view-switcher  pb-3'>
		<a class = "{{$ctrl.currentChallengeView == 'community' ? 'active' : ''}}" ng-click="$ctrl.toggleChallengeView( 'community' )"  >Community</a>
		<a class = "{{$ctrl.currentChallengeView == 'you' ? 'active' : ''}}"  ng-click="$ctrl.toggleChallengeView( 'you' )" >You</a>
	</div>


    <div ng-show="$ctrl.currentChallengeView == 'community'">
        <h4>Challenges</h4>
        <hr/>
        <div class = 'challenge-row py-2' ng-repeat="challenge in $ctrl.communityChallenges">
            <h3 class = 'challenge-row__header'>{{challenge.label}} <a class = 'question'>?</a></h3>
            <div class ='challenge-row__labels d-flex'>
                <div class = 'challenge-row__label mr-1'>
                    <div class = 'label-title'> Progress</div>
                    <div class = 'label-title'> {{challenge.progress}} mi</div>
                </div>

                <div class = 'challenge-row__label pl-1'>
                    <div class = 'label-title'> Goal Distance</div>
                    <div class = 'label-title'> {{challenge.goal | number}} mi</div>
                </div>
            </div>
            <div class = 'd-flex align-items-baseline justify-content-space-between challenge-row__progress'>
                <div class = 'icon {{challenge.icon_start}}'></div>
                <div class = 'progress-bar'><div class =  'progress' style="width:{{  challenge.progressPercent  }}%"></div></div>
                <div class = 'icon  {{challenge.icon_end}}'></div>
            </div>
        </div>
        <!-- // challenge row --> 

        <div class = 'community-totals'>
            <h5 class = 'mg-h5' >TOTALS</h5>
            <hr/>
            <div class ='stats-row d-flex justify-content-space-between'>
                <div class = 'stats__label mr-1'>
                    <div class = 'label-title'> Miles</div>
                    <div class = 'label-value'> {{$ctrl.communityData.distance | number}} mi</div>
                </div>

                <div class = 'stats__label pl-1'>
                    <div class = 'label-title'> Total Runs</div>
                    <div class = 'label-value'> {{$ctrl.communityData.runs | number}}</div>
                </div>
                <div class = 'stats__label pl-1 mr-1'>
                    <div class = 'label-title'> Members</div>
                    <div class = 'label-value'> {{$ctrl.communityData.members | number}} mi</div>
                </div>
            </div>

            <div class = 'challenge-map'>
                <img src="<?= TMPL_PATH . '/assets/img/map.svg';?>" alt="Magnolia Running Community Map" />
            </div>
        </div>


    </div>

    <div ng-show="$ctrl.currentChallengeView == 'you'">
        <div class = 'current-challenge'>
            <h4>Challenges</h4>
            <hr/>
            <span ng-if="$ctrl.currentGoal.type == 'week'">
                <h3 class = 'mg-h3'>Run {{$ctrl.currentGoal.value }} Days per Week</h3>
            </span>
            <span ng-if="$ctrl.currentGoal.type == 'miles'">
                <h3 class = 'mg-h3' >Run {{( $ctrl.currentGoal.value /  52   ) | number:2 }} Miles per Week</h3>
            </span>
        </div>

        <div class = 'challenge-streak'>
            <h4>Challenge Streak</h4>
            <hr/>
            <div class ='stats-row d-flex'>
                <div class = 'stats__label mr-1'>
                    <div class = 'label-title'>Current Streak</div>
                    <div class = 'label-value'>5 Weeks</div>
                </div>
                <div class = 'stats__label pl-1'>
                    <div class = 'label-title'> Longest Streak</div>
                    <div class = 'label-value'> 8 Weeks</div>
                </div>
            </div>
        </div>

        <div class = 'challenge-streak'>
            <h4>Stats</h4>
            <hr/>
            <h3>Year-To-Date</h3>
            <div class ='stats-row d-flex  mb-2 '>
                <div class = 'stats__label mr-1 '>
                    <div class = 'label-title'>Total Runs</div>
                    <div class = 'label-value'>{{ $ctrl.MRS.userStats.this_year.runs_total }}</div>
                </div>
                <div class = 'stats__label pl-1'>
                    <div class = 'label-title'> Total Miles</div>
                    <div class = 'label-value'>{{$ctrl.MRS.userStats.this_year.mi_total}} mi</div>
                </div>
            </div>
            <div class ='stats-row d-flex'>
                <div class = 'stats__label mr-1'>
                    <div class = 'label-title'>Longest Run</div>
                    <div class = 'label-value'>{{$ctrl.MRS.userStats.this_year.longest_run}} mi</div>
                </div>
                <div class = 'stats__label pl-1'>
                    <div class = 'label-title'> Fastest Pace</div>
                    <div class = 'label-value'> 9:55 mi</div>
                </div>
            </div>
        </div>

        <!-- <div class="swiper-container">
            <div class="swiper-wrapper">
            <div class="swiper-slide">Slide 1</div>
            <div class="swiper-slide">Slide 2</div>
            <div class="swiper-slide">Slide 3</div>
            <div class="swiper-slide">Slide 4</div>
            <div class="swiper-slide">Slide 5</div>
            <div class="swiper-slide">Slide 6</div>
            <div class="swiper-slide">Slide 7</div>
            <div class="swiper-slide">Slide 8</div>
            <div class="swiper-slide">Slide 9</div>
            <div class="swiper-slide">Slide 10</div>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>  

    </div> -->




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
