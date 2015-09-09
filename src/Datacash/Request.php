<?php
/**
 * @file
 * Provides datacash payment request functions.
 */

namespace Datacash;

/**
 * Class Request
 *
 * @package Datacash
 */
class Request extends DatacashElement implements RequestInterface {
  /**
   * DataCash default request version.
   */
  const VERSION_DEFAULT = 1;

  /**
   * Default constructor.
   */
  public function __construct($client, $password, $version = self::VERSION_DEFAULT) {
    parent::__construct('Request');

    $this->Authentication->client = $client;
    $this->Authentication->password = $password;

    // DataCash API documentation express that default version number
    // shouldn't be specified. If you change it, then specify it.
    if (self::VERSION_DEFAULT !== $version) {
      $this->setAttribute("version", $version);
    }
  }

  /**
   * Prepares Request based on the Request Parameters.
   */
  public function prepareRequest($request_params) {
    $this->Transaction->HpsTxn->method = $request_params['method'];
    if ($request_params['tnx_method']) {
      $this->Transaction->CardTxn->method = $request_params['tnx_method'];
    }
    if (!empty($request_params['amount'])) {
      $this->Transaction->TxnDetails->amount = $request_params['amount'];
    }
    if (!empty($request_params['currency'])) {
      $this->Transaction->TxnDetails->amount->setAttribute("currency", $request_params['currency']);
    }
    if (!empty($request_params['merchant_reference'])) {
      $this->Transaction->TxnDetails->merchantreference = $request_params['merchant_reference'];
    }
    if (!empty($request_params['dyn_fields'])) {
      foreach ($request_params['dyn_fields'] as $dyn_field => $dyn_value) {
        $this->Transaction->HpsTxn->DynamicData->$dyn_field = $dyn_value;
      }
    }
    if (!empty($request_params['page_set_id'])) {
      $this->Transaction->HpsTxn->page_set_id = $request_params['page_set_id'];
    }
    if (!empty($request_params['return_url'])) {
      $this->Transaction->HpsTxn->return_url = $request_params['return_url'];
    }
    if (!empty($request_params['expiry_url'])) {
      $this->Transaction->HpsTxn->expiry_url = $request_params['expiry_url'];
    }
    if (!empty($request_params['query_reference'])) {
      $this->Transaction->HistoricTxn->reference = $request_params['query_reference'];
    }
    if (!empty($request_params['query_reference_type'])) {
      $this->Transaction->HistoricTxn->reference->setAttribute('type', $request_params['query_reference_type']);
    }
  }
}
