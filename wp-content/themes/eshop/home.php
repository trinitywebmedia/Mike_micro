<?php
/**
* Home Template
*/

get_header();

do_action( 'genesis_home' );

if( genesis_get_option( 'zp_slider_enable', ZP_SETTINGS_FIELD ) ):
	require( get_stylesheet_directory() .'/include/slider/slider.php');
endif; ?>

<div id="home-wrap">
	<!-- Welcome Message -->
	<?php if( genesis_get_option( 'zp_welcome_enable', ZP_SETTINGS_FIELD ) ): ?>
    	<div class="intro"><?php echo genesis_get_option('zp_welcome_message', ZP_SETTINGS_FIELD );?></div>
	<?php endif; ?>
    <!-- end welcome message-->
    
    <!-- Home Middle-->
	<?php if(is_active_sidebar('home-middle')):?>
    
    	<div class="home-middle">
        	<div class="widget">
				<?php dynamic_sidebar('home-middle'); ?>
            </div>
        </div>
	<?php endif;?>
    <!-- end home middle -->

</div>

<!-- end #home-wrap -->

<?php get_footer();