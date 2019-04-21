	<!-- Add / Edit Run Modal -->
    <div class="modal fade" id="modal-run-added" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-body mg-modal-body">
                <div class="modal-content">
                    <div class = 'container-fluid'>

                        <div class=" row mb-1">
                            <div class = 'col-10'>
                                <h1>POSTED!</h1>
                                <div class = 'h5-light'><small>Looking Good Out THere!</small></div>
                            </div>
                            <div class="col-2 text-right">
                                <button type="button" class="close btn-modal-close" data-dismiss="modal" aria-label="Close"><span class = 'fa fa-close'></span></button>
                            </div>
                            <hr/>
                        </div>

                        <div class = 'row mb-1'>
                            <div class ='col stats-row d-flex '>
                                <div class = 'stats__label mr-1'>
                                    <div class = 'label-title mg-h5'>Date</div>
                                    <div class = 'label-value'>{{ $ctrl.MRS.new_run.run_date | date:'short'}}</div>
                                </div>
                            </div>
                            <hr class = 'col-11'/>
                        </div>

                        <div class = ' row mb-1'>
                            <div class =' col stats-row d-flex'>
                                <div class = 'stats__label mr-1'>
                                    <div class = 'label-title mg-h5'>Distance</div>
                                    <div class = 'label-value'>{{ $ctrl.MRS.new_run.distance}} mi</div>
                                </div>
                                <div class = 'stats__label pl-1'>
                                    <div class = 'label-title mg-h5'> Time</div>
                                    <div class = 'label-value'> {{ $ctrl.MRS.new_run.seconds | secondsToTime}}</div>
                                </div>
                            </div>
                            <hr class = 'col-11'/>
                        </div>


                        <div class = ' row mb-1'>
                            <div class ='col stats-row d-flex'>
                                <div class = 'stats__label mr-1'>
                                    <div class = 'label-title mg-h5'>Pace</div>
                                    <div class = 'label-value'>{{$ctrl.MRS.new_run.pace_mi == 0 ? 'n/a' : $ctrl.MRS.new_run.pace_mi | number : 2 | convertMinutes }} / mi</div>
                                </div>
                            </div>
                            <hr class = 'col-11'/>
                        </div>

                        

                        <div class = 'row mb-1'>
                            <div class ='col stats-row d-flex stats-row--border-bottom'>
                                <div class = 'stats__label mr-1'>
                                    <div class = 'label-title mg-h5'>Comments</div>
                                    <div class = 'label-value'>{{$ctrl.MRS.new_run.comments}}</div>
                                </div>
                            </div>
                            <hr class = 'col-11'/>
                        </div>


                        <div class = 'row mb-2'>
                            <div class = 'col-12'>
                                <h4 class = 'mg-h5' >Share</h5>
                            </div>
                            <div class = 'col'>
                                <div>
                                    <span class="fa fa-facebook btn-circle run-log-row-button  clickable " ng-click="$ctrl.MRS.postToFb( $ctrl.MRS.new_run ) "></span>
                                    <!-- <span class="fa fa-twitter btn-circle run-log-row-button  clickable " ng-click="$ctrl.MRS.postToTwitter( run ) "></span> -->
                                    <!-- <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-text="{{$ctrl.MRS.new_run.distance}}" data-hashtags="magnolia-running" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script> -->
                                </div>
                            </div>
                            <div class = 'col  modal-edit-buttons'>
								<span class="icon-mg-edit  mg-btn" ng-click="$ctrl.MRS.startEdit( $ctrl.MRS.new_run )"></span>
                                <div class = 'text-right'>
                                    <span class="fa fas fa-trash-o  mg-btn " ng-click="$ctrl.MRS.confirmDelete( $ctrl.MRS.new_run )"  ng-hide="$ctrl.MRS.confirmingDelete == $ctrl.MRS.new_run" ></span>
									<span class="fa fas fa-times-circle-o mg-btn " ng-click="$ctrl.MRS.cancelDelete()" ng-show="!$ctrl.deleting &&  $ctrl.MRS.confirmingDelete == $ctrl.MRS.new_run" ></span>
									<span class="fa fas fa-trash-o mg-btn" ng-click="$ctrl.deleteRun( $ctrl.MRS.new_run )" ng-show="!$ctrl.deleting && $ctrl.MRS.confirmingDelete == $ctrl.MRS.new_run" ></span>
									<span class="fa fas fa-refresh fa-spin" ng-show="$ctrl.MRS.deleting == $ctrl.MRS.new_run" ></span>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- // Add / Edit Run Modal -->