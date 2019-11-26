<?php

namespace JPWPToolkit\Traits;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Singleton trait
 * 
 * @package       JPWPToolkit
 * @subpackage    Traits
 * @since         0.1.0
 * @author        Javier Prieto
 */
trait Singleton {

  /**
   * Single instance of this class
   *
   * @since     0.1.0
   * @var       self
   */
  protected static $instance;

  /**
   * Main instance
   * Ensures only one instance of this class is loaded or can be loaded.
   *
   * @since   0.1.0
   * @static
   */
  public static function get_instance() {
    if ( empty( self::$instance ) ) {
      self::$instance = new self;
    }
    return self::$instance;
  }

  /**
   * Declared as private to prevent cloning of an instance of the class via the clone operator.
   *
   * @since   0.1.0
   */
  private function __clone() {
    
  }

  /**
   * Declared as private to prevent unserializing of an instance of the class via the global function unserialize().
   *
   * @since   0.1.0
   */
  private function __wakeup() {
    
  }

  /**
   * Declared as protected to prevent serializg of an instance of the class via the global function serialize().
   *
   * @since   0.1.0
   */
  protected function __sleep() {
    
  }

  /**
   * PHP5 style destructor and will run when object is destroyed.
   *
   * @since   0.1.0
   */
  public function __destruct() {
    self::$instance = null;
  }

}
