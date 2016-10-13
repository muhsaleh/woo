<?php
/**
* Skrill Maestro
*
* This gateway is used for Skrill Maestro.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_MAE
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_MAE extends Skrill_Payment_Gateway
{
    var $id = 'skrill_mae';
    public $payment_method_logo = 'mae.png';
    public $payment_method = 'MAE';
    public $payment_method_description = 'United Kingdom, Spain, Ireland and Austria';

}

$obj = new Gateway_Skrill_MAE();

?>
