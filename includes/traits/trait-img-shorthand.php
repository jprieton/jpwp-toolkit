<?php

namespace JPWPToolkit\Traits;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Img_Shorthand trait
 * 
 * @property      string $name Shorthand name
 * 
 * @package       JPWPToolkit
 * @subpackage    Traits
 * @since         0.1.0
 * @author        Javier Prieto
 */
trait Img_Shorthand {

  /**
   * @var     array List of image sizes.
   * @since   0.1.0
   */
  protected $image_sizes = [];

  /**
   * Class constructor
   * 
   * @since     0.1.0
   */
  public function __construct() {
    if ( empty( $this->image_sizes ) ) {
      $this->image_sizes = get_intermediate_image_sizes();
    }

    // Add shorthands keywords
    add_filter( 'jpwp_toolkit_helpers_html_img_shorthands', [ $this, 'add_shorthand' ] );

    // add image shorthands filters
    add_filter( "jpwp_toolkit_helpers_html_img_shorthand_{$this->name}", [ $this, 'parse_shorthand' ], 10, 2 );
  }

}
