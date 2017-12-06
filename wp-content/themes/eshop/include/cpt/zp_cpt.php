<?php 

// ZP Custom Post Types Initialization

 

function zp_custom_post_type() {

    if ( ! class_exists( 'Super_CPT' ) )

        return;

		

/*----------------------------------------------------*/

// Add Slide Custom Post Type

/*---------------------------------------------------*/

 

 	$slide_custom_default = array(

		'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt'),

		'menu_icon' => get_stylesheet_directory_uri().'/include/cpt/images/slide.png',

	);



	// register slide post type

	$slide = new Super_Custom_Post_Type( 'slide', 'Slide', 'Slides',  $slide_custom_default );

	$slideshow = new Super_Custom_Taxonomy( 'slideshow' ,'Slideshow', 'Slideshows', 'cat' );

	connect_types_and_taxes( $slide, array( $slideshow ) );



	// Slide meta boxes

	$slide->add_meta_box( array(

		'id' => 'slider-settings',

		'context' => 'normal',

		'priority' => 'high',

		'fields' => array(

			'video_type_value' => array( 'label' => __('Video Type','eshop'), 'type' => 'select', 'options' => array('youtube' => 'Youtube','vimeo' => 'Vimeo') , 'data-zp_desc' => __('Select appropriate video type','eshop')),

			'video_id_value' => array( 'label' => __('Video ID','eshop'), 'type' => 'text', 'data-zp_desc' => __('Enter video ID. Example: VdvEdMMtNMY','eshop') ),

			'slider_link_value' => array( 'label' => __('Slide Link','eshop'), 'type' => 'text', 'data-zp_desc' => __('Enter Slide Link: http://www.zigzagpress.com','eshop') ),

			'button_name_value' => array( 'label' => __('Button Name','eshop'), 'type' => 'text', 'data-zp_desc' => __('Enter Button Name','eshop') )

		)

	) );	



	$slide->add_meta_box( array(

		'id' => 'slide-order',

		'context' => 'side',

		'fields' => array(

			'slide_number_value' => array( 'type' => 'text', 'data-zp_desc' => __('Define slide order. Ex. 1,2,3,4,...','eshop') ),		

		)

	) );		



	// manage slide columns 

	function zp_add_slide_columns($columns) {

		return array(

			'cb' => '<input type="checkbox" />',

			'title' => __('Title', 'eshop'),

			'slideshow' => __('Slideshow', 'eshop'),

			'slide_order' =>__( 'Slide Order', 'eshop'),

			'date' => __('Date', 'eshop'),			

		);

	}

	add_filter('manage_slide_posts_columns' , 'zp_add_slide_columns');



	function zp_custom_slide_columns( $column, $post_id ) {

		switch ( $column ) {

		case 'slideshow' :

			$terms = get_the_term_list( $post_id , 'slideshow' , '' , ',' , '' );

			if ( is_string( $terms ) )

				echo $terms;

			else

				_e( 'Unable to get author(s)', 'eshop' );

			break;

	

		case 'slide_order' :

			echo get_post_meta( $post_id , 'slide_number_value' , true ); 

			break;

		}

	}

	add_action( 'manage_posts_custom_column' , 'zp_custom_slide_columns', 10, 2 );

	

	



		

/*----------------------------------------------------*/

// Add Portfolio Custom Post Type

/*---------------------------------------------------*/



	$portfolio_custom_default = array(

		'supports' => array( 'title', 'editor', 'thumbnail', 'revisions', 'excerpt'),	

		'menu_icon' =>  get_stylesheet_directory_uri().'/include/cpt/images/portfolio.png',

	);



	// register portfolio post type

	$portfolio = new Super_Custom_Post_Type( 'portfolio', 'Portfolio', 'Portfolio',  $portfolio_custom_default );

	$portfolio_category = new Super_Custom_Taxonomy( 'portfolio_category' ,'Portfolio Category', 'Portfolio Categories', 'cat' );

	connect_types_and_taxes( $portfolio, array( $portfolio_category ) );



	$portfolio->add_meta_box( array(

		'id' => 'portfolio-metaItem',

		'context' => 'normal',

		'priority' => 'high',

		'fields' => array(

			'zp_portfolio_meta_item_value' => array( 'label' =>__( 'Include Portfolio MetaItem','eshop'), 'type' => 'select', 'options' => array('true' => 'True','false' => 'False'), 'data-zp_desc' => __('Select True to include meta on portfolio single page.','eshop') ),

			'client_item_label_value' => array( 'label' => __('Client Label','eshop'), 'type' => 'text', 'data-zp_desc' => __('Define client label','eshop') ),

			'client_item_value' => array( 'label' => __('Client Value','eshop'), 'type' => 'text', 'data-zp_desc' => __('Define client Value','eshop')  ),		

				

			'date_item_label_value' => array( 'label' => __('Date Label','eshop'), 'type' => 'text' , 'data-zp_desc' => __('Define date label','eshop') ),	

			'date_item_value' => array( 'label' => __('Client Label','eshop'), 'type' => 'text', 'data-zp_desc' =>  __('Define date label','eshop')  ),				

			

			'category_item_label_value' => array( 'label' =>__( 'Category Label','eshop'), 'type' => 'text', 'data-zp_desc' => __('Define category label' ,'eshop') ),	

			'category_item_value' => array( 'label' => __('Category Value','eshop'), 'type' => 'text', 'data-zp_desc' => __('Define category value','eshop')  ),			

	

			'visit_project_label_value' => array( 'label' => __('Visit Project Label','eshop'), 'type' => 'text', 'data-zp_desc' => __('Define visit project label','eshop')  ),	

			'visit_project_value' => array( 'label' => __('Visit Project Link','eshop'), 'type' => 'text', 'data-zp_desc' => __('Define visit project link','eshop')  ),	

					

		)

	) );


	$portfolio->add_meta_box( array(
			'id' => 'portfolio-images',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				'portfolio_images' => array( 'label' => __( 'Upload/Attach an image to this portfolio item. Images attached in here will be shown in the single portfolio page as a slider','prestige'), 'type' => 'multiple_media' , 'data-zp_desc' => __( 'Attach images to this portfolio item','prestige' )),
			)
		) );



	$portfolio->add_meta_box( array(

		'id' => 'portfolio-videos',

		'context' => 'normal',

		'priority' => 'high',

		'fields' => array(

			'zp_video_url_value' => array( 'label' => __('Youtube or Vimeo URL','eshop'), 'type' => 'text', 'data-zp_desc' => __('Place here the video url. Example video url: YOUTUBE: http://www.youtube.com/watch?v=Sv5iEK-IEzw and VIMEO: http://vimeo.com/7585127','eshop') ),		

			'zp_video_embed_value' => array( 'label' => __('Embed Code','eshop'), 'type' => 'textarea' , 'data-zp_desc' => __('If you are using anything else other than YouTube or Vimeo, paste the embed code here. This field will override anything from the above.','eshop') ),	

			'zp_height_value' => array( 'label' => __('Video Height','eshop'), 'type' => 'text', 'data-zp_desc' => __('Please input your video height. Example: 300','eshop') )

												

		)

	) );		

	

	

	// manage portfolio columns 

	function zp_add_portfolio_columns($columns) {

		return array(

			'cb' => '<input type="checkbox" />',

			'title' => __('Title', 'eshop'),

			'portfolio_category' => __('Portfolio Category', 'eshop'),

			'author' =>__( 'Author', 'eshop'),

			'date' => __('Date', 'eshop'),			

		);

	}

	add_filter('manage_portfolio_posts_columns' , 'zp_add_portfolio_columns');



	function zp_custom_portfolio_columns( $column, $post_id ) {

		switch ( $column ) {

		case 'portfolio_category' :

			$terms = get_the_term_list( $post_id , 'portfolio_category' , '' , ',' , '' );

			if ( is_string( $terms ) )

				echo $terms;

			else

				_e( 'Unable to get author(s)', 'eshop' );

			break;

		}

	}

	add_action( 'manage_posts_custom_column' , 'zp_custom_portfolio_columns', 10, 2 );

	

/*----------------------------------------------------*/

// Add Page Custom Meta

/*---------------------------------------------------*/



	$page_meta = new Super_Custom_Post_Meta( 'page' );



	$page_meta->add_meta_box( array(

		'id' => 'portfolio-page-settings',

		'context' => 'side',

		'priority' => 'high',

		'fields' => array(

			'column_number_value' => array( 'label' => __('Number of Columns','eshop'), 'type' => 'select' , 'options' => array('2' => 'Two Columns','3' => 'Three Columns', '4' => 'Four Columns'), 'data-zp_desc' => __('Choose the portfolio page columns.','eshop'))

		)

	) );	

			

		

}

add_action( 'after_setup_theme', 'zp_custom_post_type' );