// look for the Website app ( directive ) 
// initialize angular if found
	
var ng_app = angular.module('WebsiteApp', [ ])
.config(['$httpProvider', function($httpProvider) {
	$httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
	$httpProvider.defaults.headers.common["X-Requested-By-Angular"] = 'Angular';
}])
.run(function(){});