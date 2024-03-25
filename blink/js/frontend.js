/*!
 * Script for initializing frontend functions and libs.
 *
 * @since 1.0.0
 */
/* global jQuery, blinkFitVids */
( function($) {
	'use strict';

	var Blink = {
		cache: {},

		init: function(){
			this.cacheElements();
			this.bindEvents();
		},

		cacheElements: function() {
			this.cache.$window     = $(window);
			this.cache.$document   = $(document);
			this.cache.$body       = $('body');
			this.cache.$menuToggle = $('#menu-toggle');
			this.cache.$MenuBar    = $('#site-navigation');
		},

		bindEvents: function(){
			var self = this;

			// Menu button toggles Blink Bar
			self.cache.$menuToggle.on('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				self.toggleMenuBar();
			});

			self.cache.$document.on('ready', function() {
				self.skipLinkFocusFix();
			});

			// FitVids
			self.cache.$document.on('ready post-load', function() {
				self.fitVidsSetup();
			});
		},

		/**
		 * Toggle the state of the Blink Bar
		 *
		 * @since 1.0.0.
		 *
		 * @return void
		 */
		toggleMenuBar: function() {
			var $body = this.cache.$body,
				$menuBar =	this.cache.$MenuBar,
				$menuToggle = this.cache.$menuToggle;

			$menuToggle.find('.genericon').toggleClass('genericon-menu genericon-close-alt');

			if ( $menuBar.hasClass('toggled-on') ) {
				$body.removeClass('pushed');
				$menuBar.removeClass('toggled-on');
			} else {
				$body.addClass('pushed');
				$menuBar.addClass('toggled-on');
			}
		},

		fitVidsSetup: function() {
			// Make sure lib is loaded.
			if ( ! $.fn.fitVids ) {
				return;
			}

			// Update the cache
			this.cache.$fitvids = $('.entry-content');

			var args = {};

			// Get custom selectors
			if ('object' === typeof blinkFitVids) {
				args.customSelector = blinkFitVids.selectors;
			}

			// Run FitVids
			this.cache.$fitvids.fitVids(args);

			// Fix padding issue with Blip.tv. Note that this *must* happen after Fitvids runs.
			// The selector finds the Blip.tv iFrame, then grabs the .fluid-width-video-wrapper div sibling.
			this.cache.$fitvids.find('.fluid-width-video-wrapper:nth-child(2)').css({ 'paddingTop': 0 });
		},

		skipLinkFocusFix: function(){
			var is_webkit = navigator.userAgent.toLowerCase().indexOf( 'webkit' ) > -1,
			    is_opera  = navigator.userAgent.toLowerCase().indexOf( 'opera' )  > -1,
			    is_ie     = navigator.userAgent.toLowerCase().indexOf( 'msie' )   > -1;

			if ( ( is_webkit || is_opera || is_ie ) && document.getElementById && window.addEventListener ) {
				window.addEventListener( 'hashchange', function() {
					var element = document.getElementById( location.hash.substring( 1 ) );

					if ( element ) {
						if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) {
							element.tabIndex = -1;
						}

						element.focus();
					}
				}, false );
			}
		}
	};

	Blink.init();
} )( jQuery );
