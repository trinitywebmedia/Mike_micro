<?php 
/**
 * Portfolio Taxonomy Template
 */

add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');
remove_action(	'genesis_loop', 'genesis_do_loop' );
add_action(	'genesis_loop', 'zp_portfolio_taxonomy_template' );

function zp_portfolio_taxonomy_template() {
	global $post;
	
	$items = genesis_get_option( 'zp_num_portfolio_items', ZP_SETTINGS_FIELD );
	zp_portfolio_template( 3, $items, false, true );

}
genesis();