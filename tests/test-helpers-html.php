<?php

use JPWPToolkit\Helpers\Html;

class HtmlTest extends WP_UnitTestCase {

  /**
   * Set of attributes to test
   * @var array 
   */
  private $testing_attr = [
      'class'      => 'test-class',
      'id'         => 'test-id',
      'boolean-attr',
      'empty-attr' => '',
      'false-attr' => false, // must be hidden.
      'empty-attr' => null,
      0            => 'numeric-attr',
  ];

  /**
   * Set of values that returns an empty attribute
   * @var arra 
   */
  private $empty_values = [
      null,
      true,
      false,
      ''
  ];

  /**
   * Test Html::tag() method
   */
  function test_tag() {
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

    // Especial values are omited and shows only the content.
    foreach ( $this->empty_values as $tag ) {
      $this->assertEquals( 'Arbitray content', Html::tag( $tag, 'Arbitray content' ) );
    }

    // Test with content and attributes.
    $set3 = [
        'div' => '<div class="test-class" id="test-id" numeric-attr empty-attr>Arbitray content</div>',
        'br'  => '<br class="test-class" id="test-id" numeric-attr empty-attr />',
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
  function test_img() {
    // Empty values are omited.
    foreach ( $this->empty_values as $tag ) {
      $this->assertEquals( '', Html::img( $tag ) );
    }

    // With a valid src.
    $this->assertEquals( '<img src="http://path/to/image.jpg" />', Html::img( 'http://path/to/image.jpg' ) );

    // With a valid src and attributes.
    $this->assertEquals( '<img src="http://path/to/image.jpg" class="test-class" id="test-id" numeric-attr empty-attr />', Html::img( 'http://path/to/image.jpg', $this->testing_attr ) );
  }

  /**
   * Test Html:img() method
   */
  public function test_open() {
    // Test without attributes.
    $set1 = [
        'div' => '<div>',
        'br'  => '<br />',
    ];
    foreach ( $set1 as $tag => $expected ) {
      $this->assertEquals( $expected, Html::open( $tag ) );
    }

    // Empty values are omited.
    foreach ( $this->empty_values as $tag ) {
      $this->assertEquals( '', Html::open( $tag ) );
    }

    // Test with attributes.
    $set2 = [
        'div' => '<div class="test-class" id="test-id" numeric-attr empty-attr>',
        'br'  => '<br class="test-class" id="test-id" numeric-attr empty-attr />',
    ];
    foreach ( $set2 as $tag => $expected ) {
      $this->assertEquals( $expected, Html::open( $tag, $this->testing_attr ) );
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
  function test_img_placeholder() {
    $handlers = [
        'placeholder'         => '<img src="' . JPWP_BASEURL . 'assets/images/placeholder.svg" height="150" width="150" class="image-size-thumbnail image-placeholder" alt="Placeholder image" />',
        'placeholder:medium'  => '<img src="' . JPWP_BASEURL . 'assets/images/placeholder.svg" height="300" width="300" class="image-size-medium image-placeholder" alt="Placeholder image" />',
        'placeholder:100x100' => '<img src="' . JPWP_BASEURL . 'assets/images/placeholder.svg" height="100" width="100" class="image-size-custom image-placeholder" alt="Placeholder image" />',
        'placeholder:none'    => '<img src="' . JPWP_BASEURL . 'assets/images/placeholder.svg" class="image-size-none image-placeholder" alt="Placeholder image" />',
        'placeholder:full'    => '<img src="' . JPWP_BASEURL . 'assets/images/placeholder.svg" class="image-size-full image-placeholder" alt="Placeholder image" />',
        'medium'              => '<img src="' . JPWP_BASEURL . 'assets/images/placeholder.svg" height="300" width="300" class="image-size-medium image-placeholder" alt="Placeholder image" />', // WordPress image size
        'thumbnail'           => '<img src="' . JPWP_BASEURL . 'assets/images/placeholder.svg" height="150" width="150" class="image-size-thumbnail image-placeholder" alt="Placeholder image" />', // WordPress image size
    ];

    foreach ( $handlers as $handler => $handlers ) {
      $this->assertEquals( $handlers, Html::img( $handler ) );
    }
  }

  /**
   * Test Html:parse_attributes() method
   */
  function test_parse_attributes() {
    // With testing attributes.
    $this->assertEquals( 'class="test-class" id="test-id" numeric-attr empty-attr', Html::parse_attributes( $this->testing_attr ) );

    // With empty values.
    $this->assertEquals( '', Html::parse_attributes( $this->empty_values ) );
  }

  /**
   * A single example test.
   */
  function test_2other() {
    echo __FUNCTION__ . "\n";

    return;
    // Test simple tags
    // Test shorhands
    $shorthands = [
        'div'                  => '<div></div>',
        'div.class1'           => '<div class="class1"></div>',
        'div.class1#id'        => '<div id="id" class="class1"></div>',
        '.class1'              => '<div class="class1"></div>',
        '.class1#id'           => '<div id="id" class="class1"></div>',
        'div.class1.class2'    => '<div class="class1 class2"></div>',
        'div.class1.class2#id' => '<div id="id" class="class1 class2"></div>',
        '.class1.class2'       => '<div class="class1 class2"></div>',
        '.class1.class2#id'    => '<div id="id" class="class1 class2"></div>',
    ];

    foreach ( $shorthands as $shorthand => $result ) {
      $this->assertEquals( $result, Html::tag( $shorthand ) );
    }

    // Test open tags
    $open_tags = [
        'img'                  => '<img />',
        'img.class1'           => '<img class="class1" />',
        'img.class1#id1'       => '<img id="id1" class="class1" />',
        'br'                   => '<br />',
        'hr'                   => '<hr />',
        'div'                  => '<div>',
        'div.class1'           => '<div class="class1">',
        'div.class1#id'        => '<div id="id" class="class1">',
        '.class1'              => '<div class="class1">',
        '.class1#id'           => '<div id="id" class="class1">',
        'div.class1.class2'    => '<div class="class1 class2">',
        'div.class1.class2#id' => '<div id="id" class="class1 class2">',
        '.class1.class2'       => '<div class="class1 class2">',
        '.class1.class2#id'    => '<div id="id" class="class1 class2">',
    ];

    foreach ( $open_tags as $tag => $result ) {
      $this->assertEquals( $result, Html::open( $tag ) );
    }

    // test magic methods
    $this->assertEquals( Html::br(), '<br />' );
    $this->assertEquals( Html::br( 'content' ), '<br />' );
    $this->assertEquals( Html::div(), '<div></div>' );
    $this->assertEquals( Html::div( 'content' ), '<div>content</div>' );
    $this->assertEquals( Html::div( 'content', [ 'class' => 'class1', 'id' => 'id1' ] ), '<div class="class1" id="id1">content</div>' );

    // Test image tag
    // Pixel
    $this->assertEquals( Html::img( 'pixel' ), '<img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" />' );
    $this->assertEquals( Html::img( null, [ 'src' => 'pixel' ] ), '<img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" />' );
    $this->assertEquals( Html::img( 'pixel', [ 'src' => 'http://path.to/image.jpg' ] ), '<img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" />' );

    $simple_list = [ 'red', 'blue', 'green', 'yellow' ];
    $nested_list = [
        'colors'  => [ 'red', 'blue', 'green', 'yellow' ],
        'numbers' => [ 'one', 'two', 'three', 'four', ]
    ];
    $attributes  = [ 'class' => 'class1', 'id' => 'id1' ];

    // Test simple lists
    $this->assertEquals( Html::ul( $simple_list ), '<ul><li>red</li><li>blue</li><li>green</li><li>yellow</li></ul>' );
    $this->assertEquals( Html::ul( $simple_list, $attributes ), '<ul class="class1" id="id1"><li>red</li><li>blue</li><li>green</li><li>yellow</li></ul>' );
    $this->assertEquals( Html::ol( $simple_list ), '<ol><li>red</li><li>blue</li><li>green</li><li>yellow</li></ol>' );
    $this->assertEquals( Html::ol( $simple_list, $attributes ), '<ol class="class1" id="id1"><li>red</li><li>blue</li><li>green</li><li>yellow</li></ol>' );

    // Test nested lists
    $this->assertEquals( Html::ul( $nested_list ), '<ul><li>colors<ul><li>red</li><li>blue</li><li>green</li><li>yellow</li></ul></li><li>numbers<ul><li>one</li><li>two</li><li>three</li><li>four</li></ul></li></ul>' );
    $this->assertEquals( Html::ul( $nested_list, $attributes ), '<ul class="class1" id="id1"><li>colors<ul><li>red</li><li>blue</li><li>green</li><li>yellow</li></ul></li><li>numbers<ul><li>one</li><li>two</li><li>three</li><li>four</li></ul></li></ul>' );
    $this->assertEquals( Html::ol( $nested_list ), '<ol><li>colors<ol><li>red</li><li>blue</li><li>green</li><li>yellow</li></ol></li><li>numbers<ol><li>one</li><li>two</li><li>three</li><li>four</li></ol></li></ol>' );
    $this->assertEquals( Html::ol( $nested_list, $attributes ), '<ol class="class1" id="id1"><li>colors<ol><li>red</li><li>blue</li><li>green</li><li>yellow</li></ol></li><li>numbers<ol><li>one</li><li>two</li><li>three</li><li>four</li></ol></li></ol>' );
  }

}
