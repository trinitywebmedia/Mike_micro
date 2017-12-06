<?php 
/**
* Theme Functions
*/
// Add new image sizes
add_image_size(  'Square', 280, 100, TRUE  );
add_image_size(  'Blog', 700, 280, TRUE  );
add_image_size(  '2col', 519, 352, TRUE  );
add_image_size(  '3col', 340, 235, TRUE  );
add_image_size(  '4col', 250, 184, TRUE  );
/*-----------------------------------------------------------------------------------*/
/* Portfolio Layout Function
/*-----------------------------------------------------------------------------------*/
function zp_portfolio_template(  $columns, $num_items, $filter, $pagination  ){
	global $post, $paged, $wp_query;
		if(  $columns == 2  )
		{
			$width=450;
			$height = 300;
			$column='2col';
			$num_post = $num_items;
		}
		if(  $columns == 3  )
		{
			$width=300;
			$height = 200;
			$column='3col';
			$num_post = $num_items;
		}
		if(  $columns == 4  )
		{
			$width=224;
			$height = 165;
			$column ='4col';
			$num_post = $num_items;
		}
	
		$html='';
		if ( have_posts() ){
			printf( '<article %s>', genesis_attr( 'entry' ) );
		}
		if( is_post_type_archive( 'portfolio' ) ){
			echo '<header class="entry-header"><h1 class="entry-title" itemprop="headline">'.post_type_archive_title( '',false ).'</h1></header>';			
		}else if( is_tax(  'portfolio_category'  ) ){
			echo '<header class="entry-header"><h1 class="entry-title" itemprop="headline">'.single_tag_title( '',FALSE ).'</h1></header>';
		}else if( is_page( ) ){
			echo '<header class="entry-header"><h1 class="entry-title" itemprop="headline">'.the_title('','', false ).'</h1></header>';
		}
		if(  $filter  ){
			echo '<div id="options" class="clearfix">';		
			echo '<ul id="portfolio-categories" class="option-set" data-option-key="filter"><li><a href="#" data-option-value="*" class="selected">'.__( 'show all', 'eshop' ).'</a></li>';						
			$term_args = array( 
			'orderby' => 'name',
			'order' => 'ASC',
			'taxonomy' => 'portfolio_category'
			 );
			
			$categories = get_categories( $term_args );
				foreach( $categories as $category ) :
				$tms=str_replace( " ","-",$category->name );
				echo '<li><a class="active" href="#" data-option-value=".'.$category->slug.'">'.$category->name.'</a></li>';
				endforeach;
			
			echo '</ul></div>';	
		}
		
		// check if it is the taxonomy page
		if(  !is_tax(  'portfolio_category'  )  ){ 
				$paged = get_query_var( 'paged' );
				$args= array( 
					'posts_per_page' =>$num_post, 
					'post_type' => 'portfolio',
					'paged' => $paged,
					'orderby' => 'date',
					'order' => 'DESC',
					
				 );				
				query_posts( $args );
		}
		?>	
		<div id="container" style="height: auto; width: 100%;">
		<?php
		if(  have_posts(  )  ) {
												
		 while (  have_posts(  )  ) {
			the_post(  ); 
			
			$t=get_the_title(  );
			$permalink=get_permalink(  );	
			$icon='';		
			$thumbnail = wp_get_attachment_image( get_post_thumbnail_id(  $post->ID  ) , $column ); 
			//get the image title
			
			$openLink='<div class="portfolio_image block_17">';
			$closeLink='</div>';
					
			if(  $filter  ){
					$span_icon='<div class="blind"></div><div class="hover_info"><h1><a href="'.$permalink.'">'.$t.'</a></h1></div>';	
			}else{
					$span_icon='<div class="blind"></div><div class="hover_info"><h1><a class="preview" href="'.wp_get_attachment_url(  get_post_thumbnail_id(  $post->ID  )  ).'" rel="prettyPhoto[pp_gal]" title="'.$t.'" >'.$t.'</a></h1></div>';
				}
					
			$terms=wp_get_post_terms( $post->ID, 'portfolio_category' );
			$term_string='';
				foreach( $terms as $term ){
					$term_string.=( $term->slug ).',';
				}
			$term_string=substr( $term_string, 0, strlen( $term_string )-1 );
			$samp=str_replace( " ","-",$term_string );	
			$string = str_replace( ","," ",$samp );
			$finale= $string." ";	
								//generate the final item HTML
			$html.= '<div class="element element-'.$column.' '.$finale.'" data-filter="'.$finale.'">'.$openLink.''.$span_icon.$thumbnail.$closeLink.'</div>';
		}
		}
		echo $html;
	?>
    </div>
    
<?php
if(  $pagination  )
	genesis_posts_nav();
		if ( have_posts() ){
			printf( '</article>');
		}
wp_reset_query(  );
}
/*-----------------------------------------------------------------------------------*/
/* Related Portfolio
/*-----------------------------------------------------------------------------------*/
function zp_related_portfolio(){
global $post;
		
	$terms = get_the_terms( $post->ID , 'portfolio_category' );	
	$term_ids = array_values( wp_list_pluck( $terms,'term_id' ) );
	$args=array(
     'post_type' => 'portfolio',
     'tax_query' => array(
                    array(
                        'taxonomy' => 'portfolio_category',
                        'field' => 'id',
                        'terms' => $term_ids,
                        'operator'=> 'IN' 
                     )),
      'posts_per_page' => 4,
      'orderby' => 'rand',
      'post__not_in'=>array( $post->ID )
	);
	query_posts( $args ); 
	  	  if( have_posts() ) {
?>
    <div class = "related_portfolio">		
        <h4><?php echo genesis_get_option( 'zp_related_portfolio_title' , ZP_SETTINGS_FIELD ); ?></h4>
    </div>
   <div id="container" class="related_container">
 
      <?php		  
		   while ( have_posts() ) {
			  the_post(); 
				$t = get_the_title();
				$permalink=get_permalink();
				$description= substr(get_the_excerpt(), 0, 100);
				$thumbnail= wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
				$date = get_the_date( 'F j, Y' );
				?>
               <div class="element element-4col">
                    <div class="portfolio_image block_17">
						<div class="blind"></div>
                        <div class="hover_info">
                        	<h1>
                            	<a href="<?php echo $permalink; ?>"><?php echo $t ?></a>
                             </h1>
                        </div>
                       <?php echo wp_get_attachment_image( get_post_thumbnail_id(  $post->ID  ) , '3col' );?>
                    </div>
               </div> 
	  
		   <?php
		   	}
		  }
		  
		 wp_reset_query(  ); 
	  ?>
	</div>
<?php }
/*-----------------------------------------------------------------------------------*/
/* Portfolio Shortcode Function
/*-----------------------------------------------------------------------------------*/
function  zp_portfolio_shortcode(  $columns, $num_items, $type, $filter ,$portfolio_category ){
	global $post, $paged;
										
										
		if(  $columns == 2  )
		{
			$width=520;
			$height = 400;
			$column='2col';
			$num_post = $num_items;
		}
		if(  $columns == 3  )
		{
			$width=343;
			$height = 200;
			$column='3col';
			$num_post = $num_items;
		}
		if(  $columns == 4  )
		{
			$width=255;
			$height = 165;
			$column='4col';
			$num_post = $num_items;
		}
	
		$html='';
		$output = '';
		
		$output .= '<div class="portfolio_shortcode">';
		
		if(  $filter == 'true'  ){
			$output .= '<div id="options" class="clearfix">';		
			$output .= '<ul id="portfolio-categories" class="option-set" data-option-key="filter"><li><a href="#" data-option-value="*" class="selected">show all</a></li>';						
			$term_args = array( 
			'orderby' => 'name',
			'order' => 'ASC',
			'taxonomy' => 'portfolio_category'
			 );
			
			$categories = get_categories( $term_args );
				foreach( $categories as $category ) :
				$tms=str_replace( " ","-",$category->name );
				$output .= '<li><a class="active" href="#" data-option-value=".'.$category->slug.'">'.$category->name.'</a></li>';
				endforeach;
			
			$output .= '</ul></div>';	
		}
		$portfolio_category = ($portfolio_category != '' )? $portfolio_category : '';
		
		// check if it is the taxonomy page
				$paged = get_query_var( 'paged' );
				$args= array( 
					'posts_per_page' =>$num_post, 
					'post_type' => 'portfolio',
					'paged' => $paged,
					'orderby' => 'date',
					'order' => 'DESC',
					'portfolio_category' => $portfolio_category
					
				 );				
				query_posts( $args );
	
		$output .= '<div id="container" style="height: auto; width: 100%;">';
		if(  have_posts(  )  ) {
												
		 while (  have_posts(  )  ) {
			the_post(  ); 
			
			$t=get_the_title(  );
			$permalink=get_permalink(  );	
			$icon='';		
			$thumbnail = wp_get_attachment_image( get_post_thumbnail_id(  $post->ID  ) , $column );
			//get the image title						
			$openLink='<div class="portfolio_image">';
			$closeLink='</div>';		
			$type = ( $type == 'portfolio' )? true : false;					
			if(  $type  ){
				$span_icon='<div class="icon" style="display:none" ><h4><a href="'.$permalink.'" class="item-desc">'.$t.'</a></h4></div>';	
			}else{
				$span_icon='<div class="icon" style="display:none" ><h4><a href="'.wp_get_attachment_url(  get_post_thumbnail_id(  $post->ID  )  ).'" rel="prettyPhoto[pp_gal]" title="'.$t.'" class="item-desc">'.$t.'</a></h4></div>';
			}
			$terms=wp_get_post_terms( $post->ID, 'portfolio_category' );
			$term_string='';
				foreach( $terms as $term ){
					$term_string.=( $term->slug ).',';
				}
			$term_string=substr( $term_string, 0, strlen( $term_string )-1 );
			$samp=str_replace( " ","-",$term_string );
			$string = str_replace( ","," ",$samp );
			$finale= $string." ";
			
			//generate the final item HTML
			$html.= '<div class="element element'.$column.' '.$finale.'" data-filter="'.$finale.'">'.$openLink.''.$span_icon.$thumbnail.$closeLink.'</div>';
			}
		}
		
		$output .= $html;
		
		$output .= '</div>';
		$output .= '</div>';
wp_reset_query(  );
return $output;
}
/*-----------------------------------------------------------------------------------*/
/* Return Comment Number
/*-----------------------------------------------------------------------------------*/
function zp_custom_comment_number(  ){
	global $post;
		$num_comments = get_comments_number();
		if ( $num_comments == 0 ) {
			$comments = __('No Comments', 'eshop');
		} elseif ( $num_comments > 1 ) {
			$comments = $num_comments . __(' Comments','eshop' );
		} else {
			$comments = __('1 Comment','eshop' );
		}
	return $comments;
}

/*-----------------------------------------------------------------------------------*/
/* Product Slider
/*-----------------------------------------------------------------------------------*/
function zp_product_slider( $title, $feature = true, $onsale= false, $product_cat = '' , $product_items = -1 , $order_by= 'date', $order = 'ASC' ){
	if( class_exists( 'Woocommerce' ) ){
		global $woocommerce;	
		wp_enqueue_script(  'jquery_carouFredSel'   );
		wp_enqueue_script(  'jquery_mousewheel'  );
		wp_enqueue_script(  'jquery_touchswipe' );
		wp_enqueue_script(  'jquery_transit' );
		wp_enqueue_script(  'jquery_throttle'  );	
	 // change title to lowercase
		if( $title ){
	 		$product_container =  strtolower ( $title );
			$product_container = str_replace( ' ', '_',  $product_container  );
			$product_container = preg_replace('/[^A-Za-z0-9\-]/', '',  $title );
		}else{
			$product_container = 'featured_product_carousel';
		}
		?>
		<script type="text/javascript">
		jQuery.noConflict();
			jQuery(window).load(function(e){	 
		        jQuery(".<?php echo $product_container; ?> ").carouFredSel({
					circular: true,
					infinite: true,
					auto    : false,			
		            swipe: {
		                onMouse: false,
		                onTouch: false
		            },
					scroll:{
						duration: 1000	
					},
					 prev    : {
				        button  : "#<?php echo $product_container; ?>_prev",
			    	   key     : "left"
		    		},
					    next    : {
				        button  : "#<?php echo $product_container; ?>_next",
				        key     : "right"
		    		},
		        });
		    });
		    </script>
		   <div class="product_carousel ">
				<div class="recent-title">   
		        		<h4><?php echo $title;?></h4> 
		        </div>
		    <div class="list_carousel_nav">
		       <a class="list_prev" id="<?php echo $product_container; ?>_prev" href="#"></a>
			   <a class="list_next" id="<?php echo $product_container; ?>_next" href="#"></a>
			</div>        
		   	<div class="list_carousel">
		         <ul class="products <?php echo $product_container; ?>">
		                        <?php   
		                        $counter = 1;
		                        $number = 4;
		                        $post_widget_count = 1;
		                   
						   if( !empty($product_cat) ){    
								 $args = array(
									  'post_type' => 'product',
									  'product_cat' => $product_cat,
									  'posts_per_page' => $product_items,
									  'orderby' => $order_by,
									  'order' => $order						  	  
								);
						   }else if($onsale)	{
								// check if settings is set to on-sale  
									// Get products on sale
									$product_ids_on_sale = woocommerce_get_product_ids_on_sale();
									$product_ids_on_sale[] = 0;
							
									$meta_query = $woocommerce->query->get_meta_query();
							
									$args = array(
										'posts_per_page' 	=> $product_items,
										'no_found_rows' => 1,
										'post_status' 	=> 'publish',
										'post_type' 	=> 'product',
										'orderby' 		=> $order_by,
										'order' 		=> $order,
										'meta_query' 	=> $meta_query,
										'post__in'		=> $product_ids_on_sale
									);	
							}else if( $feature ){
								// check if settings is set to faetured product			
							 $args = array(
								  'post_type' => 'product',
								  'posts_per_page' => $product_items,
								  'orderby' => $order_by,
								  'order' => $order						  	  
							);						
		                     $args['meta_query'][] = array(
		                                'key' => '_featured',
		                                'value' => 'yes'
		                            );
							}else{
								 $args = array(
									'post_type' => 'product',
									'posts_per_page' => $product_items,
									'orderby' => $order_by,
									'order' => $order						  	  
								);							
							}
									
		                        $query = null;
		                        $query = new WP_Query($args);
		                        if( $query->have_posts() ) {
		                        while ($query->have_posts()) : $query->the_post();
		                                    global $post;	
		                                    $post_images = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID )); 
		                                    $thumbnail= wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );	
		                                    ?>
		  								<li class="product <?php  if($counter % $number == 0){?>last<?php } ?>">
		                                <a href="<?php the_permalink(); ?>">
											<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
		                                 </a>
											<div class="post_content">
												<h3><a class="widget-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
												<div><?php 
												do_action( 'woocommerce_after_shop_loop_item_title' );
												do_action( 'woocommerce_after_shop_loop_item' );
												?></div>
												
											</div>
										</li>
		                      <?php  
		                                $counter++;		
		                                $post_widget_count++;
		                            endwhile; 
		                        }else{
										_e( 'No products available.','eshop');
							  }	
		                    wp_reset_query(); ?>
		            </ul>	
		      </div>
		</div>
<?php }
}