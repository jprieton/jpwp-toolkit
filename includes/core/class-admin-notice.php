<?php
/**
 * Generate and enqueue notices to WordPress admin
 * 
 * @package        JPWPToolkit
 * @subpackage     Core
 */

namespace JPWPToolkit\Core;

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
  die( 'Direct access is forbidden.' );
}

/**
 * Class to generate admin notices
 *
 * @package        JPWPToolkit
 * @subpackage     Core
 * @since          0.1.0
 * @author         Javier Prieto
 */
class Admin_Notice {

  /**
   * Admin notice message
   *
   * @since   0.1.0
   * @var     string
   */
  private $message;

  /**
   * Admin notice css class
   *
   * @since   0.1.0
   * @var     string
   */
  private $class;

  /**
   * Class constructor
   *
   * Creates and hook admin notice
   *
   * @since   0.1.0
   * @param   string $message The notice message.
   * @param   array  $attr    attributes of the notice.
   */
  public function __construct( $message, $attr = [] ) {
    $defaults = [
        'type'        => '',
        'dismissible' => false,
    ];
    $attr     = wp_parse_args( $attr, $defaults );

    switch ( $attr['type'] ) {
      case 'error':
        $this->class = 'notice notice-error';
        break;

      case 'warning':
        $this->class = 'notice notice-warning';
        break;

      case 'success':
        $this->class = 'notice notice-success';
        break;

      case 'info':
        $this->class = 'notice notice-info';
        break;

      default:
        $this->class = 'notice';
        break;
    }

    if ( $attr['dismissible'] ) {
      $this->class .= ' is-dismissible';
    }

    $this->message = $message;

    // enqueue notice.
    add_action( 'admin_notices', [ $this, 'show_admin_notice' ] );
  }

  /**
   * Hooked function to show admin notice
   *
   * @since   0.1.0
   */
  public function show_admin_notice() {
    $output = wpautop( make_clickable( $this->message ) );
    $output = sprintf( '<div class="%1$s">%2$s</div>', esc_attr( $this->class ), $output );

    // Show the notice.
    echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
  }

}
