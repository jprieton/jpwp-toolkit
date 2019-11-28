<?php

namespace JPWPToolkit\Core;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Admin Pages
use JPWPToolkit\Admin\Social_Network_Page;

// Html::img() shorthands
use JPWPToolkit\Filters\Img_Pixel_Shorthand;
use JPWPToolkit\Filters\Img_Placeholder_Shorthand;
use JPWPToolkit\Filters\Img_Not_Available_Shorthand;

// Plugin schedule tasks
use JPWPToolkit\Core\Schedule_Events;

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
    // Add menu/submenu pages to admin panel's menu structure.
    $this->add_admin_pages();

    // Add scheduled events
    $this->add_scheduled_events();

    // Add image shorthands filters
    $this->add_image_shorthands();
  }

  /**
   * Initialize scheduled tasks
   * 
   * @since     0.3.0
   */
  private function add_scheduled_events() {
    new Schedule_Events();
  }

  /**
   * Add menu/submenu pages to admin panel's menu structure.
   *
   * @since   0.3.0
   */
  private function add_admin_pages() {
    new Social_Network_Page();
  }

  /**
   * Add Html::img() shorthands
   *
   * @since     0.1.0
   */
  private function add_image_shorthands() {
    new Img_Pixel_Shorthand();
    new Img_Placeholder_Shorthand();
    new Img_Not_Available_Shorthand();
  }

}
