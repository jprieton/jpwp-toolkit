<?php
/**
 * An Interface to add an option shorthand handler
 * 
 * @package       JPWPToolkit
 * @subpackage    Interfaces
 */

namespace JPWPToolkit\Interfaces;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * An Interface to add an option shorthand
 * 
 * @package       JPWPToolkit
 * @subpackage    Interfaces
 * @since         0.3.0
 * @author        Javier Prieto
 */
interface Options_Shorthand {

  /**
   * Adds the shorthand handler
   * 
   * @since   0.3.0
   * 
   * @param   array $handler An array with the options shorthand handlers.
   * @return  array
   */
  public function add_shorthand_handler( $handler );


  /**
   * Parse the shorthand
   * 
   * @since   0.3.0
   * 
   * @param   string $options The options handler.
   * @return  array
   */
  public function parse_shorthand( $options );
}
