<?php

namespace JPWPToolkit\Core;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

use JPWPToolkit\Filters\Img_Pixel_Shorthand;
use JPWPToolkit\Filters\Img_Placeholder_Shorthand;
use JPWPToolkit\Filters\Img_Not_Available_Shorthand;

use JPWPToolkit\Core\Cron;

/**
 * Class to initialize plugin
 *
 * @package       JPWPToolkit
 * @subpackage    Core
 * @since         0.1.0
 * @author        Javier Prieto
 */
final class Init {

  /**
   * Adds Singleton methods and properties
   * 
   * @since     0.1.0
   */
  use \JPWPToolkit\Traits\Singleton;

  /**
   * Declared as protected to prevent creating a new instance outside of the class via the new operator.
   *
   * @since     0.1.0
   */
  protected function __construct() {
    // Add image shorthands filters
    $this->add_image_shorthands();

    // Add scheduled events
    $this->add_scheduled_events();
  }

  /**
   * initialize image shorthands filters
   *
   * @since     0.1.0
   */
  private function add_image_shorthands() {
    new Img_Pixel_Shorthand();
    new Img_Placeholder_Shorthand();
    new Img_Not_Available_Shorthand();
  }

  /**
   * Initialize scheduled tasks
   * 
   * @since     0.3.0
   */
  private function add_scheduled_events() {
    new Cron();
  }

}
