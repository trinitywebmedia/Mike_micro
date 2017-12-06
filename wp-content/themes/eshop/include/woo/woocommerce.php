<?php
/*--------------------------------------------
	Woocommerce Hooks and Functions
--------------------------------------------*/

if( class_exists( 'Woocommerce' ) ){
	add_filter( 'genesis_pre_get_option_site_layout', 'zp_woo_page_fullwidth' );
	function zp_woo_page_fullwidth( $opt ) {
	    if ( is_product() ) {
	        $opt = 'full-width-content';
	        return $opt;
	    }
	}

	/*-------------------------------------------------*/
	// Create sidebar in the shop page
	/*-------------------------------------------------*/
	add_action( 'get_header', 'zp_shop_sidebar' );
	function zp_shop_sidebar() {
	    if ( is_shop( ) ) {
	        remove_action( 'genesis_after_content', 'genesis_get_sidebar' );
	        add_action( 'genesis_after_content', 'zp_get_shop_sidebar' );
	    }
	}

	function zp_get_shop_sidebar() {
	?>
	<aside class="sidebar sidebar-primary widget-area shop_sidebar" role="complementary" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
	<?php
	    genesis_structural_wrap( 'sidebar' );
	    do_action( 'genesis_before_sidebar_widget_area' );
	    dynamic_sidebar('shop_sidebar');
		do_action( 'genesis_after_sidebar_widget_area' );
	    genesis_structural_wrap( 'sidebar', 'close' );
	?>
	</aside>
	<?php 
	}

	/*-------------------------------------------------*/
	// Shop page Column
	/*-------------------------------------------------*/
	add_filter('loop_shop_columns', 'zp_loop_columns' );
	if (!function_exists( 'zp_loop_columns' )) {
	function zp_loop_columns() {
		$number_columns = genesis_get_option( 'zp_shop_columns', ZP_SETTINGS_FIELD );
		
		if( $number_columns != '' ){
			return $number_columns;
		}else{
			return 3;
		}
	}
	}

	/*-------------------------------------------------*/
	// Shop product items
	/*-------------------------------------------------*/
	add_filter('loop_shop_per_page', 'zp_loop_shop_items');
	function zp_loop_shop_items(){
		$number_items = genesis_get_option( 'zp_shop_items', ZP_SETTINGS_FIELD );
		
		if( $number_items != '' ){
			return $number_items;	
		}else{
			return 6;
		}
	}
	/*-------------------------------------------------*/
	// Related product
	/*-------------------------------------------------*/
	add_filter( 'woocommerce_output_related_products_args','zp_related_products');
	function zp_related_products( $args ){
		$number_related_items = genesis_get_option( 'zp_shop_related_items', ZP_SETTINGS_FIELD );
		$number_related_columns = genesis_get_option( 'zp_shop_related_columns', ZP_SETTINGS_FIELD );
		
		if( $number_related_items != '' ){
			$number_related_items = $number_related_items;	
		}else{
			$number_related_items = 4;	
		}
		
		if(  $number_related_columns != '' ){
			$number_related_columns = $number_related_columns;	
		}else{
			$number_related_columns = 4;	
		}
		
		$args = array(
			'posts_per_page' => $number_related_items,
			'columns' => $number_related_columns,
			'orderby' => 'rand'
		);
		return $args;
	}

	/*-------------------------------------------------*/
	// Page Navigation
	/*-------------------------------------------------*/
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
	add_action( 'woocommerce_after_shop_loop', 'zp_shop_navigation', 10 );
	function zp_shop_navigation(){
		$shop_nav = genesis_get_option( 'zp_shop_nav', ZP_SETTINGS_FIELD );
		
		if ( 'numeric' === $shop_nav )
			genesis_numeric_posts_nav();
		else
			genesis_prev_next_posts_nav();
			
	}



	/*-------------------------------------------------*/
	// Header View Cart
	/*-------------------------------------------------*/

	add_action( 'genesis_header_right' , 'zp_cart_dropdown' , 9 );
	function zp_cart_dropdown(){
		global $woocommerce;
		$link = WC()->cart->get_cart_url();
		
		$alternate_text = apply_filters( 'zp_shop_alternate_text', __('View your shopping cart', 'eshop')  );
		$cart_label_singular = apply_filters( 'zp_cart_singular_label', __( 'You have %d item', 'eshop' ) );
		$cart_label_plural = apply_filters( 'zp_cart_plural_label', __( 'You have %d items','eshop' ) );
		$header_cart_image = apply_filters( 'zp_header_cart_image', 'i' );
		
		?>
	<div class="cart_display"><div class="cart_icon"><?php echo $header_cart_image; ?></div><ul>

	<li class="cart_display_price"><a  href="<?php echo $link; ?>" title="<?php echo $alternate_text; ?>"><span class="cart_subtotal"><?php echo WC()->cart->get_cart_total(); ?></span></a></li>

	<li class="cart_display_items"><span class="cart_subtotal"><?php echo sprintf( _n( $cart_label_singular, $cart_label_plural, WC()->cart->cart_contents_count, 'eshop'), WC()->cart->cart_contents_count);?></span></li>

	</ul></div>

	<?php 

	}

	/*-------------------------------------------------*/
	// Header View Cart Update
	/*-------------------------------------------------*/
	add_filter('woocommerce_add_to_cart_fragments', 'zp_add_to_cart_fragment');

	function zp_add_to_cart_fragment( $fragments ) {

	global $woocommerce;

	$link = WC()->cart->get_cart_url();

	$alternate_text = apply_filters( 'zp_shop_alternate_text', __('View your shopping cart', 'eshop')  );
	$cart_label_singular = apply_filters( 'zp_cart_singular_label', __( 'You have %d item', 'eshop' ) );
	$cart_label_plural = apply_filters( 'zp_cart_plural_label', __( 'You have %d items','eshop' ) );
	$header_cart_image = apply_filters( 'zp_header_cart_image', 'i' );

	ob_start();

	?>


	<div class="cart_display"><div class="cart_icon"><?php echo $header_cart_image; ?></div><ul>

	<li class="cart_display_price"><a  href="<?php echo $link; ?>" title="<?php echo $alternate_text; ?>"><span class="cart_subtotal"><?php echo WC()->cart->get_cart_total(); ?></span></a></li>

	<li class="cart_display_items"><span class="cart_subtotal"><?php echo sprintf( _n( $cart_label_singular, $cart_label_plural, WC()->cart->cart_contents_count, 'eshop'), WC()->cart->cart_contents_count);?></span></li>

	</ul></div>

	<?php

	$fragments['div.cart_display'] = ob_get_clean();

	return $fragments;

	}
}