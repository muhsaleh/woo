<?php
/**
* Skrill Direct Debit / SEPA
*
* This gateway is used for Skrill Direct Debit / SEPA.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_DID
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_DID extends Skrill_Payment_Gateway
{
    var $id = 'skrill_did';
    public $payment_method_logo = 'did.png';
    public $payment_method = 'DID';
    public $payment_method_description = 'Germany';

}

$obj = new Gateway_Skrill_DID();

?>
