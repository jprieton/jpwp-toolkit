<?php

namespace JPWPToolkit\Tools;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

use wpdb;

/**
 * Clean class
 *
 * @package        JPWPToolkit
 * @subpackage     Tools
 * @since          0.3.0
 * @author         Javier Prieto
 */
class Clean {

  /**
   * Delete all non-linked metadata
   *
   * @since   0.3.0
   * @global  wpdb    $wpdb
   */
  public static function delete_orphan_metadata() {
    global $wpdb;

    // postmeta
    $wpdb->query( "DELETE FROM `{$wpdb->postmeta}` WHERE `post_id` NOT IN ( SELECT `ID` AS `post_id` FROM `{$wpdb->posts}` )" );
    $wpdb->query( "OPTIMIZE TABLE `{$wpdb->postmeta}`" );

    // usermeta
    $wpdb->query( "DELETE FROM `{$wpdb->usermeta}` WHERE `user_id` NOT IN ( SELECT `ID` AS `user_id` FROM `{$wpdb->users}` )" );
    $wpdb->query( "OPTIMIZE TABLE `{$wpdb->usermeta}`" );

    // termmeta
    $wpdb->query( "DELETE FROM `{$wpdb->termmeta}` WHERE `term_id` NOT IN ( SELECT `term_id` FROM `{$wpdb->terms}` )" );
    $wpdb->query( "OPTIMIZE TABLE `{$wpdb->termmeta}`" );

    // commentmeta
    $wpdb->query( "DELETE FROM `{$wpdb->commentmeta}` WHERE `comment_id` NOT IN ( SELECT `comment_ID` AS `comment_id` FROM `{$wpdb->comments}` )" );
    $wpdb->query( "OPTIMIZE TABLE `{$wpdb->commentmeta}`" );
  }

  /**
   * Delete all non-linked comments
   *
   * @since   0.3.0
   * @global  wpdb    $wpdb
   */
  public static function delete_orphan_comments() {
    global $wpdb;

    // comments
    $wpdb->query( "DELETE FROM `{$wpdb->comments}` WHERE `comment_post_ID` NOT IN ( SELECT `ID` AS `comment_post_ID` FROM `{$wpdb->posts}` )" );
    $wpdb->query( "OPTIMIZE TABLE `{$wpdb->comments}`" );

    // commentmeta
    $wpdb->query( "DELETE FROM `{$wpdb->commentmeta}` WHERE `comment_id` NOT IN ( SELECT `comment_ID` as `comment_id` FROM `{$wpdb->comments}` )" );
    $wpdb->query( "OPTIMIZE TABLE `{$wpdb->commentmeta}`" );
  }

}
