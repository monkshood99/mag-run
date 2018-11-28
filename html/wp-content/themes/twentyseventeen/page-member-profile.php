<?php 
	/*
	Template Name:Member Profile Page
	*/
	get_header(); 
	wp_enqueue_script( 'view-dev', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js' , null , null, true  );
	wp_enqueue_script( 'member-page', get_template_directory_uri(  ) .'/assets/js/member-profile.js' , null , null, true  );
	$user = wp_get_current_user(  );
	$member_data = array_values( get_user_meta(13, 'memberful_subscription' , true ));
	$subscription_options = get_option( 'memberful_subscriptions'  );
	if( !empty( $member_data)){
		$member_data = (object )$member_data[0];
		$member_data->subscription = $subscription_options[$member_data->id];
// 		$member_data = $subscription_options[$member_data];
		
	}
	
?>
<pre>
	<?php print_r( $subscription_options ); ?>
	<?php print_r( $member_data ); ?>

</pre>
<div id="ran-today-form">
	<form>
	  <button type = 'button' v-on:click="submit">I Ran Today!</button>
	</form>
  <h1>{{ message }}</h1>
  
  <h4>By Runs</h4>
  <ul>
  	<li v-for="run in runs">{{ run.title }} </li>
  </ul>
</div>


<?php get_footer(); ?>