<?php
/**
 * Abstract class to add a shorthand handler for images
 * 
 * @package       JPWPToolkit
 * @subpackage    Abstracts
 */

namespace JPWPToolkit\Abstracts;

defined( 'ABSPATH' ) || exit;

/**
 * Abstract class to add a shorthand handler for images
 * 
 * @package       JPWPToolkit
 * @subpackage    Abstracts
 * @since         0.1.0
 * @author        Javier Prieto
 */
abstract class Img_Shorthand {

  /**
   * Adds the height and width values to image attributes
   *
   * @since   0.1.0
   *
   * @param   string       $src The image handler.
   * @param   array|string $attributes  An array of html attributes.
   * @return  array
   */
  protected function get_image_size_attributes( $src, $attributes ) {
    $params = explode( ':', $src );

    // No image size, set the defaults image.
    if ( count( $params ) != 2 ) {
      $params[1] = !empty( $attributes['size'] ) ? $attributes['size'] : 'thumbnail';
      unset( $attributes['size'] );
    }

    if ( in_array( $params[1], [ 'none', 'full' ] ) ) {
      // No size attributes.
      list($width, $height) = [ null, null ];

      // Update css classes.
      $attributes['class'] = empty( $attributes['class'] ) ?
              'image-size-' . $params[1] : $attributes['class'] . ' image-size-' . $params[1];
    } elseif ( in_array( $params[1], $this->image_sizes ) ) {
      // Is a WordPress defined inage size.
      $width               = get_option( $params[1] . '_size_w' );
      $height              = get_option( $params[1] . '_size_h' );
      // Update css classes.
      $attributes['class'] = empty( $attributes['class'] ) ?
              'image-size-' . $params[1] : $attributes['class'] . ' image-size-' . $params[1];
    } else {
      // Is a custom image size.
      list($width, $height) = explode( 'x', $params[1] );
      // Update css classes.
      $attributes['class'] = empty( $attributes['class'] ) ?
              'image-size-custom' : $attributes['class'] . ' image-size-custom';
    }

    // Set width.
    if ( is_numeric( $width ) ) {
      $attributes = array_merge( compact( 'width' ), $attributes );
    }

    // Set height.
    if ( is_numeric( $height ) ) {
      $attributes = array_merge( compact( 'height' ), $attributes );
    }

    unset( $attributes['size'] );

    return $attributes;
  }

}
