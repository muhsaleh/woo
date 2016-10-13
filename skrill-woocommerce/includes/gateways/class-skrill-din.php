<?php
/**
* Skrill Diners
*
* This gateway is used for Skrill Diners.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_DIN
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_DIN extends Skrill_Payment_Gateway
{
    var $id = 'skrill_din';
    public $payment_method_logo = 'din.png';
    public $payment_method = 'DIN';

}

$obj = new Gateway_Skrill_DIN();

?>
