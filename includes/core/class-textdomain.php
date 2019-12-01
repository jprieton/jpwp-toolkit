<?php
/**
 * Load the plugin texdomain
 * 
 * @package        JPWPToolkit
 * @subpackage     Core
 */

namespace JPWPToolkit\Core;

// Exit if accessed directly.
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
    load_plugin_textdomain( 'jpwp-toolkit', false, basename( dirname( JPWP_BASENAME ) ) . '/languages/' );
  }

}
