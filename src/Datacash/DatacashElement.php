<?php
/**
 * @file
 * Provides datacash payment elements.
 */

namespace Datacash;

/**
 * Class DatacashElement
 *
 * @package Datacash
 */
class DatacashElement implements DatacashElementInterface {
  /**
   * Name of the element.
   *
   * @var string
   */
  protected $name = NULL;

  /**
   * Element children.
   *
   * @var array
   */
  protected $children = array();

  /**
   * Element attributes.
   *
   * @var array
   */
  protected $attributes = array();

  /**
   * Element value.
   *
   * @var string
   */
  protected $value = NULL;

  /**
   * Default constructor.
   *
   * @param string $name
   *   Element name.
   * @param string $value
   *   Scalar value for current element, if any.
   * @param array $attributes = NULL
   *   Set of attributes.
   * @param array $children = NULL
   *   Set of children.
   */
  public function __construct($name, $value = NULL, $attributes = NULL, array $children = NULL) {
    $this->name = $name;
    $this->value = $value;
    // Allow exceptions to to be thrown.
    if (isset($attributes)) {
      foreach ($attributes as $name => $value) {
        $this->setAttribute($name, $value);
      }
    }
    if (isset($children)) {
      foreach ($children as $name => $value) {
        $this->$name = $value;
      }
    }
  }

  /**
   * Get element by name.
   *
   * @param string $name
   *   Name of element.
   *
   * @return DatacashElementInterface
   */
  public function __get($name) {
    if (!isset($this->children[$name])) {
      $this->children[$name] = new self($name);
    }
    return $this->children[$name];
  }

  /**
   * Set element value.
   *
   * @param string $name
   * @param string $value
   *
   * @return DatacashElementInterface
   *
   * @throws Exceptions\DatacashRequestException
   */
  public function __set($name, $value) {
    if ($value instanceof self) {
      $this->children[$name] = $value;
    }
    elseif (is_scalar($value)) {
      $this->children[$name] = new self($name, $value);
    }
    else {
      throw new Exceptions\DatacashRequestException("Value should be a scalar value or a DatacashElement instance.");
    }
  }

  /**
   * Set attribute for an element.
   *
   * @param string $name
   *   Attribute name.
   * @param string $value
   *   Attribute value.
   */
  public function setAttribute($name, $value) {
    $this->attributes[$name] = (string) $value;
  }

  /**
   * Get all attributes.
   *
   * @return array
   *   Array of attributes.
   */
  public function getAttributes() {
    return $this->attributes;
  }

  /**
   * Get element name.
   *
   * @return string
   *   Element name.
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Get current element value, if any.
   *
   * @return mixed
   *   Value of element.
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * Check if current element is void.
   *
   * @return bool
   *   TRUE/FALSE depending on the element value.
   */
  public function isVoid() {
    return empty($this->value);
  }

  /**
   * Checks if current element has child.
   *
   * @param string $name
   *  Name of the element.
   *
   * @return bool
   */
  public function hasChild($name) {
    return isset($this->children[$name]);
  }

  /**
   * Returns children of current element.
   *
   * @return array
   *   Array of children.
   */
  public function getChildren() {
    return $this->children;
  }

  /**
   * Returns XML for the current instance.
   *
   * @return string
   *   XML string.
   */
  public function getXml() {
    $buffer = '<' . $this->name . $this->renderAttributes() . '>';
    if (!empty($this->children)) {
      foreach ($this->children as $child) {
        $buffer .= $child->getXml();
      }
    }
    elseif (isset($this->value) && is_scalar($this->value)) {
      $buffer .= $this->value;
    }
    elseif (isset($this->value) && !is_scalar($this->value)) {
      $buffer .= $this->value->getXml();
    }
    $buffer .= '</' . $this->name . '>';
    return $buffer;
  }

  /**
   * Provides string of attributes for the current instance.
   *
   * @return string
   *   The attribute string.
   */
  protected function renderAttributes() {
    $attributes_string = '';
    foreach ($this->attributes as $key => $value) {
      $attributes_string .= " $key=" . '"' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
    }
    return $attributes_string;
  }
}