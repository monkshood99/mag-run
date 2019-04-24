<div class = 'container-fluid mb-4' ng-show="$ctrl.currentView == 'challenges'">


	<div class = 'view-switcher mb-1'>
		<a class = "{{$ctrl.currentChallengeView == 'community' ? 'active' : ''}}" ng-click="$ctrl.toggleChallengeView( 'community' )"  ><span class = 'mg-h5' >Community</span></a>
		<a class = "{{$ctrl.currentChallengeView == 'you' ? 'active' : ''}}"  ng-click="$ctrl.toggleChallengeView( 'you' )" ><span class = 'mg-h5'>You</span></a>
	</div>


    <div ng-show="$ctrl.currentChallengeView == 'community'">
        <h4 class = 'mg-h5 with-border'>CHALLENGES</h4>
        <div class = 'challenge-row py-1' ng-repeat="challenge in $ctrl.communityChallenges">
            <h3 class = 'challenge-row__header mg-h1-light'>{{challenge.label}} <a class = 'question'>?</a></h3>
            <div class ='challenge-row__labels d-flex'>
                <div class = 'challenge-row__label mr-1'>
                    <div class = 'label-title mg-h5'> Progress</div>
                    <div class = 'label-title h3-light'> {{challenge.progress}} mi</div>
                </div>

                <div class = 'challenge-row__label pl-1'>
                    <div class = 'label-title mg-h5'> Goal Distance</div>
                    <div class = 'label-title h3-light'> {{challenge.goal | number}} mi</div>
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
                    <div class = 'label-value'> {{$ctrl.communityData.totals.mi_total | number}} mi</div>
                </div>

                <div class = 'stats__label pl-1'>
                    <div class = 'label-title'> Total Runs</div>
                    <div class = 'label-value'> {{$ctrl.communityData.totals.runs_total | number}}</div>
                </div>
                <div class = 'stats__label pl-1 mr-1'>
                    <div class = 'label-title'> Members</div>
                    <div class = 'label-value'> {{$ctrl.communityData.totals.members | number}} mi</div>
                </div>
            </div>

            <div class = 'challenge-map'>
                <img src="<?= TMPL_PATH . '/assets/img/map.svg';?>" alt="Magnolia Running Community Map" />
            </div>
        </div>


    </div>

    <div ng-show="$ctrl.currentChallengeView == 'you'">
        <div class = 'current-challenge'>
            <h4 class = 'mg-h5 with-border'>YOUR CHALLENGE</h4>
            <span ng-if="$ctrl.currentGoal.type == 'week'">
                <h3 class = 'mg-h1-light'>Run {{$ctrl.currentGoal.value }} Days per Week</h3>
            </span>
            <span ng-if="$ctrl.currentGoal.type == 'miles'">
                <h3 class = 'mg-h1-light' >Run {{( $ctrl.currentGoal.value /  52   ) | number:2 }} Miles per Week</h3>
            </span>
        </div>

        <div class = 'row bkg-gray-light'>
            <div class = 'col py-1'>
                <div class="swiper-container week-swiper">
                    <div class="swiper-wrapper">

                        <div class="swiper-slide" ng-repeat="week in $ctrl.challengeWeeks">
                            <h4 class = "mg-h5 " >WEEK OF</h4>
                            <h3 class = 'mg-h1-light' >{{week.sunday}} -{{week.saturday}}, {{week.year}}</h3>
                            <hr/>
                            <div class = 'goal-circles ' ng-if="$ctrl.currentGoal.type == 'week'" >
                                <div class ='stats-row d-flex justify-content-space-between mb-1'>
                                    <div class = 'stats__label mr-1'>
                                        <div class = 'label-title'>Progress</div>
                                    </div>
                                 </div>
                                <div class = 'd-flex '>
                                    <div ng-repeat="i in $ctrl.currentGoal.value | goalToArray" class = "goal-circle {{week.run_data.runs_total >= i  ? 'active' : 'inactive' }}">
                                    </div>
                                </div>
                            </div>
                            <div ng-if="$ctrl.currentGoal.type == 'miles'">
                                <div class ='stats-row d-flex justify-content-space-between'>
                                    <div class = 'stats__label mr-1'>
                                    <div class = 'label-title'>Progress </div>
                                        <div class = 'label-value'>{{ week.run_data.mi_total  | number:2 }} / {{( $ctrl.currentGoal.value /  52 ) | number:2}} mi</div>
                                    </div>
                                 </div>
                            </div>
                            <hr/>
                            <div class ='stats-row d-flex justify-content-space-between'>
                                <div class = 'stats__label mr-1'>
                                    <div class = 'label-title'>Miles This Week</div>
                                    <div class = 'label-value'>{{ week.run_data.mi_total | number:2 }} miles</div>
                                </div>
                                <div class = 'stats__label pl-1'>
                                    <div class = 'label-title'> Longest Run</div>
                                    <div class = 'label-value'> {{week.run_data.longest_run | number:2 }} mi</div>
                                </div>
                                <div class = 'stats__label pl-1'>
                                    <div class = 'label-title'> Fastest Pace</div>
                                    <div class = 'label-value'> {{week.run_data.fastest_pace == 0 ? 'n/a' : week.run_data.fastest_pace | number : 2 | convertMinutes }} /mi</div>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <h4 class = "mg-h5 " >THIS WEEK</h4>
                            <h3 class = 'mg-h1-light' >{{$ctrl.currentWeekTitle}}</h3>
                            <hr/>
                            <div class = 'goal-circles ' ng-if="$ctrl.currentGoal.type == 'week'" >
                                <div class ='stats-row d-flex justify-content-space-between mb-1'>
                                    <div class = 'stats__label mr-1'>
                                        <div class = 'label-title'>Progress</div>
                                    </div>
                                 </div>
                                <div class = 'd-flex '>
                                    <div ng-repeat="i in $ctrl.currentGoal.value | goalToArray" class = "goal-circle {{$ctrl.MRS.userStats.this_week.runs_total >= i  ? 'active' : 'inactive' }}">
                                    </div>
                                </div>
                            </div>
                            <div ng-if="$ctrl.currentGoal.type == 'miles'">
                                <div class ='stats-row d-flex justify-content-space-between'>
                                    <div class = 'stats__label mr-1'>
                                    <div class = 'label-title'>Progress </div>
                                        <div class = 'label-value'>{{ $ctrl.MRS.userStats.this_week.mi_total  | number:2 }} / {{( $ctrl.currentGoal.value /  52 ) | number:2}} mi</div>
                                    </div>
                                 </div>
                            </div>
                            <hr/>
                            <div class ='stats-row d-flex justify-content-space-between'>
                                <div class = 'stats__label mr-1'>
                                    <div class = 'label-title'>Miles This Week</div>
                                    <div class = 'label-value'>{{ $ctrl.MRS.userStats.this_week.mi_total }} miles</div>
                                </div>
                                <div class = 'stats__label pl-1'>
                                    <div class = 'label-title'> Longest Run</div>
                                    <div class = 'label-value'> {{$ctrl.MRS.userStats.this_week.longest_run}} mi</div>
                                </div>
                                <div class = 'stats__label pl-1'>
                                    <div class = 'label-title'> Fastest Pace</div>
                                    <div class = 'label-value'> {{$ctrl.MRS.userStats.this_week.fastest_pace == 0 ? 'n/a' : $ctrl.MRS.userStats.this_week.fastest_pace | number : 2 | convertMinutes }} /mi</div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>  
            </div>
        </div>


        <div class = 'challenge-streak'>
            <h4 class = "mg-h5 with-border" >CHALLENGE STREAK</h4>
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
            <h4 class = "mg-h5 with-border"  >STATS</h4>
            <h3 class = 'mg-h1-light' >Year-To-Date</h3>
            <div class ='stats-row d-flex  mb-2 justify-content-space-between  '>
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
                    <div class = 'label-value'> {{$ctrl.MRS.userStats.this_year.fastest_pace == 0 ? 'n/a' : $ctrl.MRS.userStats.this_year.fastest_pace | number : 2 | convertMinutes }} /mi</div>
                </div>
            </div>
        </div>


    </div> 



</div><!-- challenge view --> 