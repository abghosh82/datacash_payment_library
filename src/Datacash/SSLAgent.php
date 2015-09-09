<?php
/**
 * @file
 * Provides connection functionalities.
 */

namespace Datacash;
use Datacash\Exceptions\DatacashRequestException;
use Datacash\XmlHelper\XmlHelper;
use Symfony\Component\Yaml\Yaml;

/**
 * Class SSLAgent
 *
 * @package Datacash
 */
class SSLAgent implements SSLAgentInterface {
  /**
   * Singleton pattern implementation.
   *
   * @var SSLAgentInterface
   */
  protected static $instance = NULL;

  /**
   * Datacash server host name.
   *
   * @var string
   */
  protected $hostName = NULL;

  /**
   * Datacash request timeout.
   *
   * @var int
   */
  protected $timeout = 60;

  /**
   * Datacash ssl certificate path.
   *
   * @var string
   */
  protected $sslCertPath = NULL;

  /**
   * Verify SSL certificate indicator.
   *
   * @var bool
   */
  protected $sslVerify = TRUE;

  /**
   * Proxy URL for datacash requests.
   *
   * @var string
   */
  protected $proxyUrl = NULL;

  /**
   * Singleton pattern.
   *
   * @throws Exceptions\DatacashRequestException
   */
  protected function __construct() {
    // Get the default configurations.
    $env_config = YAML::parse(file_get_contents(__DIR__ . "/../../config/environment.yaml"));
    $datacash_config = YAML::parse(file_get_contents(__DIR__ . "/../../config/datacash.yaml"));
    $datacash_config = $datacash_config[$env_config['environment']]['parameters'];

    // Set the configurations for the request.
    if (!empty($datacash_config['server_url'])) {
      $this->hostName = $datacash_config['server_url'];
    }
    else {
      throw new DatacashRequestException("Not set or invalid hostname.");
    }

    if (!empty($datacash_config['timeout'])) {
      $this->timeout = $datacash_config['timeout'];
    }

    if (!empty($datacash_config['datacash_network_ssl_path'])) {
      $this->sslCertPath = $datacash_config['datacash_network_ssl_path'];
      // Do not set cert SSL verifiation if we don't have a certificate.
      if (!empty($datacash_config['datacash_network_ssl_verify'])) {
        $this->sslVerify = $datacash_config['datacash_network_ssl_verify'];
      }
    }
    else {
      $this->sslVerify = FALSE;
    }

    // Use proxy only if proxy url is available.
    if (!empty($datacash_config['proxy_url'])) {
      $this->proxyUrl = $datacash_config['proxy_url'];
    }
  }

  /**
   * Provides current instance.
   *
   * This provides a singleton implementation.
   *
   * @return SSLAgentInterface
   *   Current instance.
   */
  public static function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new self;
    }
    return self::$instance;
  }

  /**
   * Makes a datacash request.
   *
   * @param Request $request
   *
   * @return string
   *   XML response
   *
   * @throws Exceptions\DatacashRequestException
   */
  public function send(Request $request) {
    // Set the CURL options for Datacash request.
    $options = array(
      CURLOPT_URL => $this->hostName,
      CURLOPT_POST => 1,
      CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="UTF-8"?>' . $request->getXml(),
      CURLOPT_HTTPHEADER => array('Expect: '),
      // Save the response.
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_SSL_VERIFYHOST => 0,
      CURLOPT_SSL_VERIFYPEER => 0
    );

    // Verify SSL certificate based on the configuration.
    if (isset($this->sslVerify)) {
      $options[CURLOPT_SSL_VERIFYHOST] = $this->sslVerify;
    }
    // Set the timeout.
    $options[CURLOPT_TIMEOUT] = $this->timeout;

    // Set SSL certification related options.
    if ($this->sslCertPath) {
      // We've a certificate location. Check whether it's exists.
      if (!file_exists($this->sslCertPath)) {
        $err_str = "Cannot find cacert_location: " . $this->sslCertPath;
        throw new DatacashRequestException($err_str);
      }
      // Check if the certificate file is readable.
      if (!is_readable($this->sslCertPath)) {
        $err_str = "Cannot read cacert_location: " . $this->sslCertPath;
        throw new DatacashRequestException($err_str);
      }
      // Set the various options.
      $options[CURLOPT_SSL_VERIFYPEER] = TRUE;
      $options[CURLOPT_CAINFO] = $this->sslCertPath;
    }

    if (isset($this->proxyUrl)) {
      $options[CURLOPT_PROXY] = $this->proxyUrl;
    }

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    // Store response for later use.
    $curl_response = curl_exec($ch);
    $err_no        = curl_errno($ch);
    $err_str       = curl_error($ch);
    $curl_get_info = curl_getinfo($ch);
    curl_close($ch);

    if ($err_no > 0) {
      throw new DatacashRequestException("Datacash request curl error with error no. $err_no ($err_str)");
    }

    // Throw an exception where the curl request encountered no errors but
    // the http response code != 200
    $http_response_code = $curl_get_info['http_code'];
    if ($http_response_code !== 200) {
      throw new DatacashRequestException("Datacash curl request issue. Curl got http response code: $http_response_code instead of 200");
    }

    // Prepare the response instance.
    $response = XmlHelper::parseFromXml($curl_response);

    // It's possible, although unlikely, for the transaction to succeed but
    // for an HTTP Response code to not be 200, such as a warning code.
    // Therefore, we don't want to assume that the transaction failed if a
    // 200 was not received.  What we will do is see if the Response.status
    // element isn't there, and if not, if the HTTP Response code is also not
    // 200, then we can safely assume that things didn't go according to plan
    // and we didn't get an XML Response document back.
    if ("Response" !== $response->getName() || (!$response->status && 200 != $curl_get_info["http_code"])) {
      $err_str = "HTTP Error: Response Code " . $curl_get_info["http_code"] . " received.";
      $err_no = $curl_get_info["http_code"];
      throw new DatacashRequestException("Datacash request curl error with error no. $err_no ($err_str)");
    }

    return $response;
  }
}