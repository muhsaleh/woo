<?php
/**
* Skrill Visa
*
* This gateway is used for Skrill Visa.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_VSA
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_VSA extends Skrill_Payment_Gateway
{
    var $id = 'skrill_vsa';
    public $payment_method_logo = 'vsa.png';
    public $payment_method = 'VSA';
    public $payment_brand = 'VSA';

}

$obj = new Gateway_Skrill_VSA();

?>
