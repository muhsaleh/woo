<?php
/**
* Skrill Credit Card / Visa, Mastercard, AMEX, JCB, Diners
*
* This gateway is used for Skrill Credit Card / Visa, Mastercard, AMEX, JCB, Diners.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_ACC
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_ACC extends Skrill_Payment_Gateway
{
    var $id = 'skrill_acc';
    public $payment_method_logo = 'acc.png';
    public $payment_method = 'ACC';

}

$obj = new Gateway_Skrill_ACC();

?>
