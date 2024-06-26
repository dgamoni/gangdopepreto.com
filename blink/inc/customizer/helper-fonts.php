<?php
/**
 * @package Blink
 *
 * @since 1.0.0.
 */

if ( ! function_exists( 'blink_get_font_stack' ) ) :
/**
 * Validate the font choice and get a font stack for it.
 *
 * @since  1.0.0.
 *
 * @param  string    $font    The 1st font in the stack.
 * @return string             The full font stack.
 */
function blink_get_font_stack( $font ) {
	$all_fonts = blink_get_all_fonts();

	// Sanitize font choice
	$font = blink_sanitize_font_choice( $font );

	// Standard font
	if ( isset( $all_fonts[ $font ]['stack'] ) && ! empty( $all_fonts[ $font ]['stack'] ) ) {
		$stack = $all_fonts[ $font ]['stack'];
	} elseif ( in_array( $font, blink_all_font_choices() ) ) {
		$stack = '"' . $font . '","Helvetica Neue",Helvetica,Arial,sans-serif';
	} else {
		$stack = '"Helvetica Neue",Helvetica,Arial,sans-serif';
	}

	return $stack;
}
endif;

if ( ! function_exists( 'blink_get_relative_font_size' ) ) :
/**
 * Convert a font size to a relative size based on a starting value and percentage.
 *
 * @since  1.0.0.
 *
 * @param  mixed    $value         The value to base the final value on.
 * @param  mixed    $percentage    The percentage of change.
 * @return float                   The converted value.
 */
function blink_get_relative_font_size( $value, $percentage ) {
	return (float) $value * ( $percentage / 100 );
}
endif;

if ( ! function_exists( 'blink_convert_px_to_rem' ) ) :
/**
 * Given a px value, return a rem value.
 *
 * @since  1.0.0.
 *
 * @param  mixed    $px      The value to convert.
 * @param  mixed    $base    The font-size base for the rem conversion.
 * @return float             The converted value.
 */
function blink_convert_px_to_rem( $px, $base = 0 ) {
	return (float) $px / 10;
}
endif;

if ( ! function_exists( 'blink_get_google_font_uri' ) ) :
/**
 * Build the HTTP request URL for Google Fonts.
 *
 * @since  1.0.0.
 *
 * @return string    The URL for including Google Fonts.
 */
function blink_get_google_font_uri() {
	// Grab the font choices
	$font_keys = array(
		'fonts-header',
		'fonts-body',
	);

	$fonts = array();
	foreach ( $font_keys as $key ) {
		$fonts[] = get_theme_mod( $key, blink_get_default( $key ) );
	}

	// De-dupe the fonts
	$fonts         = array_unique( $fonts );
	$allowed_fonts = blink_get_google_fonts();
	$family        = array();

	// Validate each font and convert to URL format
	foreach ( $fonts as $font ) {
		$font = trim( $font );

		// Verify that the font exists
		if ( array_key_exists( $font, $allowed_fonts ) ) {
			// Build the family name and variant string (e.g., "Open+Sans:regular,italic,700")
			$family[] = urlencode( $font . ':' . join( ',', blink_choose_google_font_variants( $font, $allowed_fonts[ $font ]['variants'] ) ) );
		}
	}

	// Convert from array to string
	if ( empty( $family ) ) {
		return '';
	} else {
		$request = '//fonts.googleapis.com/css?family=' . implode( '|', $family );
	}

	// Load the font subset
	$subset = get_theme_mod( 'fonts-subset', blink_get_default( 'fonts-subset' ) );

	if ( 'all' === $subset ) {
		$subsets_available = blink_get_google_font_subsets();

		// Remove the all set
		unset( $subsets_available['all'] );

		// Build the array
		$subsets = array_keys( $subsets_available );
	} else {
		$subsets = array(
			'latin',
			$subset,
		);
	}

	// Append the subset string
	if ( ! empty( $subsets ) ) {
		$request .= urlencode( '&subset=' . join( ',', $subsets ) );
	}

	return esc_url( $request );
}
endif;

add_filter( 'blink_font_uri', 'blink_get_google_font_uri' );

if ( ! function_exists( 'blink_choose_google_font_variants' ) ) :
/**
 * Given a font, chose the variants to load for the theme.
 *
 * Attempts to load regular, italic, and 700. If regular is not found, the first variant in the family is chosen. italic
 * and 700 are only loaded if found. No fallbacks are loaded for those fonts.
 *
 * @since  1.0.0.
 *
 * @param  string    $font        The font to load variants for.
 * @param  array     $variants    The variants for the font.
 * @return array                  The chosen variants.
 */
function blink_choose_google_font_variants( $font, $variants = array() ) {
	$chosen_variants = array();
	if ( empty( $variants ) ) {
		$fonts = blink_get_google_fonts();

		if ( array_key_exists( $font, $fonts ) ) {
			$variants = $fonts[ $font ]['variants'];
		}
	}

	// If a "regular" variant is not found, get the first variant
	if ( ! in_array( 'regular', $variants ) ) {
		$chosen_variants[] = $variants[0];
	} else {
		$chosen_variants[] = 'regular';
	}

	// Only add "italic" if it exists
	if ( in_array( 'italic', $variants ) ) {
		$chosen_variants[] = 'italic';
	}

	// Only add "700" if it exists
	if ( in_array( '700', $variants ) ) {
		$chosen_variants[] = '700';
	}

	/**
	 * Allow developers to alter the font variant choice.
	 *
	 * @since 1.0.0.
	 *
	 * @param array 	$chosen_variants 	The list of variants for a font.
	 * @param string 	$font 				The font to load variants for.
	 * @param array 	variants 			The variants for the font.
	 */
	return apply_filters( 'blink_font_variants', array_unique( $chosen_variants ), $font, $variants );
}
endif;

if ( ! function_exists( 'blink_css_fonts' ) ) :
/**
 * Build the CSS rules for the custom fonts.
 *
 * @since 1.0.0.
 *
 * @return void
 */
function blink_css_fonts() {
	$font_header          = get_theme_mod( 'fonts-header', blink_get_default( 'fonts-header' ) );
	$font_header_stack    = blink_get_font_stack( $font_header );
	$font_body            = get_theme_mod( 'fonts-body', blink_get_default( 'fonts-body' ) );
	$font_body_stack      = blink_get_font_stack( $font_body );

	if ( $font_header != blink_get_default( 'fonts-header' ) ) :
	blink_get_css()->add( array(
		'selectors'    => array(
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6',
			'th',
			'label',
			'button',
			'.button',
			'input[type="button"]',
			'input[type="reset"]',
			'input[type="submit"]',
			'.stag-button',
			'.page-links',
			'.tags-label',
			'.entry-meta',
			'.no-comments',
			'.nav-links [rel]',
			'.main-navigation',
			'.stag-toggle-title',
			'.sticky-post-label',
			'.menu-toggle__label',
			'.comment-list .comment-author',
		),
		'declarations' => array(
			'font-family' => $font_header_stack,
		)
	) );
	endif;

	if ( $font_body != blink_get_default( 'fonts-body' ) ) :
	blink_get_css()->add( array(
		'selectors'    => array( 'body', '.font-body', 'input', 'select', 'textarea', '.entry-subtitle' ),
		'declarations' => array(
			'font-family' => $font_body_stack,
		)
	) );
	endif;
}
endif;

add_action( 'blink_css', 'blink_css_fonts' );

if ( ! function_exists( 'blink_sanitize_font_subset' ) ) :
/**
 * Sanitize the Character Subset choice.
 *
 * @since  1.0.0
 *
 * @param  string    $value    The value to sanitize.
 * @return array               The sanitized value.
 */
function blink_sanitize_font_subset( $value ) {
	if ( ! array_key_exists( $value, blink_get_google_font_subsets() ) ) {
		$value = 'latin';
	}

	return $value;
}
endif;

if ( ! function_exists( 'blink_get_google_font_subsets' ) ) :
/**
 * Retrieve the list of available Google font subsets.
 *
 * @since  1.0.0.
 *
 * @return array    The available subsets.
 */
function blink_get_google_font_subsets() {
	return array(
		'all'          => esc_html__( 'All', 'blink' ),
		'arabic'       => esc_html__( 'Arabic', 'blink' ),
		'bengali'      => esc_html__( 'Bengali', 'blink' ),
		'cyrillic'     => esc_html__( 'Cyrillic', 'blink' ),
		'cyrillic-ext' => esc_html__( 'Cyrillic Extended', 'blink' ),
		'devanagari'   => esc_html__( 'Devanagari', 'blink' ),
		'greek'        => esc_html__( 'Greek', 'blink' ),
		'greek-ext'    => esc_html__( 'Greek Extended', 'blink' ),
		'gujarati'     => esc_html__( 'Gujarati', 'blink' ),
		'hebrew'       => esc_html__( 'Hebrew', 'blink' ),
		'khmer'        => esc_html__( 'Khmer', 'blink' ),
		'latin'        => esc_html__( 'Latin', 'blink' ),
		'latin-ext'    => esc_html__( 'Latin Extended', 'blink' ),
		'tamil'        => esc_html__( 'Tamil', 'blink' ),
		'telugu'       => esc_html__( 'Telugu', 'blink' ),
		'thai'         => esc_html__( 'Thai', 'blink' ),
		'vietnamese'   => esc_html__( 'Vietnamese', 'blink' ),
	);
}
endif;

if ( ! function_exists( 'blink_sanitize_font_choice' ) ) :
/**
 * Sanitize a font choice.
 *
 * @since  1.0.0.
 *
 * @param  string    $value    The font choice.
 * @return string              The sanitized font choice.
 */
function blink_sanitize_font_choice( $value ) {
	if ( is_int( $value ) ) {
		// The array key is an integer, so the chosen option is a heading, not a real choice
		return '';
	} else if ( array_key_exists( $value, blink_all_font_choices() ) ) {
		return $value;
	} else {
		return '';
	}
}
endif;

if ( ! function_exists( 'blink_get_all_fonts' ) ) :
/**
 * Compile font options from different sources.
 *
 * @since  1.0.0.
 *
 * @return array    All available fonts.
 */
function blink_get_all_fonts() {
	$heading1            = array( 1 => array( 'label' => sprintf( '&mdash; %s &mdash;', __( 'Standard Fonts', 'blink' ) ) ) );
	$standard_fonts      = blink_get_standard_fonts();

	$google_fonts        = blink_get_google_fonts();

	$serif_heading       = array( 2 => array( 'label' => sprintf( '&mdash; %s &mdash;', __( 'Serif Fonts (Google)', 'blink' ) ) ) );
	$serif_fonts         = wp_list_filter( $google_fonts, array( 'category' => 'serif' ) );

	$sans_serif_heading  = array( 3 => array( 'label' => sprintf( '&mdash; %s &mdash;', __( 'Sans Serif Fonts (Google)', 'blink' ) ) ) );
	$sans_serif_fonts    = wp_list_filter( $google_fonts, array( 'category' => 'sans-serif' ) );

	$display_heading     = array( 4 => array( 'label' => sprintf( '&mdash; %s &mdash;', __( 'Display Fonts (Google)', 'blink' ) ) ) );
	$display_fonts       = wp_list_filter( $google_fonts, array( 'category' => 'display' ) );

	$handwriting_heading = array( 4 => array( 'label' => sprintf( '&mdash; %s &mdash;', __( 'Handwriting Fonts (Google)', 'blink' ) ) ) );
	$handwriting_fonts   = wp_list_filter( $google_fonts, array( 'category' => 'handwriting' ) );

	$monospace_heading   = array( 4 => array( 'label' => sprintf( '&mdash; %s &mdash;', __( 'Monospace Fonts (Google)', 'blink' ) ) ) );
	$monospace_fonts     = wp_list_filter( $google_fonts, array( 'category' => 'monospace' ) );

	return apply_filters( 'blink_all_fonts', array_merge(
		$heading1, $standard_fonts,
		$serif_heading, $serif_fonts,
		$sans_serif_heading, $sans_serif_fonts,
		$display_heading, $display_fonts,
		$handwriting_heading, $handwriting_fonts,
		$monospace_heading, $monospace_fonts
	) );
}
endif;

if ( ! function_exists( 'blink_all_font_choices' ) ) :
/**
 * Packages the font choices into value/label pairs for use with the customizer.
 *
 * @since  1.0.0.
 *
 * @return array    The fonts in value/label pairs.
 */
function blink_all_font_choices() {
	$fonts   = blink_get_all_fonts();
	$choices = array();

	// Repackage the fonts into value/label pairs
	foreach ( $fonts as $key => $font ) {
		$choices[ $key ] = $font['label'];
	}

	return $choices;
}
endif;

if ( ! function_exists( 'blink_get_standard_fonts' ) ) :
/**
 * Return an array of standard websafe fonts.
 *
 * @since  1.0.0.
 *
 * @return array    Standard websafe fonts.
 */
function blink_get_standard_fonts() {
	return array(
		'serif' => array(
			'label' => _x( 'Serif', 'font style', 'blink' ),
			'stack' => 'Georgia,Times,"Times New Roman",serif',
		),
		'sans-serif' => array(
			'label' => _x( 'Sans Serif', 'font style', 'blink' ),
			'stack' => '"Helvetica Neue",Helvetica,Arial,sans-serif',
		),
		'monospace' => array(
			'label' => _x( 'Monospaced', 'font style', 'blink' ),
			'stack' => 'Monaco,"Lucida Sans Typewriter","Lucida Typewriter","Courier New",Courier,monospace',
		)
	);
}
endif;

if ( ! function_exists( 'blink_get_google_fonts' ) ) :
/**
 * Return an array of all available Google Fonts.
 *
 * Updated: 2017-02-13T09:11:45+01:00.
 * Total 818 Fonts.
 *
 * @since  1.0.0.
 *
 * @return array All Google Fonts.
 */
function blink_get_google_fonts() {
	return apply_filters( 'blink_get_google_fonts', array(
		'ABeeZee' => array(
			'label' => 'ABeeZee',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Abel' => array(
			'label' => 'Abel',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Abhaya Libre' => array(
			'label' => 'Abhaya Libre',
			'variants' => array(
				'regular',
				'500',
				'600',
				'700',
				'800',
			),
			'subsets' => array(
				'sinhala',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Abril Fatface' => array(
			'label' => 'Abril Fatface',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Aclonica' => array(
			'label' => 'Aclonica',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Acme' => array(
			'label' => 'Acme',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Actor' => array(
			'label' => 'Actor',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Adamina' => array(
			'label' => 'Adamina',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Advent Pro' => array(
			'label' => 'Advent Pro',
			'variants' => array(
				'100',
				'200',
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'greek',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Aguafina Script' => array(
			'label' => 'Aguafina Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Akronim' => array(
			'label' => 'Akronim',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Aladin' => array(
			'label' => 'Aladin',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Aldrich' => array(
			'label' => 'Aldrich',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Alef' => array(
			'label' => 'Alef',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'hebrew',
			),
			'category' => 'sans-serif',
		),
		'Alegreya' => array(
			'label' => 'Alegreya',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Alegreya SC' => array(
			'label' => 'Alegreya SC',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Alegreya Sans' => array(
			'label' => 'Alegreya Sans',
			'variants' => array(
				'100',
				'100italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Alegreya Sans SC' => array(
			'label' => 'Alegreya Sans SC',
			'variants' => array(
				'100',
				'100italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Alex Brush' => array(
			'label' => 'Alex Brush',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Alfa Slab One' => array(
			'label' => 'Alfa Slab One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Alice' => array(
			'label' => 'Alice',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'cyrillic-ext',
			),
			'category' => 'serif',
		),
		'Alike' => array(
			'label' => 'Alike',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Alike Angular' => array(
			'label' => 'Alike Angular',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Allan' => array(
			'label' => 'Allan',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Allerta' => array(
			'label' => 'Allerta',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Allerta Stencil' => array(
			'label' => 'Allerta Stencil',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Allura' => array(
			'label' => 'Allura',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Almendra' => array(
			'label' => 'Almendra',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Almendra Display' => array(
			'label' => 'Almendra Display',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Almendra SC' => array(
			'label' => 'Almendra SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Amarante' => array(
			'label' => 'Amarante',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Amaranth' => array(
			'label' => 'Amaranth',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Amatic SC' => array(
			'label' => 'Amatic SC',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'hebrew',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Amatica SC' => array(
			'label' => 'Amatica SC',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'hebrew',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Amethysta' => array(
			'label' => 'Amethysta',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Amiko' => array(
			'label' => 'Amiko',
			'variants' => array(
				'regular',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Amiri' => array(
			'label' => 'Amiri',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'arabic',
				'latin',
			),
			'category' => 'serif',
		),
		'Amita' => array(
			'label' => 'Amita',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Anaheim' => array(
			'label' => 'Anaheim',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Andada' => array(
			'label' => 'Andada',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Andika' => array(
			'label' => 'Andika',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'Angkor' => array(
			'label' => 'Angkor',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Annie Use Your Telescope' => array(
			'label' => 'Annie Use Your Telescope',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Anonymous Pro' => array(
			'label' => 'Anonymous Pro',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'greek',
				'latin',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'monospace',
		),
		'Antic' => array(
			'label' => 'Antic',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Antic Didone' => array(
			'label' => 'Antic Didone',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Antic Slab' => array(
			'label' => 'Antic Slab',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Anton' => array(
			'label' => 'Anton',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Arapey' => array(
			'label' => 'Arapey',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Arbutus' => array(
			'label' => 'Arbutus',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Arbutus Slab' => array(
			'label' => 'Arbutus Slab',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Architects Daughter' => array(
			'label' => 'Architects Daughter',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Archivo Black' => array(
			'label' => 'Archivo Black',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Archivo Narrow' => array(
			'label' => 'Archivo Narrow',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Aref Ruqaa' => array(
			'label' => 'Aref Ruqaa',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'arabic',
				'latin',
			),
			'category' => 'serif',
		),
		'Arima Madurai' => array(
			'label' => 'Arima Madurai',
			'variants' => array(
				'100',
				'200',
				'300',
				'regular',
				'500',
				'700',
				'800',
				'900',
			),
			'subsets' => array(
				'latin',
				'tamil',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Arimo' => array(
			'label' => 'Arimo',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'hebrew',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'Arizonia' => array(
			'label' => 'Arizonia',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Armata' => array(
			'label' => 'Armata',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Arsenal' => array(
			'label' => 'Arsenal',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'Artifika' => array(
			'label' => 'Artifika',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Arvo' => array(
			'label' => 'Arvo',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Arya' => array(
			'label' => 'Arya',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Asap' => array(
			'label' => 'Asap',
			'variants' => array(
				'regular',
				'italic',
				'500',
				'500italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Asar' => array(
			'label' => 'Asar',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Asset' => array(
			'label' => 'Asset',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Assistant' => array(
			'label' => 'Assistant',
			'variants' => array(
				'200',
				'300',
				'regular',
				'600',
				'700',
				'800',
			),
			'subsets' => array(
				'latin',
				'hebrew',
			),
			'category' => 'sans-serif',
		),
		'Astloch' => array(
			'label' => 'Astloch',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Asul' => array(
			'label' => 'Asul',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Athiti' => array(
			'label' => 'Athiti',
			'variants' => array(
				'200',
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'thai',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Atma' => array(
			'label' => 'Atma',
			'variants' => array(
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'bengali',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Atomic Age' => array(
			'label' => 'Atomic Age',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Aubrey' => array(
			'label' => 'Aubrey',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Audiowide' => array(
			'label' => 'Audiowide',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Autour One' => array(
			'label' => 'Autour One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Average' => array(
			'label' => 'Average',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Average Sans' => array(
			'label' => 'Average Sans',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Averia Gruesa Libre' => array(
			'label' => 'Averia Gruesa Libre',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Averia Libre' => array(
			'label' => 'Averia Libre',
			'variants' => array(
				'300',
				'300italic',
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Averia Sans Libre' => array(
			'label' => 'Averia Sans Libre',
			'variants' => array(
				'300',
				'300italic',
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Averia Serif Libre' => array(
			'label' => 'Averia Serif Libre',
			'variants' => array(
				'300',
				'300italic',
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Bad Script' => array(
			'label' => 'Bad Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
			),
			'category' => 'handwriting',
		),
		'Bahiana' => array(
			'label' => 'Bahiana',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Baloo' => array(
			'label' => 'Baloo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'devanagari',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Baloo Bhai' => array(
			'label' => 'Baloo Bhai',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'gujarati',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Baloo Bhaina' => array(
			'label' => 'Baloo Bhaina',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'oriya',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Baloo Chettan' => array(
			'label' => 'Baloo Chettan',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'malayalam',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Baloo Da' => array(
			'label' => 'Baloo Da',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'bengali',
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Baloo Paaji' => array(
			'label' => 'Baloo Paaji',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'gurmukhi',
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Baloo Tamma' => array(
			'label' => 'Baloo Tamma',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'kannada',
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Baloo Thambi' => array(
			'label' => 'Baloo Thambi',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'tamil',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Balthazar' => array(
			'label' => 'Balthazar',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Bangers' => array(
			'label' => 'Bangers',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Barrio' => array(
			'label' => 'Barrio',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Basic' => array(
			'label' => 'Basic',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Battambang' => array(
			'label' => 'Battambang',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Baumans' => array(
			'label' => 'Baumans',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Bayon' => array(
			'label' => 'Bayon',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Belgrano' => array(
			'label' => 'Belgrano',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Belleza' => array(
			'label' => 'Belleza',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'BenchNine' => array(
			'label' => 'BenchNine',
			'variants' => array(
				'300',
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Bentham' => array(
			'label' => 'Bentham',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Berkshire Swash' => array(
			'label' => 'Berkshire Swash',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Bevan' => array(
			'label' => 'Bevan',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Bigelow Rules' => array(
			'label' => 'Bigelow Rules',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Bigshot One' => array(
			'label' => 'Bigshot One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Bilbo' => array(
			'label' => 'Bilbo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Bilbo Swash Caps' => array(
			'label' => 'Bilbo Swash Caps',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'BioRhyme' => array(
			'label' => 'BioRhyme',
			'variants' => array(
				'200',
				'300',
				'regular',
				'700',
				'800',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'BioRhyme Expanded' => array(
			'label' => 'BioRhyme Expanded',
			'variants' => array(
				'200',
				'300',
				'regular',
				'700',
				'800',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Biryani' => array(
			'label' => 'Biryani',
			'variants' => array(
				'200',
				'300',
				'regular',
				'600',
				'700',
				'800',
				'900',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Bitter' => array(
			'label' => 'Bitter',
			'variants' => array(
				'regular',
				'italic',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Black Ops One' => array(
			'label' => 'Black Ops One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Bokor' => array(
			'label' => 'Bokor',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Bonbon' => array(
			'label' => 'Bonbon',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Boogaloo' => array(
			'label' => 'Boogaloo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Bowlby One' => array(
			'label' => 'Bowlby One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Bowlby One SC' => array(
			'label' => 'Bowlby One SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Brawler' => array(
			'label' => 'Brawler',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Bree Serif' => array(
			'label' => 'Bree Serif',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Bubblegum Sans' => array(
			'label' => 'Bubblegum Sans',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Bubbler One' => array(
			'label' => 'Bubbler One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Buda' => array(
			'label' => 'Buda',
			'variants' => array(
				'300',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Buenard' => array(
			'label' => 'Buenard',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Bungee' => array(
			'label' => 'Bungee',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Bungee Hairline' => array(
			'label' => 'Bungee Hairline',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Bungee Inline' => array(
			'label' => 'Bungee Inline',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Bungee Outline' => array(
			'label' => 'Bungee Outline',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Bungee Shade' => array(
			'label' => 'Bungee Shade',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Butcherman' => array(
			'label' => 'Butcherman',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Butterfly Kids' => array(
			'label' => 'Butterfly Kids',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Cabin' => array(
			'label' => 'Cabin',
			'variants' => array(
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Cabin Condensed' => array(
			'label' => 'Cabin Condensed',
			'variants' => array(
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Cabin Sketch' => array(
			'label' => 'Cabin Sketch',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Caesar Dressing' => array(
			'label' => 'Caesar Dressing',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Cagliostro' => array(
			'label' => 'Cagliostro',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Cairo' => array(
			'label' => 'Cairo',
			'variants' => array(
				'200',
				'300',
				'regular',
				'600',
				'700',
				'900',
			),
			'subsets' => array(
				'arabic',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Calligraffitti' => array(
			'label' => 'Calligraffitti',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Cambay' => array(
			'label' => 'Cambay',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Cambo' => array(
			'label' => 'Cambo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Candal' => array(
			'label' => 'Candal',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Cantarell' => array(
			'label' => 'Cantarell',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Cantata One' => array(
			'label' => 'Cantata One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Cantora One' => array(
			'label' => 'Cantora One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Capriola' => array(
			'label' => 'Capriola',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Cardo' => array(
			'label' => 'Cardo',
			'variants' => array(
				'regular',
				'italic',
				'700',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Carme' => array(
			'label' => 'Carme',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Carrois Gothic' => array(
			'label' => 'Carrois Gothic',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Carrois Gothic SC' => array(
			'label' => 'Carrois Gothic SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Carter One' => array(
			'label' => 'Carter One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Catamaran' => array(
			'label' => 'Catamaran',
			'variants' => array(
				'100',
				'200',
				'300',
				'regular',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'subsets' => array(
				'latin',
				'tamil',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Caudex' => array(
			'label' => 'Caudex',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Caveat' => array(
			'label' => 'Caveat',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Caveat Brush' => array(
			'label' => 'Caveat Brush',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Cedarville Cursive' => array(
			'label' => 'Cedarville Cursive',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Ceviche One' => array(
			'label' => 'Ceviche One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Changa' => array(
			'label' => 'Changa',
			'variants' => array(
				'200',
				'300',
				'regular',
				'500',
				'600',
				'700',
				'800',
			),
			'subsets' => array(
				'arabic',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Changa One' => array(
			'label' => 'Changa One',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Chango' => array(
			'label' => 'Chango',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Chathura' => array(
			'label' => 'Chathura',
			'variants' => array(
				'100',
				'300',
				'regular',
				'700',
				'800',
			),
			'subsets' => array(
				'telugu',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Chau Philomene One' => array(
			'label' => 'Chau Philomene One',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Chela One' => array(
			'label' => 'Chela One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Chelsea Market' => array(
			'label' => 'Chelsea Market',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Chenla' => array(
			'label' => 'Chenla',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Cherry Cream Soda' => array(
			'label' => 'Cherry Cream Soda',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Cherry Swash' => array(
			'label' => 'Cherry Swash',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Chewy' => array(
			'label' => 'Chewy',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Chicle' => array(
			'label' => 'Chicle',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Chivo' => array(
			'label' => 'Chivo',
			'variants' => array(
				'300',
				'300italic',
				'regular',
				'italic',
				'700',
				'700italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Chonburi' => array(
			'label' => 'Chonburi',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'thai',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Cinzel' => array(
			'label' => 'Cinzel',
			'variants' => array(
				'regular',
				'700',
				'900',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Cinzel Decorative' => array(
			'label' => 'Cinzel Decorative',
			'variants' => array(
				'regular',
				'700',
				'900',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Clicker Script' => array(
			'label' => 'Clicker Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Coda' => array(
			'label' => 'Coda',
			'variants' => array(
				'regular',
				'800',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Coda Caption' => array(
			'label' => 'Coda Caption',
			'variants' => array(
				'800',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Codystar' => array(
			'label' => 'Codystar',
			'variants' => array(
				'300',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Coiny' => array(
			'label' => 'Coiny',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'tamil',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Combo' => array(
			'label' => 'Combo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Comfortaa' => array(
			'label' => 'Comfortaa',
			'variants' => array(
				'300',
				'regular',
				'700',
			),
			'subsets' => array(
				'greek',
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'display',
		),
		'Coming Soon' => array(
			'label' => 'Coming Soon',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Concert One' => array(
			'label' => 'Concert One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Condiment' => array(
			'label' => 'Condiment',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Content' => array(
			'label' => 'Content',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Contrail One' => array(
			'label' => 'Contrail One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Convergence' => array(
			'label' => 'Convergence',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Cookie' => array(
			'label' => 'Cookie',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Copse' => array(
			'label' => 'Copse',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Corben' => array(
			'label' => 'Corben',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Cormorant' => array(
			'label' => 'Cormorant',
			'variants' => array(
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'serif',
		),
		'Cormorant Garamond' => array(
			'label' => 'Cormorant Garamond',
			'variants' => array(
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'serif',
		),
		'Cormorant Infant' => array(
			'label' => 'Cormorant Infant',
			'variants' => array(
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'serif',
		),
		'Cormorant SC' => array(
			'label' => 'Cormorant SC',
			'variants' => array(
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'serif',
		),
		'Cormorant Unicase' => array(
			'label' => 'Cormorant Unicase',
			'variants' => array(
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'serif',
		),
		'Cormorant Upright' => array(
			'label' => 'Cormorant Upright',
			'variants' => array(
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Courgette' => array(
			'label' => 'Courgette',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Cousine' => array(
			'label' => 'Cousine',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'hebrew',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'monospace',
		),
		'Coustard' => array(
			'label' => 'Coustard',
			'variants' => array(
				'regular',
				'900',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Covered By Your Grace' => array(
			'label' => 'Covered By Your Grace',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Crafty Girls' => array(
			'label' => 'Crafty Girls',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Creepster' => array(
			'label' => 'Creepster',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Crete Round' => array(
			'label' => 'Crete Round',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Crimson Text' => array(
			'label' => 'Crimson Text',
			'variants' => array(
				'regular',
				'italic',
				'600',
				'600italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Croissant One' => array(
			'label' => 'Croissant One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Crushed' => array(
			'label' => 'Crushed',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Cuprum' => array(
			'label' => 'Cuprum',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'Cutive' => array(
			'label' => 'Cutive',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Cutive Mono' => array(
			'label' => 'Cutive Mono',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'monospace',
		),
		'Damion' => array(
			'label' => 'Damion',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Dancing Script' => array(
			'label' => 'Dancing Script',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Dangrek' => array(
			'label' => 'Dangrek',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'David Libre' => array(
			'label' => 'David Libre',
			'variants' => array(
				'regular',
				'500',
				'700',
			),
			'subsets' => array(
				'latin',
				'hebrew',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Dawning of a New Day' => array(
			'label' => 'Dawning of a New Day',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Days One' => array(
			'label' => 'Days One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Dekko' => array(
			'label' => 'Dekko',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Delius' => array(
			'label' => 'Delius',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Delius Swash Caps' => array(
			'label' => 'Delius Swash Caps',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Delius Unicase' => array(
			'label' => 'Delius Unicase',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Della Respira' => array(
			'label' => 'Della Respira',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Denk One' => array(
			'label' => 'Denk One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Devonshire' => array(
			'label' => 'Devonshire',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Dhurjati' => array(
			'label' => 'Dhurjati',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'telugu',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Didact Gothic' => array(
			'label' => 'Didact Gothic',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'Diplomata' => array(
			'label' => 'Diplomata',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Diplomata SC' => array(
			'label' => 'Diplomata SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Domine' => array(
			'label' => 'Domine',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Donegal One' => array(
			'label' => 'Donegal One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Doppio One' => array(
			'label' => 'Doppio One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Dorsa' => array(
			'label' => 'Dorsa',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Dosis' => array(
			'label' => 'Dosis',
			'variants' => array(
				'200',
				'300',
				'regular',
				'500',
				'600',
				'700',
				'800',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Dr Sugiyama' => array(
			'label' => 'Dr Sugiyama',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Droid Sans' => array(
			'label' => 'Droid Sans',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Droid Sans Mono' => array(
			'label' => 'Droid Sans Mono',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'monospace',
		),
		'Droid Serif' => array(
			'label' => 'Droid Serif',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Duru Sans' => array(
			'label' => 'Duru Sans',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Dynalight' => array(
			'label' => 'Dynalight',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'EB Garamond' => array(
			'label' => 'EB Garamond',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'serif',
		),
		'Eagle Lake' => array(
			'label' => 'Eagle Lake',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Eater' => array(
			'label' => 'Eater',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Economica' => array(
			'label' => 'Economica',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Eczar' => array(
			'label' => 'Eczar',
			'variants' => array(
				'regular',
				'500',
				'600',
				'700',
				'800',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Ek Mukta' => array(
			'label' => 'Ek Mukta',
			'variants' => array(
				'200',
				'300',
				'regular',
				'500',
				'600',
				'700',
				'800',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'El Messiri' => array(
			'label' => 'El Messiri',
			'variants' => array(
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'arabic',
				'latin',
				'cyrillic',
			),
			'category' => 'sans-serif',
		),
		'Electrolize' => array(
			'label' => 'Electrolize',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Elsie' => array(
			'label' => 'Elsie',
			'variants' => array(
				'regular',
				'900',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Elsie Swash Caps' => array(
			'label' => 'Elsie Swash Caps',
			'variants' => array(
				'regular',
				'900',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Emblema One' => array(
			'label' => 'Emblema One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Emilys Candy' => array(
			'label' => 'Emilys Candy',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Engagement' => array(
			'label' => 'Engagement',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Englebert' => array(
			'label' => 'Englebert',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Enriqueta' => array(
			'label' => 'Enriqueta',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Erica One' => array(
			'label' => 'Erica One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Esteban' => array(
			'label' => 'Esteban',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Euphoria Script' => array(
			'label' => 'Euphoria Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Ewert' => array(
			'label' => 'Ewert',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Exo' => array(
			'label' => 'Exo',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Exo 2' => array(
			'label' => 'Exo 2',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Expletus Sans' => array(
			'label' => 'Expletus Sans',
			'variants' => array(
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Fanwood Text' => array(
			'label' => 'Fanwood Text',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Farsan' => array(
			'label' => 'Farsan',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'gujarati',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Fascinate' => array(
			'label' => 'Fascinate',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Fascinate Inline' => array(
			'label' => 'Fascinate Inline',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Faster One' => array(
			'label' => 'Faster One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Fasthand' => array(
			'label' => 'Fasthand',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'serif',
		),
		'Fauna One' => array(
			'label' => 'Fauna One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Federant' => array(
			'label' => 'Federant',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Federo' => array(
			'label' => 'Federo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Felipa' => array(
			'label' => 'Felipa',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Fenix' => array(
			'label' => 'Fenix',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Finger Paint' => array(
			'label' => 'Finger Paint',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Fira Mono' => array(
			'label' => 'Fira Mono',
			'variants' => array(
				'regular',
				'500',
				'700',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'monospace',
		),
		'Fira Sans' => array(
			'label' => 'Fira Sans',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'Fira Sans Condensed' => array(
			'label' => 'Fira Sans Condensed',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'Fira Sans Extra Condensed' => array(
			'label' => 'Fira Sans Extra Condensed',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'Fjalla One' => array(
			'label' => 'Fjalla One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Fjord One' => array(
			'label' => 'Fjord One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Flamenco' => array(
			'label' => 'Flamenco',
			'variants' => array(
				'300',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Flavors' => array(
			'label' => 'Flavors',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Fondamento' => array(
			'label' => 'Fondamento',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Fontdiner Swanky' => array(
			'label' => 'Fontdiner Swanky',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Forum' => array(
			'label' => 'Forum',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'display',
		),
		'Francois One' => array(
			'label' => 'Francois One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Frank Ruhl Libre' => array(
			'label' => 'Frank Ruhl Libre',
			'variants' => array(
				'300',
				'regular',
				'500',
				'700',
				'900',
			),
			'subsets' => array(
				'latin',
				'hebrew',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Freckle Face' => array(
			'label' => 'Freckle Face',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Fredericka the Great' => array(
			'label' => 'Fredericka the Great',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Fredoka One' => array(
			'label' => 'Fredoka One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Freehand' => array(
			'label' => 'Freehand',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Fresca' => array(
			'label' => 'Fresca',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Frijole' => array(
			'label' => 'Frijole',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Fruktur' => array(
			'label' => 'Fruktur',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Fugaz One' => array(
			'label' => 'Fugaz One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'GFS Didot' => array(
			'label' => 'GFS Didot',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'greek',
			),
			'category' => 'serif',
		),
		'GFS Neohellenic' => array(
			'label' => 'GFS Neohellenic',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'greek',
			),
			'category' => 'sans-serif',
		),
		'Gabriela' => array(
			'label' => 'Gabriela',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'cyrillic-ext',
			),
			'category' => 'serif',
		),
		'Gafata' => array(
			'label' => 'Gafata',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Galada' => array(
			'label' => 'Galada',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'bengali',
				'latin',
			),
			'category' => 'display',
		),
		'Galdeano' => array(
			'label' => 'Galdeano',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Galindo' => array(
			'label' => 'Galindo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Gentium Basic' => array(
			'label' => 'Gentium Basic',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Gentium Book Basic' => array(
			'label' => 'Gentium Book Basic',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Geo' => array(
			'label' => 'Geo',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Geostar' => array(
			'label' => 'Geostar',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Geostar Fill' => array(
			'label' => 'Geostar Fill',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Germania One' => array(
			'label' => 'Germania One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Gidugu' => array(
			'label' => 'Gidugu',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'telugu',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Gilda Display' => array(
			'label' => 'Gilda Display',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Give You Glory' => array(
			'label' => 'Give You Glory',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Glass Antiqua' => array(
			'label' => 'Glass Antiqua',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Glegoo' => array(
			'label' => 'Glegoo',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Gloria Hallelujah' => array(
			'label' => 'Gloria Hallelujah',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Goblin One' => array(
			'label' => 'Goblin One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Gochi Hand' => array(
			'label' => 'Gochi Hand',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Gorditas' => array(
			'label' => 'Gorditas',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Goudy Bookletter 1911' => array(
			'label' => 'Goudy Bookletter 1911',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Graduate' => array(
			'label' => 'Graduate',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Grand Hotel' => array(
			'label' => 'Grand Hotel',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Gravitas One' => array(
			'label' => 'Gravitas One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Great Vibes' => array(
			'label' => 'Great Vibes',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Griffy' => array(
			'label' => 'Griffy',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Gruppo' => array(
			'label' => 'Gruppo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Gudea' => array(
			'label' => 'Gudea',
			'variants' => array(
				'regular',
				'italic',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Gurajada' => array(
			'label' => 'Gurajada',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'telugu',
				'latin',
			),
			'category' => 'serif',
		),
		'Habibi' => array(
			'label' => 'Habibi',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Halant' => array(
			'label' => 'Halant',
			'variants' => array(
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Hammersmith One' => array(
			'label' => 'Hammersmith One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Hanalei' => array(
			'label' => 'Hanalei',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Hanalei Fill' => array(
			'label' => 'Hanalei Fill',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Handlee' => array(
			'label' => 'Handlee',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Hanuman' => array(
			'label' => 'Hanuman',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'serif',
		),
		'Happy Monkey' => array(
			'label' => 'Happy Monkey',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Harmattan' => array(
			'label' => 'Harmattan',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Headland One' => array(
			'label' => 'Headland One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Heebo' => array(
			'label' => 'Heebo',
			'variants' => array(
				'100',
				'300',
				'regular',
				'500',
				'700',
				'800',
				'900',
			),
			'subsets' => array(
				'latin',
				'hebrew',
			),
			'category' => 'sans-serif',
		),
		'Henny Penny' => array(
			'label' => 'Henny Penny',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Herr Von Muellerhoff' => array(
			'label' => 'Herr Von Muellerhoff',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Hind' => array(
			'label' => 'Hind',
			'variants' => array(
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Hind Guntur' => array(
			'label' => 'Hind Guntur',
			'variants' => array(
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'telugu',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Hind Madurai' => array(
			'label' => 'Hind Madurai',
			'variants' => array(
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'tamil',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Hind Siliguri' => array(
			'label' => 'Hind Siliguri',
			'variants' => array(
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'bengali',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Hind Vadodara' => array(
			'label' => 'Hind Vadodara',
			'variants' => array(
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'gujarati',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Holtwood One SC' => array(
			'label' => 'Holtwood One SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Homemade Apple' => array(
			'label' => 'Homemade Apple',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Homenaje' => array(
			'label' => 'Homenaje',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'IM Fell DW Pica' => array(
			'label' => 'IM Fell DW Pica',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'IM Fell DW Pica SC' => array(
			'label' => 'IM Fell DW Pica SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'IM Fell Double Pica' => array(
			'label' => 'IM Fell Double Pica',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'IM Fell Double Pica SC' => array(
			'label' => 'IM Fell Double Pica SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'IM Fell English' => array(
			'label' => 'IM Fell English',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'IM Fell English SC' => array(
			'label' => 'IM Fell English SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'IM Fell French Canon' => array(
			'label' => 'IM Fell French Canon',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'IM Fell French Canon SC' => array(
			'label' => 'IM Fell French Canon SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'IM Fell Great Primer' => array(
			'label' => 'IM Fell Great Primer',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'IM Fell Great Primer SC' => array(
			'label' => 'IM Fell Great Primer SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Iceberg' => array(
			'label' => 'Iceberg',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Iceland' => array(
			'label' => 'Iceland',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Imprima' => array(
			'label' => 'Imprima',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Inconsolata' => array(
			'label' => 'Inconsolata',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'monospace',
		),
		'Inder' => array(
			'label' => 'Inder',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Indie Flower' => array(
			'label' => 'Indie Flower',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Inika' => array(
			'label' => 'Inika',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Inknut Antiqua' => array(
			'label' => 'Inknut Antiqua',
			'variants' => array(
				'300',
				'regular',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Irish Grover' => array(
			'label' => 'Irish Grover',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Istok Web' => array(
			'label' => 'Istok Web',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'Italiana' => array(
			'label' => 'Italiana',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Italianno' => array(
			'label' => 'Italianno',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Itim' => array(
			'label' => 'Itim',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'thai',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Jacques Francois' => array(
			'label' => 'Jacques Francois',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Jacques Francois Shadow' => array(
			'label' => 'Jacques Francois Shadow',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Jaldi' => array(
			'label' => 'Jaldi',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Jim Nightshade' => array(
			'label' => 'Jim Nightshade',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Jockey One' => array(
			'label' => 'Jockey One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Jolly Lodger' => array(
			'label' => 'Jolly Lodger',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Jomhuria' => array(
			'label' => 'Jomhuria',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Josefin Sans' => array(
			'label' => 'Josefin Sans',
			'variants' => array(
				'100',
				'100italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'600',
				'600italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Josefin Slab' => array(
			'label' => 'Josefin Slab',
			'variants' => array(
				'100',
				'100italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'600',
				'600italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Joti One' => array(
			'label' => 'Joti One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Judson' => array(
			'label' => 'Judson',
			'variants' => array(
				'regular',
				'italic',
				'700',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Julee' => array(
			'label' => 'Julee',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Julius Sans One' => array(
			'label' => 'Julius Sans One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Junge' => array(
			'label' => 'Junge',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Jura' => array(
			'label' => 'Jura',
			'variants' => array(
				'300',
				'regular',
				'500',
				'600',
			),
			'subsets' => array(
				'greek',
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'Just Another Hand' => array(
			'label' => 'Just Another Hand',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Just Me Again Down Here' => array(
			'label' => 'Just Me Again Down Here',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Kadwa' => array(
			'label' => 'Kadwa',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
			),
			'category' => 'serif',
		),
		'Kalam' => array(
			'label' => 'Kalam',
			'variants' => array(
				'300',
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Kameron' => array(
			'label' => 'Kameron',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Kanit' => array(
			'label' => 'Kanit',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'thai',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Kantumruy' => array(
			'label' => 'Kantumruy',
			'variants' => array(
				'300',
				'regular',
				'700',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'sans-serif',
		),
		'Karla' => array(
			'label' => 'Karla',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Karma' => array(
			'label' => 'Karma',
			'variants' => array(
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Katibeh' => array(
			'label' => 'Katibeh',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Kaushan Script' => array(
			'label' => 'Kaushan Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Kavivanar' => array(
			'label' => 'Kavivanar',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'tamil',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Kavoon' => array(
			'label' => 'Kavoon',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Kdam Thmor' => array(
			'label' => 'Kdam Thmor',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Keania One' => array(
			'label' => 'Keania One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Kelly Slab' => array(
			'label' => 'Kelly Slab',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Kenia' => array(
			'label' => 'Kenia',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Khand' => array(
			'label' => 'Khand',
			'variants' => array(
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Khmer' => array(
			'label' => 'Khmer',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Khula' => array(
			'label' => 'Khula',
			'variants' => array(
				'300',
				'regular',
				'600',
				'700',
				'800',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Kite One' => array(
			'label' => 'Kite One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Knewave' => array(
			'label' => 'Knewave',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Kotta One' => array(
			'label' => 'Kotta One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Koulen' => array(
			'label' => 'Koulen',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Kranky' => array(
			'label' => 'Kranky',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Kreon' => array(
			'label' => 'Kreon',
			'variants' => array(
				'300',
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Kristi' => array(
			'label' => 'Kristi',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Krona One' => array(
			'label' => 'Krona One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Kumar One' => array(
			'label' => 'Kumar One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'gujarati',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Kumar One Outline' => array(
			'label' => 'Kumar One Outline',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'gujarati',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Kurale' => array(
			'label' => 'Kurale',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'serif',
		),
		'La Belle Aurore' => array(
			'label' => 'La Belle Aurore',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Laila' => array(
			'label' => 'Laila',
			'variants' => array(
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Lakki Reddy' => array(
			'label' => 'Lakki Reddy',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'telugu',
				'latin',
			),
			'category' => 'handwriting',
		),
		'Lalezar' => array(
			'label' => 'Lalezar',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Lancelot' => array(
			'label' => 'Lancelot',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Lateef' => array(
			'label' => 'Lateef',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
			),
			'category' => 'handwriting',
		),
		'Lato' => array(
			'label' => 'Lato',
			'variants' => array(
				'100',
				'100italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'700',
				'700italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'League Script' => array(
			'label' => 'League Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Leckerli One' => array(
			'label' => 'Leckerli One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Ledger' => array(
			'label' => 'Ledger',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Lekton' => array(
			'label' => 'Lekton',
			'variants' => array(
				'regular',
				'italic',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Lemon' => array(
			'label' => 'Lemon',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Lemonada' => array(
			'label' => 'Lemonada',
			'variants' => array(
				'300',
				'regular',
				'600',
				'700',
			),
			'subsets' => array(
				'arabic',
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Libre Baskerville' => array(
			'label' => 'Libre Baskerville',
			'variants' => array(
				'regular',
				'italic',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Libre Franklin' => array(
			'label' => 'Libre Franklin',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Life Savers' => array(
			'label' => 'Life Savers',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Lilita One' => array(
			'label' => 'Lilita One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Lily Script One' => array(
			'label' => 'Lily Script One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Limelight' => array(
			'label' => 'Limelight',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Linden Hill' => array(
			'label' => 'Linden Hill',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Lobster' => array(
			'label' => 'Lobster',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Lobster Two' => array(
			'label' => 'Lobster Two',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Londrina Outline' => array(
			'label' => 'Londrina Outline',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Londrina Shadow' => array(
			'label' => 'Londrina Shadow',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Londrina Sketch' => array(
			'label' => 'Londrina Sketch',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Londrina Solid' => array(
			'label' => 'Londrina Solid',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Lora' => array(
			'label' => 'Lora',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'serif',
		),
		'Love Ya Like A Sister' => array(
			'label' => 'Love Ya Like A Sister',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Loved by the King' => array(
			'label' => 'Loved by the King',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Lovers Quarrel' => array(
			'label' => 'Lovers Quarrel',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Luckiest Guy' => array(
			'label' => 'Luckiest Guy',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Lusitana' => array(
			'label' => 'Lusitana',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Lustria' => array(
			'label' => 'Lustria',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Macondo' => array(
			'label' => 'Macondo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Macondo Swash Caps' => array(
			'label' => 'Macondo Swash Caps',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Mada' => array(
			'label' => 'Mada',
			'variants' => array(
				'300',
				'regular',
				'500',
				'900',
			),
			'subsets' => array(
				'arabic',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Magra' => array(
			'label' => 'Magra',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Maiden Orange' => array(
			'label' => 'Maiden Orange',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Maitree' => array(
			'label' => 'Maitree',
			'variants' => array(
				'200',
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'thai',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Mako' => array(
			'label' => 'Mako',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Mallanna' => array(
			'label' => 'Mallanna',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'telugu',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Mandali' => array(
			'label' => 'Mandali',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'telugu',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Marcellus' => array(
			'label' => 'Marcellus',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Marcellus SC' => array(
			'label' => 'Marcellus SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Marck Script' => array(
			'label' => 'Marck Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Margarine' => array(
			'label' => 'Margarine',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Marko One' => array(
			'label' => 'Marko One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Marmelad' => array(
			'label' => 'Marmelad',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Martel' => array(
			'label' => 'Martel',
			'variants' => array(
				'200',
				'300',
				'regular',
				'600',
				'700',
				'800',
				'900',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Martel Sans' => array(
			'label' => 'Martel Sans',
			'variants' => array(
				'200',
				'300',
				'regular',
				'600',
				'700',
				'800',
				'900',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Marvel' => array(
			'label' => 'Marvel',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Mate' => array(
			'label' => 'Mate',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Mate SC' => array(
			'label' => 'Mate SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Maven Pro' => array(
			'label' => 'Maven Pro',
			'variants' => array(
				'regular',
				'500',
				'700',
				'900',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'McLaren' => array(
			'label' => 'McLaren',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Meddon' => array(
			'label' => 'Meddon',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'MedievalSharp' => array(
			'label' => 'MedievalSharp',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Medula One' => array(
			'label' => 'Medula One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Meera Inimai' => array(
			'label' => 'Meera Inimai',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'tamil',
			),
			'category' => 'sans-serif',
		),
		'Megrim' => array(
			'label' => 'Megrim',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Meie Script' => array(
			'label' => 'Meie Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Merienda' => array(
			'label' => 'Merienda',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Merienda One' => array(
			'label' => 'Merienda One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Merriweather' => array(
			'label' => 'Merriweather',
			'variants' => array(
				'300',
				'300italic',
				'regular',
				'italic',
				'700',
				'700italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'serif',
		),
		'Merriweather Sans' => array(
			'label' => 'Merriweather Sans',
			'variants' => array(
				'300',
				'300italic',
				'regular',
				'italic',
				'700',
				'700italic',
				'800',
				'800italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Metal' => array(
			'label' => 'Metal',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Metal Mania' => array(
			'label' => 'Metal Mania',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Metamorphous' => array(
			'label' => 'Metamorphous',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Metrophobic' => array(
			'label' => 'Metrophobic',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Michroma' => array(
			'label' => 'Michroma',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Milonga' => array(
			'label' => 'Milonga',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Miltonian' => array(
			'label' => 'Miltonian',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Miltonian Tattoo' => array(
			'label' => 'Miltonian Tattoo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Miniver' => array(
			'label' => 'Miniver',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Miriam Libre' => array(
			'label' => 'Miriam Libre',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'hebrew',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Mirza' => array(
			'label' => 'Mirza',
			'variants' => array(
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'arabic',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Miss Fajardose' => array(
			'label' => 'Miss Fajardose',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Mitr' => array(
			'label' => 'Mitr',
			'variants' => array(
				'200',
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'thai',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Modak' => array(
			'label' => 'Modak',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Modern Antiqua' => array(
			'label' => 'Modern Antiqua',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Mogra' => array(
			'label' => 'Mogra',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'gujarati',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Molengo' => array(
			'label' => 'Molengo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Molle' => array(
			'label' => 'Molle',
			'variants' => array(
				'italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Monda' => array(
			'label' => 'Monda',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Monofett' => array(
			'label' => 'Monofett',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Monoton' => array(
			'label' => 'Monoton',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Monsieur La Doulaise' => array(
			'label' => 'Monsieur La Doulaise',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Montaga' => array(
			'label' => 'Montaga',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Montez' => array(
			'label' => 'Montez',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Montserrat' => array(
			'label' => 'Montserrat',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Montserrat Alternates' => array(
			'label' => 'Montserrat Alternates',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Montserrat Subrayada' => array(
			'label' => 'Montserrat Subrayada',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Moul' => array(
			'label' => 'Moul',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Moulpali' => array(
			'label' => 'Moulpali',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Mountains of Christmas' => array(
			'label' => 'Mountains of Christmas',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Mouse Memoirs' => array(
			'label' => 'Mouse Memoirs',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Mr Bedfort' => array(
			'label' => 'Mr Bedfort',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Mr Dafoe' => array(
			'label' => 'Mr Dafoe',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Mr De Haviland' => array(
			'label' => 'Mr De Haviland',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Mrs Saint Delafield' => array(
			'label' => 'Mrs Saint Delafield',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Mrs Sheppards' => array(
			'label' => 'Mrs Sheppards',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Mukta Vaani' => array(
			'label' => 'Mukta Vaani',
			'variants' => array(
				'200',
				'300',
				'regular',
				'500',
				'600',
				'700',
				'800',
			),
			'subsets' => array(
				'latin',
				'gujarati',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Muli' => array(
			'label' => 'Muli',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Mystery Quest' => array(
			'label' => 'Mystery Quest',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'NTR' => array(
			'label' => 'NTR',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'telugu',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Neucha' => array(
			'label' => 'Neucha',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
			),
			'category' => 'handwriting',
		),
		'Neuton' => array(
			'label' => 'Neuton',
			'variants' => array(
				'200',
				'300',
				'regular',
				'italic',
				'700',
				'800',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'New Rocker' => array(
			'label' => 'New Rocker',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'News Cycle' => array(
			'label' => 'News Cycle',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Niconne' => array(
			'label' => 'Niconne',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Nixie One' => array(
			'label' => 'Nixie One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Nobile' => array(
			'label' => 'Nobile',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Nokora' => array(
			'label' => 'Nokora',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'serif',
		),
		'Norican' => array(
			'label' => 'Norican',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Nosifer' => array(
			'label' => 'Nosifer',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Nothing You Could Do' => array(
			'label' => 'Nothing You Could Do',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Noticia Text' => array(
			'label' => 'Noticia Text',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Noto Sans' => array(
			'label' => 'Noto Sans',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'vietnamese',
				'devanagari',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'Noto Serif' => array(
			'label' => 'Noto Serif',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'serif',
		),
		'Nova Cut' => array(
			'label' => 'Nova Cut',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Nova Flat' => array(
			'label' => 'Nova Flat',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Nova Mono' => array(
			'label' => 'Nova Mono',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'greek',
				'latin',
			),
			'category' => 'monospace',
		),
		'Nova Oval' => array(
			'label' => 'Nova Oval',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Nova Round' => array(
			'label' => 'Nova Round',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Nova Script' => array(
			'label' => 'Nova Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Nova Slim' => array(
			'label' => 'Nova Slim',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Nova Square' => array(
			'label' => 'Nova Square',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Numans' => array(
			'label' => 'Numans',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Nunito' => array(
			'label' => 'Nunito',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Nunito Sans' => array(
			'label' => 'Nunito Sans',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Odor Mean Chey' => array(
			'label' => 'Odor Mean Chey',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Offside' => array(
			'label' => 'Offside',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Old Standard TT' => array(
			'label' => 'Old Standard TT',
			'variants' => array(
				'regular',
				'italic',
				'700',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'serif',
		),
		'Oldenburg' => array(
			'label' => 'Oldenburg',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Oleo Script' => array(
			'label' => 'Oleo Script',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Oleo Script Swash Caps' => array(
			'label' => 'Oleo Script Swash Caps',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Open Sans' => array(
			'label' => 'Open Sans',
			'variants' => array(
				'300',
				'300italic',
				'regular',
				'italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'Open Sans Condensed' => array(
			'label' => 'Open Sans Condensed',
			'variants' => array(
				'300',
				'300italic',
				'700',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'Oranienbaum' => array(
			'label' => 'Oranienbaum',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'serif',
		),
		'Orbitron' => array(
			'label' => 'Orbitron',
			'variants' => array(
				'regular',
				'500',
				'700',
				'900',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Oregano' => array(
			'label' => 'Oregano',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Orienta' => array(
			'label' => 'Orienta',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Original Surfer' => array(
			'label' => 'Original Surfer',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Oswald' => array(
			'label' => 'Oswald',
			'variants' => array(
				'200',
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Over the Rainbow' => array(
			'label' => 'Over the Rainbow',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Overlock' => array(
			'label' => 'Overlock',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Overlock SC' => array(
			'label' => 'Overlock SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Overpass' => array(
			'label' => 'Overpass',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Overpass Mono' => array(
			'label' => 'Overpass Mono',
			'variants' => array(
				'300',
				'regular',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'monospace',
		),
		'Ovo' => array(
			'label' => 'Ovo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Oxygen' => array(
			'label' => 'Oxygen',
			'variants' => array(
				'300',
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Oxygen Mono' => array(
			'label' => 'Oxygen Mono',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'monospace',
		),
		'PT Mono' => array(
			'label' => 'PT Mono',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'monospace',
		),
		'PT Sans' => array(
			'label' => 'PT Sans',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'PT Sans Caption' => array(
			'label' => 'PT Sans Caption',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'PT Sans Narrow' => array(
			'label' => 'PT Sans Narrow',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'PT Serif' => array(
			'label' => 'PT Serif',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'serif',
		),
		'PT Serif Caption' => array(
			'label' => 'PT Serif Caption',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'serif',
		),
		'Pacifico' => array(
			'label' => 'Pacifico',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Padauk' => array(
			'label' => 'Padauk',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'myanmar',
			),
			'category' => 'sans-serif',
		),
		'Palanquin' => array(
			'label' => 'Palanquin',
			'variants' => array(
				'100',
				'200',
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Palanquin Dark' => array(
			'label' => 'Palanquin Dark',
			'variants' => array(
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Pangolin' => array(
			'label' => 'Pangolin',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'handwriting',
		),
		'Paprika' => array(
			'label' => 'Paprika',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Parisienne' => array(
			'label' => 'Parisienne',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Passero One' => array(
			'label' => 'Passero One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Passion One' => array(
			'label' => 'Passion One',
			'variants' => array(
				'regular',
				'700',
				'900',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Pathway Gothic One' => array(
			'label' => 'Pathway Gothic One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Patrick Hand' => array(
			'label' => 'Patrick Hand',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Patrick Hand SC' => array(
			'label' => 'Patrick Hand SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Pattaya' => array(
			'label' => 'Pattaya',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'thai',
				'vietnamese',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Patua One' => array(
			'label' => 'Patua One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Pavanam' => array(
			'label' => 'Pavanam',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'tamil',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Paytone One' => array(
			'label' => 'Paytone One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Peddana' => array(
			'label' => 'Peddana',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'telugu',
				'latin',
			),
			'category' => 'serif',
		),
		'Peralta' => array(
			'label' => 'Peralta',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Permanent Marker' => array(
			'label' => 'Permanent Marker',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Petit Formal Script' => array(
			'label' => 'Petit Formal Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Petrona' => array(
			'label' => 'Petrona',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Philosopher' => array(
			'label' => 'Philosopher',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'cyrillic',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'Piedra' => array(
			'label' => 'Piedra',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Pinyon Script' => array(
			'label' => 'Pinyon Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Pirata One' => array(
			'label' => 'Pirata One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Plaster' => array(
			'label' => 'Plaster',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Play' => array(
			'label' => 'Play',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'greek',
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'Playball' => array(
			'label' => 'Playball',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Playfair Display' => array(
			'label' => 'Playfair Display',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Playfair Display SC' => array(
			'label' => 'Playfair Display SC',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Podkova' => array(
			'label' => 'Podkova',
			'variants' => array(
				'regular',
				'500',
				'600',
				'700',
				'800',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'serif',
		),
		'Poiret One' => array(
			'label' => 'Poiret One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Poller One' => array(
			'label' => 'Poller One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Poly' => array(
			'label' => 'Poly',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Pompiere' => array(
			'label' => 'Pompiere',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Pontano Sans' => array(
			'label' => 'Pontano Sans',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Poppins' => array(
			'label' => 'Poppins',
			'variants' => array(
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Port Lligat Sans' => array(
			'label' => 'Port Lligat Sans',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Port Lligat Slab' => array(
			'label' => 'Port Lligat Slab',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Pragati Narrow' => array(
			'label' => 'Pragati Narrow',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Prata' => array(
			'label' => 'Prata',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'cyrillic',
				'cyrillic-ext',
			),
			'category' => 'serif',
		),
		'Preahvihear' => array(
			'label' => 'Preahvihear',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Press Start 2P' => array(
			'label' => 'Press Start 2P',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'greek',
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'display',
		),
		'Pridi' => array(
			'label' => 'Pridi',
			'variants' => array(
				'200',
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'thai',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Princess Sofia' => array(
			'label' => 'Princess Sofia',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Prociono' => array(
			'label' => 'Prociono',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Prompt' => array(
			'label' => 'Prompt',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'thai',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Prosto One' => array(
			'label' => 'Prosto One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Proza Libre' => array(
			'label' => 'Proza Libre',
			'variants' => array(
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Puritan' => array(
			'label' => 'Puritan',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Purple Purse' => array(
			'label' => 'Purple Purse',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Quando' => array(
			'label' => 'Quando',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Quantico' => array(
			'label' => 'Quantico',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Quattrocento' => array(
			'label' => 'Quattrocento',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Quattrocento Sans' => array(
			'label' => 'Quattrocento Sans',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Questrial' => array(
			'label' => 'Questrial',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Quicksand' => array(
			'label' => 'Quicksand',
			'variants' => array(
				'300',
				'regular',
				'500',
				'700',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Quintessential' => array(
			'label' => 'Quintessential',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Qwigley' => array(
			'label' => 'Qwigley',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Racing Sans One' => array(
			'label' => 'Racing Sans One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Radley' => array(
			'label' => 'Radley',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Rajdhani' => array(
			'label' => 'Rajdhani',
			'variants' => array(
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Rakkas' => array(
			'label' => 'Rakkas',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Raleway' => array(
			'label' => 'Raleway',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Raleway Dots' => array(
			'label' => 'Raleway Dots',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Ramabhadra' => array(
			'label' => 'Ramabhadra',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'telugu',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Ramaraja' => array(
			'label' => 'Ramaraja',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'telugu',
				'latin',
			),
			'category' => 'serif',
		),
		'Rambla' => array(
			'label' => 'Rambla',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Rammetto One' => array(
			'label' => 'Rammetto One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Ranchers' => array(
			'label' => 'Ranchers',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Rancho' => array(
			'label' => 'Rancho',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Ranga' => array(
			'label' => 'Ranga',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Rasa' => array(
			'label' => 'Rasa',
			'variants' => array(
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'gujarati',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Rationale' => array(
			'label' => 'Rationale',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Ravi Prakash' => array(
			'label' => 'Ravi Prakash',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'telugu',
				'latin',
			),
			'category' => 'display',
		),
		'Redressed' => array(
			'label' => 'Redressed',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Reem Kufi' => array(
			'label' => 'Reem Kufi',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Reenie Beanie' => array(
			'label' => 'Reenie Beanie',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Revalia' => array(
			'label' => 'Revalia',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Rhodium Libre' => array(
			'label' => 'Rhodium Libre',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Ribeye' => array(
			'label' => 'Ribeye',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Ribeye Marrow' => array(
			'label' => 'Ribeye Marrow',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Righteous' => array(
			'label' => 'Righteous',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Risque' => array(
			'label' => 'Risque',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Roboto' => array(
			'label' => 'Roboto',
			'variants' => array(
				'100',
				'100italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'700',
				'700italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'Roboto Condensed' => array(
			'label' => 'Roboto Condensed',
			'variants' => array(
				'300',
				'300italic',
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'Roboto Mono' => array(
			'label' => 'Roboto Mono',
			'variants' => array(
				'100',
				'100italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'monospace',
		),
		'Roboto Slab' => array(
			'label' => 'Roboto Slab',
			'variants' => array(
				'100',
				'300',
				'regular',
				'700',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'serif',
		),
		'Rochester' => array(
			'label' => 'Rochester',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Rock Salt' => array(
			'label' => 'Rock Salt',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Rokkitt' => array(
			'label' => 'Rokkitt',
			'variants' => array(
				'100',
				'200',
				'300',
				'regular',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Romanesco' => array(
			'label' => 'Romanesco',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Ropa Sans' => array(
			'label' => 'Ropa Sans',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Rosario' => array(
			'label' => 'Rosario',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Rosarivo' => array(
			'label' => 'Rosarivo',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Rouge Script' => array(
			'label' => 'Rouge Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Rozha One' => array(
			'label' => 'Rozha One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Rubik' => array(
			'label' => 'Rubik',
			'variants' => array(
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'700',
				'700italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'hebrew',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Rubik Mono One' => array(
			'label' => 'Rubik Mono One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Ruda' => array(
			'label' => 'Ruda',
			'variants' => array(
				'regular',
				'700',
				'900',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Rufina' => array(
			'label' => 'Rufina',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Ruge Boogie' => array(
			'label' => 'Ruge Boogie',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Ruluko' => array(
			'label' => 'Ruluko',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Rum Raisin' => array(
			'label' => 'Rum Raisin',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Ruslan Display' => array(
			'label' => 'Ruslan Display',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Russo One' => array(
			'label' => 'Russo One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Ruthie' => array(
			'label' => 'Ruthie',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Rye' => array(
			'label' => 'Rye',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Sacramento' => array(
			'label' => 'Sacramento',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Sahitya' => array(
			'label' => 'Sahitya',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
			),
			'category' => 'serif',
		),
		'Sail' => array(
			'label' => 'Sail',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Salsa' => array(
			'label' => 'Salsa',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Sanchez' => array(
			'label' => 'Sanchez',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Sancreek' => array(
			'label' => 'Sancreek',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Sansita' => array(
			'label' => 'Sansita',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Sarala' => array(
			'label' => 'Sarala',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Sarina' => array(
			'label' => 'Sarina',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Sarpanch' => array(
			'label' => 'Sarpanch',
			'variants' => array(
				'regular',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Satisfy' => array(
			'label' => 'Satisfy',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Scada' => array(
			'label' => 'Scada',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'Scheherazade' => array(
			'label' => 'Scheherazade',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'arabic',
				'latin',
			),
			'category' => 'serif',
		),
		'Schoolbell' => array(
			'label' => 'Schoolbell',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Scope One' => array(
			'label' => 'Scope One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Seaweed Script' => array(
			'label' => 'Seaweed Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Secular One' => array(
			'label' => 'Secular One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'hebrew',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Sevillana' => array(
			'label' => 'Sevillana',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Seymour One' => array(
			'label' => 'Seymour One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Shadows Into Light' => array(
			'label' => 'Shadows Into Light',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Shadows Into Light Two' => array(
			'label' => 'Shadows Into Light Two',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Shanti' => array(
			'label' => 'Shanti',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Share' => array(
			'label' => 'Share',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Share Tech' => array(
			'label' => 'Share Tech',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Share Tech Mono' => array(
			'label' => 'Share Tech Mono',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'monospace',
		),
		'Shojumaru' => array(
			'label' => 'Shojumaru',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Short Stack' => array(
			'label' => 'Short Stack',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Shrikhand' => array(
			'label' => 'Shrikhand',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'gujarati',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Siemreap' => array(
			'label' => 'Siemreap',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Sigmar One' => array(
			'label' => 'Sigmar One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Signika' => array(
			'label' => 'Signika',
			'variants' => array(
				'300',
				'regular',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Signika Negative' => array(
			'label' => 'Signika Negative',
			'variants' => array(
				'300',
				'regular',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Simonetta' => array(
			'label' => 'Simonetta',
			'variants' => array(
				'regular',
				'italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Sintony' => array(
			'label' => 'Sintony',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Sirin Stencil' => array(
			'label' => 'Sirin Stencil',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Six Caps' => array(
			'label' => 'Six Caps',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Skranji' => array(
			'label' => 'Skranji',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Slabo 13px' => array(
			'label' => 'Slabo 13px',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Slabo 27px' => array(
			'label' => 'Slabo 27px',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Slackey' => array(
			'label' => 'Slackey',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Smokum' => array(
			'label' => 'Smokum',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Smythe' => array(
			'label' => 'Smythe',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Sniglet' => array(
			'label' => 'Sniglet',
			'variants' => array(
				'regular',
				'800',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Snippet' => array(
			'label' => 'Snippet',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Snowburst One' => array(
			'label' => 'Snowburst One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Sofadi One' => array(
			'label' => 'Sofadi One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Sofia' => array(
			'label' => 'Sofia',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Sonsie One' => array(
			'label' => 'Sonsie One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Sorts Mill Goudy' => array(
			'label' => 'Sorts Mill Goudy',
			'variants' => array(
				'regular',
				'italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Source Code Pro' => array(
			'label' => 'Source Code Pro',
			'variants' => array(
				'200',
				'300',
				'regular',
				'500',
				'600',
				'700',
				'900',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'monospace',
		),
		'Source Sans Pro' => array(
			'label' => 'Source Sans Pro',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Source Serif Pro' => array(
			'label' => 'Source Serif Pro',
			'variants' => array(
				'regular',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Space Mono' => array(
			'label' => 'Space Mono',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'monospace',
		),
		'Special Elite' => array(
			'label' => 'Special Elite',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Spicy Rice' => array(
			'label' => 'Spicy Rice',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Spinnaker' => array(
			'label' => 'Spinnaker',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Spirax' => array(
			'label' => 'Spirax',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Squada One' => array(
			'label' => 'Squada One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Sree Krushnadevaraya' => array(
			'label' => 'Sree Krushnadevaraya',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'telugu',
				'latin',
			),
			'category' => 'serif',
		),
		'Sriracha' => array(
			'label' => 'Sriracha',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'thai',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Stalemate' => array(
			'label' => 'Stalemate',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Stalinist One' => array(
			'label' => 'Stalinist One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Stardos Stencil' => array(
			'label' => 'Stardos Stencil',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Stint Ultra Condensed' => array(
			'label' => 'Stint Ultra Condensed',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Stint Ultra Expanded' => array(
			'label' => 'Stint Ultra Expanded',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Stoke' => array(
			'label' => 'Stoke',
			'variants' => array(
				'300',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Strait' => array(
			'label' => 'Strait',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Sue Ellen Francisco' => array(
			'label' => 'Sue Ellen Francisco',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Suez One' => array(
			'label' => 'Suez One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'hebrew',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Sumana' => array(
			'label' => 'Sumana',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Sunshiney' => array(
			'label' => 'Sunshiney',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Supermercado One' => array(
			'label' => 'Supermercado One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Sura' => array(
			'label' => 'Sura',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Suranna' => array(
			'label' => 'Suranna',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'telugu',
				'latin',
			),
			'category' => 'serif',
		),
		'Suravaram' => array(
			'label' => 'Suravaram',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'telugu',
				'latin',
			),
			'category' => 'serif',
		),
		'Suwannaphum' => array(
			'label' => 'Suwannaphum',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Swanky and Moo Moo' => array(
			'label' => 'Swanky and Moo Moo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Syncopate' => array(
			'label' => 'Syncopate',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Tangerine' => array(
			'label' => 'Tangerine',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Taprom' => array(
			'label' => 'Taprom',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Tauri' => array(
			'label' => 'Tauri',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Taviraj' => array(
			'label' => 'Taviraj',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'thai',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Teko' => array(
			'label' => 'Teko',
			'variants' => array(
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Telex' => array(
			'label' => 'Telex',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Tenali Ramakrishna' => array(
			'label' => 'Tenali Ramakrishna',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'telugu',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Tenor Sans' => array(
			'label' => 'Tenor Sans',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Text Me One' => array(
			'label' => 'Text Me One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'The Girl Next Door' => array(
			'label' => 'The Girl Next Door',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Tienne' => array(
			'label' => 'Tienne',
			'variants' => array(
				'regular',
				'700',
				'900',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Tillana' => array(
			'label' => 'Tillana',
			'variants' => array(
				'regular',
				'500',
				'600',
				'700',
				'800',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Timmana' => array(
			'label' => 'Timmana',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'telugu',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Tinos' => array(
			'label' => 'Tinos',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'hebrew',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'serif',
		),
		'Titan One' => array(
			'label' => 'Titan One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Titillium Web' => array(
			'label' => 'Titillium Web',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'900',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Trade Winds' => array(
			'label' => 'Trade Winds',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Trirong' => array(
			'label' => 'Trirong',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'thai',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Trocchi' => array(
			'label' => 'Trocchi',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Trochut' => array(
			'label' => 'Trochut',
			'variants' => array(
				'regular',
				'italic',
				'700',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Trykker' => array(
			'label' => 'Trykker',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Tulpen One' => array(
			'label' => 'Tulpen One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Ubuntu' => array(
			'label' => 'Ubuntu',
			'variants' => array(
				'300',
				'300italic',
				'regular',
				'italic',
				'500',
				'500italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'Ubuntu Condensed' => array(
			'label' => 'Ubuntu Condensed',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'sans-serif',
		),
		'Ubuntu Mono' => array(
			'label' => 'Ubuntu Mono',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'monospace',
		),
		'Ultra' => array(
			'label' => 'Ultra',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Uncial Antiqua' => array(
			'label' => 'Uncial Antiqua',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Underdog' => array(
			'label' => 'Underdog',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Unica One' => array(
			'label' => 'Unica One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'UnifrakturCook' => array(
			'label' => 'UnifrakturCook',
			'variants' => array(
				'700',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'UnifrakturMaguntia' => array(
			'label' => 'UnifrakturMaguntia',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Unkempt' => array(
			'label' => 'Unkempt',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Unlock' => array(
			'label' => 'Unlock',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Unna' => array(
			'label' => 'Unna',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'VT323' => array(
			'label' => 'VT323',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'monospace',
		),
		'Vampiro One' => array(
			'label' => 'Vampiro One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Varela' => array(
			'label' => 'Varela',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Varela Round' => array(
			'label' => 'Varela Round',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'hebrew',
				'vietnamese',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Vast Shadow' => array(
			'label' => 'Vast Shadow',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Vesper Libre' => array(
			'label' => 'Vesper Libre',
			'variants' => array(
				'regular',
				'500',
				'700',
				'900',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Vibur' => array(
			'label' => 'Vibur',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Vidaloka' => array(
			'label' => 'Vidaloka',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Viga' => array(
			'label' => 'Viga',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Voces' => array(
			'label' => 'Voces',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Volkhov' => array(
			'label' => 'Volkhov',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Vollkorn' => array(
			'label' => 'Vollkorn',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Voltaire' => array(
			'label' => 'Voltaire',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Waiting for the Sunrise' => array(
			'label' => 'Waiting for the Sunrise',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Wallpoet' => array(
			'label' => 'Wallpoet',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Walter Turncoat' => array(
			'label' => 'Walter Turncoat',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Warnes' => array(
			'label' => 'Warnes',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Wellfleet' => array(
			'label' => 'Wellfleet',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Wendy One' => array(
			'label' => 'Wendy One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Wire One' => array(
			'label' => 'Wire One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Work Sans' => array(
			'label' => 'Work Sans',
			'variants' => array(
				'100',
				'200',
				'300',
				'regular',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Yanone Kaffeesatz' => array(
			'label' => 'Yanone Kaffeesatz',
			'variants' => array(
				'200',
				'300',
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Yantramanav' => array(
			'label' => 'Yantramanav',
			'variants' => array(
				'100',
				'300',
				'regular',
				'500',
				'700',
				'900',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Yatra One' => array(
			'label' => 'Yatra One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'devanagari',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Yellowtail' => array(
			'label' => 'Yellowtail',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Yeseva One' => array(
			'label' => 'Yeseva One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
			'category' => 'display',
		),
		'Yesteryear' => array(
			'label' => 'Yesteryear',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Yrsa' => array(
			'label' => 'Yrsa',
			'variants' => array(
				'300',
				'regular',
				'500',
				'600',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Zeyada' => array(
			'label' => 'Zeyada',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
	) );
}
endif;
