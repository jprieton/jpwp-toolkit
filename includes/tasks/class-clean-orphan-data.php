<?php
/**
 * Task to clean and optimize tables with orphan data
 * 
 * @package        JPWPToolkit
 * @subpackage     Tasks
 */

namespace JPWPToolkit\Tasks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use wpdb;

/**
 * Clean_Orphan_Metadata class
 *
 * @package        JPWPToolkit
 * @subpackage     Tasks
 * @since          0.3.0
 * @author         Javier Prieto
 */
class Clean_Orphan_Data {

  /**
   * Class constructor
   *
   * @since     0.3.0
   */
  public function __construct() {
    $current_timestamp = current_time( 'timestamp' );

    // Delete all non-linked metadata.
    add_action( 'clean_orphan_metadata', [ $this, 'clean_orphan_metadata' ] );
    if ( !wp_next_scheduled( 'clean_orphan_metadata' ) ) {
      wp_schedule_event( $current_timestamp, 'monthly', 'clean_orphan_metadata' );
    }

    // Delete all non-linked comments.
    add_action( 'clean_orphan_comments', [ $this, 'clean_orphan_comments' ] );
    if ( !wp_next_scheduled( 'clean_orphan_comments' ) ) {
      wp_schedule_event( $current_timestamp, 'monthly', 'clean_orphan_comments' );
    }

    // Un-schedule all cron events to clean orphan data.
    register_deactivation_hook( JPWP_FILENAME, [ __CLASS__, 'remove_clean_orphan_data' ] );
  }

  /**
   * Delete all non-linked metadata
   *
   * @since   0.3.0
   * @global  wpdb    $wpdb
   */
  public function clean_orphan_metadata() {  // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
    global $wpdb;

    // Remove unlinked postmeta.
    $wpdb->query( "DELETE FROM `{$wpdb->postmeta}` WHERE `post_id` NOT IN ( SELECT `ID` AS `post_id` FROM `{$wpdb->posts}` )" ); // phpcs:ignore
    $wpdb->query( "OPTIMIZE TABLE `{$wpdb->postmeta}`" ); // phpcs:ignore
    // Remove unlinked usermeta.
    $wpdb->query( "DELETE FROM `{$wpdb->usermeta}` WHERE `user_id` NOT IN ( SELECT `ID` AS `user_id` FROM `{$wpdb->users}` )" ); // phpcs:ignore
    $wpdb->query( "OPTIMIZE TABLE `{$wpdb->usermeta}`" ); // phpcs:ignore
    // Remove unlinked termmeta.
    $wpdb->query( "DELETE FROM `{$wpdb->termmeta}` WHERE `term_id` NOT IN ( SELECT `term_id` FROM `{$wpdb->terms}` )" ); // phpcs:ignore
    $wpdb->query( "OPTIMIZE TABLE `{$wpdb->termmeta}`" ); // phpcs:ignore
    // Remove unlinked commentmeta.
    $wpdb->query( "DELETE FROM `{$wpdb->commentmeta}` WHERE `comment_id` NOT IN ( SELECT `comment_ID` AS `comment_id` FROM `{$wpdb->comments}` )" ); // phpcs:ignore
    $wpdb->query( "OPTIMIZE TABLE `{$wpdb->commentmeta}`" ); // phpcs:ignore
  }

  /**
   * Delete all non-linked comments
   *
   * @since   0.3.0
   * @global  wpdb    $wpdb
   */
  public function clean_orphan_comments() {
    global $wpdb;

    // Remove unlinked comments.
    $wpdb->query( "DELETE FROM `{$wpdb->comments}` WHERE `comment_post_ID` NOT IN ( SELECT `ID` AS `comment_post_ID` FROM `{$wpdb->posts}` )" ); // phpcs:ignore
    $wpdb->query( "OPTIMIZE TABLE `{$wpdb->comments}`" ); // phpcs:ignore
    // Remove unlinked commentmeta.
    $wpdb->query( "DELETE FROM `{$wpdb->commentmeta}` WHERE `comment_id` NOT IN ( SELECT `comment_ID` as `comment_id` FROM `{$wpdb->comments}` )" ); // phpcs:ignore
    $wpdb->query( "OPTIMIZE TABLE `{$wpdb->commentmeta}`" ); // phpcs:ignore
  }

  /**
   * Un-schedules all hooked cron events to clean orphan data
   *
   * @since     0.3.0
   */
  public static function remove_clean_orphan_data() {
    wp_clear_scheduled_hook( 'clean_orphan_metadata' );
    wp_clear_scheduled_hook( 'clean_orphan_comments' );
  }

}
