<?php
/**
 * @file
 * Provides defination for DatacashManagerInterface
 */

namespace Datacash;

/**
 * Interface DatacashManagerInterface
 *
 * @package Datacash
 */
interface DatacashManagerInterface {
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
  public function createRequest($client_id, $client_pass, $request_params);

  /**
   * Sends a request to datacash server.
   *
   * @return SetupManagerInterface
   *
   * @throws DatacashConnectException
   */
  public function connect();

  /**
   * Provides the current Request instance.
   *
   * @return Request
   */
  public function getRequest();

  /**
   * Provides the current Response instance.
   *
   * @return Response
   */
  public  function getResponse();

}