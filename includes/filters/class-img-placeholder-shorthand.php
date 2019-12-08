<?php
/**
 * Add the placeholder handler to Html::img()
 *
 * @package       JPWPToolkit
 * @subpackage    Filter
 */

namespace JPWPToolkit\Filters;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use JPWPToolkit\Interfaces\Img_Shorthand as Interface_Img_Shorthand;
use JPWPToolkit\Abstracts\Img_Shorthand as Abstract_Img_Shorthand;

/**
 * Add the placeholder shorthand to Html::img() method
 * Some examples of shorthands are placeholder, placeholder:200, placeholder:300x150, placeholder:none,
 * placeholder:medium
 *
 * @package       JPWPToolkit
 * @subpackage    Filters
 * @since         0.1.0
 * @author        Javier Prieto
 */
class Img_Placeholder_Shorthand extends Abstract_Img_Shorthand implements Interface_Img_Shorthand {

  /**
   * Adds Img_Shorthand methods and properties
   *
   * @since     0.1.0
   */
  use \JPWPToolkit\Traits\Img_Shorthand;

  /**
   * Shorthand handler
   *
   * @var     string
   * @since   0.1.0
   */
  private $handler = 'placeholder';

  /**
   * Overrides the class constructor to allow use the WordPress image sizes as shorthand for placeholder
   *
   * @since     0.1.0
   */
  public function __construct() {
    if ( empty( $this->image_sizes ) ) {
      $this->image_sizes = get_intermediate_image_sizes();
    }

    // Allow to use the WordPress image sizes as alias of placeholder.
    add_filter( "jpwp_toolkit_helpers_html_img_src", [ $this, 'parse_src' ], 10, 2 );

    // Add shorthands keywords.
    add_filter( 'jpwp_toolkit_helpers_html_img_shorthand_handlers', [ $this, 'add_shorthand_handler' ] );

    // add image shorthands filters.
    add_filter( "jpwp_toolkit_helpers_html_img_shorthand_{$this->handler}", [ $this, 'parse_shorthand' ], 10, 2 );
  }

  /**
   * Parse the shorthand
   *
   * @since   0.1.0
   *
   * @param array  $attributes An array of html attributes.
   * @param string $src The img handler.
   */
  public function parse_shorthand( $attributes, $src ) {
    $attributes = $this->get_image_size_attributes( $src, $attributes );

    $lang             = substr( get_locale(), 0, 2 );
    $default_img_path = "assets/images/placeholder-{$lang}.svg";

    if ( file_exists( JPWP_ABSPATH . $default_img_path ) ) {
      $src = JPWP_BASEURL . $default_img_path;
    } else {
      $src = JPWP_BASEURL . 'assets/images/placeholder.svg';
    }

    // Set src of the image.
    $attributes['src'] = $src;

    // Update css classes.
    $attributes['class'] = empty( $attributes['class'] ) ?
            'image-placeholder' : $attributes['class'] . ' image-placeholder';

    // Updtate alt value.
    $attributes['alt'] = empty( $attributes['alt'] ) ?
            __( 'Placeholder image', 'jpwp-toolkit' ) : $attributes['alt'];

    // Allow filter all attributes.
    $attributes = apply_filters( "jpwp_toolkit_helpers_html_img_{$this->handler}_attributes", $attributes );

    return $attributes;
  }

  /**
   * Allow to use the WordPress image sizes as alias of placeholder
   *
   * @since   0.1.0
   *
   * @param   string $src The img handler.
   * @return  string
   */
  public function parse_src( $src ) {
    $shorthand = strpos( $src, ':' ) ? substr( $src, 0, strpos( $src, ':' ) ) : substr( $src, 0 );

    // If the src is a name of any of the WordPress image sizes, update it.
    if ( in_array( $shorthand, $this->image_sizes ) ) {
      $src = "placeholder:{$src}";
    }

    return $src;
  }

}
