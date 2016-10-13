<?php
/**
* Skrill Direct (Online Bank Transfer)
*
* This gateway is used for Skrill Direct (Online Bank Transfer).
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_OBT
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_OBT extends Skrill_Payment_Gateway
{
    var $id = 'skrill_obt';
    public $payment_method_logo = 'obt.png';
    public $payment_method = 'OBT';
    public $payment_method_description = 'Germany, United Kingdom, France, Italy, Spain, Hungary and Austria';

}

$obj = new Gateway_Skrill_OBT();

?>
