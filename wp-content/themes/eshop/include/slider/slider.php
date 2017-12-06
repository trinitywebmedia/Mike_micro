<div id="home_gallery" style="height: <?php echo genesis_get_option( 'zp_slider_height',  ZP_SETTINGS_FIELD );?>px;">
      <?php	  wp_enqueue_script( 'jquery_flexslider_js' );
		 //adjust video height
		 $video_height = genesis_get_option( 'zp_slider_height',  ZP_SETTINGS_FIELD );
		 $home_slideshow  = genesis_get_option ( 'zp_home_slideshow' , ZP_SETTINGS_FIELD );
	  	 $recent = new WP_Query(array('post_type'=> 'slide', 'showposts' => '-1','orderby' => 'meta_value_num', 'meta_key'=>'slide_number_value','order'=>'ASC', 'slideshow'=>$home_slideshow )); 
      	 	$images = array();
	 		$captions = array();
	 		$counter = 0;
			$type = array();
			$video_id = array();
			$link = array();			
			$title = array();
			while($recent->have_posts()) : $recent->the_post();
					$images[$counter] = genesis_get_image("format=html");
		
					$title[$counter] = get_the_title('',FALSE);			
		
					$captions[$counter] = substr(get_the_content(),0 , 200);
		
					$type[$counter]= get_post_meta($post->ID, 'video_type_value', true);
		
					$video_id[$counter]= get_post_meta($post->ID, 'video_id_value', true);
		
					$link[$counter] = get_post_meta($post->ID, 'slider_link_value', true);
		
					$button_name[$counter] = get_post_meta($post->ID, 'button_name_value', true);
		
					$counter += 1;
			endwhile;
  			$counter=0;			
	  ?>
		<div class="flexslider">
	    <ul class="slides">       
			<?php foreach($images as $image){
					if($type[$counter] == "youtube"){?>
						 <li>
                                <iframe width="100%" height="<?php echo $video_height;?>px;" src="http://www.youtube.com/embed/<?php echo $video_id[$counter]; ?>?wmode=opaque" frameborder="0" allowfullscreen></iframe>
                         </li>
			<?php	}elseif($type[$counter] == "vimeo"){?>
						    <li>
                                <iframe src="http://player.vimeo.com/video/<?php echo $video_id[$counter]; ?>?portrait=0&amp;color=ffffff" width="100%" height="<?php echo $video_height;?>px;" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe>
                            </li>
			<?php	}else{	 ?>
              			<li><?php echo $image;?>
                        <div class="flex-caption">
                        <h1><a href="<?php echo $link[$counter];?>"><?php echo $title[$counter]; ?></a></h1>
                        <div><?php echo $captions[$counter];?></div>
                        <?php if(  $button_name[$counter] ): ?>
                        	<a class="button" href="<?php echo $link[$counter];?>"><?php echo $button_name[$counter]; ?></a>
                        <?php endif; ?>
                        </div>
                        </li>                        
	 		<?php }
				$counter++;
			}
			?>
	    </ul>
	  </div>      
       	<script type="text/javascript">
		var J = jQuery.noConflict();		
        J(document).ready(function() {      
			J('#home_gallery .flexslider').flexslider({
					animation: "<?php echo genesis_get_option( 'zp_animation' , ZP_SETTINGS_FIELD );?>",              
					slideDirection: "horizontal",                  
					slideshowSpeed: <?php echo genesis_get_option( 'zp_slider_speed' , ZP_SETTINGS_FIELD );?>,           
					animationDuration: <?php echo genesis_get_option( 'zp_animation_duration' , ZP_SETTINGS_FIELD );?>,         
					directionNav: <?php echo genesis_get_option( 'zp_direction_nav' , ZP_SETTINGS_FIELD );?>,             
					controlNav: <?php echo genesis_get_option( 'zp_control_nav' , ZP_SETTINGS_FIELD );?>,                                                                   
					pauseOnAction: <?php echo genesis_get_option( 'zp_pauseonaction' , ZP_SETTINGS_FIELD );?>,         
					pauseOnHover: <?php echo genesis_get_option( 'zp_pauseonhover' , ZP_SETTINGS_FIELD );?>,
				    animationLoop: true
			});			
			J('.flexslider').hover(function(){	
					J(this).children('ul.flex-direction-nav').css({display: 'block'});					
			}, function(){
					J(this).children('ul.flex-direction-nav').css({display: 'none'});
			});														
		});
	</script>
</div>