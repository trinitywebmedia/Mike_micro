<?php
/**
* This template displays the single Product
* @version 1.6.4
*/

/** Remove default Genesis loop */
remove_action( 'genesis_loop', 'genesis_do_loop' );

/** Remove WooCommerce breadcrumbs */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/** Remove Woo #container and #content divs */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

add_action( 'genesis_loop', 'gencwooc_single_product_loop' );
function gencwooc_single_product_loop() {
	global $post;
	
	do_action( 'woocommerce_before_main_content' );
	
	// Let developers override the query used, in case they want to use this function for their own loop/wp_query
	
	$wc_query = false;
	
	// Added a hook for developers in case they need to modify the query
	
	$wc_query = apply_filters( 'gencwooc_custom_query', $wc_query );
		if ( ! $wc_query) {
			global $wp_query;
			$wc_query = $wp_query;
		}
		
		if ( $wc_query->have_posts() ) while ( $wc_query->have_posts() ) : $wc_query->the_post();
		
		$thumbnail= wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
		$t= get_the_title();
		
		do_action('woocommerce_before_single_product');
	?>
    	<div itemscope itemtype="http://schema.org/Product" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php do_action( 'woocommerce_before_single_product_summary' ); ?>
           
           	<div class="summary">
				<?php do_action( 'woocommerce_single_product_summary'); ?>
            </div>
			
			<?php do_action( 'woocommerce_after_single_product_summary' ); ?>
		</div>
		<?php do_action( 'woocommerce_after_single_product' );
	endwhile;
	
	do_action( 'woocommerce_after_main_content' );
}

genesis();