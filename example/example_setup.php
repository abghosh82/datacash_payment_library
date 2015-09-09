<?php
/**
 * @file
 * Provides usage example.
 */
require_once __DIR__ . "/../vendor/autoload.php";

use Datacash\DatacashManager;
use Datacash\MethodInterface;

$client_id = 99999999;
$client_pass = 'pass';
$datacash_setup_request = array(
  'method' => MethodInterface::SETUP_FULL,
  'tnx_method' => MethodInterface::AUTH,
  'amount' => 2.5,
  'currency' => 'gbp',
  'merchant_reference' => 'TEST'. substr(time(), -4),
  'page_set_id' => 9999,
  'dyn_fields1' => 'dynamic_data',
  'return_url' => '/',
);

$datacash_setup = new DatacashManager();
$response = $datacash_setup->createRequest($client_id, $client_pass, $datacash_setup_request)
  ->connect()
  ->getResponse();

print_r($response);

echo $response->getRedirectUrl();


