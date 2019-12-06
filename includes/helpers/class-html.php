<?php
/**
 * The Html class is a helper that provides a set of static methods for
 * generating commonly used HTML tags
 *
 * @package       JPWPToolkit
 * @subpackage    Helpers
 */

namespace JPWPToolkit\Helpers;

// Exit if accessed directly.
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
 * @since         0.1.0
 * @author        Javier Prieto
 */
class Html {

  /**
   * List of void elements.
   *
   * @see     http://w3c.github.io/html/syntax.html#void-elements
   *
   * @var     array
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
   * @param   string       $tag       The tag name.
   * @param   array|string $attributes An array of html attributes.
   * @return  string
   */
  public static function open( $tag, $attributes = [] ) {
    if ( empty( $tag ) || !is_string( $tag ) ) {
      return '';
    }

    $tag = esc_attr( $tag );

    self::parse_shorthand( $tag, $attributes );
    $attributes = self::parse_attributes( $attributes );

    if ( in_array( $tag, self::$void ) ) {
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
   * @param   string $tag       The tag name.
   * @return  string
   */
  public static function close( $tag ) {
    if ( empty( $tag ) ) {
      return '';
    }
    return in_array( $tag, self::$void ) ? '' : sprintf( '</%s>', trim( esc_attr( $tag ) ) );
  }

  /**
   * Retrieve a HTML complete tag
   *
   * @since   0.1.0
   *
   * @param   string       $tag       The tag name.
   * @param   string       $content   The content between tags. Is ignored if is a void tag.
   * @param   array|string $attributes An array of html attributes.
   * @return  string
   */
  public static function tag( $tag, $content = '', $attributes = [] ) {
    if ( empty( $tag ) || !is_string( $tag ) ) {
      return $content;
    }

    self::parse_shorthand( $tag, $attributes );

    // This filter allows to you override the tag attributes.
    $attributes = apply_filters( "jpwp_toolkit_helpers_html_{$tag}_attributes", $attributes, $tag );
    $attributes = self::parse_attributes( $attributes );

    if ( in_array( $tag, self::$void ) ) {
      $html = sprintf( '<%s />', trim( "{$tag} {$attributes}" ) );
    } else {
      $html = sprintf( '<%s>%s</%s>', trim( "{$tag} {$attributes}" ), $content, $tag );
    }

    // This filter allows to you override the html result.
    $html = apply_filters( "jpwp_toolkit_helpers_html_{$tag}", $html, $tag, $content, $attributes );

    return $html;
  }

  /**
   * Retrieve an HTML img element
   *
   * @since   0.1.0
   *
   * @param   string       $src        The image src.
   * @param   array|string $attributes An array of html attributes.
   *
   * @link    http://png-pixel.com/
   *
   * @return  string
   */
  public static function img( $src, $attributes = [] ) {
    if ( empty( $src ) || !is_string( $src ) ) {
      return '';
    }

    $attributes = wp_parse_args( $attributes );

    // Filter to allow overrides the src.
    $src = apply_filters( 'jpwp_toolkit_helpers_html_img_src', $src, $attributes );

    // Filter to allow add shorthands.
    $shorthands = apply_filters( 'jpwp_toolkit_helpers_html_img_shorthand_handlers', [] );

    // Check if is a shorthand.
    foreach ( $shorthands as $handler ) {
      if ( strpos( $src, $handler ) !== 0 ) {
        continue;
      }

      $handler = strpos( $src, ':' ) ? substr( $src, 0, strpos( $src, ':' ) ) : substr( $src, 0 );
      if ( !has_filter( "jpwp_toolkit_helpers_html_img_shorthand_{$handler}" ) ) {
        continue;
      }

      // if $src starts with any of the shorthands update the $attributes.
      $attributes = apply_filters( "jpwp_toolkit_helpers_html_img_shorthand_{$handler}", $attributes, $src );

      // remove the $src, must be returned by the filter as part of $attributes array.
      $src = '';

      // All attributes of the shorthand is set, it must be exit of the loop.
      break;
    }

    // Merge all attributes.
    $attributes = wp_parse_args( $attributes, compact( 'src' ) );

    // Generates the <img> tag.
    $html = self::tag( 'img', '', $attributes );

    // Filter to allow overrides the output.
    $html = apply_filters( 'jpwp_toolkit_helpers_html_img', $html, $attributes );

    return $html;
  }

  /**
   * Convert an asociative array to HTML attributes.
   *
   * @since   0.1.0
   *
   * @param   array|string $attr An array of html attributes.
   * @return  string
   */
  public static function parse_attributes( $attr = [] ) {
    $attr = wp_parse_args( $attr );

    if ( count( $attr ) == 0 ) {
      return '';
    }

    $_attributes = [];

    foreach ( (array) $attr as $key => $value ) {
      // Skip invalid key/values
      if ( is_bool( $value ) && !$value ) {
        continue;
      } else
      if ( is_bool( $value ) && is_numeric( $key ) ) {
        continue;
      } else
      if ( is_null( $value ) || is_null( $key ) ) {
        continue;
      } else
      if ( is_numeric( $key ) && empty( $value ) ) {
        continue;
      }

      // Boolean values
      if ( is_numeric( $key ) ) {
        $key   = $value;
        $value = true;
      } else
      if ( is_bool( $value ) && $value ) {
        $value = true;
      }

      if ( !is_bool( $value ) ) {
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
   * @param   string $tag       The tag name or the shorthand.
   * @param   array  $attributes An array of html attributes.
   * @return  array
   */
  public static function parse_shorthand( &$tag, &$attributes = [] ) {
    $attributes = wp_parse_args( $attributes );

    $matches = [];
    preg_match( '(#|\.)', $tag, $matches );

    if ( empty( $matches ) ) {
      // isn't shorthand, do nothing.
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
   * @param   string       $email     The email address.
   * @param   string       $content   The content between tags.
   * @param   array|string $attributes An array of html attributes.
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

    return self::tag( 'a', $content, $attributes );
  }

  /**
   * Generate a HTML-formatted unordered list
   *
   * @since   0.1.0
   *
   * @param   array|string $list    Elements of the list.
   * @param   array|string $attributes An array of html attributes.
   * @return  string
   */
  public static function ul( $list, $attributes = [] ) {
    $content = '';

    foreach ( (array) $list as $key => $item ) {
      if ( is_array( $item ) ) {
        $content .= self::tag( 'li', $key . self::ul( $item ) );
      } else {
        $content .= self::tag( 'li', $item );
      }
    }

    $content = self::tag( 'ul', $content, $attributes );
    return $content;
  }

  /**
   * Generate a HTML-formatted ordered list
   *
   * @since   0.1.0
   *
   * @param   array|string $list    Elements of the list.
   * @param   array|string $attributes An array of html attributes.
   * @return  string
   */
  public static function ol( $list, $attributes = [] ) {
    $content = '';

    foreach ( (array) $list as $key => $item ) {
      if ( is_array( $item ) ) {
        $content .= self::tag( 'li', $key . self::ol( $item ) );
      } else {
        $content .= self::tag( 'li', $item );
      }
    }

    $content = self::tag( 'ol', $content, $attributes );
    return $content;
  }

  /**
   * Magic method for tags
   *
   * @since   0.1.0
   *
   * @param   string $tag       The tag name.
   * @param   array  $arguments      An array that may contain content and attributes.
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
    $html = self::tag( $tag, $content, $attributes );

    return $html;
  }

}
