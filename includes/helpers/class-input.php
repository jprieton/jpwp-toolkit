<?php
/**
 * Helper to manage POST, GET requests
 * 
 * @package        JPWPToolkit
 * @subpackage     Helpers
 */

namespace JPWPToolkit\Helpers;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Input class
 *
 * Access form data (POST, GET, SERVER, COOKIE)
 *
 * @since          0.2.0
 * @author         Javier Prieto
 */
class Input {

  private static function get_value( $type, $field, $args, $default = '' ) {
    $value = filter_input( $type, $field, $args['filter'], $args['options'] );

    if ( empty( $value ) ) {
      return $default;
    }

    if ( !empty( $args['callback'] ) ) {
      $value = self::callback( $value, $args['callback'] );
    }

    return $value;
  }

  /**
   * Return $_GET parameter
   *
   * @since     0.2.0
   * @see       http://php.net/manual/es/filter.filters.php
   * @see       https://developer.wordpress.org/reference/functions/sanitize_text_field/
   * @param     string|array $field
   * @param     mixed        $default
   * @param     array        $args
   * @return    mixed
   */
  public static function get( $field, $default = '', $args = [] ) {
    $defaults = [
        'filter'   => FILTER_SANITIZE_STRING,
        'callback' => [ 'sanitize_text_field' ],
        'options'  => []
    ];
    $args     = wp_parse_args( $args, $defaults );

    if ( is_array( $field ) ) {
      foreach ( $field as $_field ) {
        $value[$_field] = self::get( $_field, $default, $args );
      }
    } else {
      $value = self::get_value( INPUT_GET, $field, $args, $default );
    }

    return $value;
  }

  /**
   * Return $_POST parameter
   *
   * @since     0.2.0
   * @see       http://php.net/manual/es/filter.filters.php
   * @see       https://developer.wordpress.org/reference/functions/sanitize_text_field/
   * @param     string|array $field
   * @param     mixed        $default
   * @param     array        $args
   * @return    mixed
   */
  public static function post( $field, $default = '', $args = [] ) {
    $defaults = [
        'filter'   => FILTER_SANITIZE_STRING,
        'callback' => [ 'sanitize_text_field' ],
        'options'  => []
    ];
    $args     = wp_parse_args( $args, $defaults );

    if ( is_array( $field ) ) {
      foreach ( $field as $_field ) {
        $value[$_field] = self::post( $_field, $default, $args );
      }
    } else {
      $value = self::get_value( INPUT_POST, $field, $args, $default );
    }

    return $value;
  }

  /**
   * Return $_SERVER parameter
   *
   * @since     0.2.0
   * @see       http://php.net/manual/es/filter.filters.php
   * @see       https://developer.wordpress.org/reference/functions/sanitize_text_field/
   * @param     string|array $field
   * @param     mixed        $default
   * @param     array        $args
   * @return    mixed
   */
  public static function server( $field, $default = '', $args = [] ) {
    $defaults = [
        'filter'   => FILTER_SANITIZE_STRING,
        'callback' => [ 'sanitize_text_field' ],
        'options'  => []
    ];
    $args     = wp_parse_args( $args, $defaults );

    if ( is_array( $field ) ) {
      foreach ( $field as $_field ) {
        $value['field'] = self::server( $_field, $default, $args );
      }
    } else {
      $value = self::get_value( INPUT_SERVER, $field, $args, $default );
    }

    return $value;
  }

  /**
   * Return $_COOKIE parameter
   *
   * @since     0.2.0
   * @see       http://php.net/manual/es/filter.filters.php
   * @see       https://developer.wordpress.org/reference/functions/sanitize_text_field/
   * @param     string|array $field
   * @param     mixed        $default
   * @param     array        $args
   * @return    mixed
   */
  public static function cookie( $field, $default = '', $args = [] ) {
    $defaults = [
        'filter'   => FILTER_SANITIZE_STRING,
        'callback' => [ 'sanitize_text_field' ],
        'options'  => []
    ];
    $args     = wp_parse_args( $args, $defaults );

    if ( is_array( $field ) ) {
      foreach ( $field as $_field ) {
        $value['field'] = self::cookie( $_field, $default, $args );
      }
    } else {
      $value = self::get_value( INPUT_COOKIE, $field, $args, $default );
    }

    return $value;
  }

  /**
   * Executes the filter callbacks
   *
   * @since     0.2.0
   * @param     string $value
   * @param     array  $callable
   * @return    string
   */
  private static function callback( $value, $callable = [] ) {
    foreach ( (array) $callable as $callback ) {
      if ( is_callable( $callback ) ) {
        $value = call_user_func( $callback, $value );
      }
    }
    return $value;
  }

  /**
   * Is AJAX request?
   *
   * Test to see if a request contains the HTTP_X_REQUESTED_WITH header.
   *
   * @since     0.2.0
   * @return    bool
   */
  public function is_ajax_request() {
    return (self::server( 'HTTP_X_REQUESTED_WITH' ) === 'xmlhttprequest');
  }

  /**
   * Get Request Method
   *
   * Return the request method
   *
   * @since     0.2.0
   * @return 	string
   */
  public function method() {
    return self::server( 'REQUEST_METHOD' );
  }

  /**
   * Fetch User Agent string
   *
   * @since     0.2.0
   * @return	string    User Agent string or empty if it doesn't exist.
   */
  public function user_agent() {
    return self::server( 'HTTP_USER_AGENT' );
  }

  /**
   * Generate URL-encoded query string for method request
   *
   * @since     0.2.0
   * @param     string $method Request method.
   * @param     array  $override Overrides method query data.
   * @return    string
   */
  public function query_string( $method = 'GET', $override = [] ) {
    $method     = '_' . strtoupper( $method );
    $query_data = wp_parse_args( $override, ${$method} );
    return http_build_query( $query_data );
  }

}
