<?php
/**
 * PHPUnit bootstrap file
 *
 * @package JPWPToolkit
 */

$jpwp_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $jpwp_tests_dir ) {
	$jpwp_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $jpwp_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find $jpwp_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $jpwp_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function jpwp_phpunit_manually_load_plugin() {
	require dirname( dirname( __FILE__ ) ) . '/jpwp-toolkit.php';
}
tests_add_filter( 'muplugins_loaded', 'jpwp_phpunit_manually_load_plugin' );

// Start up the WP testing environment.
require $jpwp_tests_dir . '/includes/bootstrap.php';
