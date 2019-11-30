<?php

namespace JPWPToolkit\Filters;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

use JPWPToolkit\Interfaces\Options_Shorthand as Interface_Options_Shorthand;

/**
 * Add the weekdays shorthand to Form::options() method
 * 
 * @package       JPWPToolkit
 * @subpackage    Filters
 * @since         0.3.0
 * @author        Javier Prieto
 */
class Options_Weekdays_Shorthand implements Interface_Options_Shorthand {

  /**
   * Adds Options_Shorthand methods and properties
   * 
   * @since     0.3.0
   */
  use \JPWPToolkit\Traits\Options_Shorthand;

  /**
   * @var     string Shorthand name
   * @since   0.3.0
   */
  private $name = 'weekdays';

  /**
   * Adds the shorthand
   * 
   * @since   0.3.0
   * 
   * @param   array $shorthands
   * @return  array
   */
  public function add_shorthand( $shorthands = [] ) {
    $shorthands[] = $this->name;
    return $shorthands;
  }

  /**
   * Parse the shorthand
   * 
   * @since   0.3.0
   *
   * @global  WP_Locale   $wp_locale
   * 
   * @param   string      $options
   */
  public function parse_shorthand( $options ) {
    global $wp_locale;
    
    $options = $wp_locale->weekday;

    return $options;
  }

}
