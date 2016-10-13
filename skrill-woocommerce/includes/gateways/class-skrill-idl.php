<?php
/**
* Skrill iDEAL
*
* This gateway is used for Skrill iDEAL.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_IDL
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_IDL extends Skrill_Payment_Gateway
{
    var $id = 'skrill_idl';
    public $payment_method_logo = 'idl.png';
    public $payment_method = 'IDL';
    public $payment_brand = 'IDL';
    public $payment_method_description = 'Netherlands';

}

$obj = new Gateway_Skrill_IDL();

?>
