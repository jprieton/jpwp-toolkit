<?php
/**
 * Testing use cases of Html helper
 *
 * @package JPWPToolkit
 * @subpackage Tests
 */

/**
 * Load namespaces
 */
use JPWPToolkit\Helpers\Html;

/**
 * Testing Html helper
 *
 * @since 0.3.0
 */
class HtmlTest extends WP_UnitTestCase {

  /**
   * Set of attributes to test
   *
   * @var array
   */
  private $testing_attr = [
      'class'      => 'test-class',
      'id'         => 'test-id',
      'boolean-attr',
      'empty-attr' => '',
      'true-attr'  => true, // same as boolean attribute.
      'false-attr' => false, // must be hidden.
      'null-attr'  => null, // must be hidden.
  ];

  /**
   * Set of values that returns an empty attribute
   *
   * @var arra
   */
  private $empty_values = [
      null,
      true,
      false,
      ''
  ];

  /**
   * Set of empty attributes
   *
   * @var arra
   */
  private $empty_attributes = [
      null,
      true,
      false,
      ''
  ];

  /**
   * Test Html::tag() method
   */
  public function test_tag() {
    // Test without content nor attributes.
    $set1 = [
        'div' => '<div></div>',
        'br'  => '<br />',
    ];
    foreach ( $set1 as $tag => $result ) {
      $this->assertEquals( $result, Html::tag( $tag ) );
    }

    // Empty values are omited.
    foreach ( $this->empty_values as $tag ) {
      $this->assertEquals( '', Html::tag( $tag ) );
    }

    // Test with content but without attributes.
    $set2 = [
        'div' => '<div>Arbitray content</div>',
        'br'  => '<br />',
    ];
    foreach ( $set2 as $tag => $result ) {
      $this->assertEquals( $result, Html::tag( $tag, 'Arbitray content' ) );
    }

    $this->assertEquals( Html::tag( 'div', null ), '<div></div>' );
    $this->assertEquals( Html::tag( 'div', false ), '<div></div>' );
    $this->assertEquals( Html::tag( 'div', true ), '<div>1</div>' );


    // Not add attributes.
    foreach ( $set2 as $tag => $result ) {
      $this->assertEquals( $result, Html::tag( $tag, 'Arbitray content', $this->empty_attributes ) );
    }

    // Especial values are omited and shows only the content.
    foreach ( $this->empty_values as $tag ) {
      $this->assertEquals( 'Arbitray content', Html::tag( $tag, 'Arbitray content' ) );
    }

    // Test with content and attributes.
    $set3 = [
        'div' => '<div class="test-class" id="test-id" boolean-attr empty-attr="" true-attr>Arbitray content</div>',
        'br'  => '<br class="test-class" id="test-id" boolean-attr empty-attr="" true-attr />',
    ];
    foreach ( $set3 as $tag => $result ) {
      $this->assertEquals( $result, Html::tag( $tag, 'Arbitray content', $this->testing_attr ) );
    }

    // Especial values are omited and shows only the content.
    foreach ( $this->empty_values as $tag ) {
      $this->assertEquals( 'Arbitray content', Html::tag( $tag, 'Arbitray content', $this->testing_attr ) );
    }
  }

  /**
   * Test Html:img() method
   */
  public function test_img() {
    // Empty values are omited.
    foreach ( $this->empty_values as $tag ) {
      $this->assertEquals( '', Html::img( $tag ) );
    }

    // With a valid src.
    $this->assertEquals( '<img src="http://path/to/image.jpg" />', Html::img( 'http://path/to/image.jpg' ) );

    // With a valid src and attributes.
    $this->assertEquals( '<img src="http://path/to/image.jpg" class="test-class" id="test-id" boolean-attr empty-attr="" true-attr />', Html::img( 'http://path/to/image.jpg', $this->testing_attr ) );
  }

  /**
   * Test Html:img() method
   */
  public function test_open() {
    // Test without attributes.
    $this->assertEquals( '<div>', Html::open( 'div' ) );
    $this->assertEquals( '<br />', Html::open( 'br' ) );

    // Empty values are omited.
    foreach ( $this->empty_values as $tag ) {
      $this->assertEquals( '', Html::open( $tag ) );
    }

    // Test with attributes.
    $set1 = [
        'div' => '<div class="test-class" id="test-id" boolean-attr empty-attr="" true-attr>',
        'br'  => '<br class="test-class" id="test-id" boolean-attr empty-attr="" true-attr />',
    ];
    foreach ( $set1 as $tag => $expected ) {
      $this->assertEquals( $expected, Html::open( $tag, $this->testing_attr ) );
    }

    $set1 = [
        'div' => '<div>',
        'br'  => '<br />',
    ];
    // Test with empty attributes.
    foreach ( $set1 as $tag => $expected ) {
      $this->assertEquals( $expected, Html::open( $tag, $this->empty_attributes ) );
    }
  }

  /**
   * Test Html:img() method
   */
  public function test_close() {
    // Test without attributes.
    $set1 = [
        'div' => '</div>',
        'br'  => '',
    ];
    foreach ( $set1 as $tag => $expected ) {
      $this->assertEquals( $expected, Html::close( $tag ) );
    }

    // Empty values are omited.
    foreach ( $this->empty_values as $tag ) {
      $this->assertEquals( '', Html::close( $tag ) );
    }
  }

  /**
   * Test Html:img() method
   */
  public function test_img_placeholder() {
    $handlers = [
        'placeholder'         => '<img src="' . JPWP_BASEURL . 'assets/images/placeholder.svg" height="150" width="150" class="image-size-thumbnail image-placeholder" alt="Placeholder image" />',
        'placeholder:medium'  => '<img src="' . JPWP_BASEURL . 'assets/images/placeholder.svg" height="300" width="300" class="image-size-medium image-placeholder" alt="Placeholder image" />',
        'placeholder:100x100' => '<img src="' . JPWP_BASEURL . 'assets/images/placeholder.svg" height="100" width="100" class="image-size-custom image-placeholder" alt="Placeholder image" />',
        'placeholder:none'    => '<img src="' . JPWP_BASEURL . 'assets/images/placeholder.svg" class="image-size-none image-placeholder" alt="Placeholder image" />',
        'placeholder:full'    => '<img src="' . JPWP_BASEURL . 'assets/images/placeholder.svg" class="image-size-full image-placeholder" alt="Placeholder image" />',
        // WordPress image size.
        'medium'              => '<img src="' . JPWP_BASEURL . 'assets/images/placeholder.svg" height="300" width="300" class="image-size-medium image-placeholder" alt="Placeholder image" />',
        // WordPress image size.
        'thumbnail'           => '<img src="' . JPWP_BASEURL . 'assets/images/placeholder.svg" height="150" width="150" class="image-size-thumbnail image-placeholder" alt="Placeholder image" />',
    ];

    foreach ( $handlers as $handler => $handlers ) {
      $this->assertEquals( $handlers, Html::img( $handler ) );
    }
  }

  /**
   * Test Html:parse_attributes() method
   */
  public function test_parse_attributes() {
    // With testing attributes.
    $this->assertEquals(
            Html::parse_attributes( $this->testing_attr ),
            'class="test-class" id="test-id" boolean-attr empty-attr="" true-attr'
    );

    // With empty values.
    $this->assertEquals( '', Html::parse_attributes( $this->empty_values ) );

    // With empty attributes.
    $this->assertEquals( '', Html::parse_attributes( $this->empty_attributes ) );
  }

  /**
   * Test Html:ol() and Html:ul() method
   */
  public function test_lists() {
    $simple_list = [ 'red', 'blue', 'green', 'yellow' ];
    $nested_list = [
        'colors'  => [ 'red', 'blue', 'green', 'yellow' ],
        'numbers' => [ 'one', 'two', 'three', 'four', ]
    ];

    // Test simple lists.
    $this->assertEquals(
            '<ul><li>red</li><li>blue</li><li>green</li><li>yellow</li></ul>',
            Html::ul( $simple_list )
    );
    $this->assertEquals(
            '<ol><li>red</li><li>blue</li><li>green</li><li>yellow</li></ol>',
            Html::ol( $simple_list )
    );

    // Test simple lists with empty values as attributes.
    $this->assertEquals(
            '<ul><li>red</li><li>blue</li><li>green</li><li>yellow</li></ul>',
            Html::ul( $simple_list, $this->empty_values )
    );
    $this->assertEquals(
            '<ol><li>red</li><li>blue</li><li>green</li><li>yellow</li></ol>',
            Html::ol( $simple_list, $this->empty_values )
    );

    // Test simple lists with empty attributes.
    $this->assertEquals(
            '<ul><li>red</li><li>blue</li><li>green</li><li>yellow</li></ul>',
            Html::ul( $simple_list, $this->empty_attributes )
    );
    $this->assertEquals(
            Html::ol( $simple_list, $this->empty_attributes ),
            '<ol><li>red</li><li>blue</li><li>green</li><li>yellow</li></ol>'
    );

    // Test simple lists with attributes.
    $this->assertEquals(
            '<ul class="test-class" id="test-id" boolean-attr empty-attr="" true-attr><li>red</li><li>blue</li><li>green</li><li>yellow</li></ul>',
            Html::ul( $simple_list, $this->testing_attr )
    );
    $this->assertEquals(
            '<ol class="test-class" id="test-id" boolean-attr empty-attr="" true-attr><li>red</li><li>blue</li><li>green</li><li>yellow</li></ol>',
            Html::ol( $simple_list, $this->testing_attr )
    );

    // Test nested lists.
    $this->assertEquals(
            '<ul><li>colors<ul><li>red</li><li>blue</li><li>green</li><li>yellow</li></ul></li><li>numbers<ul><li>one</li><li>two</li><li>three</li><li>four</li></ul></li></ul>',
            Html::ul( $nested_list )
    );
    $this->assertEquals(
            '<ol><li>colors<ol><li>red</li><li>blue</li><li>green</li><li>yellow</li></ol></li><li>numbers<ol><li>one</li><li>two</li><li>three</li><li>four</li></ol></li></ol>',
            Html::ol( $nested_list )
    );
  }

  /**
   * Test overloading methods
   */
  public function test_overloading() {
    // test overloding tags.
    $this->assertEquals(
            '<br />',
            Html::br()
    );

    $this->assertEquals(
            '<div></div>',
            Html::div()
    );

    // Test with content but without attributes.
    $this->assertEquals(
            '<br />',
            Html::br( 'Arbitray content' )
    );

    $this->assertEquals(
            '<div>Arbitray content</div>',
            Html::div( 'Arbitray content' )
    );

    // Test with content with attributes.
    $this->assertEquals(
            '<br class="test-class" id="test-id" boolean-attr empty-attr="" true-attr />',
            Html::br( 'Arbitray content', $this->testing_attr )
    );

    $this->assertEquals(
            Html::div( 'Arbitray content', $this->testing_attr ),
            '<div class="test-class" id="test-id" boolean-attr empty-attr="" true-attr>Arbitray content</div>' );

    $this->assertEquals( Html::div( null ), '<div></div>' );
    $this->assertEquals( Html::div( false ), '<div></div>' );
    $this->assertEquals( Html::div( true ), '<div>1</div>' );
  }

  /**
   * Testing shorthand capabilities (beta)
   */
  public function test_shorthands() {
    // Test shorhands.
    $shorthands = [
        'div'                       => '<div></div>',
        'div.class1'                => '<div class="class1"></div>',
        'div.class1#id'             => '<div id="id" class="class1"></div>',
        '.class1'                   => '<div class="class1"></div>',
        '.class1#test-id'           => '<div id="test-id" class="class1"></div>',
        'div.class1.class2'         => '<div class="class1 class2"></div>',
        'div.class1.class2#test-id' => '<div id="test-id" class="class1 class2"></div>',
        '.class1.class2'            => '<div class="class1 class2"></div>',
        '.class1.class2#test-id'    => '<div id="test-id" class="class1 class2"></div>',
    ];

    foreach ( $shorthands as $shorthand => $result ) {
      $this->assertEquals( $result, Html::tag( $shorthand ) );
    }
  }

}
