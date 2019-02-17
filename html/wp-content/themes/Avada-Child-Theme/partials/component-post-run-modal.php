	<!-- Modal -->
	<div class="modal fade post-my-run-modal" id="post-my-run-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" mg-post-my-run-modal-component >
		<div class="modal-dialog" role="document" >
			<div class="modal-content">
				<div class="modal-body mg-modal-body">
					<div class = 'container-fluid'>
						<form ng-submit="$ctrl.saveEdit( run )">
							<input type = 'hidden' ng-model="$ctrl.edit_run.run_date"/>
							<div class = 'row mb-2'>
								<div class = 'col'>
									<h1 class = 'mg-h2 text-upper'>Date <sup>*</sup></h1>
									<div class="mg-h4 ">What day did you run?</div>
								</div>
								<div class = 'col'>
									<input type = 'date' class = 'mg-input bkg-white'  ng-model="$ctrl.edit_run.run_date" name="distance" />
								</div>
							</div>
							<div class = 'row' >
								<div class="col">
									<h1 class = 'mg-h2 text-upper'>Distance <sup>*</sup> </h1>
									<div class="mg-h4 ">How far did you run? (in miles)</div>
								</div>
								<div class="col mb-2">
									<input type = 'number'  class = 'mg-input bkg-white  text-gray'  step="0.1" ng-model="$ctrl.edit_run.distance" name="distance" />
								</div>
							</div>
							<div class = 'row mb-2'>
								<div class="col">
									<h1 class = 'mg-h2 text-upper'>Time <sup>&nbsp;</sup></h1>
									<div class="mg-h4 ">Minutes you ran. </div>
								</div>
								<div class="col">
								<input type = 'number' class = 'mg-input bkg-white text-gray'   ng-model="$ctrl.edit_run.minutes" name="minutes" />
								</div>
							</div>
							<div class = ''>
								<div class="text-center mt-2">
									<button type = 'submit' class = 'mg-btn bkg-yellow btn-xl'>
										<small><span ng-show="$ctrl.MRS.posting" class = 'fa-refresh fa-spin fas fa'></span></small>
										<span ng-show="$ctrl.MRS.success">Updated!</span>
										<span ng-hide="$ctrl.MRS.success || $ctrl.MRS.posting ">Update!</span>
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

