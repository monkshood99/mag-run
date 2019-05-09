( function(){
	// ############## 
	//	Directives // These can be removed + reused
	// ##############
	var eba = angular.module('WebsiteApp');
	eba.requires.push( 'ngFileUpload' );

	// -------------------------------------------------------
	//  Submits to the abstract contact form class 
	// 	This is a new version ( mxForm ) is going to be deprecated
	//  This isolates the scope
	// -------------------------------------------------------
	eba.directive('mgAvatarUpload', ['$parse', '$http', 'Upload',   '$injector' , 'MagRunService',
		function($parse, $http, Upload, $injector , MRS){
		return {
			scope : { "userId" : "=" },
			link : function( $scope, $element, $attrs , ngModel ){ }, // link
			template : function ( scope, element ){ }, // template 
			controller : function($scope, $http, $timeout, $attrs ){

				var $ctrl = $scope.$ctrl = this;
				$ctrl.MRS = MRS;
				console.log( $ctrl.MRS )
				$ctrl.data = {  
					'user_id' : $scope.userId
				};
				$ctrl.uploadProgress = 0;
				$ctrl.submitting = false;


				$ctrl.submit = function(){
					console.log( $ctrl.MRS )
					$ctrl.submitting = true;
					Upload.upload({ url: "/?mg::update-avatar", data : $ctrl.data 
					}).then(function ( response ) {
						console.log( response );
						
						/// check the success response 
						if(response.data.success ){
							$ctrl.MRS.userMeta.avatar = response.data.avatar;
							$timeout(function(){
									$ctrl.submitting = false
							}, 1500)
							jQuery('#modal-avatar').modal('hide');

						}else{
							$ctrl.submitting = 'error';
							$timeout(function(){
									$ctrl.submitting = false
							}, 1500)
						}
					}, function ( response ) {
						// there was a server error in uploading
						$ctrl.submitting = 'error';
						$timeout(function(){
								$ctrl.submitting = false
						}, 1500)
					}, function (evt) {
						// track the progress
						$ctrl.uploadProgress = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
					});

				}
				return;

				$ctrl.options = {
					action : '/?submit_contact_form_abstract',
					uploading : false
				}
				
								
				// any options that the form should know about ? 
				if( $attrs.mxFormOptions && $attrs.mxFormOptions !== '' ){
					try {
						$ctrl.mxFormOptions = JSON.parse( $attrs.mxFormOptions);
					}catch( $e ){ console.log( $e ); }
				}
				// are we overriding action / uploading / or the default email address		
				if( $attrs.mxFormUploading ) $ctrl.options.uploading = true;
				if( $attrs.mxFormAction ) $ctrl.options.action = $attrs.mxFormAction;
				if( $attrs.mxFormContactEmail ) $ctrl.data.contact = $attrs.mxFormContactEmail;
				// is using recaptcha ? 
				$ctrl.recap  = ( typeof( $attrs.mxFormUseRecaptcha ) !== 'undefined' && $attrs.mxFormUseRecaptcha  ) ? $injector.get( 'vcRecaptchaService') : false;
				$ctrl.recapValidated = false;
				$ctrl.recapFailed = false;
				
				

				if( $ctrl.mxFormOptions  ){
					$ctrl.data.accepted='not';
					$ctrl.data.initials=''
				}
				
				$ctrl.recapValidate = function(){
					return $ctrl.recap.getResponse() !== "";
				}

				/**
				 * Submit to our abstract function to handle all form submissions
				 * 
				 * @var mixed
				 * @access public
				 */
				$ctrl.submit_contact_form_abstract = function(){
					$ctrl.recapFailed = false;
					$ctrl.recapValidated = false;
					if( $ctrl.recap ){
						$ctrl.data.recap = $ctrl.recap.getResponse()
						if( $ctrl.recap.getResponse() === ''){
							$('#failed-recap').modal( 'show');
							$ctrl.recapValidated = false;
							$ctrl.recapFailed = true ;
						}
					}
					if( $ctrl.recapFailed ) return false;
					$ctrl.contact_form_submitting = true;
					$ctrl.data.nofill = typeof($ctrl.data.nofill) !== 'undefined' ? $ctrl.data.nofill : ''; 
					if( typeof( $ctrl.data.newsletter_opt_in) !== 'undefined'  && $ctrl.data.newsletter_opt_in ){
						
						$ctrl.optin = $injector.get( 'mxOptinService');
						$ctrl.optin.submit( $ctrl.data )
					}

					// if uploading use the upload service
					if( $ctrl.options.uploading ){
   					 					
					}else{
						// standard form posting / no uploads 
						$http.post($ctrl.options.action, $ctrl.data).then(function(response){
							$ctrl.contact_form_response = response.data;
							if(response.data.contact.success ){
								$ctrl.contact_form_submitting = false
							}else{
								$ctrl.contact_form_submitting = 'error';
								$timeout(function(){
										$ctrl.contact_form_submitting = false
								}, 1500)
							}
						}, function ( response ){ console.log( response ) })	
					}
				}

			},
	    link: function (scope, element, attrs) {
	      if (attrs.ngModel && attrs.mxValue) {
	        $parse(attrs.ngModel).assign(scope, attrs.mxValue);
	      }
	    }
		}
	}])
	;







; // end directive s

})()


;// end function scoep