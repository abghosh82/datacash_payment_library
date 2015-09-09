<?php
/**
 * @file
 * Provides defination for MethodInterface
 */

namespace Datacash;

/**
 * Interface MethodInterface
 *
 * @package Datacash
 */
interface MethodInterface {
  /**
   * Transaction authorization.
   */
  const AUTH = "auth";

  /**
   * Transaction cancel.
   */
  const CANCEL = "cancel";

  /**
   * Transaction pre.
   */
  const PRE = "pre";

  /**
   * Transaction pre.
   */
  const REFUND = "refund";

  /**
   * Transaction refund (HPS).
   */
  const TXN_REFUND = "txn_refund";

  /**
   * Explicit fufill/settlement.
   */
  const FULFILL = "fulfill";

  /**
   * Transaction pre.
   */
  const ERP = "erp";

  /**
   * Transaction history query.
   */
  const QUERY = "query";

  /**
   * Session setup.
   */
  const SETUP = "setup";

  /**
   * Session setup for hps.
   */
  const SETUP_FULL = "setup_full";

  /**
   * Validate 3D Secure PaRes response.
   */
  const THREEDS_VALIDATION = "threedsecure_validate_authentication";

  /**
   * Validate 3D Secure PaRes response.
   */
  const THREEDS_AUTH_REFERRAL_REQUEST = "threedsecure_authorize_referral_request";

  /**
   * Validate 3D Secure PaRes response.
   */
  const THREEDS_AUTH_REQUEST = "threedsecure_authorization_request";
}