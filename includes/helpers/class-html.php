<?php

namespace JPWPToolkit\Helpers;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Html class
 *
 * Class inspired on Laravel Forms & HTML helper and Yii Framework BaseHtml helper
 *
 * @see           https://laravelcollective.com/docs/6.0/html
 * @see           http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html
 * @see           https://docs.phalconphp.com/en/latest/reference/tags.html#tag-service
 *
 * @package       JPWPToolkit
 * @subpackage    Helpers
 * @since         0.1.0
 * @author        Javier Prieto
 */
class Html {

  /**
   * @see     http://w3c.github.io/html/syntax.html#void-elements
   *
   * @var     array   List of void elements.
   * @since   0.1.0
   */
  private static $void = [
      'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input',
      'link', 'meta', 'param', 'source', 'track', 'wbr'
  ];

  /**
   * Retrieve a HTML open tag
   *
   * @since   0.1.0
   *
   * @param   string              $tag
   * @param   array|string        $attributes
   * @return  string
   */
  public static function open( $tag, $attributes = [] ) {
    $tag = esc_attr( $tag );
    if ( empty( $tag ) ) {
      return '';
    }
    static::parse_shorthand( $tag, $attributes );
    $attributes = static::parse_attributes( $attributes );

    if ( in_array( $tag, static::$void ) ) {
      $html = sprintf( '<%s />', trim( $tag . ' ' . $attributes ) );
    } else {
      $html = sprintf( '<%s>', trim( $tag . ' ' . $attributes ) );
    }

    return $html;
  }

  /**
   * Retrieve a HTML close tag
   *
   * @since   0.1.0
   *
   * @param   string              $tag
   * @return  string
   */
  public static function close( $tag ) {
    if ( empty( $tag ) ) {
      return '';
    }
    return in_array( $tag, static::$void ) ? '' : sprintf( '</%s>', trim( esc_attr( $tag ) ) );
  }

  /**
   * Retrieve a HTML complete tag
   *
   * @since   0.1.0
   *
   * @param   string              $tag
   * @param   string              $content
   * @param   array|string        $attributes
   * @return  string
   */
  public static function tag( $tag, $content = '', $attributes = [] ) {
    if ( empty( $tag ) ) {
      return $content;
    }

    static::parse_shorthand( $tag, $attributes );

    // This filter allows to you override the tag attributes
    $attributes = apply_filters( "jpwp_toolkit_helpers_html_{$tag}_attributes", $attributes, $tag );
    $attributes = static::parse_attributes( $attributes );

    if ( in_array( $tag, static::$void ) ) {
      $html = sprintf( '<%s />', trim( "{$tag} {$attributes}" ) );
    } else {
      $html = sprintf( '<%s>%s</%s>', trim( "{$tag} {$attributes}" ), $content, $tag );
    }

    // This filter allows to you override the html result
    $html = apply_filters( "jpwp_toolkit_helpers_html_{$tag}", $html, $tag, $content, $attributes );

    return $html;
  }

  /**
   * Retrieve an HTML img element
   *
   * @since   0.1.0
   *
   * @param   string              $src
   * @param   string|array        $attributes
   *
   * @link    http://png-pixel.com/
   *
   * @return  string
   */
  public static function img( $src = '', $attributes = [] ) {
    $attributes = wp_parse_args( $attributes );

    // Filter to allow overrides the src
    $src = apply_filters( 'jpwp_toolkit_helpers_html_img_src', $src, $attributes );

    // Filter to allow add shorthands
    $shorthands = apply_filters( 'jpwp_toolkit_helpers_html_img_shorthands', [] );

    // Check if is a shorthand
    foreach ( $shorthands as $shorthand ) {
      if ( strpos( $src, $shorthand ) !== 0 ) {
        continue;
      }

      $shorthand = strpos( $src, ':' ) ? substr( $src, 0, strpos( $src, ':' ) ) : substr( $src, 0 );
      if ( !has_filter( "jpwp_toolkit_helpers_html_img_shorthand_{$shorthand}" ) ) {
        continue;
      }

      // if $src starts with any of the shorthands update the $attributes
      $attributes = apply_filters( "jpwp_toolkit_helpers_html_img_shorthand_{$shorthand}", $attributes, $src );

      // remove the $src, must be returned by the filter as part of $attributes array
      $src = '';

      // All attributes of the shorthand is set, it must be exit of the loop
      break;
    }

    // Merge all attributes
    $attributes = wp_parse_args( $attributes, compact( 'src' ) );

    // Generates the <img> tag
    $html = self::tag( 'img', '', $attributes );

    // Filter to allow overrides the output
    $html = apply_filters( 'jpwp_toolkit_helpers_html_img', $html, $attributes );

    return $html;
  }

  /**
   * Convert an asociative array to HTML attributes
   *
   * @since   0.1.0
   *
   * @param   array|string    $attributes
   * @return  string
   *
   */
  public static function parse_attributes( $attributes = [] ) {
    $attributes = wp_parse_args( $attributes );

    if ( count( $attributes ) == 0 ) {
      return '';
    }

    $_attributes = [];

    foreach ( (array) $attributes as $key => $value ) {
      if ( is_bool( $value ) && !$value ) {
        continue;
      }

      if ( is_numeric( $key ) && !is_bool( $value ) ) {
        $key   = $value;
        $value = null;
      }

      if ( is_bool( $value ) && $value ) {
        $value = null;
      }

      if ( is_array( $value ) ) {
        $value = implode( ' ', $value );
      }

      if ( !is_null( $value ) ) {
        $_attributes[] = sprintf( '%s="%s"', trim( esc_attr( $key ) ), trim( esc_attr( $value ) ) );
      } else {
        $_attributes[] = trim( esc_attr( $key ) );
      }
    }

    return implode( ' ', $_attributes );
  }

  /**
   * Parse a shorthand for single element (beta).
   *
   * @since   0.1.0
   *
   * @param   string              $tag
   * @param   array               $attributes
   * @return  array
   */
  public static function parse_shorthand( &$tag, &$attributes = [] ) {
    $attributes = wp_parse_args( $attributes );

    $matches = [];
    preg_match( '(#|\.)', $tag, $matches );

    if ( empty( $matches ) ) {
      // isn't shorthand, do nothing
      return;
    }

    $items = str_replace( [ '.', '#' ], [ ' .', ' #' ], $tag );
    $items = explode( ' ', $items );

    $tag   = !empty( $items[0] ) ? $items[0] : 'div';
    $id    = null;
    $class = null;

    foreach ( $items as $item ) {
      if ( strpos( $item, '#' ) !== false ) {
        $id = trim( str_replace( '#', '', $item ) );
      } elseif ( strpos( $item, '.' ) !== false ) {
        $class .= ' ' . trim( str_replace( '.', '', $item ) );
      }
    }

    if ( $id && empty( $attributes['id'] ) ) {
      $attributes['id'] = $id;
    }

    if ( $class && empty( $attributes['class'] ) ) {
      $attributes['class'] = $class;
    }

    $tag = esc_attr( $tag );
  }

  /**
   * Generate a HTML link to an email address.
   *
   * @since   0.1.0
   *
   * @param   string              $email
   * @param   string              $content
   * @param   array|string        $attributes
   * @return  string
   */
  public static function mailto( $email, $content = null, $attributes = [] ) {
    if ( empty( $email ) || !is_email( $email ) ) {
      return '';
    }

    $content = $content ?: antispambot( $email );
    $email   = antispambot( 'mailto:' . $email );

    $defaults   = [
        'href' => $email,
    ];
    $attributes = wp_parse_args( $attributes, $defaults );

    return static::tag( 'a', $content, $attributes );
  }

  /**
   * Generate a HTML-formatted unordered list
   *
   * @since   0.1.0
   *
   * @param   array|string        $list
   * @param   array|string        $attributes
   * @return  string
   */
  public static function ul( $list, $attributes = [] ) {
    $content = '';

    foreach ( (array) $list as $key => $item ) {
      if ( is_array( $item ) ) {
        $content .= static::tag( 'li', $key . static::ul( $item ) );
      } else {
        $content .= static::tag( 'li', $item );
      }
    }

    $content = static::tag( 'ul', $content, $attributes );
    return $content;
  }

  /**
   * Generate a HTML-formatted ordered list
   *
   * @since   0.1.0
   *
   * @param   array|string        $list
   * @param   array|string        $attributes
   * @return  string
   */
  public static function ol( $list, $attributes = [] ) {
    $content = '';

    foreach ( (array) $list as $key => $item ) {
      if ( is_array( $item ) ) {
        $content .= static::tag( 'li', $key . static::ol( $item ) );
      } else {
        $content .= static::tag( 'li', $item );
      }
    }

    $content = static::tag( 'ol', $content, $attributes );
    return $content;
  }

  /**
   * Magic method for tags
   *
   * @since   0.1.0
   *
   * @param   string    $tag
   * @param   array     $arguments
   * @return  string
   */
  public static function __callStatic( $tag, $arguments ) {
    if ( !isset( $arguments[0] ) ) {
      $arguments[0] = null;
    }

    if ( !isset( $arguments[1] ) ) {
      $arguments[1] = null;
    }

    list($content, $attributes) = $arguments;
    $html = static::tag( $tag, $content, $attributes );

    return $html;
  }

}
