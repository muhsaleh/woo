<?php
/**
* Skrill Flexible
*
* This gateway is used for Flexible.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_Flexible
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_Flexible extends Skrill_Payment_Gateway
{
    var $id = 'skrill_flexible';
    public $payment_method_logo = 'flexible.png';
    public $payment_method = 'FLEXIBLE';
    public $payment_brand = 'FLEXIBLE';

}

$obj = new Gateway_Skrill_Flexible();

?>
