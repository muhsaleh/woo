<?php
/**
* Skrill Astropay
*
* This gateway is used for Skrill Astropay.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_Astropay
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_Astropay extends Skrill_Payment_Gateway
{
    var $id = 'skrill_astropay';
    public $payment_method_logo = 'astropay.png';
    public $payment_method = 'ADB';
    public $payment_brand = 'ADB,AOB,ACI,AUP';

}

$obj = new Gateway_Skrill_Astropay();

?>
