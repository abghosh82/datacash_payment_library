<?php
/**
 * @file
 * Datacash xml helper
 */

namespace Datacash\XmlHelper;
use Datacash\Exceptions\DatacashRequestException;

/**
 * Class XmlHelper
 *
 * @package Datacash\XmlHelper
 */
class XmlHelper implements XmlHelperInterface {
  /**
   * Recursive parse SimpleXMLElement element.
   *
   * @param SimpleXMLElement $xml
   *   XML string buffer.
   * @param string $class
   *   Name of the transformation class.
   *
   * @return object
   *   An instance of transformation class containg parsed XML.
   */
  protected function recursiveParse($xml, $class = 'Datacash\Response') {
    // Parse root node values.
    $name = $xml->getName();
    $value = (string) $xml;
    $attributes = $xml->attributes();

    // And fetch children.
    $children = array();
    foreach ($xml->children() as $child) {
      // Later call will remain basic DataCash_Element istances.
      $children[$child->getName()] = self::recursiveParse($child);
    }

    return new $class($name, $value, $attributes, $children);
  }

  /**
   * Get array from XML string buffer.
   *
   * @param string $xml_buffer
   *   XML string buffer.
   *
   * @return object
   *   An stdclass object containg parsed XML.
   *
   * @throws DatacashRequestException
   */
  public static function parseFromXml($xml_buffer) {
    if (!function_exists('simplexml_load_string')) {
      throw new DatacashRequestException("Cannot use SimpleXml for parsing because extension is not present.");
    }

    $xml = simplexml_load_string($xml_buffer);
    // Check if xml buffer was loaded properly into
    // a SimpleXMLElement instance.
    if ($xml->count() <= 0) {
      throw new DatacashRequestException("Could not parse XML from buffer.");
    }

    return self::recursiveParse($xml);
  }

  /**
   * Returns formatted XML string.
   *
   * @param \Datacash\DatacashElementInterface $element
   *   Instance of DatacashElement.
   *
   * @return string
   *   A string of formatted XML.
   */
  public static function debugXml(\Datacash\DatacashElementInterface $element) {
    $xmlBuffer = (string) $element;
    if (class_exists('DOMDocument')) {
      $dom = new DOMDocument();
      $dom->loadXML($xmlBuffer);
      $dom->formatOutput = true;
      return $dom->saveXML();
    }
    return $xmlBuffer;
  }
}