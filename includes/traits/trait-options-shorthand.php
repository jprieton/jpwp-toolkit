<?php

namespace JPWPToolkit\Traits;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Options_Shorthand trait
 * 
 * @property      string $name Shorthand name
 * 
 * @package       JPWPToolkit
 * @subpackage    Traits
 * @since         0.3.0
 * @author        Javier Prieto
 */
trait Options_Shorthand {

  /**
   * Class constructor
   * 
   * @since     0.3.0
   */
  public function __construct() {
    // Add shorthands keywords
    add_filter( 'jpwp_toolkit_helpers_form_options_shorthands', [ $this, 'add_shorthand' ] );

    // Add shorthands filters
    add_filter( "jpwp_toolkit_helpers_form_options_shorthand_{$this->name}", [ $this, 'parse_shorthand' ], 10, 2 );
  }

}
