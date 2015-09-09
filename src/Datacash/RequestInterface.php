<?php
/**
 * @file
 * Provides defination for RequestInterface
 */

namespace Datacash;

/**
 * Interface RequestInterface
 *
 * @package Datacash
 */
interface RequestInterface {
  /**
   * Prepares a datacash request
   *
   * @param array $request_params
   *   An array of request params.
   */
  public function prepareRequest($request_params);
}