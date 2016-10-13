<?php
/**
* Skrill Alipay
*
* This gateway is used for Skrill Alipay.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_ALI
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_ALI extends Skrill_Payment_Gateway
{
    var $id = 'skrill_ali';
    public $payment_method_logo = 'ali.png';
    public $payment_method = 'ALI';

}

$obj = new Gateway_Skrill_ALI();

?>
