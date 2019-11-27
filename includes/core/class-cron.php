<?php

namespace JPWPToolkit\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Cron class
 *
 * @package        JPWPToolkit
 * @subpackage     Cron
 * @since          0.3.0
 * @author         Javier Prieto
 */
final class Cron {

  /**
   * Class constructor
   *
   * @since     0.3.0
   */
  public function __construct() {
    // Adds non-default cron schedules
    add_filter( 'cron_schedules', [ $this, 'add_cron_schedules' ] );

    // Delete all non-linked metadata
    add_action( 'delete_orphan_metadata', [ $this, 'delete_orphan_metadata' ] );

    // Delete all non-linked comments
    add_action( 'delete_orphan_comments', [ $this, 'delete_orphan_comments' ] );

    // TODO: Add the option to enable/disable in the admin
    // Schedule a cron event to clean orphan data
    $this->add_scheduled_events();

    // Un-schedule all cron events to clean orphan data
    register_deactivation_hook( JPWP_FILENAME, [ __CLASS__, 'remove_schedule' ] );
  }

  /**
   * Adds non-default cron schedules
   *
   * @since     0.3.0
   * @param     array     $schedules
   * @return    array
   */
  public function add_cron_schedules( $schedules = array() ) {
    return array_merge( $schedules, [ 'weekly'  => [
            'interval' => WEEK_IN_SECONDS,
            'display'  => __( 'Once weekly', 'jpwp-toolkit' ),
        ],
        'monthly' => [
            'interval' => MONTH_IN_SECONDS,
            'display'  => __( 'Once monthly', 'jpwp-toolkit' ),
        ] ] );
  }

  /**
   * Schedules an cron event to clean orphan data
   *
   * @since     0.3.0
   */
  public function add_scheduled_events() {
    $current_timestamp = current_time( 'timestamp' );
    if ( !wp_next_scheduled( 'delete_orphan_metadata' ) ) {
      wp_schedule_event( $current_timestamp, 'monthly', 'delete_orphan_metadata' );
    }
    if ( !wp_next_scheduled( 'delete_orphan_comments' ) ) {
      wp_schedule_event( $current_timestamp, 'monthly', 'delete_orphan_comments' );
    }
  }

  /**
   * Un-schedules all hooked cron events to clean orphan data
   *
   * @since     0.3.0
   */
  public static function remove_schedule() {
    wp_clear_scheduled_hook( 'delete_orphan_metadata' );
    wp_clear_scheduled_hook( 'delete_orphan_comments' );
  }

}
