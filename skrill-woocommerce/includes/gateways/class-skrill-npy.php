<?php
/**
* Skrill EPS (Netpay)
*
* This gateway is used for Skrill EPS (Netpay).
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_NPY
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_NPY extends Skrill_Payment_Gateway
{
    var $id = 'skrill_npy';
    public $payment_method_logo = 'npy.png';
    public $payment_method = 'NPY';
    public $payment_method_description = 'Austria';

}

$obj = new Gateway_Skrill_NPY();

?>
