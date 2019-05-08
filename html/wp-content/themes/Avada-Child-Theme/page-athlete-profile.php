<?php 
	/*
	Template Name:Member Profile Page
	*/
	get_header(); 
	wp_enqueue_script( 'moment',  TMPL_PATH . '/bower_components/moment/moment.js' , null , null  );
	wp_enqueue_script( 'fullcalendar-script',  TMPL_PATH . '/bower_components/fullcalendar/dist/fullcalendar.js' , null , null  );
	wp_enqueue_style( 'fullcalendar-style', TMPL_PATH . '/bower_components/fullcalendar/dist/fullcalendar.min.css');
	wp_enqueue_script( 'swiper-script',  TMPL_PATH . '/bower_components/swiper/dist/js/swiper.min.js' , null , null  );
	wp_enqueue_style( 'swiper-style', TMPL_PATH . '/bower_components/swiper/dist/css/swiper.min.css');

	wp_enqueue_script( 'ng-file-upload-shim',  TMPL_PATH .'/bower_components/ng-file-upload/ng-file-upload-shim.min.js' , null , null, true  );
	wp_enqueue_script( 'ng-file-upload',  TMPL_PATH .'/bower_components/ng-file-upload/ng-file-upload.min.js' , null , null, true  );
	wp_enqueue_script( 'ng-run-tracker',  TMPL_PATH .'/assets/js/component.member-profile.js' , null , '24', true  );
	wp_enqueue_script( 'mg-avatar-upload',  TMPL_PATH .'/assets/js/component.avatar-upload.js' , null , null, true  );
	wp_enqueue_style( 'boostrap-grid' , TMPL_PATH . "/bower_components/bootstrap4-grid-only/dist/css/bootstrap-grid.min.css" );

// 	wp_enqueue_script( 'vue',  TMPL_PATH . '/bower_components/vue/dist/vue.min.js' , null , null, true  );
// 	wp_enqueue_script( 'member-page',  TMPL_PATH .'/assets/js/vue.member-profile.js' , null , null, true  );

	$current_user = wp_get_current_user();
	$user_meta = normalize_post_meta( get_user_meta($current_user->ID) ) ;
	$userStats = Atw_app::getUserStats();
	$communityData = Atw_app::get_community_data( true );
	$goal_options= Atw_app::getGoalOptions();
	$user_avatar = Atw_app::getUserAvatar( $user_meta );
?>


<?php the_content();?>


<div class = "loading-container"  >
	<div class="loading">
		<span class="load-1"></span>
		<span class="load-2"></span>
		<span class="load-3"></span>
		<span class="load-4"></span>
	</div>
	<h1>Loading </h1>
</div> 


<div  athlete-calendar target="#calendar" 
	user-stats='<?= json_encode_attr( $userStats);?>' 
	goal-options='<?= json_encode_attr($goal_options);?>' 
	community-data='<?= json_encode_attr( $communityData);?>' >

	<!-- App Container  --> 		
	<div class = "app-container  bootstrap-wrapper full-width {{$ctrl.ready ? 'ready' : ''}}"  ng-cloak>





		<!-- Challenges Dektop --> 		
		<div class = 'hide-mobile'>
			<?= Loader::partial( 'partials/desktop-challenges' , compact( 'userStats', 'goal_options' , 'current_user', 'user_meta') );?>
			<?= Loader::partial( 'partials/desktop-add-run' , compact( 'userStats', 'goal_options' , 'current_user', 'user_meta') );?>
		</div>
		<!--//  Add Run Desktop --> 



		<!-- Challenges Dektop --> 		
		<div class = 'show-mobile'>
			<?= Loader::partial( 'partials/mobile-header' , compact( 'userStats', 'goal_options' , 'current_user', 'user_meta'));?>
			<?= Loader::partial( 'partials/mobile-challenges' , compact( 'userStats', 'goal_options' , 'current_user', 'user_meta'));?>
			<?= Loader::partial( 'partials/mobile-add-run'  , compact( 'userStats', 'goal_options' , 'current_user', 'user_meta'));?>
		</div>
		<!--//  Add Run Desktop --> 


		<?= Loader::partial( 'partials/calendar' , compact( 'userStats', 'goal_options', 'current_user' , 'user_meta') );?>


		<!-- Modal Add / Edit Run --> 
		<?= Loader::partial( 'partials/modal-add-edit-run'  , compact( 'userStats', 'goal_options' , 'current_user', 'user_meta'));?>
		<!-- // Modal Add / Edit Run --> 


		<!-- Modal Run Added --> 
		<?= Loader::partial( 'partials/modal-run-added'  , compact( 'userStats', 'goal_options' , 'current_user', 'user_meta'));?>
		<!-- // Modal Run Added --> 


		<div class = 'show-mobile'>
			<!-- Mobile View Switcher --> 
			<div id = 'mobile-view-switch'>
				<a class = " h5-light {{$ctrl.currentView == 'challenges' ? 'active':''}}" ng-click="$ctrl.changeView( 'challenges' )" >
					<span class = 'icon-challenges'></span>Challenges</a>
				<a class = "h5-light  {{$ctrl.currentView == 'add_run' ? 'active':''}}" ng-click="$ctrl.changeView( 'add_run' )">
					<span class = 'fa fa-plus-circle'></span>Add Run</a>
				<a class = "h5-light {{$ctrl.currentView == 'calendar' ? 'active':''}}" ng-click="$ctrl.changeView( 'calendar' )">
					<span class = 'icon-calendar'></span>Calendar</a>
			</div>
		</div>
		<!-- /  Mobile View Switcher --> 
	</div>
	<!-- //  App Container  --> 		

</div><!-- // athlete calendar --> 





<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId            : '257135764955242',
      autoLogAppEvents : true,
      xfbml            : true,
      version          : 'v2.10'
    });
    FB.AppEvents.logPageView();
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>


<?php get_footer(); ?>