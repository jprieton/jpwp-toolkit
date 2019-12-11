<?php
/**
 * Task to clean and optimize tables with orphan data
 *
 * @package        JPWPToolkit
 * @subpackage     Shortcodes
 */

namespace JPWPToolkit\Shortcodes;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use JPWPToolkit\Helpers\Html;

/**
 * Mailto shortcodes
 *
 * @since 0.3.0
 */
class Mailto {

  /**
   * Class constructor
   */
  public function __construct() {
    add_shortcode( 'mailto', [ $this, 'add_shortcode' ] );
  }

  /**
   * Add mailto shortcode
   *
   * @since 0.3.0
   *
   * @param   array     $attr      An array of html attributes.
   * @param   string    $content   The content between tags.
   * @return string
   */
  public function add_shortcode( $attr, $content = null ) {
    $defaults = [ 'href' => '' ];
    $attr     = wp_parse_args( $attr, $defaults );

    $attr = apply_filters( 'jpwp_toolkit_shortcode_mailto_attributes', $attr );

    if ( is_email( $content ) ) {
      $attr['href'] = $content;
    }

    if ( empty( $attr['href'] ) ) {
      return '';
    } else {
      $email = $attr['href'];
      unset( $attr['href'] );
    }

    if ( empty( $content ) ) {
      $content = $email;
    }

    return Html::mailto( $email, $content, $attr );
  }

}
