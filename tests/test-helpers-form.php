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
use JPWPToolkit\Helpers\Form;

/**
 * Testing Html helper
 *
 * @since 0.3.0
 */
class FormTest extends WP_UnitTestCase {

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
      'false-attr' => false, // must be hidden.
      'empty-attr' => null,
      0            => 'numeric-attr',
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
   * Test Form::label() method
   */
  public function test_label() {
    // Simple
    $this->assertEquals( '<label></label>', Form::label( '' ) );
    $this->assertEquals( '<label>Arbitray content</label>', Form::label( 'Arbitray content' ) );

    // Empty values generates an empty label
    $this->assertEquals( '<label></label>', Form::label( null ) );
    $this->assertEquals( '<label></label>', Form::label( false ) );

    // True is converted to integer
    $this->assertEquals( '<label>1</label>', Form::label( true ) );

    // Test with content and attributes.
    $this->assertEquals( '<label class="test-class" id="test-id" numeric-attr empty-attr>Arbitray content</label>',
            Form::label( 'Arbitray content', $this->testing_attr ) );

    // Empty values generates an empty label
    $this->assertEquals( '<label class="test-class" id="test-id" numeric-attr empty-attr></label>',
            Form::label( null, $this->testing_attr ) );
    $this->assertEquals( '<label class="test-class" id="test-id" numeric-attr empty-attr></label>',
            Form::label( false, $this->testing_attr ) );

    // True is converted to integer
    $this->assertEquals( '<label class="test-class" id="test-id" numeric-attr empty-attr>1</label>',
            Form::label( true, $this->testing_attr ) );
  }

}