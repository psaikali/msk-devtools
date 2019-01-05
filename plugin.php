<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define plugin constants
 */
define( 'MSK_DEVTOOLS_VERSION', '1.0.0' );
define( 'MSK_DEVTOOLS_URL', plugin_dir_url( __FILE__ ) );
define( 'MSK_DEVTOOLS_DIR', plugin_dir_path( __FILE__ ) );
define( 'MSK_DEVTOOLS_PLUGIN_DIRNAME', basename( rtrim( dirname( __FILE__ ), '/' ) ) );
define( 'MSK_DEVTOOLS_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Register required files.
 */
function msk_fire() {
	$files = [ 'class', 'utils', 'debug' ];

	foreach ( $files as $file ) {
		require_once MSK_DEVTOOLS_DIR . "includes/{$file}.php";
	}
}
add_action( 'plugins_loaded', 'msk_fire' );
