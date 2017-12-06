<?php 
/**
* Template Name: Portfolio Filter
*/

// Force full width layout 
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');
remove_action(	'genesis_loop', 'genesis_do_loop' );

// Modify default layout
add_action(	'genesis_loop', 'zp_portfolio_filter_template' );
function zp_portfolio_filter_template() {
	global $post;
	
	$column_number=get_post_meta( $post->ID, 'column_number_value', true );
	$items = genesis_get_option( 'zp_num_portfolio_items' , ZP_SETTINGS_FIELD );
	zp_portfolio_template( $column_number, $items, true, true );
}

genesis();