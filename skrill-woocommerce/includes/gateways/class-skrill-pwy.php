<?php
/**
* Skrill Przelewy24
*
* This gateway is used for Skrill Przelewy24.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_PWY
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_PWY extends Skrill_Payment_Gateway
{
    var $id = 'skrill_pwy';
    public $payment_method_logo = 'pwy.png';
    public $payment_method = 'PWY';
    public $payment_method_description = 'Poland';

}

$obj = new Gateway_Skrill_PWY();

?>
