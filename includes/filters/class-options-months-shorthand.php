<?php
/**
 * Add the month to the options handler
 * 
 * @package       JPWPToolkit
 * @subpackage    Filters
 */

namespace JPWPToolkit\Filters;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use JPWPToolkit\Interfaces\Options_Shorthand as Interface_Options_Shorthand;

/**
 * Add the months shorthand to Form::options() method
 * 
 * @package       JPWPToolkit
 * @subpackage    Filters
 * @since         0.3.0
 * @author        Javier Prieto
 */
class Options_Months_Shorthand implements Interface_Options_Shorthand {

  /**
   * Adds Options_Shorthand methods and properties
   * 
   * @since     0.3.0
   */
  use \JPWPToolkit\Traits\Options_Shorthand;

  /**
   * The options handler name
   * 
   * @var     string
   * @since   0.3.0
   */
  private $handler = 'months';

  /**
   * Adds the shorthand handler
   * 
   * @since   0.1.0
   * 
   * @param   array $handlers An array with the img shorthand handlers.
   * @return  array
   */
  public function add_shorthand_handler( $handlers = [] ) {
    $handlers[] = $this->handler;
    return $handlers;
  }

  /**
   * Parse the shorthand
   * 
   * @since   0.3.0
   *
   * @global  WP_Locale   $wp_locale
   * 
   * @param   string $options The options handler.
   */
  public function parse_shorthand( $options ) {
    global $wp_locale;

    $options = $wp_locale->month;

    return $options;
  }

}
