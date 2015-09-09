<?php
/**
 * @file
 * Provides defination for StatusInterface
 */

namespace Datacash;

/**
 * Interface StatusInterface
 *
 * @package Datacash
 */
interface StatusInterface {
  /**
   * Retrieves the status message.
   *
   * @param $message_id
   *   The message id. returned in response.
   *
   * @return string
   *   The status message text.
   */
  public static function getStatusMessage($message_id);
}