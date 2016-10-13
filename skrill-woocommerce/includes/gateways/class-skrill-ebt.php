<?php
/**
* Skrill Nordea Solo
*
* This gateway is used for Skrill Nordea Solo.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_EBT
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_EBT extends Skrill_Payment_Gateway
{
    var $id = 'skrill_ebt';
    public $payment_method_logo = 'ebt.png';
    public $payment_method = 'EBT';
    public $payment_brand = 'EBT';
    public $payment_method_description = 'Sweden';

}

$obj = new Gateway_Skrill_EBT();

?>
