<?php
/**
* Skrill Giropay
*
* This gateway is used for Skrill Giropay.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_GIR
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_GIR extends Skrill_Payment_Gateway
{
    var $id = 'skrill_gir';
    public $payment_method_logo = 'gir.png';
    public $payment_method = 'GIR';
    public $payment_method_description = 'Germany';

}

$obj = new Gateway_Skrill_GIR();

?>
