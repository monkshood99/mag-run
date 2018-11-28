<?php 
	/*
	Template Name:Member Profile Page
	*/
	get_header(); 
	wp_enqueue_script( 'angular',  TMPL_PATH . '/bower_components/angular/angular.min.js' , null , null, true  );
	wp_enqueue_script( 'ng-app',  TMPL_PATH . '/assets/js/ng-app.js' , null , null, true  );
	wp_enqueue_script( 'moment',  TMPL_PATH . '/bower_components/moment/min/moment.min.js' , null , null, true  );
	wp_enqueue_script( 'fullcalendar-script',  TMPL_PATH . '/bower_components/fullcalendar/dist/fullcalendar.min.js' , null , null, true  );
	wp_enqueue_style( 'fullcalendar-style', TMPL_PATH . '/bower_components/fullcalendar/dist/fullcalendar.min.css');
	wp_enqueue_script( 'ng-athlete-profile',  TMPL_PATH .'/assets/js/component.member-profile.js' , null , null, true  );
// 	wp_enqueue_script( 'vue',  TMPL_PATH . '/bower_components/vue/dist/vue.min.js' , null , null, true  );
// 	wp_enqueue_script( 'member-page',  TMPL_PATH .'/assets/js/vue.member-profile.js' , null , null, true  );


	$user = wp_get_current_user(  );
	$user = pods( 'user'  )->find( ["`user`.`id` = '{$user->ID}'"])->data()[0];
	$runs = pods( 'run'  )->find( ["`user`.`id` = '{$user->ID}'"])->data();
?>


<div ng-app="WebsiteApp">

	<div athlete-calendar target="#calendar" user-id="<?= $user->ID;?>">
		<pre>
			{{$ctrl | json }}
		</pre>
		<form ng-submit="$ctrl.addRun()">
			<p>
				<label>Distance</label>
				<input type = 'number' ng-model="$ctrl.run_data.distance" name="distance" />
			</p>
			<p>
				<label>Date</label>
				<input type = 'date' ng-model="$ctrl.run_data.date" name="distance" />
			</p>
			<p>
			  <button type = 'submit' >Post My Run</button>
			</p>
		</form>
	
	
		<div id='calendar'></div>
	</div>
</div>
	
<script>
	var $page_data = {
		"today" : "<?= date( 'Y-m-d' , strtotime( 'today' ) ) ;?>" 
	}	
</script>
<?php get_footer(); ?>