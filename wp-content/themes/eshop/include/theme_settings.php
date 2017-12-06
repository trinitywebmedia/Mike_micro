<?php
/**
* ZP Theme Settings
*/
define( 'ZP_SETTINGS_FIELD', 'zp-settings' );
/**
* zpsettings_default_theme_options function.
*/
function zpsettings_default_theme_options() {
	
	$options = array(
		'zp_welcome_enable' => 1,
		'zp_welcome_message' => __( 'This is the welcome message section','eshop' ),
		'zp_front_enable' => 1,
		'zp_home_portfolio_items' => 8,
		'zp_home_portfolio_columns' => 4,
		'zp_home_portfolio_title' => __( 'Latest Portfolio','eshop' ),
		'zp_home_portfolio_filter' => 1,
		'zp_latest_blog_enable' => 1,
		'zp_latest_blog_title' => __( 'Latest Blog','eshop' ),
		'zp_latest_blog_desc' => __( 'This is a blog description.','eshop' ),
		'zp_latest_blog_link_title' => __( 'Read More','eshop' ),
		'zp_latest_blog_link' => '#',
		'zp_latest_blog_items' => 6,
		'zp_latest_blog_category' => '',
		'zp_color_scheme' => 'default',
		'zp_css_code' => '',
		'zp_slider_enable' 	=> 1,
		'zp_slider_height' 	=> 450,
		'zp_animation' 	=> 'slide',
		'zp_slider_speed' 	=> 6000,
		'zp_animation_duration' => 7000,
		'zp_control_nav' 	=> 'true',
		'zp_direction_nav' 	=> 'true',
		'zp_pauseonaction' 	=> 'true',
		'zp_pauseonhover' 	=> 'true',
		'zp_related_portfolio' => 1,
		'zp_related_portfolio_title' => __( 'Related Portfolio','eshop' ),
		'zp_num_portfolio_items' => -1,
		'zp_footer_text' 	=> '',
		'zp_shop_columns' => 3,
		'zp_shop_items' => 6,
		'zp_shop_related_items' => 3,
		'zp_shop_related_columns' => 3,
		'zp_shop_nav' => 'numeric'
	);
	return apply_filters( 'zpsettings_default_theme_options', $options );
}
/*
* Sanitize any inputs
*/
add_action( 'genesis_settings_sanitizer_init', 'zpsettings_sanitize_inputs' );
/**
* zpsettings_sanitize_inputs function.
*/ 
function zpsettings_sanitize_inputs() {
	genesis_add_option_filter( 'one_zero', ZP_SETTINGS_FIELD,
		array(
			'zp_slider_enable',
			'zp_home_portfolio_filter',
			'zp_latest_blog_enable',
			'zp_front_enable',
			'zp_welcome_enable'
		)
	);
	
	genesis_add_option_filter( 'no_html', ZP_SETTINGS_FIELD, 
		array(
			'zp_home_portfolio_title',
			'zp_latest_blog_title',
			'zp_home_portfolio_items',
			'zp_latest_blog_link',
			'zp_latest_blog_items',
			'zp_slider_height',
			'zp_slider_speed',
			'zp_animation_duration',
			'zp_num_portfolio_items',
			'zp_shop_columns',
			'zp_shop_items',
			'zp_shop_related_items',
			'zp_shop_related_columns',
			'zp_shop_nav'
		)
	);
	
	genesis_add_option_filter( 'requires_unfiltered_html', ZP_SETTINGS_FIELD, 
		array(
			'zp_welcome_message',
			'zp_latest_blog_desc',
			'zp_latest_blog_link_title',
			'zp_footer_text',
			)
		);
}
/* 
* Register our settings and add the options to the database
*/
add_action( 'admin_init', 'zpsettings_register_settings' );
/**
* zpsettings_register_settings function.
*/
function zpsettings_register_settings() {
	register_setting( ZP_SETTINGS_FIELD, ZP_SETTINGS_FIELD );
	add_option( ZP_SETTINGS_FIELD, zpsettings_default_theme_options() );
	
	if ( genesis_get_option( 'reset', ZP_SETTINGS_FIELD ) ) {
		update_option( ZP_SETTINGS_FIELD, zpsettings_default_theme_options() );
		genesis_admin_redirect( ZP_SETTINGS_FIELD, array( 'reset' => 'true' ) );
		exit;
	}
}
/*
* Admin notices for when options are saved/reset
*/
add_action( 'admin_notices', 'zpsettings_theme_settings_notice' );
/**
* zpsettings_theme_settings_notice function.
*/
function zpsettings_theme_settings_notice() {
	
	if ( ! isset( $_REQUEST['page'] ) || $_REQUEST['page'] != ZP_SETTINGS_FIELD )
		return;
		
	if ( isset( $_REQUEST['reset'] ) && 'true' == $_REQUEST['reset'] )
		echo '<div id="message" class="updated"><p><strong>' . __( 'Settings reset.', 'eshop' ) . '</strong></p></div>';
	elseif ( isset( $_REQUEST['settings-updated'] ) && 'true' == $_REQUEST['settings-updated'] )
		echo '<div id="message" class="updated"><p><strong>' . __( 'Settings saved.', 'eshop' ) . '</strong></p></div>';
}
/* 
* Register our theme options page
*/
add_action( 'admin_menu', 'zpsettings_theme_options' );
/**
* zpsettings_theme_options function.
*/
function zpsettings_theme_options() {
	global $_zpsettings_settings_pagehook;
	
	$_zpsettings_settings_pagehook = add_submenu_page( 'genesis', 'Eshop Settings', 'Eshop Settings', 'edit_theme_options', ZP_SETTINGS_FIELD, 'zpsettings_theme_options_page' );
	
	//add_action( 'load-'.$_zpsettings_settings_pagehook, 'zpsettings_settings_styles' );
	add_action( 'load-'.$_zpsettings_settings_pagehook, 'zpsettings_settings_scripts' );
	add_action( 'load-'.$_zpsettings_settings_pagehook, 'zpsettings_settings_boxes' );
}
/**
* zpsettings_settings_scripts function.
* This function enqueues the scripts needed for the ZP Settings settings page.
*/
function zpsettings_settings_scripts() {
	global $_zpsettings_settings_pagehook, $post;
	
	if( is_admin() ){				
		//wp_register_script( 'zp_image_upload', get_stylesheet_directory_uri() .'/include/upload/image-upload.js', array('jquery','media-upload','thickbox') );
		wp_enqueue_script('jquery');
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
		wp_enqueue_script( 'common' );
		wp_enqueue_script( 'wp-lists' );
		wp_enqueue_script( 'postbox' );
		
		wp_enqueue_media( array( 'post' => $post ) );
		//wp_enqueue_script('zp_image_upload');		
	}
}
/* 
* Setup our metaboxes
*/
/**
* zpsettings_settings_boxes function.
*
* This function sets up the metaboxes to be populated by their respective callback functions.
*
*/
function zpsettings_settings_boxes() {
	global $_zpsettings_settings_pagehook;
	
	add_meta_box( 'zpsettings_shop_settings', __( 'Shop Settings', 'eshop' ), 'zpsettings_shop_settings', $_zpsettings_settings_pagehook, 'main','high' );
	add_meta_box( 'zpsettings_home_welcome', __( 'Home Settings', 'eshop' ), 'zpsettings_home_welcome', $_zpsettings_settings_pagehook, 'main', 'high' );
	add_meta_box( 'zpsettings_slideshow_settings', __( 'Slideshow Settings', 'eshop' ), 'zpsettings_slideshow_settings', $_zpsettings_settings_pagehook, 'main','high' );
	add_meta_box( 'zpsettings_appearance_settings', __( 'Appearance Settings', 'eshop' ), 'zpsettings_appearance_settings', $_zpsettings_settings_pagehook, 'main' ,'high');
	add_meta_box( 'zpsettings_portfolio', __( 'Portfolio Settings ', 'eshop' ), 'zpsettings_portfolio', $_zpsettings_settings_pagehook, 'main','high' );
	add_meta_box( 'zpsettings_footer_settings', __( 'Footer Settings', 'eshop' ), 'zpsettings_footer_settings', $_zpsettings_settings_pagehook, 'main','high' );	
}
/*
* Add our custom post metabox for social sharing
*/
/**
* zpsettings_home_settings function.
*
* Callback function for the ZP Settings Social Sharing metabox.
*
*/
function zpsettings_home_welcome(){?>
	<p><input type="checkbox" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_welcome_enable]" id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_welcome_enable]" value="1" <?php checked( 1, genesis_get_option( 'zp_welcome_enable', ZP_SETTINGS_FIELD ) ); ?> /> <label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_welcome_enable]"><?php _e( 'Check to enable welcome message.', 'eshop' ); ?></label>    </p>
    <p><label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_welcome_message]"><?php _e( 'Welcome Message', 'eshop' ); ?></label><br>
    <textarea class="widefat" class="widefat" rows="3" cols="78" id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_welcome_message]" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_welcome_message]"><?php echo genesis_get_option( 'zp_welcome_message', ZP_SETTINGS_FIELD ); ?></textarea>
    </p>
	<?php
}
function zpsettings_slideshow_settings() { ?>
	<p><input type="checkbox" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_slider_enable]" id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_slider_enable]" value="1" <?php checked( 1, genesis_get_option( 'zp_slider_enable', ZP_SETTINGS_FIELD ) ); ?> /> <label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_slider_enable]"><?php _e( 'Check to enable slider.', 'eshop' ); ?></label></p>
    <p><label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_home_slideshow]"><?php _e( 'Select HomePage Slideshow', 'eshop' ); ?></label>
    <select name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_home_slideshow]" id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_home_slideshow]">
	<?php
    	$of_categories_obj = get_terms('slideshow');
		foreach ($of_categories_obj as $of_cat) {?>
        	<option value="<?php echo $of_cat->slug; ?>" <?php selected( genesis_get_option( 'zp_home_slideshow', ZP_SETTINGS_FIELD ), $of_cat->slug ); ?> > <?php echo $of_cat->name; ?></option>
         <?php } ?>
     </select></p>
     <p><label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_slider_height]"><?php _e( 'Slider Height (in pixel)', 'eshop' ); ?></label>
     <input type="text" size="30" value="<?php echo genesis_get_option( 'zp_slider_height', ZP_SETTINGS_FIELD ); ?>" id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_slider_height]" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_slider_height]">
     </p>      
	<p><label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_animation]"><?php _e( 'Select slider animation:','eshop' );?></label>
    <select id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_animation]" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_animation]">
    	<option value="fade" <?php selected( genesis_get_option( 'zp_animation', ZP_SETTINGS_FIELD ), 'fade' ); ?>>Fade</option>
        <option  value="slide" <?php selected( genesis_get_option( 'zp_animation', ZP_SETTINGS_FIELD ), 'slide' ); ?>>Slide</option>
    </select></p>
    
    <p><label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_slider_speed]"><?php _e( 'Set the speed of slideshow cycling in milliseconds.', 'eshop' ); ?></label>
    <input type="text" size="20" value="<?php echo genesis_get_option( 'zp_slider_speed', ZP_SETTINGS_FIELD ); ?>" id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_slider_speed]" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_slider_speed]">
    </p>
    
    <p><label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_animation_duration]"><?php _e( 'Set the speed of animation in milliseconds.', 'eshop' ); ?></label>
    <input type="text" size="20" value="<?php echo genesis_get_option( 'zp_animation_duration', ZP_SETTINGS_FIELD ); ?>" id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_animation_duration]" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_animation_duration]"></p>
    
    <p><label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_control_nav]"><?php _e( 'Control Navigation.','absolute'); ?></label>
    <select id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_control_nav]" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_control_nav]">
    	<option value="true" <?php selected( genesis_get_option( 'zp_control_nav', ZP_SETTINGS_FIELD ), 'true' ); ?>>True</option>
        <option  value="false" <?php selected( genesis_get_option( 'zp_control_nav', ZP_SETTINGS_FIELD ), 'false' ); ?>>False</option>
    </select></p>  
	<p><label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_direction_nav]"><?php _e( 'Direction Navigation.','absolute'); ?></label>
    	<select id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_direction_nav]" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_direction_nav]">
        	<option value="true" <?php selected( genesis_get_option( 'zp_direction_nav', ZP_SETTINGS_FIELD ), 'true' ); ?>>True</option>
            <option  value="false" <?php selected( genesis_get_option( 'zp_direction_nav', ZP_SETTINGS_FIELD ), 'false' ); ?>>False</option>
       </select></p>        
   	<p><label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_pauseonaction]"><?php _e( 'Pause on Action.','absolute'); ?></label>
    <select id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_pauseonaction]" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_pauseonaction]">         
	<option value="true" <?php selected( genesis_get_option( 'zp_pauseonaction', ZP_SETTINGS_FIELD ), 'true' ); ?>>True</option>
	<option  value="false" <?php selected( genesis_get_option( 'zp_pauseonaction', ZP_SETTINGS_FIELD ), 'false' ); ?>>False</option>
    </select>
	</p> 
   	<p><label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_pauseonhover]"><?php _e( 'Pause on Hover.','absolute'); ?></label>
    <select id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_pauseonhover]" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_pauseonhover]">         
	<option value="true" <?php selected( genesis_get_option( 'zp_pauseonhover', ZP_SETTINGS_FIELD ), 'true' ); ?>>True</option>
	<option  value="false" <?php selected( genesis_get_option( 'zp_pauseonhover', ZP_SETTINGS_FIELD ), 'false' ); ?>>False</option>
    </select>
	</p>        
   
<?php }
function zpsettings_appearance_settings() { ?>
     <p>
	<label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_color_scheme]"><?php _e( 'Select color scheme.','eshop'); ?></label>
	<select id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_color_scheme]" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_color_scheme]">         
	<option value="default" <?php selected( genesis_get_option( 'zp_color_scheme', ZP_SETTINGS_FIELD ), 'default' ); ?>>Default</option>
	<option  value="blue" <?php selected( genesis_get_option( 'zp_color_scheme', ZP_SETTINGS_FIELD ), 'blue' ); ?>>Blue</option>
    <option  value="dark_blue" <?php selected( genesis_get_option( 'zp_color_scheme', ZP_SETTINGS_FIELD ), 'dark_blue' ); ?>>Dark Blue</option>
    <option  value="earth" <?php selected( genesis_get_option( 'zp_color_scheme', ZP_SETTINGS_FIELD ), 'earth' ); ?>>Earth</option>
    <option  value="green" <?php selected( genesis_get_option( 'zp_color_scheme', ZP_SETTINGS_FIELD ), 'green' ); ?>>Green</option>
    <option  value="magenta" <?php selected( genesis_get_option( 'zp_color_scheme', ZP_SETTINGS_FIELD ), 'magenta' ); ?>>Magenta</option>
    <option  value="pink" <?php selected( genesis_get_option( 'zp_color_scheme', ZP_SETTINGS_FIELD ), 'pink' ); ?>>Pink</option>
    <option  value="purple" <?php selected( genesis_get_option( 'zp_color_scheme', ZP_SETTINGS_FIELD ), 'purple' ); ?>>Purple</option>
    <option  value="red" <?php selected( genesis_get_option( 'zp_color_scheme', ZP_SETTINGS_FIELD ), 'red' ); ?>>Red</option>
    <option  value="teal" <?php selected( genesis_get_option( 'zp_color_scheme', ZP_SETTINGS_FIELD ), 'teal' ); ?>>Teal</option>
    </select>
	</p>
    <p><label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_css_code]"><?php _e( 'Custom CSS Code.', 'eshop' ); ?><br>
	<textarea class="widefat" rows="3" cols="78" id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_css_code]" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_css_code]"><?php echo genesis_get_option( 'zp_css_code', ZP_SETTINGS_FIELD ); ?></textarea>
	</label>
	</p>  
    <p><span class="description"><?php _e( 'This is the appearance settings.','eshop' ); ?></span></p>    
	
<?php } 
function zpsettings_footer_settings() { ?>
    <p><label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_footer_text]"><?php _e( 'Footer Text', 'eshop' ); ?><br>
	<textarea class="widefat" rows="3" cols="78" id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_footer_text]" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_footer_text]"><?php echo genesis_get_option( 'zp_footer_text', ZP_SETTINGS_FIELD ); ?></textarea>
	<br><small><strong><?php _e( 'Enter your site copyright here.', 'eshop' ); ?></strong></small>
	</label>
	</p>    
	
<?php }
function zpsettings_portfolio() { ?>
    <p>	<input type="checkbox" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_related_portfolio]" id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_related_portfolio]" value="1" <?php checked( 1, genesis_get_option( 'zp_related_portfolio', ZP_SETTINGS_FIELD ) ); ?> /> <label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_related_portfolio]"><?php _e( 'Check to enable related portfolio in single portfolio page.', 'eshop' ); ?></label>
    </p>
     <p><label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_related_portfolio_title]"><?php _e( 'Related Portfolio Title', 'eshop' ) ?></label>
        <input type="text" size="30" value="<?php echo genesis_get_option( 'zp_related_portfolio_title', ZP_SETTINGS_FIELD ); ?>" id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_related_portfolio_title]" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_related_portfolio_title]"></p> 
        
     <p><label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_num_portfolio_items]"><?php _e( 'Number of items in the portfolio page', 'eshop' ) ?></label>
        <input type="text" size="30" value="<?php echo genesis_get_option( 'zp_num_portfolio_items', ZP_SETTINGS_FIELD ); ?>" id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_num_portfolio_items]" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_num_portfolio_items]"></p>              
     <p><span class="description"><?php _e( 'This settings applies to portfolio.','eshop' ) ?></span></p>      
<?php }
function zpsettings_shop_settings() { ?>
     <p><label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_shop_columns]"><?php _e( 'Shop number of columns', 'eshop' ) ?></label>
        <input type="text" size="30" value="<?php echo genesis_get_option( 'zp_shop_columns', ZP_SETTINGS_FIELD ); ?>" id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_shop_columns]" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_shop_columns]"><br /><span class="description"><?php _e( 'Default was set to 3. When number of columns changed, you have to update the CSS to change the width.','eshop' ) ?></span></p>
        
     <p><label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_shop_items]"><?php _e( 'Shop number of items', 'eshop' ) ?></label>
        <input type="text" size="30" value="<?php echo genesis_get_option( 'zp_shop_items', ZP_SETTINGS_FIELD ); ?>" id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_shop_items]" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_shop_items]"><br /><span class="description"><?php _e( 'Default was set to 6.','eshop' ) ?></span></p> 
     
     <p><label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_shop_related_items]"><?php _e( 'Related Products Number of Items', 'eshop' ) ?></label>
        <input type="text" size="30" value="<?php echo genesis_get_option( 'zp_shop_related_items', ZP_SETTINGS_FIELD ); ?>" id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_shop_related_items]" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_shop_related_items]"><br /><span class="description"><?php _e( 'Default was set to 4.','eshop' ) ?></span></p>
        
     <p><label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_shop_related_columns]"><?php _e( 'Related Products Number of Columns', 'eshop' ) ?></label>
        <input type="text" size="30" value="<?php echo genesis_get_option( 'zp_shop_related_columns', ZP_SETTINGS_FIELD ); ?>" id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_shop_related_columns]" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_shop_related_columns]"><br /><span class="description"><?php _e( 'Default was set to 4. When number of columns is changed, you need to update the CSS to cater the desired columns.','eshop' ) ?></span></p>
     
     <p>
     	<label for="<?php echo ZP_SETTINGS_FIELD; ?>[zp_shop_nav]"><?php _e( 'Select Shop Navigation.','eshop'); ?></label>
        <select id="<?php echo ZP_SETTINGS_FIELD; ?>[zp_shop_nav]" name="<?php echo ZP_SETTINGS_FIELD; ?>[zp_shop_nav]">
        	<option value="numeric" <?php selected( genesis_get_option( 'zp_shop_nav', ZP_SETTINGS_FIELD ), 'numeric' ); ?>><?php _e( 'Numeric', 'eshop' )?></option>
            <option  value="prev-next" <?php selected( genesis_get_option( 'zp_shop_nav', ZP_SETTINGS_FIELD ), 'prev-next' ); ?>><?php _e( 'Previous / Next', 'eshop' );?></option>
        </select>
	</p>   
                  
<?php }
/* 
* Replace the 'Insert into Post Button inside Thickbox'
*/
function zp_replace_thickbox_text($translated_text, $text ) {	
	if ( 'Insert into Post' == $text ) {
		$referer = strpos( wp_get_referer(), ZP_SETTINGS_FIELD );
		
		if ( $referer != '' ) {
			return __('Insert Image!', 'eshop' );
		}
	}
	return $translated_text;
}
/* 
* Hook to filter Insert into Post Button in thickbox
*/
function zp_change_insert_button_text() {
	add_filter( 'gettext', 'zp_replace_thickbox_text' , 1, 2 );
}
add_action( 'admin_init', 'zp_change_insert_button_text' );
/* 
* Set the screen layout to one column
*/
add_filter( 'screen_layout_columns', 'zpsettings_settings_layout_columns', 10, 2 );
/**
* zpsettings_settings_layout_columns function.
*
* This function sets the column layout to one for the ZP Settings settings page.
*
*/
function zpsettings_settings_layout_columns( $columns, $screen ) {
	global $_zpsettings_settings_pagehook;
		if ( $screen == $_zpsettings_settings_pagehook ) {
			$columns[$_zpsettings_settings_pagehook] = 2;
		}
		
	return $columns;
}
/* 
* Build our theme options page
*/
/**
* zpsettings_theme_options_page function.
*
* This function displays the content for the ZP Settings settings page, builds the forms and outputs the metaboxes.
*
*/
function zpsettings_theme_options_page() {
	global $_zpsettings_settings_pagehook, $screen_layout_columns;
	
	$screen = get_current_screen();
	$width = "width: 100%;";
	$hide2 = $hide3 = " display: none;";
?>	
	<div id="zpsettings" class="wrap genesis-metaboxes">
    	<form method="post" action="options.php">
		<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
		<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>
		<?php settings_fields( ZP_SETTINGS_FIELD ); ?>
           
        <h2 style="width: 100%; margin-bottom: 10px;" ><?php _e( 'Eshop Theme Settings', 'eshop' ); ?>
                <span style="float: right; text-align: center;"><input type="submit" class="button-primary genesis-h2-button" value="<?php _e( 'Save Settings', 'eshop' ) ?>" style="vertical-align: top;" />
                <input type="submit" class="button genesis-h2-button" name="<?php echo ZP_SETTINGS_FIELD; ?>[reset]" value="<?php _e( 'Reset Settings', 'eshop' ); ?>" onclick="return genesis_confirm('<?php echo esc_js( __( 'Are you sure you want to reset?', 'eshop' ) ); ?>');" /></span>
        </h2>
                
        <div class="metabox-holder">
        	<div class="postbox-container" style=" <?php  echo $width; ?> ">
				<?php do_meta_boxes( $_zpsettings_settings_pagehook, 'main', null ); ?>
            </div>
        </div>
        <div class="bottom-buttons">
            <input type="submit" class="button-primary genesis-h2-button" value="<?php _e( 'Save Settings', 'eshop' ) ?>" />
            <input type="submit" class="button genesis-h2-button" name="<?php echo ZP_SETTINGS_FIELD; ?>[reset]" value="<?php _e( 'Reset Settings', 'eshop' ); ?>" onclick="return genesis_confirm('<?php echo esc_js( __( 'Are you sure you want to reset?', 'eshop' ) ); ?>');" />            
        </div> 
        
        </form>
       </div>
	<script type="text/javascript">
	//<![CDATA[
		jQuery(document).ready( function($) {
			// close postboxes that should be closed
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			// postboxes setup
			postboxes.add_postbox_toggles('<?php echo $_zpsettings_settings_pagehook; ?>');
		});
		//]]>
	</script>
<?php }