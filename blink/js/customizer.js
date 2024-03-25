/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */
/* global wp */
( function( $ ) {
	var api = wp.customize;

	// Site title and description.
	api( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	api( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	api( 'header_image-text', function( value ) {
		value.bind( function( to ) {
			$( '.custom-header__text' ).html( to );
		} );
	} );

	api( 'header_image-color', function( value ) {
		value.bind( function( to ) {
			$( '.custom-header__overlay' ).css('background-color', to );
		} );
	} );

	api( 'header_image-opacity', function( value ) {
		value.bind( function( to ) {
			$('.custom-header__overlay').css('opacity', to/100);
		} );
	} );

	api( 'layout-site', function( value ) {
		value.bind( function( to ) {
			var $classes = 'layout-odd layout-even layout-hero layout-half';
			$( 'body' ).removeClass($classes).addClass( 'layout-' + to );
		} );
	} );

	api( 'layout-uppercase-title', function( value ) {
		value.bind( function( to ) {
			if ( to === true ) {
				$( '.entry-title, .nav-links [rel]' ).css('text-transform', 'uppercase');
			} else {
				$( '.entry-title, .nav-links [rel]' ).css('text-transform', 'none');
			}
		} );
	} );

} )( jQuery );
