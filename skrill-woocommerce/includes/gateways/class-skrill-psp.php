<?php
/**
* Skrill PostePay by Visa
*
* This gateway is used for Skrill PostePay by Visa.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_PSP
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_PSP extends Skrill_Payment_Gateway
{
    var $id = 'skrill_psp';
    public $payment_method_logo = 'psp.png';
    public $payment_method = 'PSP';
    public $payment_brand = 'PSP';
    public $payment_method_description = 'Italy';

}

$obj = new Gateway_Skrill_PSP();

?>
