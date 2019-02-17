<?php
/**
 * Template Name: Account Pages
 *
 * @package Avada
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>

<?php get_header(); ?>

<style>
	.mg-main{
		width:100%;
		background-color: rgba(255, 255, 255, 0); 
		background-image: url(http://magnoliarunning.com/wp-content/uploads/2016/08/magnolia_running_home-slider_01.jpg); 
		background-position: center center; 
		background-repeat: no-repeat; 
		margin-bottom: 0px; 
		margin-top: 0px; 
		background-size: cover; 
		padding:2% 10%;
	}
	.mg-main-content{
		margin:100px auto;
		background:white;
		text-align: center;
		padding:10%;
		width:100%;
	}
	#mepr-account-welcome-message img {
		display:none;
	}
</style>
	

<div class='mg-main'>
	<div class = 'mg-main-content'>
		<img src="http://magnoliarunning.com/wp-content/uploads/2018/10/magnolia-running_logo_favicon_iphone_retina.png" width="115" height="115" alt="" title="magnolia-running_logo_favicon_iphone_retina" class="img-responsive wp-image-1374">
		<?php the_content(); ?>
		<?php fusion_link_pages(); ?>
	</div>
</div>
			
<?php do_action( 'avada_after_content' ); ?>
<?php
get_footer();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
