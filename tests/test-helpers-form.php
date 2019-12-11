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
   * Test Form::label() method
   */
  public function test_label() {
    // Simple.
    $this->assertEquals( '<label></label>', Form::label( '' ) );
    $this->assertEquals( '<label>Arbitray content</label>', Form::label( 'Arbitray content' ) );

    // Empty values generates an empty label.
    $this->assertEquals( '<label></label>', Form::label( null ) );
    $this->assertEquals( '<label></label>', Form::label( false ) );

    // True is converted to integer.
    $this->assertEquals( '<label>1</label>', Form::label( true ) );

    // Test with content and attributes.
    $this->assertEquals( '<label class="test-class" id="test-id" boolean-attr empty-attr="" true-attr>Arbitray content</label>',
            Form::label( 'Arbitray content', $this->testing_attr ) );

    // Empty values generates an empty label.
    $this->assertEquals( '<label class="test-class" id="test-id" boolean-attr empty-attr="" true-attr></label>',
            Form::label( null, $this->testing_attr ) );
    $this->assertEquals( '<label class="test-class" id="test-id" boolean-attr empty-attr="" true-attr></label>',
            Form::label( false, $this->testing_attr ) );

    // True is converted to integer.
    $this->assertEquals( '<label class="test-class" id="test-id" boolean-attr empty-attr="" true-attr>1</label>',
            Form::label( true, $this->testing_attr ) );
  }

  /**
   * Test Form::button();
   */
  public function test_button() {
    // Simple.
    $this->assertEquals( '<button type="button"></button>', Form::button( '' ) );
    $this->assertEquals( '<button type="button">Arbitray content</button>', Form::button( 'Arbitray content' ) );

    // Empty values generates an empty button.
    $this->assertEquals( '<button type="button"></button>', Form::button( null ) );
    $this->assertEquals( '<button type="button"></button>', Form::button( false ) );

    // True is converted to integer.
    $this->assertEquals( '<button type="button">1</button>', Form::button( true ) );

    // Test with content and attributes.
    $this->assertEquals(
            '<button type="button" class="test-class" id="test-id" boolean-attr empty-attr="" true-attr>Arbitray content</button>',
            Form::button( 'Arbitray content', $this->testing_attr ) );

    // Empty values generates an empty button.
    $this->assertEquals(
            '<button type="button" class="test-class" id="test-id" boolean-attr empty-attr="" true-attr></button>',
            Form::button( null, $this->testing_attr ) );
    $this->assertEquals(
            '<button type="button" class="test-class" id="test-id" boolean-attr empty-attr="" true-attr></button>',
            Form::button( false, $this->testing_attr ) );

    // True is converted to integer.
    $this->assertEquals(
            '<button type="button" class="test-class" id="test-id" boolean-attr empty-attr="" true-attr>1</button>',
            Form::button( true, $this->testing_attr ) );
  }

  /**
   * Test cases for Form:option()
   */
  public function test_option() {
    $this->assertEquals(
            '',
            Form::option( '' )
    );

    $this->assertEquals(
            '<option value="Label">Label</option>',
            Form::option( 'Label' )
    );

    $this->assertEquals(
            '<option value="value">Label</option>',
            Form::option( 'Label', [ 'value' => "value" ] )
    );

    $this->assertEquals(
            '<option value="value" selected>Label</option>',
            Form::option( 'Label', [ 'value' => 'value', 'selected' => true ] )
    );

    $this->assertEquals(
            '<option value="value">Label</option>',
            Form::option( 'Label', [ 'value' => 'value', 'selected' => false ] )
    );

    $this->assertEquals(
            '<option value="value">Label</option>',
            Form::option( 'Label', [ 'value' => 'value', 'selected' => null ] )
    );

    $this->assertEquals(
            '<option value="value">Label</option>',
            Form::option( 'Label', [ 'value' => 'value', 'selected' => '' ] )
    );

    $this->assertEquals(
            '<option value="value" selected>Label</option>',
            Form::option( 'Label', [ 'value' => 'value', 'selected' => 'value' ] )
    );
  }

}
