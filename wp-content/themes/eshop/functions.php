<?php
/*-------------------------------------------------*/
// Start the engine
/*-------------------------------------------------*/

require_once( get_template_directory() .'/lib/init.php');

/** Localization */
load_child_theme_textdomain(  'eshop', apply_filters(  'child_theme_textdomain', get_stylesheet_directory(  ) . '/languages', 'eshop'  )  );

/*-------------------------------------------------*/
// Disable Woocommerce CSS
/*-------------------------------------------------*/
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

/*-------------------------------------------------*/
// Child Theme Info
/*-------------------------------------------------*/
define( 'CHILD_THEME_NAME', 'Eshop'  );
define( 'CHILD_THEME_URL', 'http://demo.zigzagpress.com/eshop/'  );

/*-------------------------------------------------*/
// Include Custom Post Types
/*-------------------------------------------------*/
require_once( get_stylesheet_directory() . '/include/cpt/super-cpt.php'  );
require_once( get_stylesheet_directory() . '/include/cpt/zp_cpt.php'  );

/*-------------------------------------------------*/
// Include Woocommerce Custom Functions
/*-------------------------------------------------*/
require_once( get_stylesheet_directory() . '/include/woo/woocommerce.php');

/*-------------------------------------------------*/
// Woocommerce Connect
/*-------------------------------------------------*/
add_theme_support( 'genesis-connect-woocommerce' );

/*-------------------------------------------------*/
// Enable HTML5 Support
/*-------------------------------------------------*/
add_theme_support( 'html5' );

/*-------------------------------------------------*/
// Mobile Viewport
/*-------------------------------------------------*/
add_theme_support( 'genesis-responsive-viewport' );

/*-------------------------------------------------*/
// Enable custom logo support
/*-------------------------------------------------*/
add_theme_support( 'custom-logo' );

// Filter Genesis Site title to enable logo
add_action( 'get_header', 'zp_custom_logo_option' );
function zp_custom_logo_option(){
	if( has_custom_logo() ){
		//remove site title and site description
		remove_action( 'genesis_site_title', 'genesis_seo_site_title' );
		remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

		//display new custom logo
		add_action( 'genesis_site_title', 'zp_custom_logo' );
	}
}
// Custom Logo Function
function zp_custom_logo(){
	if ( function_exists( 'the_custom_logo' ) ) {
		the_custom_logo();
	}
}

/*-------------------------------------------------*/
// Bckground Support
/*------------------------------------------------*/
$args = array(
	'default-color'          => 'ffffff',
	'default-image'          => '',
);
add_theme_support( 'custom-background', $args );

/*-------------------------------------------------*/
// Footer Navigation
/*-------------------------------------------------*/
add_action( 'after_setup_theme', 'zp_footer_menu' );
function zp_footer_menu(){
	register_nav_menu( 'footer_nav', 'Footer Navigation Menu');
}
/*-------------------------------------------------*/
// Theme Options/Functions
/*-------------------------------------------------*/
require_once ( get_stylesheet_directory() . '/include/theme_settings.php'  );
require_once ( get_stylesheet_directory() . '/include/theme_functions.php'  );
/*-------------------------------------------------*/
// Include Shortcodes
/*-------------------------------------------------*/
require_once( get_stylesheet_directory() . '/include/shortcodes/shortcode.php'  );
/*-------------------------------------------------*/
//	Widgets 
/*-------------------------------------------------*/
require_once( get_stylesheet_directory()  .'/include/widgets/widget-address.php'  );
require_once( get_stylesheet_directory()  .'/include/widgets/widget-flickr.php'  );
require_once( get_stylesheet_directory()  .'/include/widgets/widget-products.php'  );
/*-------------------------------------------------*/
//Reposition the secondary navigation menu 
/*-------------------------------------------------*/
add_theme_support( 'genesis-menus', array( 'primary' => __( 'Primary Navigation Menu', 'genesis' ) ) );
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
/*-------------------------------------------------*/
// Reposition Breadcrumb
/*-------------------------------------------------*/
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs'  );
add_action( 'genesis_before_content_sidebar_wrap', 'genesis_do_breadcrumbs'  );
/*-------------------------------------------------*/
// Wrap Support
/*-------------------------------------------------*/
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'inner', 'footer-widgets', 'footer' ) );


/*-------------------------------------------------*/
// Google fonts
/*-------------------------------------------------*/
add_action( 'wp_enqueue_scripts', 'zp_google_font', 5  );
function zp_google_font( ) {
	$query_args = array(
		'family' => 'Open+Sans:400,800,700,300|Droid+Sans:400,700|Oswald:400,700,300'
	);
	wp_enqueue_style( 'zp_google_fonts', add_query_arg( $query_args, "//fonts.googleapis.com/css" ), array(), null );
}

/*-------------------------------------------------*/
// Additional Stylesheets
/*-------------------------------------------------*/
add_action( 'wp_enqueue_scripts', 'zp_print_styles' );
function zp_print_styles() {
	wp_register_style( 'flexslider-css', get_stylesheet_directory_uri().'/css/flexslider.css' );
	wp_enqueue_style( 'flexslider-css' );
	wp_register_style( 'pretty_photo_css', get_stylesheet_directory_uri().'/css/prettyPhoto.css' );
	wp_enqueue_style( 'pretty_photo_css' );
	wp_register_style( 'shortcode-css', get_stylesheet_directory_uri().'/include/shortcodes/shortcode.css' );
	wp_enqueue_style( 'shortcode-css' );	
	wp_register_style( 'woocommerce-css', get_stylesheet_directory_uri().'/include/woo/woocommerce_css.css' );
	wp_enqueue_style( 'woocommerce-css' );
	
    wp_register_style( 'custom_css', get_stylesheet_directory_uri( ).'/custom.css' );
	wp_enqueue_style( 'custom_css'  );
	
	// check if there is color scheme
	$color = strtolower(genesis_get_option( 'zp_color_scheme', ZP_SETTINGS_FIELD ));
	if( $color != "default" ){
		wp_enqueue_style( 'color-scheme-css', get_stylesheet_directory_uri().'/css/'.$color.'.css' );
	}
	wp_register_style( 'mobile_css', get_stylesheet_directory_uri().'/css/mobile.css' );
	wp_enqueue_style( 'mobile_css' );
}
/*-------------------------------------------------*/
// Shortcode CSS
/*-------------------------------------------------*/
add_action('admin_enqueue_scripts', 'zp_enqueue_scripts');  
function zp_enqueue_scripts(){
	global $current_screen;
	if($current_screen->base=='post'){
		//enqueue the script and CSS files for the TinyMCE editor formatting buttons
   		
		 wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');
		//set the style files
		wp_enqueue_style( 'shortcode_editor_style',get_stylesheet_directory_uri( ).'/include/shortcodes/shortcode_editor_style.css' );
	}
}
/*-------------------------------------------------*/
// Theme Scripts
/*-------------------------------------------------*/
add_action('wp_enqueue_scripts', 'zp_theme_js');
function zp_theme_js() {
	wp_register_script( 'jquery_carouFredSel', get_stylesheet_directory_uri(  ) . '/js/carousel/jquery.carouFredSel.min.js', array( 'jquery' ), '6.2.1', true );
	wp_register_script( 'jquery_mousewheel', get_stylesheet_directory_uri(  ) . '/js/carousel/jquery.mousewheel.min.js',array( 'jquery' ), '3.0.6', true );
	wp_register_script( 'jquery_touchswipe', get_stylesheet_directory_uri(  ) . '/js/carousel/jquery.touchSwipe.min.js',array( 'jquery' ), '1.3.3', true );
	wp_register_script( 'jquery_transit', get_stylesheet_directory_uri(  ) . '/js/carousel/jquery.transit.min.js',array( 'jquery' ), '0.9.9', true );
	wp_register_script( 'jquery_throttle', get_stylesheet_directory_uri(  ) . '/js/carousel/jquery.ba-throttle-debounce.min.js', array( 'jquery' ), '1.1', true );
	
	wp_register_script( 'jquery_cycle', get_stylesheet_directory_uri(  ) . '/js/jquery.cycle.lite.js', array( 'jquery' ), '1.7', true );	
	
	wp_register_script('custom_js', get_stylesheet_directory_uri().'/js/jquery.custom.js',array( 'jquery' ), '1.5', true );
	
	wp_register_script('jquery_pretty_photo_js', get_stylesheet_directory_uri() . '/js/jquery.prettyPhoto.js', array('jquery', 'custom_js') ,'3.1.6', true);			
	
    wp_enqueue_script('jquery');
	wp_enqueue_script('jquery_easing_js', get_stylesheet_directory_uri() . '/js/jquery-easing.js', array(), '1.3', true);
	wp_enqueue_script('script_js', get_stylesheet_directory_uri() . '/js/script.js', array(), '1.2.4', true );	
	wp_enqueue_script('jquery_isotope_min_js', get_stylesheet_directory_uri().'/js/jquery.isotope.min.js', array(), '1.5.25', true );
	wp_enqueue_script('jquery.flexslider_js', get_stylesheet_directory_uri().'/js/jquery.flexslider.js', array(), '2.0', true );	
	wp_enqueue_script('jQuery_ScrollTo_min_js', get_stylesheet_directory_uri() . '/js/jQuery.ScrollTo.min.js', array(), '1.4.2', true );
	wp_enqueue_script('jquery_tipTip', get_stylesheet_directory_uri().'/js/jquery.tipTip.minified.js', array(), '1.3', true );
		
	wp_enqueue_script('custom_js');	
	wp_enqueue_script('jquery_pretty_photo_js');	
}
/*-------------------------------------------------*/
// Custom CSS Style
/*-------------------------------------------------*/
add_action( 'wp_head', 'zp_custom_styles' );
function zp_custom_styles( ) {
$css_custom = genesis_get_option('zp_css_code', ZP_SETTINGS_FIELD );
	if($css_custom){
		echo '<style type="text/css">'.$css_custom.'</style>';
	}
}

/*-------------------------------------------------*/
// Post Image Above Post Title
/*------------------------------------------------*/
remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
add_action( 'genesis_entry_header', 'zp_reposition_post_image', 5 );
function zp_reposition_post_image() {
	if ( is_page() || ! genesis_get_option( 'content_archive_thumbnail' ) )
		return;
	if ( $image = genesis_get_image( array( 'format' => 'url', 'size' => genesis_get_option( 'image_size' ) ) ) ) {
		printf( '<a href="%s" rel="bookmark"><img class="post-image" src="%s" alt="%s" /></a>', get_permalink(), $image, the_title_attribute( 'echo=0' ) );
	}
}

/*-------------------------------------------------*/
// Enable Shortcode in the Widget Area
/*-------------------------------------------------*/
add_filter('widget_text', 'do_shortcode');

/*-------------------------------------------------*/
// Filter "Read More" Text
/*-------------------------------------------------*/
add_filter( 'excerpt_more', 'zp_read_more_link' );
add_filter( 'get_the_content_more_link', 'zp_read_more_link' );
function zp_read_more_link() {
    return '&hellip; <a class="more-link" href="' . get_permalink() . '">Read More &raquo;</a>';
}

/*-------------------------------------------------*/
// Customize Post Meta Section
/*-------------------------------------------------*/
add_filter( 'genesis_post_meta', 'zp_post_meta_filter' );
function zp_post_meta_filter($post_meta) {
	$post_meta = '[post_categories sep=", " before="Categories: "] [post_tags sep=", " before="Tags: "]';
	return $post_meta;
}

/*-------------------------------------------------*/
// Custom Post Info
/*-------------------------------------------------*/
add_filter( 'genesis_post_info', 'zp_post_info_filter' );
function zp_post_info_filter($post_info) {
if (!is_page()) {
    $post_info = '[post_date] [post_author_posts_link] [post_comments] [post_edit]';
    return $post_info;
}}

/*-------------------------------------------------*/
// #-Column Footer Widget
/*-------------------------------------------------*/
add_theme_support( 'genesis-footer-widgets', 4 );

/*-------------------------------------------------*/
// To Top Link
/*-------------------------------------------------*/
add_action('genesis_before_footer','zp_add_top_link');
function zp_add_top_link(){
	echo '<a href="#top" id="top-link"> &uarr; Top of Page</a>';
}

/*-------------------------------------------------*/
// Footer Navigation
/*-------------------------------------------------*/
register_nav_menu( 'footer_nav', 'Footer Navigation Menu' );

/*-------------------------------------------------*/
// Custom Footer
/*-------------------------------------------------*/
remove_action('genesis_footer', 'genesis_do_footer');
add_action('genesis_footer', 'zp_do_custom_footer');
function zp_do_custom_footer() { 
$copyright = genesis_get_option('zp_footer_text', ZP_SETTINGS_FIELD );
?>
<!-- Start footer menu. -->
<div id="footer_nav">
	<?php 
	if(has_nav_menu('footer_nav')){
		wp_nav_menu( array( 'theme_location' => 'footer_nav', 'menu_class' => 'footer_menu' ) ); 
	}
	?>
</div>
<div class="creds"> 
<?php
	if($copyright){
		echo $copyright ;
	}else{
?>
&copy; <?php echo date("Y"); ?> <?php bloginfo('name'); ?> | <?php bloginfo('description'); echo " | <a href=\"http://zigzagpress.com\">BY ZIGZAGPRESS</a>";
}
 ?>
</div>
<?php }

/*-------------------------------------------------*/
// Custom Top Section
/*-------------------------------------------------*/
add_action( 'genesis_before_header', 'zp_custom_top_section' );
function zp_custom_top_section(){
?>
	<div class="top_section">
    	<div class="wrap">
         <?php if(is_active_sidebar('top_left')):?>
        	<div class="top_left">
           		<?php dynamic_sidebar('top_left'); ?>
            </div>
         <?php endif;?>
         <?php if(is_active_sidebar('top_right')):?>
        	<div class="top_right">
          			  <?php dynamic_sidebar('top_right'); ?>
            </div>
         <?php endif;?>          
        </div>
    </div>
<?php 
}

/*-------------------------------------------------*/
// Theme Widgets
/*-------------------------------------------------*/
genesis_register_sidebar(array(
	'name'=>'Home Middle',
	'id' => 'home-middle',
	'description' => __( 'This is the home middle 1 section of the homepage.', 'eshop' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget'  => '</div>',
	'before_title'=>'<div class="recent-title"><h4>','after_title'=>' </h4></div>'
));
genesis_register_sidebar(array(
	'name'=>'Shop Sidebar',
	'id' => 'shop_sidebar',
	'description' => __( 'This is the shop sidebar.', 'eshop' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget'  => '</div>',
	'before_title'=>'<h4 class="widgettitle">','after_title'=>'</h4>'
));
genesis_register_sidebar(array(
	'name'=>'Top Left Section',
	'id' => 'top_left',
	'description' => __( 'This is the top left section sidebar.', 'eshop' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget'  => '</div>',
	'before_title'=>'<h4 class="widgettitle">','after_title'=>'</h4>'
));
genesis_register_sidebar(array(
	'name'=>'Top Right Section',
	'id' => 'top_right',
	'description' => __( 'This is the top right section sidebar.', 'eshop' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget'  => '</div>',
	'before_title'=>'<h4 class="widgettitle">','after_title'=>'</h4>'
));

// Add mobile menu
add_action( 'genesis_after_header', 'zp_mobile_nav', 9 );
function zp_mobile_nav(){
	$output = '';
	
	$output .=  '<div class="mobile_menu navbar-default" role="navigation"><button type="button" class="navbar-toggle">';
	$output .= '<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>';
	$output .= '</button><span class="mobile_button_label">Menu</div>';
	
	echo $output;
}
add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {
  // $cols contains the current number of products per page based on the value stored on Options -> Reading
  // Return the number of products you wanna show per page.
  $cols = 9;
  return $cols;
}/**
 * Change price format from range to "From:"
 *
 * @param float $price
 * @param obj $product
 * @return str
 */
function iconic_variable_price_format( $price, $product ) {

    $prefix = sprintf('%s: ', __('From', 'iconic'));

    $min_price_regular = $product->get_variation_regular_price( 'min', true );
    $min_price_sale    = $product->get_variation_sale_price( 'min', true );
    $max_price = $product->get_variation_price( 'max', true );
    $min_price = $product->get_variation_price( 'min', true );

    $price = ( $min_price_sale == $min_price_regular ) ?
        wc_price( $min_price_regular ) :
        '<del>' . wc_price( $min_price_regular ) . '</del>' . '<ins>' . wc_price( $min_price_sale ) . '</ins>';

    return ( $min_price == $max_price ) ?
        $price :
        sprintf('%s%s', $prefix, $price);

}

add_filter( 'woocommerce_variable_sale_price_html', 'iconic_variable_price_format', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'iconic_variable_price_format', 10, 2 );


add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
function woo_rename_tabs( $tabs ) {

	$tabs['description']['title'] = __( 'More Information' );		// Rename the description tab
	$tabs['reviews']['title'] = __( 'Ratings' );				// Rename the reviews tab
	$tabs['additional_information']['title'] = __( 'Product Data' );	// Rename the additional information tab

	return $tabs;

}
/** 
 * Change the heading on the Additional Information tab section title for single products.
 */
add_filter( 'woocommerce_product_additional_information_heading', 'isa_additional_info_heading' );
 
function isa_additional_info_heading() {
    return 'Product Specifications';
}
add_filter( 'wp_nav_menu_items', 'theme_menu_extras', 10, 2 );
/**
 * Filter menu items, appending either a search form or today's date.
 *
 * @param string   $menu HTML string of list items.
 * @param stdClass $args Menu arguments.
 *
 * @return string Amended HTML string of list items.
 */
function theme_menu_extras( $menu, $args ) {
	//* Change 'primary' to 'secondary' to add extras to the secondary navigation menu
	if ( 'primary' !== $args->theme_location )
		return $menu;
	//* Uncomment this block to add a search form to the navigation menu
	/*
	ob_start();
	get_search_form();
	$search = ob_get_clean();
	$menu  .= '<li class="right search">' . $search . '</li>';
	*/
	//* Uncomment this block to add the date to the navigation menu
	/*
	$menu .= '<li class="right date">' . date_i18n( get_option( 'date_format' ) ) . '</li>';
	*/
	return $menu;
}
//Change search form text

function themeprefix_search_button_text( $text ) {
return ( 'Search Our Store');
}
add_filter( 'genesis_search_text', 'themeprefix_search_button_text' );
add_filter( 'wp_nav_menu_items', 'custom_nav_item', 10, 2 );
/**
 * Callback for Genesis 'wp_nav_menu_items' filter.
 * 
 * Add custom right nav item to Genesis primary menu.
 * 
 * @package Genesis
 * @category Nav Menu
 * @author Ryan Meier http://www.rfmeier.net
 * 
 * @param string $menu The menu html
 * @param stdClass $args the current menu args
 * @return string $menu The menu html
 */
function custom_nav_item( $menu, $args ) {
        
    //* make sure we are in the primary menu
    if ( 'primary' != $args->theme_location ) {
        return $menu;
    }
    
    //* see if a nav extra was already specified with Theme options
    if ( genesis_get_option( 'nav_extras' ) ) {
        return $menu;
    }
    
    //* additional checks?
    
    //* append your custom code
    $menu .= sprintf( '<li class="right your-custom-code-class">%s</li>', __( 'Your custom code' ) );
    
    return $menu;
        
}