<?php
/**
 * Add the not_available shorthand to Html::img() method
 *
 * @package       JPWPToolkit
 * @subpackage    Filters
 */

namespace JPWPToolkit\Filters;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use JPWPToolkit\Interfaces\Img_Shorthand as Interface_Img_Shorthand;
use JPWPToolkit\Abstracts\Img_Shorthand as Abstract_Img_Shorthand;

/**
 * Add the not_available shorthand to Html::img() method
 * Some examples of shorthands are not_available, not_available:200, not_available:300x150,
 * not_available:none, not_available:medium
 *
 * @package       JPWPToolkit
 * @subpackage    Filters
 * @since         0.1.0
 * @author        Javier Prieto
 */
class Img_Not_Available_Shorthand extends Abstract_Img_Shorthand implements Interface_Img_Shorthand {

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
  private $handler = 'not_available';

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
    $default_img_path = "assets/images/not-available-{$lang}.svg";

    if ( file_exists( JPWP_ABSPATH . $default_img_path ) ) {
      $src = JPWP_BASEURL . $default_img_path;
    } else {
      $src = JPWP_BASEURL . 'assets/images/not-available.svg';
    }

    // Set src of the image.
    $attributes['src'] = $src;

    // Update css classes.
    $attributes['class'] = empty( $attributes['class'] ) ?
            'image-not-available' : $attributes['class'] . ' image-not-available';

    // Updtate alt value.
    $attributes['alt'] = empty( $attributes['alt'] ) ?
            __( 'Not available image', 'jpwp-toolkit' ) : $attributes['alt'];

    // Allow filter all attributes.
    $attributes = apply_filters( "jpwp_toolkit_helpers_html_img_{$this->handler}_attributes", $attributes );

    return $attributes;
  }

}
