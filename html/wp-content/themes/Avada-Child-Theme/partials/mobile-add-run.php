<div class = 'bkg-gray-light ' ng-show="$ctrl.currentView == 'add_run'">
    <div class='container '>
        <form ng-submit="$ctrl.addRun()" class = 'mb-5 '>
            <div class = 'row '>
                <div class = 'col-sm-12 col-md-4'>
                    <label><b>Date</b> <sup>*</sup></label>
                    <input type = 'date' class = 'mg-input bkg-white'  ng-model="$ctrl.run_data.run_date" name="distance" />
                </div>
                <div class = 'col-sm-12 col-md-4'>
                    <label><b>Distance</b> <sup>*</sup> </label>
                    <input type = 'number'  class = 'mg-input bkg-white  text-gray'  step="0.1" max="100" ng-model="$ctrl.run_data.distance" name="distance" />
                </div>
                <div class = 'col-sm-12 col-md-4'>
                    <label><b>Time</b> <sup>&nbsp;</sup></label>
                    <input type = 'hidden' class = 'mg-input bkg-white text-gray'   ng-model="$ctrl.run_data.seconds" name="seconds" />
                    <mg-duration ng-model="$ctrl.run_data.seconds" ></mg-duration> 
                </div>
                <div class = 'col-sm-12 col-md-8 offset-md-2  mt-2'>
                    <label><b>Comments</b> </label>
                    <textarea ng-model="$ctrl.run_data.comment" class = 'form-control mg-textarea' placeholder="Say Something about your Run!"></textarea>
                </div>
    
                <div class="col-sm-12  col-md-6 offset-md-3  text-center mt-2">
                    <button type = 'submit' class = 'mg-btn bkg-yellow btn-xl'>
                            <small><span ng-show="$ctrl.MRS.posting" class = 'fa-refresh fa-spin fas fa'></span></small>
                            <span ng-show="$ctrl.MRS.success">Posted!</span>
                            <span ng-hide="$ctrl.MRS.success || $ctrl.MRS.posting ">Post It!</span>
                    </button>
                    <h4 class = 'mg-h4'>* Required</h4>
                </div>
    
            </div>
        </form><!-- // form container --> 
            
    </div>
</div>
