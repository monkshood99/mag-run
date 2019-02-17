// look for the Website app ( directive ) 
// initialize angular if found

var ng_app = angular.module('WebsiteApp', [ ])
.config(['$httpProvider', function($httpProvider) {
	$httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
	$httpProvider.defaults.headers.common["X-Requested-By-Angular"] = 'Angular';
}])
.run(function(){});

// jQuery('<span/>' , {
// 	'class' : 'mepr-nav-item mepr-home',
// 	'html' : jQuery( '<a/>' , {
// 		'href' : '/run-tracker',
// 		'text' : 'Run Tracker'
// 	})
// }).appendTo('#mepr-account-nav');

var deferredPrompt;
var btnAdd = document.querySelector('#btn-add-to-home-screen')
if( btnAdd ){
	window.addEventListener('beforeinstallprompt', (e) => {
		// Prevent Chrome 67 and earlier from automatically showing the prompt
		e.preventDefault();
		// Stash the event so it can be triggered later.
		deferredPrompt = e;
		// Update UI notify the user they can add to home screen
		btnAdd.style.display = 'inline-block';
		// Wait for the user to respond to the prompt
		deferredPrompt.userChoice
		.then((choiceResult) => {
			if (choiceResult.outcome === 'accepted') {
			console.log('User accepted the A2HS prompt');
			} else {
			console.log('User dismissed the A2HS prompt');
			}
			deferredPrompt = null;
		});

	});

	btnAdd.addEventListener('click', (e) => {
		// hide our user interface that shows our A2HS button
		btnAdd.style.display = 'none';
		// Show the prompt
		deferredPrompt.prompt();
		// Wait for the user to respond to the prompt
		deferredPrompt.userChoice
		.then((choiceResult) => {
			if (choiceResult.outcome === 'accepted') {
			console.log('User accepted the A2HS prompt');
			} else {
			console.log('User dismissed the A2HS prompt');
			}
			deferredPrompt = null;
		});
	});

}