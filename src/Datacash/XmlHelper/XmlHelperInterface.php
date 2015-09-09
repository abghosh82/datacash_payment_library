<?php
/**
 * @file
 * Datacash xml helper interface.
 */

namespace Datacash\XmlHelper;

/**
 * Interface XmlHelperInterface
 *
 * @package Datacash\XmlHelper
 */
interface XmlHelperInterface {
  /**
   * Get array from XML string buffer.
   *
   * @param string $xmlBuffer
   *   XML string buffer.
   *
   * @return object
   *
   * @throws DatacashRequestException
   */
  public static function parseFromXml($xml_buffer);

  /**
   * Returns formatted XML string.
   *
   * @param \Datacash\DatacashElementInterface $element
   *   Instance of DatacashElement.
   */
  public static function debugXml(\Datacash\DatacashElementInterface $element);
}