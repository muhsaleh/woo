<?php
/**
* Skrill Wallet
*
* This gateway is used for Skrill Wallet.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_WLT
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_WLT extends Skrill_Payment_Gateway
{
    var $id = 'skrill_wlt';
    public $payment_method_logo = 'wlt.png';
    public $payment_method = 'WLT';
    public $payment_brand = 'WLT';

}

$obj = new Gateway_Skrill_WLT();

?>
