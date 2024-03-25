<?php
/**
 * Easy Digital Downloads Theme Updater
 *
 * @package EDD Theme Updater
 */

// Includes the files needed for the theme updater
if ( ! class_exists( 'EDD_Theme_Updater_Admin' ) ) {
	include( dirname( __FILE__ ) . '/theme-updater-admin.php' );
}

// Loads the updater classes
$updater = new EDD_Theme_Updater_Admin(

	// Config settings
	$config = array(
		'remote_api_url' => 'https://codestag.com',
		'item_name'      => 'Blink',
		'theme_slug'     => 'blink',
		'version'        => BLINK_VERSION,
		'author'         => 'Codestag',
	),

	// Strings
	$strings = array(
		'theme-license'             => __( 'Theme License', 'blink' ),
		'enter-key'                 => __( 'Enter your theme license key.', 'blink' ),
		'license-key'               => __( 'License Key', 'blink' ),
		'license-action'            => __( 'License Action', 'blink' ),
		'deactivate-license'        => __( 'Deactivate License', 'blink' ),
		'activate-license'          => __( 'Activate License', 'blink' ),
		'status-unknown'            => __( 'License status is unknown.', 'blink' ),
		'renew'                     => __( 'Renew?', 'blink' ),
		'unlimited'                 => __( 'unlimited', 'blink' ),
		'license-key-is-active'     => __( 'License key is active.', 'blink' ),
		'expires%s'                 => __( 'Expires %s.', 'blink' ),
		'%1$s/%2$-sites'            => __( 'You have %1$s / %2$s sites activated.', 'blink' ),
		'license-key-expired-%s'    => __( 'License key expired %s.', 'blink' ),
		'license-key-expired'       => __( 'License key has expired.', 'blink' ),
		'license-keys-do-not-match' => __( 'License keys do not match.', 'blink' ),
		'license-is-inactive'       => __( 'License is inactive.', 'blink' ),
		'license-key-is-disabled'   => __( 'License key is disabled.', 'blink' ),
		'site-is-inactive'          => __( 'Site is inactive.', 'blink' ),
		'license-status-unknown'    => __( 'License status is unknown.', 'blink' ),
		'update-notice'             => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'blink' ),
		'update-available'          => __('<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'blink' )
	)
);
