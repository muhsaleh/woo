<?php
/**
* Skrill POLi
*
* This gateway is used for Skrill POLi.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_PLI
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_PLI extends Skrill_Payment_Gateway
{
    var $id = 'skrill_pli';
    public $payment_method_logo = 'pli.png';
    public $payment_method = 'PLI';
    public $payment_method_description = 'Australia';

}

$obj = new Gateway_Skrill_PLI();

?>
