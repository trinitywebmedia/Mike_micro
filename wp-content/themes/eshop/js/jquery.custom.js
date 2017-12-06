/*-------------------------------------------------*/
// Custom JS
/*-------------------------------------------------*/
jQuery.noConflict();

// portfolio dimension on window resize
function zp_portfolio_item_dimension(){
	var window_width = jQuery(window).width();
	var container_width = jQuery('#container').width();
	
	if( window_width < 480 ){
		jQuery('.element').each( function(){
			// 2 columns
			if( jQuery(this).hasClass( 'element-2col' )  ){
				item_height = jQuery(this).children('.portfolio_image').children('img').height();
				item_width  = Math.floor(( container_width - 20 ) / 1);
				jQuery(this).css({"width": item_width+"px", "max-width":item_width+"px"});
				jQuery(this).children('.portfolio_image').css({"height": item_height+"px"});
				
			}
			
			//3 columns
			if( jQuery(this).hasClass( 'element-3col' ) ){
				item_height = jQuery(this).children('.portfolio_image').children('img').height();
				item_width  = Math.floor(( container_width - 20 ) / 1);
				jQuery(this).css({"width": item_width+"px", "max-width":item_width+"px"});
				jQuery(this).children('.portfolio_image').css({"height": item_height+"px"});
			}
			
			// 4 columns
			if( jQuery(this).hasClass( 'element-4col' ) ){
				item_height = jQuery(this).children('.portfolio_image').children('img').height();
				item_width  = Math.floor(( container_width - 20 ) / 1);
				jQuery(this).css({"width": item_width+"px", "max-width":item_width+"px"});
				jQuery(this).children('.portfolio_image').css({"height": item_height+"px"});
			}
		});
	}else if( window_width <= 600 ){
		jQuery('.element').each( function(){
			// 2 columns
			if( jQuery(this).hasClass( 'element-2col' )  ){
				item_height = jQuery(this).children('.portfolio_image').children('img').height();
				item_width  = Math.floor(( container_width - 40 ) / 2);
				jQuery(this).css({"width": item_width+"px"});
				jQuery(this).children('.portfolio_image').css({"height": item_height+"px"});
			}
			
			//3 columns
			if( jQuery(this).hasClass( 'element-3col' ) ){
				item_height = jQuery(this).children('.portfolio_image').children('img').height();
				item_width  = Math.floor(( container_width - 40 ) / 2);
				jQuery(this).css({"width": item_width+"px"});
				jQuery(this).children('.portfolio_image').css({"height": item_height+"px"});
			}
			
			// 4 columns
			if( jQuery(this).hasClass( 'element-4col' ) ){
				item_height = jQuery(this).children('.portfolio_image').children('img').height();
				item_width  = Math.floor(( container_width - 40 ) / 2);
				jQuery(this).css({"width": item_width+"px"});
				jQuery(this).children('.portfolio_image').css({"height": item_height+"px"});
			}	
		});
	}else{
		jQuery('.element').each( function(){
			// 2 columns
			if( jQuery(this).hasClass( 'element-2col' )  ){
				item_height = jQuery(this).children('.portfolio_image').children('img').height();
				item_width  = Math.floor(( container_width - 40 ) / 2);
				jQuery(this).css({"width": item_width+"px"});
				jQuery(this).children('.portfolio_image').css({"height": item_height+"px"});
			}
			
			//3 columns
			if( jQuery(this).hasClass( 'element-3col' ) ){
				item_height = jQuery(this).children('.portfolio_image').children('img').height();
				item_width  = Math.floor(( container_width - 60 ) / 3);
				jQuery(this).css({"width": item_width+"px"});
				jQuery(this).children('.portfolio_image').css({"height": item_height+"px"});
			}
			
			// 4 columns
			if( jQuery(this).hasClass( 'element-4col' ) ){
				item_height = jQuery(this).children('.portfolio_image').children('img').height();
				item_width  = Math.floor(( container_width - 80 ) / 4);
				jQuery(this).css({"width": item_width+"px"});
				jQuery(this).children('.portfolio_image').css({"height": item_height+"px"});
			}	
		});
	}
	
}


jQuery(document).ready(function(jQuery) {
/*-------------------------------------------------*/
//	To Top Link
/*-------------------------------------------------*/
		jQuery.fn.topLink = function(settings) {
			settings = jQuery.extend({
				min: 1,
				fadeSpeed: 200
				},
				settings );
				return this.each(function() {
					var el = jQuery(this);
					el.hide(); 
					jQuery(window).scroll(function() {
					if(jQuery(window).scrollTop() >= settings.min) {
					el.fadeIn(settings.fadeSpeed);
					} else {
					el.fadeOut(settings.fadeSpeed);
					}
				});
			});
			};			
			jQuery(document).ready(function() {

				jQuery('#top-link').topLink({

				min: 400,

				fadeSpeed: 500

				});							

				jQuery('#top-link').click(function() {

					 jQuery(' html body').stop().animate({		

								scrollTop: jQuery('body').offset().top		

							}, 1000);

						   //e.preventDefault();

				});

			});		

/*-------------------------------------------------*/

// Isotope Filter Effect

/*-------------------------------------------------*/

	// cache container
	zp_portfolio_item_dimension();

	var jQuerycontainer = jQuery('#container');

	jQuerycontainer.isotope({

		 itemSelector : '.element'

	});

	//filter

	var jQueryoptionSets = jQuery('#options .option-set'),

	  jQueryoptionLinks = jQueryoptionSets.find('a');							

		  jQueryoptionLinks.click(function(){

			var jQuerythis = jQuery(this);	

			// don't proceed if already selected

			if ( jQuerythis.hasClass('selected') ) {

			  return false;

			}		

			var jQueryoptionSet = jQuerythis.parents('.option-set');

			jQueryoptionSet.find('.selected').removeClass('selected');

			jQuerythis.addClass('selected');								  

			// make option object dynamically, i.e. { filter: '.my-filter-class' }

			var options = {},

				key = jQueryoptionSet.attr('data-option-key'),

				value = jQuerythis.attr('data-option-value');

				// parse 'false' as false boolean

				value = value === 'false' ? false : value;

				options[ key ] = value;			

			if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {

			  // changes in layout modes need extra logic

			  changeLayoutMode( jQuerythis, options )

			} else {

			 // otherwise, apply new options

				jQuerycontainer.isotope( options );

			}										

			return false;

	});

/*-------------------------------------------------*/

// Portfolio Hover Effect

/*-------------------------------------------------*/			

	jQuery('.portfolio_image').hover(function(){	

		jQuery(this).children('.icon').css({display: 'block'});

	}, function(){

		jQuery(this).children('.icon').css({display: 'none'});		

	});			

/*-------------------------------------------------*/

// Tooltip

/*-------------------------------------------------*/		

			jQuery(".hastip").tipTip({defaultPosition: "top"});			

/*-------------------------------------------------*/

// Mobile Menu

/*-------------------------------------------------*/							

		/*jQuery('.nav-primary ul.menu').mobileMenu({

			defaultText: 'Navigate to...',

			className: 'select-menu',

			subMenuDash: '&nbsp;&nbsp;&nbsp;&ndash;'

		});*/

		jQuery( '.mobile_menu' ).toggle(function(){
			jQuery('.nav-primary .menu').show();
		},function(){
			jQuery('.nav-primary .menu').hide();
		});			

/*-------------------------------------------------*/

// Lightbox (Prettyphoto)

/*-------------------------------------------------*/	

			jQuery("a[rel^='prettyPhoto']").prettyPhoto({

				theme: 'light_rounded',

				counter_separator_label: ' of '

			});

/*-------------------------------------------------*/

// Image Hover

/*-------------------------------------------------*/	

			jQuery( ".ad img, .portfolio-large img" ).hover( 

				function( ) {

					jQuery( this ).animate( {"opacity": ".8"}, "fast" );

					},

				function( ) {

					jQuery( this ).animate( {"opacity": "1"}, "fast" );

			} );																		

/*-------------------------------------------------*/

// Toggle 

/*-------------------------------------------------*/	

			jQuery( ".toggle-container" ).hide( );	 

			jQuery( ".trigger" ).toggle( function( ){

				jQuery( this ).addClass( "active" );

				}, function ( ) {

				jQuery( this ).removeClass( "active" );

			} );

			jQuery( ".trigger" ).click( function( ){

				jQuery( this ).next( ".toggle-container" ).slideToggle( );

			} );

			jQuery( '.trigger a' ).hover( function( ) {

			jQuery( this ).stop( true,false ).animate( {color: '#666'},50 );

				}, function ( ) {

				jQuery( this ).stop( true,false ).animate( {color: '#888'},150 );

			} );

/*-------------------------------------------------*/

// Accordion

/*-------------------------------------------------*/	

			jQuery( '.accordion' ).hide( );

			jQuery( '.trigger-button' ).click( function( ) {

				jQuery( ".trigger-button" ).removeClass( "active" )

				jQuery( '.accordion' ).slideUp( 'normal' );

				if( jQuery( this ).next( ).is( ':hidden' ) == true ) {

					jQuery( this ).next( ).slideDown( 'normal' );

					jQuery( this ).addClass( "active" );

				 } 

			 } );

			 jQuery( '.trigger-button' ).hover( function( ) {

				jQuery( this ).stop( true,false ).animate( {color: '#666'},50 );

					}, function ( ) {

					jQuery( this ).stop( true,false ).animate( {color: '#888'},150 );

			} );							
});

/* ========== ISOTOPE FILTERING ========== */

jQuery(window).load(function(){
	
		zp_portfolio_item_dimension();
		
	var jQuerycontainer = jQuery('#container');
	jQuerycontainer.isotope({
		 itemSelector : '.element'
	});

});

/*-------------------------------------------------------------*/
//			Refresh isotope when window resize
/*------------------------------------------------------------*/	
jQuery( window ).smartresize(function() {
	
	zp_portfolio_item_dimension();
	
	var jQuerycontainer = jQuery('#container');
	jQuerycontainer.isotope({
		 itemSelector : '.element'
	});
});
