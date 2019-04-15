	<!-- Add / Edit Run Modal -->
    <div class="modal fade" id="run-edit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-body mg-modal-body">
                <div class="modal-content">
                    <div class = 'container-fluid'>
                        <form ng-submit="$ctrl.saveEdit( $ctrl.MRS.edit_run )">
                            <input type = 'hidden' ng-model="$ctrl.MRS.edit_run.run_date"/>
                            <div class="row">
                                <div class="col text-right">
                                    <button type="button" class="close btn-modal-close" data-dismiss="modal" aria-label="Close"><span class = 'fa fa-close'></span></button/>
                                </div>
                            </div>
                            <div class = 'row mb-2'>
                                <div class = 'col'>
                                    <label>Date</label>
                                    <input type = 'date' class = 'mg-input bkg-white'  ng-model="$ctrl.MRS.edit_run.run_date" name="distance" />
                                </div>
                            </div>
                            <div class = 'row mb-2' >
                                <div class="col">
                                    <label>Distance</label>
                                    <input type = 'number'  class = 'mg-input bkg-white  text-gray'  step="0.1" ng-model="$ctrl.MRS.edit_run.distance" name="distance" />
                                </div>
                            </div>
                            <div class = 'row mb-2'>
                                <div class="col" ng-if="$ctrl.MRS.edit_run.id">
                                    <label>Time</label>
                                    <input type = 'hidden' class = 'mg-input bkg-white text-gray'   ng-model="$ctrl.MRS.edit_run.seconds" name="seconds" />
                                    <mg-duration ng-model="$ctrl.MRS.edit_run.seconds"  obj-id="$ctrl.MRS.edit_run.id"></mg-duration> 
                                </div>
                                <div class="col" ng-if="!$ctrl.MRS.edit_run.id">
                                    <label>Time</label>
                                    <input type = 'hidden' class = 'mg-input bkg-white text-gray'   ng-model="$ctrl.MRS.edit_run.seconds" name="seconds" />
                                    <mg-duration ng-model="$ctrl.MRS.edit_run.seconds" ></mg-duration> 
                                </div>
                            </div>
                            <div class = 'row mb-2'>
                                <div class = 'col'>
                                    <label></label>
                                    <textarea  ng-model="$ctrl.MRS.edit_run.comment" class = 'form-control mg-textarea text-left ' placeholder="Say Something about your Run!"></textarea>
                                </div>
                            </div>
                            <div class = ''>
                                <div class="text-center mt-2">
                                    <button type = 'submit' class = 'mg-btn bkg-yellow btn-xl'>
                                        <small><span ng-show="$ctrl.MRS.posting" class = 'fa-refresh fa-spin fas fa'></span></small>
                                        <span ng-show="$ctrl.MRS.success">Saved!</span>
                                        <span ng-hide="$ctrl.MRS.success || $ctrl.MRS.posting ">{{ $ctrl.MRS.edit_run.id ? 'Update!' : 'Post.'}}</span>
                                    </button>
                                    <h4 class = 'mg-h4'>* Required</h4>
                                </div>		
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- // Add / Edit Run Modal -->