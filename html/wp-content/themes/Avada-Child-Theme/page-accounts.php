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
<section id="content" <?php Avada()->layout->add_style( 'content_style' ); ?>>
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php echo fusion_render_rich_snippets_for_pages(); // WPCS: XSS ok. ?>
			<?php if ( ! post_password_required( $post->ID ) ) : ?>
				<?php if ( Avada()->settings->get( 'featured_images_pages' ) ) : ?>
					<?php if ( 0 < avada_number_of_featured_images() || get_post_meta( $post->ID, 'pyre_video', true ) ) : ?>
						<div class="fusion-flexslider flexslider post-slideshow">
							<ul class="slides">
								<?php if ( get_post_meta( $post->ID, 'pyre_video', true ) ) : ?>
									<li>
										<div class="full-video">
											<?php echo apply_filters( 'privacy_iframe_embed', get_post_meta( $post->ID, 'pyre_video', true ) ); // WPCS: XSS ok. ?>
										</div>
									</li>
								<?php endif; ?>
								<?php if ( has_post_thumbnail() && 'yes' != get_post_meta( $post->ID, 'pyre_show_first_featured_image', true ) ) : ?>
									<?php $attachment_data = Avada()->images->get_attachment_data( get_post_thumbnail_id() ); ?>
									<?php if ( is_array( $attachment_data ) ) : ?>
										<li>
											<a href="<?php echo esc_url_raw( $attachment_data['url'] ); ?>" data-rel="iLightbox[gallery<?php the_ID(); ?>]" title="<?php echo esc_attr( $attachment_data['caption_attribute'] ); ?>" data-title="<?php echo esc_attr( $attachment_data['title_attribute'] ); ?>" data-caption="<?php echo esc_attr( $attachment_data['caption_attribute'] ); ?>">
												<img src="<?php echo esc_url_raw( $attachment_data['url'] ); ?>" alt="<?php echo esc_attr( $attachment_data['alt'] ); ?>" role="presentation" />
											</a>
										</li>
									<?php endif; ?>
								<?php endif; ?>
								<?php $i = 2; ?>
								<?php while ( $i <= Avada()->settings->get( 'posts_slideshow_number' ) ) : ?>
									<?php $attachment_new_id = fusion_get_featured_image_id( 'featured-image-' . $i, 'page' ); ?>
									<?php if ( $attachment_new_id ) : ?>
										<?php $attachment_data = Avada()->images->get_attachment_data( $attachment_new_id ); ?>
										<?php if ( is_array( $attachment_data ) ) : ?>
											<li>
												<a href="<?php echo esc_url_raw( $attachment_data['url'] ); ?>" data-rel="iLightbox[gallery<?php the_ID(); ?>]" title="<?php echo esc_attr( $attachment_data['caption_attribute'] ); ?>" data-title="<?php echo esc_attr( $attachment_data['title_attribute'] ); ?>" data-caption="<?php echo esc_attr( $attachment_data['caption_attribute'] ); ?>">
													<img src="<?php echo esc_url_raw( $attachment_data['url'] ); ?>" alt="<?php echo esc_attr( $attachment_data['alt'] ); ?>" role="presentation" />
												</a>
											</li>
										<?php endif; ?>
									<?php endif; ?>
									<?php $i++; ?>
								<?php endwhile; ?>
							</ul>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; // Password check. ?>

            <main  role="main" class="clearfix width-100" >
			    <div class="fusion-row" style="max-width:100%;">
                    <section id="content" style="width: 100%;">
					    <div id="post-1989" class="post-1989 page type-page status-publish hentry">
			                <span class="entry-title rich-snippet-hidden">Design – Member Login</span>
                            <span class="vcard rich-snippet-hidden">
                                <span class="fn">
                                    <a href="http://magnoliarunning.com/author/tyler/" title="Posts by Tyler Beckwith" rel="author">Tyler Beckwith</a>
                                </span>
                            </span>
                            <span class="updated rich-snippet-hidden">2018-12-16T21:06:55+00:00</span>																			
			                <div class="post-content">
				                <div class="fusion-fullwidth fullwidth-box fusion-parallax-none nonhundred-percent-fullwidth hundred-percent-height hundred-percent-height-center-content non-hundred-percent-height-scrolling" style="background-color: rgba(255, 255, 255, 0); background-image: url(&quot;http://magnoliarunning.com/wp-content/uploads/2016/08/magnolia_running_home-slider_01.jpg&quot;); background-position: center center; background-repeat: no-repeat; padding: 10%; margin-bottom: 0px; margin-top: 0px; background-size: cover; height: calc(100vh - 32px);">
                                    <div class="fusion-fullwidth-center-content">
                                        <div class="fusion-builder-row fusion-row ">

                                        
                                            <div class="fusion-layout-column fusion_builder_column fusion_builder_column_1_1  fusion-one-full fusion-column-first fusion-column-last fusion-blend-mode 1_1" style="margin-top:15px;margin-bottom:15px;">
					                            <div class="fusion-column-wrapper" style="background-color:#ffffff;padding: 5% 5% 5% 5%;background-position:left top;background-repeat:no-repeat;-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover;" data-bg-url="">
						                            <div class="imageframe-align-center">
                                                        <span class="fusion-imageframe imageframe-none imageframe-1 hover-type-none">
                                                            <img src="http://magnoliarunning.com/wp-content/uploads/2018/10/magnolia-running_logo_favicon_iphone_retina.png" width="115" height="115" alt="" title="magnolia-running_logo_favicon_iphone_retina" class="img-responsive wp-image-1374">
                                                        </span>
                                                    </div>
                                                    <div class="fusion-login-box fusion-login-box-1 fusion-login-box-login fusion-login-align-center fusion-login-field-layout-stacked">
                                                        <div class="post-content">
                                                            <?php the_content(); ?>
                                                            <?php fusion_link_pages(); ?>
                                                        </div>
                                                    </div>
                                                    <div class="fusion-clearfix"></div>

					                            </div>
				                            </div>
                                        
                                        
                                        </div>
                                    </div>
                                </div>
							</div>
                        </div>
		            </section>
				</div>  <!-- fusion-row -->
			</main>
            

			<?php if ( ! post_password_required( $post->ID ) ) : ?>
				<?php do_action( 'avada_before_additional_page_content' ); ?>
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<?php $woo_thanks_page_id = get_option( 'woocommerce_thanks_page_id' ); ?>
					<?php $is_woo_thanks_page = ( ! get_option( 'woocommerce_thanks_page_id' ) ) ? false : is_page( get_option( 'woocommerce_thanks_page_id' ) ); ?>
					<?php if ( Avada()->settings->get( 'comments_pages' ) && ! is_cart() && ! is_checkout() && ! is_account_page() && ! $is_woo_thanks_page ) : ?>
						<?php wp_reset_postdata(); ?>
						<?php comments_template(); ?>
					<?php endif; ?>
				<?php else : ?>
					<?php if ( Avada()->settings->get( 'comments_pages' ) ) : ?>
						<?php wp_reset_postdata(); ?>
						<?php comments_template(); ?>
					<?php endif; ?>
				<?php endif; ?>
				<?php do_action( 'avada_after_additional_page_content' ); ?>
			<?php endif; // Password check. ?>
		</div>
	<?php endwhile; ?>
	<?php wp_reset_postdata(); ?>
</section>
<?php do_action( 'avada_after_content' ); ?>
<?php
get_footer();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
