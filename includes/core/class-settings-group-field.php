<?php

namespace JPWPToolkit\Core;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

use JPWPToolkit\Helpers\Html;
use JPWPToolkit\Helpers\Form;
use JPWPToolkit\Core\Settings_Group;

/**
 * Settings_Group_Field class
 *
 * @package        JPWPToolkit
 * @subpackage     Core
 * @author         Javier Prieto
 * @since          0.3.0
 */
class Settings_Group_Field extends Settings_Group {

  /**
   * Constructor
   *
   * @since   0.3.0
   * @param string $setting_group Setting group name
   */
  public function __construct( $setting_group ) {
    parent::__construct( $setting_group );
  }

  /**
   * Add field to section
   *
   * @since   0.3.0
   *
   * @param   array          $field
   * @return  boolean
   */
  public function add_settings_field( $page, $section, $field ) {
    $defaults = [
        'type'        => 'text',
        'desc'        => '',
        'id'          => null,
        'input_class' => '',
        'options'     => null
    ];

    $field = wp_parse_args( $field, $defaults );

    switch ( $field['type'] ) {
      case 'checkbox':
        $callback = [ $this, 'render_checkbox' ];
        break;

      case 'textarea':
        $callback = [ &$this, 'render_textarea' ];
        break;

      case 'select':
        $callback = [ &$this, 'render_select' ];
        break;

      case 'text':
      case 'password':
      case 'email':
      default:
        $callback = [ &$this, 'render_input' ];
        break;
    }

    add_settings_field( $field['id'], $field['title'], $callback, $page, $section, $field );
  }

  /**
   * Render a input field
   *
   * @since   0.3.0
   *
   * @param   array          $field
   */
  public function render_input( $field ) {
    $desc          = '';
    $default_value = '';

    if ( array_key_exists( 'desc', $field ) ) {
      $desc = $this->_parse_description( $field['desc'] );
      unset( $field['desc'] );
    }

    if ( array_key_exists( 'input_class', $field ) ) {
      $field['class'] = $field['input_class'];
      unset( $field['input_class'] );
    }

    if ( array_key_exists( 'default', $field ) ) {
      $default_value = $field['default'];
      unset( $field['default'] );
    }

    if ( !empty( $this->setting_group_name ) ) {
      $field['name']  = sprintf( "{$this->setting_group_name}[%s]", $field['id'] );
      $field['value'] = $this->get_option( $field['id'], $default_value );
    } else {
      $field['name']  = $field['id'];
      $field['value'] = get_option( $field['id'], $default_value );
    }

    $defaults = [
        'type'  => 'text',
        'class' => '',
    ];

    $attributes = wp_parse_args( $field, $defaults );
    $input      = Form::input( $attributes );

    echo $input . $desc;
  }

  /**
   * Render a textarea field
   *
   * @since   0.3.0
   *
   * @param   array          $field
   */
  public function render_textarea( $field ) {
    $desc          = '';
    $default_value = '';
    $value         = '';
    $raw           = false;

    if ( array_key_exists( 'desc', $field ) ) {
      $desc = $this->_parse_description( $field['desc'] );
      unset( $field['desc'] );
    }

    if ( array_key_exists( 'input_class', $field ) ) {
      $field['class'] = $field['input_class'];
      unset( $field['input_class'] );
    }

    if ( array_key_exists( 'raw', $field ) ) {
      $raw = (bool) $field['raw'];
      unset( $field['raw'] );
    }

    if ( array_key_exists( 'default', $field ) ) {
      $default_value = (string) $field['default'];
      unset( $field['default'] );
    }

    if ( !empty( $this->setting_group ) ) {
      $field['name'] = sprintf( "{$this->setting_group->setting_group_name}[%s]", $field['id'] );
      $value         = $this->setting_group->get_option( $field['id'], $default_value );
    } else {
      $field['name'] = $field['id'];
      $value         = get_option( $field['id'], $default_value );
    }

    if ( !$raw ) {
      $value = esc_textarea( $value );
    }

    $defaults = [
        'class' => '',
    ];

    $attributes = wp_parse_args( $field, $defaults );
    $input      = Form::textarea( $value, $attributes );

    echo $input . $desc;
  }

  /**
   * Render a checkbox field
   *
   * @since   0.5.0
   *
   * @param   array          $field
   */
  public function render_checkbox( $field ) {
    echo '<fieldset>';
    $options        = (array) $field['options'];
    $is_multiple    = isset( $field['multiple'] ) ? $field['multiple'] : false;
    $field['class'] = $field['input_class'];

    unset( $field['options'], $field['input_class'] );

    if ( empty( $options ) ) {
      $options[] = $field;
    }

    $defaults = [
        'id'    => '',
        'name'  => '',
        'value' => 'yes',
        'type'  => 'checkbox'
    ];

    if ( $is_multiple ) {
      echo Form::hidden( sprintf( "{$this->setting_group_name}[%s][]", $field['id'] ), '' );
    }

    foreach ( $options as $item ) {
      $item = wp_parse_args( $item, $defaults );

      if ( $is_multiple && !$item['id'] && !$item['name'] ) {
        $item['id']   = $field['id'] . '-' . substr( md5( $item['value'] ), 0, 8 );
        $item['name'] = sprintf( "{$this->setting_group_name}[%s][]", $field['id'] );
      } else {
        $is_multiple  = false;
        $item['name'] = sprintf( "{$this->setting_group_name}[%s]", $item['id'] );
      }

      if ( array_key_exists( 'label', $item ) ) {
        $label = '%s ' . $item['label'];
        unset( $item['label'] );
      } else {
        $label = '%s ';
      }

      if ( array_key_exists( 'desc', $item ) ) {
        $desc = $this->_parse_description( $item['desc'] );
        unset( $item['desc'] );
      } else {
        $desc = '<br />';
      }

      if ( $is_multiple ) {
        $options = (array) $this->get_option( $field['id'], [] );
      } else {
        $options = (bool) $this->get_bool_option( $item['id'] );
      }

      if ( (is_bool( $options ) && $options) || (is_array( $options ) && in_array( $item['value'], $options )) ) {
        $item['checked'] = 'checked';
      }

      $label = Form::label( $label, [ 'for' => $item['id'] ] );
      if ( !$is_multiple ) {
        $input = Form::hidden( $item['name'], 'no' );
      } else {
        $input = '';
      }
      $input .= Form::input( $item );

      echo sprintf( $label, $input ) . $desc;
    }

    if ( array_key_exists( 'desc', $field ) ) {
      $desc = $this->_parse_description( $field['desc'] );
      unset( $field['desc'] );
      echo $desc;
    }
    echo '</fieldset>';
  }

  /**
   * Render a select field
   *
   * @since   0.3.0
   *
   * @param   array          $field
   */
  public function render_select( $field ) {
    $defaults = [
        'class'       => '',
        'default'     => '',
        'desc'        => '',
        'options'     => [],
        'placeholder' => '',
    ];

    $field = wp_parse_args( $field, $defaults );
    unset( $field['type'] );

    if ( array_key_exists( 'desc', $field ) ) {
      $desc = $this->_parse_description( $field['desc'] );
      unset( $field['desc'] );
    } else {
      $desc = '';
    }

    if ( array_key_exists( 'input_class', $field ) ) {
      $field['class'] = $field['input_class'];
      unset( $field['input_class'] );
    }

    if ( !empty( $this->setting_group ) ) {
      $field['name'] = sprintf( "{$this->setting_group->setting_group_name}[%s]", $field['id'] );
      $value         = $this->setting_group->get_option( $field['id'], $field['default'] );
    } else {
      $field['name'] = $field['id'];
      $value         = get_option( $field['id'], $field['default'] );
    }

    $field['selected'] = $this->setting_group->get_option( $field['id'] );

    if ( array_key_exists( 'default', $field ) && empty( $field['selected'] ) ) {
      $field['selected'] = (string) $field['default'];
      unset( $field['default'] );
    }

    $options = $field['options'];
    unset( $field['options'] );

    echo Form::select( $field, $options ) . $desc;
  }

  /**
   * Parses the description text and adds class <code>description</code>
   *
   * @since     0.3.0
   *
   * @param     string    $description
   * @return    string
   */
  private function _parse_description( $description ) {
    $description = apply_filters( 'the_content', trim( $description ) );
    $description = str_replace( '<p>', '<p class="description">', $description );
    return $description;
  }

}
