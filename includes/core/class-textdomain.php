<?php

namespace JPWPToolkit\Core;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Textdomain class
 *
 * @package        JPWPToolkit
 * @subpackage     Core
 * @since          0.1.0
 * @author         Javier Prieto
 */
class Textdomain {

  /**
   * Load plugin textdomain
   *
   * @since     1.1.0
   */
  public static function load_plugin_textdomain() {
    load_plugin_textdomain( 'jpwp-toolkit', FALSE, basename( dirname( JPWP_BASENAME ) ) . '/languages/' );
  }

  /**
   * Adds tanslation to plugin description
   *
   * @since     2.0.0
   * @param     array $all_plugins
   * @return    array
   */
  public static function modify_plugin_description( $all_plugins = [] ) {
    if ( key_exists( JPWP_BASENAME, $all_plugins ) ) {
      $all_plugins[JPWP_BASENAME]['Description'] = __( 'An extensible object-oriented set of tools for WordPress '
              . 'that helps you to develop themes and plugins.', 'jpwp-toolkit' );
    }
    return $all_plugins;
  }

}
