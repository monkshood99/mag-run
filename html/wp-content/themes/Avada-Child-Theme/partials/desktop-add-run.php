
<div class = 'horizontal-section-bar '>
    <h1 class = 'horizontal-section-title text-white text-upper'>Run Tracker</h1>
    <button type = 'button' id = 'btn-add-to-home-screen' style="display:none" class ="mg-btn bkg-yellow btn ">Add The Run Tracker to Your Home Screen</button>
</div>

<div class = 'bkg-gray-light '>
    <div class='container '>
        <form ng-submit="$ctrl.addRun()" class = 'mb-5 '>
            <div class = 'row text-center'>
                <div class = 'col-md-4'>
                    <h1 class = 'mg-h2 text-upper'>Date <sup>*</sup></h1>
                    <div class="mg-h4 mb-2">What day did you run?</div>
                    <input type = 'date' class = 'mg-input bkg-white'  ng-model="$ctrl.run_data.run_date" name="distance" />
                </div>
                <div class = 'col-md-4'>
                    <h1 class = 'mg-h2 text-upper'>Distance <sup>*</sup> </h1>
                    <div class="mg-h4 mb-2">How far did you run? (in miles)</div>
                    <input type = 'number'  class = 'mg-input bkg-white  text-gray'  step="0.1" max="100" ng-model="$ctrl.run_data.distance" name="distance" />
                </div>
                <div class = 'col-md-4'>
                    <h1 class = 'mg-h2 text-upper'>Time <sup>&nbsp;</sup></h1>
                    <div class="mg-h4 mb-2">How long did you run?</div>
                    <input type = 'hidden' class = 'mg-input bkg-white text-gray'   ng-model="$ctrl.run_data.seconds" name="seconds" />
                    <mg-duration ng-model="$ctrl.run_data.seconds" ></mg-duration> 
                </div>
                <div class = 'col-md-8 offset-md-2 text-center mt-2'>
                    <div class="mg-h4 mb-2 show-mobile">Comments </div>
                    <textarea ng-model="$ctrl.run_data.comment" class = 'form-control mg-textarea' placeholder="Say Something about your Run!"></textarea>
                </div>
    
                <div class="col-md-6 offset-md-3 text-center mt-2">
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
