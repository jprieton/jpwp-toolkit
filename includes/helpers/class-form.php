<?php

namespace JPWPToolkit\Helpers;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

use JPWPToolkit\Helpers\Html;
use WP_Locale;

/**
 * Form class
 *
 * A collection of static methods to generate form elements markup.
 *
 * @see         https://laravelcollective.com/docs/6.0/html
 * @see         http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html
 * @see         https://docs.phalconphp.com/en/latest/reference/tags.html#tag-service
 *
 * @package     JPWPToolkit
 * @subpackage  Helpers
 * @author      Javier Prieto
 * @since       0.3.0
 */
class Form {

  /**
   * Create a input label.
   *
   * @since 0.3.0
   *
   * @param   string              $label
   * @param   array|string        $attributes
   * @return  string
   */
  public static function label( $label, $attributes = [] ) {
    return Html::label( $label, $attributes );
  }

  /**
   * Create a form input field.
   *
   * @since 0.3.0
   *
   * @param   string              $name
   * @param   string              $value
   * @param   array|string        $attributes
   * @return  string
   */
  public static function input( $attributes = [] ) {
    $defaults   = [
        'name'  => '',
        'value' => '',
        'type'  => 'text'
    ];
    $attributes = wp_parse_args( $attributes, $defaults );

    return Html::input( null, $attributes );
  }

  /**
   * Create a textarea input field.
   *
   * @since 0.3.0
   *
   * @param   string              $name
   * @param   string              $text
   * @param   array|string        $attributes
   * @return  string
   */
  public static function textarea( $name, $text = '', $attributes = [] ) {
    $defaults   = [
        'name' => $name,
    ];
    $attributes = wp_parse_args( $attributes, $defaults );

    return Html::textarea( esc_textarea( $text ), $attributes );
  }

  /**
   * Create a button element.
   *
   * @param   string              $label
   * @param   string              $type
   * @param   type                $attributes
   * @return  string
   */
  public static function button( $label, $type = 'button', $attributes = [] ) {
    $defaults   = [
        'type' => $type,
    ];
    $attributes = wp_parse_args( $attributes, $defaults );

    return Html::button( 'button', $label, $attributes );
  }

  /**
   * Shorthand for hidden input field.
   *
   * @since 0.3.0
   *
   * @param   string              $name
   * @param   string              $value
   * @param   array|string        $attributes
   * @return  string
   */
  public static function hidden( $name, $value = '', $attributes = [] ) {
    $type       = 'hidden';
    $attributes = array_merge( $attributes, compact( 'name', 'value', 'type' ) );
    return self::input( $attributes );
  }

  /**
   * Shorthand for text input field.
   *
   * @since 0.3.0
   *
   * @param   string              $name
   * @param   string              $value
   * @param   array|string        $attributes
   * @return  string
   */
  public static function text( $name, $value = '', $attributes = [] ) {
    $type       = 'text';
    $attributes = array_merge( $attributes, compact( 'name', 'value', 'type' ) );
    return self::input( $attributes );
  }

  /**
   * Shorthand for email input field.
   *
   * @since 0.3.0
   *
   * @param   string              $name
   * @param   string              $value
   * @param   array|string        $attributes
   * @return  string
   */
  public static function email( $name, $value = '', $attributes = [] ) {
    $type       = 'email';
    $attributes = array_merge( $attributes, compact( 'name', 'value', 'type' ) );
    return self::input( $attributes );
  }

  /**
   * Shorthand for email input field.
   *
   * @since 0.3.0
   *
   * @param   string              $name
   * @param   string              $value
   * @param   array|string        $attributes
   * @return  string
   */
  public static function url( $name, $value = '', $attributes = [] ) {
    $type       = 'url';
    $attributes = array_merge( $attributes, compact( 'name', 'value', 'type' ) );
    return self::input( $attributes );
  }

  /**
   * Shorthand for password input field.
   *
   * @since 0.3.0
   *
   * @param   string              $name
   * @param   array|string        $attributes
   * @return  string
   */
  public static function password( $name, $attributes = [] ) {
    $type       = 'password';
    $attributes = array_merge( $attributes, compact( 'name', 'type' ) );
    return self::input( $attributes );
  }

  /**
   * Shorthand for file input field.
   *
   * @since 0.3.0
   *
   * @param   string              $name
   * @param   array|string        $attributes
   * @return  string
   */
  public static function file( $name, $attributes = [] ) {
    $type       = 'file';
    $attributes = array_merge( $attributes, compact( 'name', 'value', 'type' ) );
    return self::input( $attributes );
  }

  /**
   * Create a dropdown list.
   *
   * @since 0.3.0
   *
   * @param   string              $name
   * @param   array|string        $options
   * @param   array|string        $attributes
   * @return  string
   */
  public static function select( $options = [], $attributes = [] ) {
    $placeholder = '';
    $required    = false;
    $selected    = '';

    if ( !empty( $attributes['placeholder'] ) && !is_bool( $attributes['placeholder'] ) ) {
      $placeholder = $attributes['placeholder'];
    } elseif ( !empty( $attributes['placeholder'] ) && is_bool( $attributes['placeholder'] ) && $attributes['placeholder'] ) {
      $placeholder = __( 'Select...', SF_TEXTDOMAIN );
    }

    if ( !empty( $attributes['required'] ) && is_bool( $attributes['required'] ) && $attributes['required'] ) {
      $required = true;
    }

    if ( !empty( $attributes['selected'] ) ) {
      $selected = $attributes['selected'];
    }

    if ( !empty( $placeholder ) ) {
      $atts        = [
          'selected' => ($selected == ''),
          'value'    => '',
          'disabled' => $required,
      ];
      $placeholder = Html::option( $placeholder, $atts );
    }

    unset( $attributes['placeholder'], $attributes['selected'], $attributes['required'] );

    return Html::select( $placeholder . self::options( $options, $selected ), $attributes );
  }

  /**
   * Create a list of option tags from array .
   *
   * @since 0.3.0
   *
   * @global WP_Locale            $wp_locale
   *
   * @param  array|string         $options
   * @param  array                $selected
   * @return string
   */
  public static function options( $options, $selected = '' ) {
    global $wp_locale;

    $_options = '';

    switch ( $options ) {
      case 'month':
        $options = $wp_locale->month;
        break;
      case 'weekday':
        $options = $wp_locale->weekday;
        break;
      default:
        $options = (array) $options;
        break;
    }

    foreach ( $options as $key => $value ) {
      if ( is_array( $value ) ) {
        $_options .= Html::tag( 'optgroup', self::options( $value, $selected ), [ 'label' => $key ] );
      } else {
        $attributes = [
            'value'    => $key,
            'selected' => (!empty( $selected ) && $selected == $key) ? 'selected' : false,
        ];
        $_options   .= Html::tag( 'option', $value, $attributes );
      }
    }
    return $_options;
  }

}
