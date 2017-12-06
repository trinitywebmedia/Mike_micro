<?php 
/**
 * Single Portfolio Template
 */

/* Force fullwidth template */
add_filter( 'genesis_pre_get_option_site_layout', 'zp_single_portfolio_layout' );
function zp_single_portfolio_layout( $opt ) {
	return 'full-width-content';
}

/* Modify the default loop */
remove_action(	'genesis_loop', 'genesis_do_loop' );
add_action(	'genesis_loop', 'zp_single_portfolio_template' );
function zp_single_portfolio_template() { ?>
	
    <h1 class="entry-title"><?php echo the_title('' ,'' , false); ?></h1>
	
	<?php global $post;?>
    	
        <div class="portfolio_single_feature">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
			
			// get portfolio attached images ids
			$portfolio_images = get_post_meta( $post->ID, 'portfolio_images', true );
			
			$video_url = get_post_meta( $post->ID, 'zp_video_url_value', true );
			$video_embed = get_post_meta( $post->ID, 'zp_video_embed_value', true );
			$video_ht = get_post_meta( $post->ID, 'zp_height_value', true );
			
			//if video exist
			if( $video_url != '' || $video_embed != '' ) { ?>
            	<div class="portfolio_single_video">
					<?php
                    	if( trim( $video_embed ) == '' ) {
							if( preg_match( '/youtube/' , $video_url ) )	{
								if(preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $video_url, $matches)) {
									$output = '<iframe title="YouTube video player" class="youtube-player" type="text/html" width="100%" height="'.$video_ht.'" src="http://www.youtube.com/embed/'.$matches[1].'" frameborder="0" allowFullScreen></iframe>';
								}else {
									$output = __('Sorry that seems to be an invalid <strong>YouTube</strong> URL. Please check it again.', 'eshop');
								}
						}elseif(preg_match('/vimeo/', $video_url)) {
							if(preg_match('~^http://(?:www\.)?vimeo\.com/(?:clip:)?(\d+)~', $video_url, $matches)) {
								$output = '<iframe src="http://player.vimeo.com/video/'.$matches[1].'" width="100%" height="'.$video_ht.'" frameborder="0"></iframe>';
							}else {
								$output = __('Sorry that seems to be an invalid <strong>Vimeo</strong> URL. Please check it again. Make sure there is a string of numbers at the end.', 'eshop');
							}
						}else {
							$output = __('Sorry that is an invalid YouTube or Vimeo URL.', 'eshop');
						}
							echo $output;
						}else {
							echo stripslashes(htmlspecialchars_decode($video_embed));
						}?>
				</div>
         <!-- if images exists ( slider )-->
        <?php } elseif( $portfolio_images != '' ){?> 
		
		<script type="text/javascript">
			var J = jQuery.noConflict();
				J(document).ready(function() {      	
					J('.portfolio_single_slider').flexslider({
						animation: "fade",
						slideDirection: "horizontal",
						slideshowSpeed: 6000,
						animationDuration: 7000,
						directionNav: true,
						controlNav: false,
						pauseOnAction: true,
						pauseOnHover: true,
						animationLoop: true
					});
					
					J('.portfolio_single_slider').hover(function(){
						J(this).children('ul.flex-direction-nav').css({display: 'block'});
					}, function(){
						J(this).children('ul.flex-direction-nav').css({display: 'none'});
					});
				});        
        </script>
        
		<div class="portfolio_single_slider flexslider">
        	<ul class="slides">
            <?php
			$ids = explode(",", $portfolio_images );	
			$i=0;
			while( $i < count( $ids ) ){
				if( $ids[$i] ){
					// get image url
					$url = wp_get_attachment_image_src( $ids[$i] , 'full' );			
					echo '<li><img src="'.$url[0].'" /></li>';
				}
				$i++;
			}
			?>

            </ul>
        </div>        
        <?php }else {?>
        <div class="portfolio-items">
        	<?php echo wp_get_attachment_image( get_post_thumbnail_id(  $post->ID  ) , 'full' );?>
        </div>
		<?php } endwhile; endif; ?>
        
        </div> <!-- end portfolio_single_feature-->
		
		<?php
        	$full = '';
			if( get_post_meta( $post->ID, 'zp_portfolio_meta_item_value', true) == 'false'){
				$full= 'style="width: 100%;"';
			}else{ ?>
            
            <div class="metaItem">
            	<div class="authorStuff"><strong><?php echo get_post_meta( $post->ID,'client_item_label_value', true ); ?></strong><span><?php echo get_post_meta( $post->ID,'client_item_value', true );?></span></div>
                <div class="dateStuff"><strong><?php echo get_post_meta( $post->ID,'date_item_label_value', true );	?></strong><span><?php echo get_post_meta( $post->ID,'date_item_value', true ); ?></span></div>
                <div class="categoryStuff"><strong><?php echo get_post_meta( $post->ID,'category_item_label_value', true );	?></strong> <span><?php echo get_post_meta( $post->ID,'category_item_value', true );?></span></div>
                <div class="projectStuff"><a href="<?php echo get_post_meta( $post->ID,'visit_project_value', true );?>"><strong><?php echo get_post_meta( $post->ID,'visit_project_label_value', true );	?></strong></a> </div>
            </div>

<?php } ?>
	<div class="folio-entry" <?php echo $full; ?>>
		<?php the_content(); ?>
    </div>
<div class="folio-more">
	<?php zp_related_portfolio(); ?>
</div><!-- End columns. -->

<?php }
genesis();