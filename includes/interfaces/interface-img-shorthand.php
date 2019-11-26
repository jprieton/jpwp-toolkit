<?php

namespace JPWPToolkit\Interfaces;

// Exit if accessed directly
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
   * Adds the shorthand
   * 
   * @since   0.1.0
   * 
   * @param   array $shorthands
   * @return  array
   */
  public function add_shorthand( $shorthands );

  /**
   * Parse the shorthand
   * 
   * @since   0.1.0
   * 
   * @param   array   $attributes
   * @param   string  $src
   * @return  array
   */
  public function parse_shorthand( $attributes, $src );
}
