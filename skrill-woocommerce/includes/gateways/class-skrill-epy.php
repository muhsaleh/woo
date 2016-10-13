<?php
/**
* Skrill ePay.bg
*
* This gateway is used for Skrill ePay.bg.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_EPY
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_EPY extends Skrill_Payment_Gateway
{
    var $id = 'skrill_epy';
    public $payment_method_logo = 'epy.png';
    public $payment_method = 'EPY';
    public $payment_method_description = 'Bulgaria';

}

$obj = new Gateway_Skrill_EPY();

?>
