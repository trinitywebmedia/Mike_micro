<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Genesis\SEO
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://my.studiopress.com/themes/genesis/
 */

/**
 * Disable the Genesis SEO features.
 *
 * @since 1.6.0
 *
 * @see genesis_default_title()
 * @see genesis_doc_head_control()
 * @see genesis_seo_meta_description()
 * @see genesis_seo_meta_keywords()
 * @see genesis_robots_meta()
 * @see genesis_canonical()
 * @see genesis_add_inpost_seo_box()
 * @see genesis_add_inpost_seo_save()
 * @see genesis_add_taxonomy_seo_options()
 * @see genesis_user_seo_fields()
 *
 * @uses GENESIS_SEO_SETTINGS_FIELD
 */
function genesis_disable_seo() {

	remove_filter( 'wp_title', 'genesis_default_title', 10, 3 );
	remove_action( 'get_header', 'genesis_doc_head_control' );
	remove_action( 'genesis_meta','genesis_seo_meta_description' );
	remove_action( 'genesis_meta','genesis_seo_meta_keywords' );
	remove_action( 'genesis_meta','genesis_robots_meta' );
	remove_action( 'wp_head','genesis_canonical', 5 );
	remove_action( 'wp_head', 'genesis_rel_author' );

	remove_action( 'admin_menu', 'genesis_add_inpost_seo_box' );
	remove_action( 'save_post', 'genesis_inpost_seo_save', 1, 2 );

	remove_action( 'admin_init', 'genesis_add_taxonomy_seo_options' );

	remove_action( 'show_user_profile', 'genesis_user_seo_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_seo_fields' );
	remove_filter( 'user_contactmethods', 'genesis_user_contactmethods' );

	remove_theme_support( 'genesis-seo-settings-menu' );
	add_filter( 'pre_option_' . GENESIS_SEO_SETTINGS_FIELD, '__return_empty_array' );

	define( 'GENESIS_SEO_DISABLED', true );

}

/**
 * Detect whether or not Genesis SEO has been disabled.
 *
 * @since 1.8.0
 *
 * @uses GENESIS_SEO_DISABLED
 *
 * @return bool True if Genesis SEO is disabled, false otherwise.
 */
function genesis_seo_disabled() {

	if ( defined( 'GENESIS_SEO_DISABLED' ) && GENESIS_SEO_DISABLED )
		return true;

	return false;

}

add_action( 'after_setup_theme', 'genesis_seo_compatibility_check', 5 );
/**
 * Check for the existence of popular SEO plugins and disable the Genesis SEO features if one or more of the plugins
 * is active.
 *
 * Runs before the menu is built, so we can disable SEO Settings menu, if necessary.
 *
 * @since 1.2.0
 *
 * @see genesis_default_title()
 *
 * @uses genesis_detect_seo_plugins() Detect certain SEO plugins.
 * @uses genesis_disable_seo()        Disable all aspects of Genesis SEO features.
 */
function genesis_seo_compatibility_check() {

	if ( genesis_detect_seo_plugins() )
		genesis_disable_seo();

	//* Disable Genesis <title> generation if SEO Title Tag is active
	if ( function_exists( 'seo_title_tag' ) ) {
		remove_filter( 'wp_title', 'genesis_default_title', 10, 3 );
		remove_action( 'genesis_title', 'wp_title' );
		add_action( 'genesis_title', 'seo_title_tag' );
	}

}

add_action( 'admin_notices', 'genesis_scribe_nag' );
/**
 * Display admin notice for Scribe SEO Copywriting tool.
 *
 * @since 1.4.0
 *
 * @link http://scribeseo.com/
 *
 * @uses genesis_is_menu_page()  Check that we're targeting a specific Genesis admin page.
 * @uses genesis_detect_plugin() Detect active plugin by constant, class or function existence.
 *
 * @return null Return early if not on the SEO Settings page, Scribe is installed, or it has already been dismissed.
 */
function genesis_scribe_nag() {

	if ( ! genesis_is_menu_page( 'seo-settings' ) )
		return;

	if ( genesis_detect_plugin( array( 'classes' => array( 'Ecordia' ) ) ) || get_option( 'genesis-scribe-nag-disabled' ) )
		return;

	$copy = sprintf( __( 'Have you tried our Scribe content marketing software? Do research, content and website optimization, and relationship building without leaving WordPress. <b>Genesis owners save big when using the special link on the special page we\'ve created just for you</b>. <a href="%s" target="_blank">Click here for more info</a>.', 'genesis' ), 'http://scribecontent.com/genesis-owners-only' );

	printf( '<div class="scribe-nag updated"><p class="alignleft">%s</p> <p class="alignright"><a href="%s">%s</a></p></div>', $copy, add_query_arg( 'dismiss-scribe', 'true', menu_page_url( 'seo-settings', false ) ), __( 'Dismiss', 'genesis' ) );

}

add_action( 'admin_init', 'genesis_disable_scribe_nag' );
/**
 * Potentially disable Scribe admin notice.
 *
 * Detect a query flag, and disables the Scribe nag, then redirect the user back to the SEO settings page.
 *
 * @since 1.4.0
 *
 * @uses genesis_is_menu_page()   Check that we're targeting a specific Genesis admin page.
 * @uses genesis_admin_redirect() Redirect to SEO Settings page after dismissing.
 *
 * @return null Return early if not on the SEO Settings page, or dismiss-scribe querystring argument not present and set
 *              to true.
 */
function genesis_disable_scribe_nag() {

	if ( ! genesis_is_menu_page( 'seo-settings' ) )
		return;

	if ( ! isset( $_REQUEST['dismiss-scribe'] ) || 'true' !== $_REQUEST['dismiss-scribe'] )
		return;

	update_option( 'genesis-scribe-nag-disabled', 1 );

	genesis_admin_redirect( 'seo-settings' );
	exit;

}

/**
 * Detect some SEO Plugin that add constants, classes or functions.
 *
 * Applies `genesis_detect_seo_plugins` filter to allow third party manpulation of SEO plugin list.
 *
 * @since 1.6.0
 *
 * @uses genesis_detect_plugin() Detect active plugin by constant, class or function existence.
 *
 * @return boolean True if plugin exists or false if plugin constant, class or function not detected.
 */
function genesis_detect_seo_plugins() {

	return genesis_detect_plugin(
		// Use this filter to adjust plugin tests.
		apply_filters(
			'genesis_detect_seo_plugins',
			//* Add to this array to add new plugin checks.
			array(

				// Classes to detect.
				'classes' => array(
					'All_in_One_SEO_Pack',
					'All_in_One_SEO_Pack_p',
					'HeadSpace_Plugin',
					'Platinum_SEO_Pack',
					'wpSEO',
					'SEO_Ultimate',
				),

				// Functions to detect.
				'functions' => array(),

				// Constants to detect.
				'constants' => array( 'WPSEO_VERSION', ),
			)
		)
	);

}
