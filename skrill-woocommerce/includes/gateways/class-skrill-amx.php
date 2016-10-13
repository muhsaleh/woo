<?php
/**
* Skrill American Express
*
* This gateway is used for Skrill American Express.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_AMX
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_AMX extends Skrill_Payment_Gateway
{
    var $id = 'skrill_amx';
    public $payment_method_logo = 'amx.png';
    public $payment_method = 'AMX';

}

$obj = new Gateway_Skrill_AMX();

?>
