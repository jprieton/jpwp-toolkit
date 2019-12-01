<?php
/**
 * Add the pixel shorthand to Html::img() method
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
 * Add the pixel shorthand to Html::img() method
 * Some examples of shorthands are pixel, pixel:200, pixel:300x150, pixel:none, pixel:medium 
 * 
 * @package       JPWPToolkit
 * @subpackage    Filter
 * @since         0.1.0
 * @author        Javier Prieto
 */
class Img_Pixel_Shorthand extends Abstract_Img_Shorthand implements Interface_Img_Shorthand {

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
  protected $handler = 'pixel';

  /**
   * Parse the shorthand
   * 
   * @since   0.1.0
   * 
   * @param array  $attributes An array of html attributes.
   * @param string $src The img handler.
   */
  public function parse_shorthand( $attributes, $src ) {
    if ( !isset( $attributes['width'] ) && !isset( $attributes['height'] ) ) {
      $attributes['size'] = '1x1';
    }

    $attributes = $this->get_image_size_attributes( $src, $attributes );

    // Set src of the image.
    $attributes['src'] = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';

    // Update css classes.
    $attributes['class'] = empty( $attributes['class'] ) ?
            'image-pixel' : $attributes['class'] . ' image-pixel';

    // Updtate alt value.
    $attributes['alt'] = empty( $attributes['alt'] ) ?
            __( 'Pixel image', 'jpwp-toolkit' ) : $attributes['alt'];

    // Allow filter all attributes.
    $attributes = apply_filters( "jpwp_toolkit_helpers_html_img_{$this->handler}_attributes", $attributes );

    return $attributes;
  }

}
