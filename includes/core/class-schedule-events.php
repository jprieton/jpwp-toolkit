<?php
/**
 * Adds plugin's schedules events
 *
 * @package        JPWPToolkit
 * @subpackage     Core
 */

namespace JPWPToolkit\Core;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use JPWPToolkit\Tasks\Clean_Orphan_Data;

/**
 * Cron class
 *
 * @package        JPWPToolkit
 * @subpackage     Core
 * @since          0.3.0
 * @author         Javier Prieto
 */
final class Schedule_Events {

  /**
   * Class constructor
   *
   * @since     0.3.0
   */
  public function __construct() {
    // Adds non-default cron schedules.
    add_filter( 'cron_schedules', [ $this, 'add_cron_schedules' ] );

    // TODO: Add the option to enable/disable schedules in the admin.
    // Schedule a cron event to clean orphan data.
    new Clean_Orphan_Data();
  }

  /**
   * Adds non-default cron schedules
   *
   * @since     0.3.0
   * @param     array $schedules Array of schedules registered in WordPress.
   * @return    array
   */
  public function add_cron_schedules( $schedules = [] ) {
    $new_schedules = [
        'weekly'  => [
            'interval' => WEEK_IN_SECONDS,
            'display'  => __( 'Once weekly', 'jpwp-toolkit' ),
        ],
        'monthly' => [
            'interval' => MONTH_IN_SECONDS,
            'display'  => __( 'Once monthly', 'jpwp-toolkit' ),
        ] ];
    return array_merge( $schedules, $new_schedules );
  }

}
