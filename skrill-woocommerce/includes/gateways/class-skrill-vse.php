<?php
/**
* Skrill Visa Electron
*
* This gateway is used for Skrill Visa Electron.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_VSE
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_VSE extends Skrill_Payment_Gateway
{
    var $id = 'skrill_vse';
    public $payment_method_logo = 'vse.png';
    public $payment_method = 'VSE';
    public $payment_brand = 'VSE';
    public $payment_method_description = 'All Countries (excluding United States Of America)';

}

$obj = new Gateway_Skrill_VSE();

?>
