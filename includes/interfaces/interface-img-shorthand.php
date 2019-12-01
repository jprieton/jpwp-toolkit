<?php
/**
 * An Interface to add an image shorthand handler
 * 
 * @package       JPWPToolkit
 * @subpackage    Interfaces
 */

namespace JPWPToolkit\Interfaces;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * An Interface to add an image shorthand
 * 
 * @package       JPWPToolkit
 * @subpackage    Interfaces
 * @since         0.1.0
 * @author        Javier Prieto
 */
interface Img_Shorthand {

  /**
   * Adds the shorthand handler
   * 
   * @since   0.1.0
   * 
   * @param   array $handlers An array with the img shorthand handlers.
   * @return  array
   */
  public function add_shorthand_handler( $handlers );

  /**
   * Parse the shorthand
   * 
   * @since   0.1.0
   * 
   * @param array  $attributes An array of html attributes.
   * @param string $src The img handler.
   * @return  array
   */
  public function parse_shorthand( $attributes, $src );
}
