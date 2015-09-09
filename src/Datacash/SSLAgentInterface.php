<?php
/**
 * @file
 * Provides defination for SSLAgentInterface
 */

namespace Datacash;

/**
 * Interface SSLAgentInterface
 *
 * @package Datacash
 */
interface SSLAgentInterface {
  /**
   * Provides current instance.
   *
   * This provides a singleton implementation.
   *
   * @return SSLAgentInterface
   *   Current instance.
   */
  public static function getInstance();

  /**
   * Makes a datacash request.
   *
   * @param Request $request
   *
   * @return string
   *   XML response
   */
  public function send(Request $request);
}