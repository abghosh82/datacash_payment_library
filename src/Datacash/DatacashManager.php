<?php
/**
 * @file
 * Provides datacash request dependencies.
 */

namespace Datacash;
use Datacash\Exceptions\DatacashRequestException;

/**
 * Class DatacashManager
 *
 * @package Datacash
 */
class DatacashManager implements DatacashManagerInterface {
  /**
   * An instance of Request.
   *
   * @var Request
   */
  protected $request;

  /**
   * An instance of Response.
   *
   * @var Response
   */
  protected $response;

  /**
   * Creates a datacash request.
   *
   * @param string $client_id
   *   Datacash client id.
   * @param $client_pass
   *   Datacash client password.
   * @param array $request_params
   *  Datacash request parameters.
   *
   * @return SetupManagerInterface
   *
   * @throws DatacashRequestException
   */
  public function createRequest($client_id, $client_pass, $request_params) {
    if (empty($request_params['method'])) {
      throw new DatacashRequestException('Request should have a method.');
    }
    // Create a request instance and prepare request.
    $this->request = new Request($client_id, $client_pass);
    $this->request->prepareRequest($request_params);

    return $this;
  }

  /**
   * Sends a request to datacash server.
   *
   * @return SetupManagerInterface
   *
   * @throws DatacashConnectException
   */
  public function connect() {
    if (empty($this->request)) {
      throw new DatacashConnectException('Cannot connect to datacash without proper request parameters.');
    }
    // Initiate the request and store the response.
    $this->response = SSLAgent::getInstance()->send($this->request);

    return $this;
  }

  /**
   * Provides the current Request instance.
   *
   * @return Request
   */
  public function getRequest() {
    return $this->request;
  }

  /**
   * Provides the current Response instance.
   *
   * @return Response
   */
  public  function getResponse() {
    return $this->response;
  }
}