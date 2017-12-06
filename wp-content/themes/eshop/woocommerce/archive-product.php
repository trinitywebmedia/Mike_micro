<?php
/**
* This template displays the archive for Products
* @version 2.0.0
*/

/** Remove default Genesis loop */
remove_action( 'genesis_loop', 'genesis_do_loop' );

/** Remove WooCommerce breadcrumbs */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/** Remove Woo #container and #content divs */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/** Get Shop Page ID */

// @TODO Retained for backwards compatibility with < 1.6.0 WooC installs

global $shop_page_id;

$shop_page_id = get_option( 'woocommerce_shop_page_id' );

add_filter( 'genesis_pre_get_option_site_layout', 'genesiswooc_archive_layout' );

function genesiswooc_archive_layout( $layout ) {
	global $shop_page_id;
	
	$layout = get_post_meta( $shop_page_id, '_genesis_layout', true );
		//return 'content-sidebar';
		
		return $layout;
}

remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );

add_action( 'genesis_before_loop', 'genesiswooc_archive_product_loop' );
function genesiswooc_archive_product_loop() {
	do_action('woocommerce_before_main_content');
?>
	<h1 class="page-title">
	
		<?php if ( is_search() ) : ?>
		<?php
        	printf( __( 'Search Results: &ldquo;%s&rdquo;', 'woocommerce' ), get_search_query() ); 
			if ( get_query_var( 'paged' ) )
				printf( __( '&nbsp;&ndash; Page %s', 'woocommerce' ), get_query_var( 'paged' ) );
		?>
		<?php elseif ( is_tax() ) : ?>
			<?php echo single_term_title( "", false ); ?>
		<?php else : ?>
		<?php
        $shop_page = get_post( woocommerce_get_page_id( 'shop' ) );
			echo apply_filters( 'the_title', ( $shop_page_title = get_option( 'woocommerce_shop_page_title' ) ) ? $shop_page_title : $shop_page->post_title );
		?>
		<?php endif; ?>
     </h1>
     
	<?php if ( is_tax() && get_query_var( 'paged' ) == 0 ) : ?>
		<?php echo '<div class="term-description">' . wpautop( wptexturize( term_description() ) ) . '</div>'; ?>
	<?php elseif ( ! is_search() && get_query_var( 'paged' ) == 0 && ! empty( $shop_page ) && is_object( $shop_page ) ) : ?>
		<?php echo '<div class="page-description">' . apply_filters( 'the_content', $shop_page->post_content ) . '</div>'; ?>
	<?php endif; ?>
				
	<?php if ( have_posts() ) : ?>
		
		<?php do_action('woocommerce_before_shop_loop'); ?>
		
		<ul class="products">
			
			<?php woocommerce_product_subcategories(); ?>
		
			<?php while ( have_posts() ) : the_post(); ?>
		
				<?php woocommerce_get_template_part( 'content', 'product' ); ?>
		
			<?php endwhile; // end of the loop. ?>
				
		</ul>		<?php do_action('woocommerce_after_shop_loop'); ?>
		
	<?php else : ?>
		
		<?php if ( ! woocommerce_product_subcategories( array( 'before' => '<ul class="products">', 'after' => '</ul>' ) ) ) : ?>
					
			<p><?php _e( 'No products found which match your selection.', 'woocommerce' ); ?></p>
					
		<?php endif; ?>
		
	<?php endif; ?>
		
	<div class="clear"></div>	<?php do_action( 'woocommerce_pagination' ); ?>

	<?php do_action('woocommerce_after_main_content');
	
}
	 
genesis();