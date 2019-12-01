<?php

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
        'JPWP_BASEDIR',
        'JPWP_BASEURL',
        'JPWP_ABSPATH',
    ];

    foreach ( $items as $item ) {
      $this->assertTrue( defined( $item ) );
    }
  }

}
