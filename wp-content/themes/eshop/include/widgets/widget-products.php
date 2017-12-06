<?php
add_action( 'widgets_init', 'zp_custom_product_widget' );
function zp_custom_product_widget() {
	register_widget( 'zp_custom_product_widget' );
}
class zp_custom_product_widget extends WP_Widget {		
	function __construct() {
		//Constructor
		global $thumb_url;
		$widget_ops = array('classname' => 'widget zp_custom_product_widget', 'description' => __('Display a slider of featured, latest and on-sale products. Best used inside "Home Middle Widget" in the homepage','eshop') );
		parent::__construct('zp_custom_product_widget',__('ZP Custom Product Widget','eshop'), $widget_ops);
	}
	 
	function widget($args, $instance) {
		// prints the widget
			
			extract($args, EXTR_SKIP);
	 
			echo $before_widget;
			$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			$category = empty($instance['category']) ? '' : apply_filters('widget_category', $instance['category']);
			$number = empty($instance['number']) ? '15' : apply_filters('widget_number', $instance['number']);
			$type = empty($instance['type']) ? '' : apply_filters('widget_text', $instance['type']);
			$orderby = empty($instance['orderby']) ? '' : $instance['orderby'];
			$order = empty($instance['order']) ? '' : $instance['order'];
			global $post,$wpdb, $woocommerce,$product;
			$post_widget_count = 1;
			$counter=1;
			
			switch( $type ){					
			
				case 'featured':
					zp_product_slider( $title, true, false, '' , $number ,$orderby , $order );
					break;
				
				case 'latest':
					zp_product_slider( $title, false, false, '' , $number ,'date' , 'DESC' );
					break;
				case 'onsale':
					zp_product_slider( $title, false, true, '' , $number ,$orderby , $order );
					break;
				case 'category':
					zp_product_slider( $title, false, false, $category , $number ,$orderby , $order );
					break;		
					
				default:
					break;												
			}
			

	 echo $after_widget;
		}
		function update($new_instance, $old_instance) {
		//save the widget
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['category'] = strip_tags($new_instance['category']);
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['type'] = strip_tags($new_instance['type']);
			$instance['orderby'] = strip_tags($new_instance['orderby']);
			$instance['order'] = strip_tags($new_instance['order']);
			return $instance;
		}
	 
		function form($instance) {
		//widgetform in backend
			$instance = wp_parse_args( (array) $instance, array(  'title' => '',  'category' => '', 'number' => '', 'type'=>'', 'orderby'=>'', 'order'=>'' ) );
			$title = strip_tags($instance['title']);
			$category = strip_tags($instance['category']);
			$number = strip_tags($instance['number']);
			$type = strip_tags($instance['type']);
			$orderby = strip_tags($instance['orderby']);
			$order = strip_tags($instance['order']);
	?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','eshop');?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
			<p>
			  <label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Display Type:','eshop');?>
				
				<select id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>">
					<option value="featured" <?php if($type=='featured'){?> selected="selected"<?php }?> ><?php _e("Featured","eshop");?></option>
					<option value="latest" <?php if($type=='latest'){?> selected="selected"<?php }?> ><?php _e("Latest","eshop");?></option>
					<option value="onsale" <?php if($type=='onsale'){?> selected="selected"<?php }?> ><?php _e("Onsale","eshop");?></option>
	                <option value="category" <?php if($type=='category'){?> selected="selected"<?php }?> ><?php _e("Category","eshop");?></option>
				</select>
				</label>
			</p>
			<p>
			  <label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Product Category. Applies to Category display type:','eshop');?>
			  <input class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" type="text" value="<?php echo esc_attr($category); ?>" />
			  </label>
			</p>
	        <p>
			  <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts:','eshop');?>
			  <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" />
			  </label>
			</p>
			<p>
			  <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Order By:','eshop');?>
				
				<select id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>">
					<option value="date" <?php if($orderby=='date'){?> selected="selected"<?php }?> ><?php _e("Date","eshop");?></option>
					<option value="rand" <?php if($orderby=='rand'){?> selected="selected"<?php }?> ><?php _e("Random","eshop");?></option>
					<option value="title" <?php if($orderby=='title'){?> selected="selected"<?php }?> ><?php _e("Title","eshop");?></option>
				</select>
				</label>
			</p>
			<p>
			  <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order:','eshop');?>
				
				<select id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
					<option value="ASC" <?php if($order=='ASC'){?> selected="selected"<?php }?> ><?php _e("Ascending","eshop");?></option>
					<option value="DESC" <?php if($order=='DESC'){?> selected="selected"<?php }?> ><?php _e("Descending","eshop");?></option>
				</select>
				</label>
			</p>
	<?php
		}
}
?>