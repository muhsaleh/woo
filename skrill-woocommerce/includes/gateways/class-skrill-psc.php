<?php
/**
* Skrill Paysafecard
*
* This gateway is used for Skrill Paysafecard.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_PSC
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_PSC extends Skrill_Payment_Gateway
{
    var $id = 'skrill_psc';
    public $payment_method_logo = 'psc.png';
    public $payment_method = 'PSC';
    public $payment_method_description = 'American Samoa, Austria, Belgium, Canada,
        Croatia, Cyprus, Czech Republic, Denmark,
        Finland, France, Germany, Guam, Hungary,
        Ireland, Italy, Latvia, Luxembourg, Malta,
        Mexico, Netherlands, Northern Mariana Islands,
        Norway, Poland, Portugal, Puerto Rico,
        Romania, Slovakia, Slovenia, Spain, Sweden,
        Switzerland, Turkey, United Kingdom, United
        States Of America and US Virgin Islands';

}

$obj = new Gateway_Skrill_PSC();

?>
