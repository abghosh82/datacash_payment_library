<?php
/**
 * @file
 * Provides defination for DatacashElementInterface
 */

namespace Datacash;

/**
 * Interface DatacashElementInterface
 *
 * @package Datacash
 */
interface DatacashElementInterface {

  /**
   * Get element by name.
   *
   * @param string $name
   *   Name of element.
   *
   * @return DatacashElementInterface
   */
  public function __get($name);

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
  public function __set($name, $value);

  /**
   * Set attribute for an element.
   *
   * @param string $name
   *   Attribute name.
   * @param string $value
   *   Attribute value.
   */
  public function setAttribute($name, $value);

  /**
   * Get all attributes.
   *
   * @return array
   *   Array of attributes.
   */
  public function getAttributes();

  /**
   * Get element name.
   *
   * @return string
   *   Element name.
   */
  public function getName();

  /**
   * Get current element value, if any.
   *
   * @return mixed
   *   Value of element.
   */
  public function getValue();

  /**
   * Check if current element is void.
   *
   * @return bool
   *   TRUE/FALSE depending on the element value.
   */
  public function isVoid();

  /**
   * Checks if current element has child.
   *
   * @param string $name
   *  Name of the element.
   *
   * @return bool
   */
  public function hasChild($name);

  /**
   * Returns children of current element.
   *
   * @return array
   *   Array of children.
   */
  public function getChildren();

  /**
   * Returns XML for the current instance.
   *
   * @return string
   *   XML string.
   */
  public function getXml();
}