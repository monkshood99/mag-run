	<!-- Add / Edit Run Modal -->
    <div class="modal fade" id="modal-run-added" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-body mg-modal-body">
                <div class="modal-content">
                    <div class = 'container-fluid'>
                        <pre>
                            {{$ctrl.MRS.new_run | json }}
                        </pre>
                        <div class="row">
                            <div class="col text-right">
                                <button type="button" class="close btn-modal-close" data-dismiss="modal" aria-label="Close"><span class = 'fa fa-close'></span></button/>
                            </div>
                        </div>
                        <div class = 'row mb-2'>
                            <div class = 'col'>
                                <label>Date</label>
                                <div>{{ $ctrl.MRS.new_run.run_date | date:'short'}}</div>
                            </div>
                        </div>
                        <div class = 'row mb-2' >
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <label>Distance</label>
                                        <div>{{ $ctrl.MRS.new_run.distance}} mi</div>
                                    </div>
                                    <div class="col">
                                        <label>Time</label>
                                        {{ $ctrl.MRS.new_run.seconds | secondsToTime}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class = 'row mb-2'>
                            <div class="col">
                                <label>Pace</label>   
                                {{$ctrl.MRS.new_run.pace_mi == 0 ? 'n/a' : $ctrl.MRS.new_run.pace_mi | number : 2 | convertMinutes }} / mi
                            </div>
                        </div>
                        <div class = 'row mb-2'>
                            <div class = 'col'>
                                <label>Comments</label>
                                <p>{{ctrl.MRS.new_run.comments}}</p>
                            </div>
                        </div>
                        <div class = 'row mb-2'>
                            <div class = 'col'>
                                <label>Share</label>
                                <div>
                                    <span class="fa fa-facebook run-log-row-button  clickable " ng-click="$ctrl.MRS.postToFb( run ) "></span>
                                    <span class="fa fa-twitter run-log-row-button  clickable " ng-click="$ctrl.MRS.postToFb( run ) "></span>
                                </div>
                            </div>
                            <div class = 'col text-right'>
<!-- 								<span ng-show="$ctrl.MRS.confirmingDelete !== run && $ctrl.MRS.deleting !== run"  > -->
                                <span>
									<span class="icon-mg-edit clickable" ng-click="$ctrl.MRS.startEdit( $ctrl.MRS.new_run )"></span>
									<span class="fa fas fa-trash-o clickable " ng-click="$ctrl.MRS.confirmDelete( $ctrl.MRS.new_run )"></span>
								</span>
								<!-- <span ng-show="$ctrl.MRS.confirmingDelete == $ctrl.MRS.new_run"  > -->
                                <span>
									<span class="fa fas fa-times-circle-o clickable " ng-click="$ctrl.MRS.cancelDelete()"></span>
									<span class="fa fas fa-trash-o clickable" ng-click="$ctrl.deleteRun( $ctrl.MRS.new_run )"></span>
								</span>
								<!-- <span ng-show="$ctrl.MRS.deleting == $ctrl.MRS.new_run" class = ""> -->
                                <span>
									<span class="fa fas fa-refresh fa-spin clickable"></span>
								</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- // Add / Edit Run Modal -->