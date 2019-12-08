<?php
/**
 * Testing core setup
 *
 * @package JPWPToolkit/Tests
 */

/**
 * Testing core setup
 *
 * @since 0.3.0
 */
class CoreTest extends WP_UnitTestCase {

  /**
   * Tests if the plugin's constants are defined
   */
  public function test_constants_defined() {
    // 9 tests.
    $items = [
        'JPWP_VERSION',
        'JPWP_FILENAME',
        'JPWP_BASENAME',
        'JPWP_BASEURL',
        'JPWP_ABSPATH',
    ];

    foreach ( $items as $item ) {
      $this->assertTrue( defined( $item ) );
    }
  }

}
