<?php
/**
* Skrill MasterCard
*
* This gateway is used for Skrill MasterCard.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_MSC
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_MSC extends Skrill_Payment_Gateway
{
    var $id = 'skrill_msc';
    public $payment_method_logo = 'msc.png';
    public $payment_method = 'MSC';
    public $payment_brand = 'MSC';

}

$obj = new Gateway_Skrill_MSC();

?>
