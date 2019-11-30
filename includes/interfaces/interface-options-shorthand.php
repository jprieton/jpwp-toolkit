<?php

namespace JPWPToolkit\Interfaces;

// Exit if accessed directly
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
   * Adds the shorthand
   * 
   * @since   0.3.0
   * 
   * @param   array $shorthands
   * @return  array
   */
  public function add_shorthand( $shorthands );

  /**
   * Parse the shorthand
   * 
   * @since   0.3.0
   * 
   * @param   string  $options
   * @return  array
   */
  public function parse_shorthand( $options );
}
