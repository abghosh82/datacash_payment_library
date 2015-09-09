<?php
/**
 * @file
 * Provides defination for ResponseInterface
 */

namespace Datacash;

/**
 * Interface ResponseInterface
 *
 * @package Datacash
 */
interface ResponseInterface {
  /**
   * Get status code.
   *
   * @return int
   *   Status code.
   */
  public function getStatusCode();

  /**
   * Checks if there is some error in the current request.
   *
   * @return bool
   */
  public function isError();

  /**
   * Get reason text.
   *
   * If request is an error, there will be an error abbreviation.
   *
   * @return string
   *   Reason text.
   */
  public function getReason();

  /**
   * Get raw information string from DataCash.
   *
   * If the current request has some error, you will have the error human
   * readable explanation in the response.
   *
   * @return string
   */
  public function getInformation();

  /**
   * Get datacash redirect URL for payment page.
   *
   * @return string
   */
  public function getRedirectUrl();
}