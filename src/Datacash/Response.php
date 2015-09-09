<?php
/**
 * @file
 * Provides datacash payment response functionalities.
 */

namespace Datacash;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ResponseInterface
 *
 * @package Datacash
 */
class Response extends DatacashElement implements ResponseInterface {
  /**
   * Status code.
   *
   * @var int
   */
  protected $statusCode;

  /**
   * Get the status code.
   *
   * @return int
   *   Status code.
   */
  public function getStatusCode() {
    if (!isset($this->statusCode)) {
      $this->statusCode = (int)$this->status->getValue();
    }
    return $this->statusCode;
  }

  /**
   * Tells if the current request is an error.
   *
   * @return bool
   *   TRUE/FALSE depending on if the current request is an error.
   */
  public function isError() {
    return 1 !== $this->getStatusCode();
  }

  /**
   * Get reason field.
   *
   * If request is an error, you will get the error abbreviation.
   *
   * @return string
   *
   */
  public function getReason() {
    return $this->reason->getValue();
  }

  /**
   * Get raw information string from DataCash.
   *
   * If the current error is a request, you will have the error human readable
   * explanation in there.
   *
   * @return string
   */
  public function getInformation() {
    return $this->information->getValue();
  }

  /**
   * Get datacash redirect URL for payment page.
   *
   * @return string
   */
  public function getRedirectUrl() {
    // Get the default configurations.
    $env_config = YAML::parse(file_get_contents(__DIR__ . "/../../config/environment.yaml"));
    $datacash_config = YAML::parse(file_get_contents(__DIR__ . "/../../config/datacash.yaml"));
    $datacash_config = $datacash_config[$env_config['environment']]['parameters'];
    return $datacash_config['redirect_url'] . $this->HpsTxn->session_id->getValue();
  }
}