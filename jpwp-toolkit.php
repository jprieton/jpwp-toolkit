<?php
/**
 * Plugin Name:   JPWP Toolkit
 * Plugin URI:    https://github.com/jprieton/jpwp-toolkit
 * Description:   An extensible object-oriented set of tools for WordPress that helps you to develop themes and plugins.
 * Version:       0.3.0
 * Author:        Javier Prieto
 * Author URI:    https://github.com/jprieton
 * Text Domain:   jpwp-toolkit
 * Domain Path:   /languages/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * @package JPWPToolkit
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Define plugin constants
 *
 * @since 0.1.0
 */
define( 'JPWP_VERSION', '0.3.0' );
define( 'JPWP_FILENAME', __FILE__ );
define( 'JPWP_BASENAME', plugin_basename( __FILE__ ) );
define( 'JPWP_BASEDIR', trailingslashit( __DIR__ ) );
define( 'JPWP_BASEURL', plugin_dir_url( __FILE__ ) );
define( 'JPWP_ABSPATH', plugin_dir_path( JPWP_FILENAME ) . 'includes' );

/**
 * Registering an autoload implementation
 *
 * @since 0.1.0
 */
spl_autoload_register( function( $class_name ) {
  $namespace = explode( '\\', $class_name );

  if ( $namespace[0] != 'JPWPToolkit' ) {
    return false;
  }

  $namespace = array_map( 'strtolower', $namespace );

  if ( in_array( 'abstracts', $namespace ) ) {
    $class_filename = 'abstract-class-' . str_replace( '_', '-', end( $namespace ) );
    array_pop( $namespace );
  } else if ( in_array( 'traits', $namespace ) ) {
    $class_filename = 'trait-' . str_replace( '_', '-', end( $namespace ) );
    array_pop( $namespace );
  } else if ( in_array( 'interfaces', $namespace ) ) {
    $class_filename = 'interface-' . str_replace( '_', '-', end( $namespace ) );
    array_pop( $namespace );
  } else {
    $class_filename = 'class-' . str_replace( '_', '-', end( $namespace ) );
    array_pop( $namespace );
  }

  $namespace[0] = JPWP_ABSPATH;
  $namespace[]  = $class_filename;

  $filename = implode( DIRECTORY_SEPARATOR, $namespace ) . '.php';

  if ( file_exists( $filename ) ) {
    require_once $filename;
  } else if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
    $backtrace = debug_backtrace(); // phpcs:ignore

    foreach ( $backtrace as $info ) {
      if ( empty( $info['file'] ) || $info['function'] != 'spl_autoload_call' ) {
        continue;
      }
      break;
    }

    // development.
    var_dump( $class_name, $filename, $info['file'] );  // phpcs:ignore
    die;
  } else {
    // translators: %s: class name.
    wp_die( sprintf( __( "The class %s could not be loaded", 'jpwp-toolkit' ), // phpcs:ignore
                    "<b><code>{$class_name}</code></b>" ) );  // phpcs:ignore
  }
} );

// Load the plugin textdomain.
add_action( 'plugins_loaded', [ 'JPWPToolkit\Core\Textdomain', 'load_plugin_textdomain' ] );

use JPWPToolkit\Core\Admin_Notice;
use JPWPToolkit\Core\Init;

// Check if the minimum requirements are met.
if ( version_compare( PHP_VERSION, '5.6.20', '<' ) ) {

  // Show notice for minimum PHP version required for JPWP Toolkit.
  new Admin_Notice( __( 'JPWP Toolkit requires PHP version 5.6.20 or later.', 'jpwp-toolkit' ), [
      'type'        => 'error',
      'dismissible' => true,
          ] );
} else {

  // Initialize the plugin.
  Init::get_instance();
}
