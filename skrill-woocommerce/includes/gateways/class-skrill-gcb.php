<?php
/**
* Skrill Carte Bleue by Visa
*
* This gateway is used for Skrill Carte Bleue by Visa.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_GCB
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_GCB extends Skrill_Payment_Gateway
{
    var $id = 'skrill_gcb';
    public $payment_method_logo = 'gcb.png';
    public $payment_method = 'GCB';
    public $payment_method_description = 'France';

}

$obj = new Gateway_Skrill_GCB();

?>
