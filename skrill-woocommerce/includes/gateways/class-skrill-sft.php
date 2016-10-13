<?php
/**
* Skrill Sofortueberweisung
*
* This gateway is used for Skrill Sofortueberweisung.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_SFT
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_SFT extends Skrill_Payment_Gateway
{
    var $id = 'skrill_sft';
    public $payment_method_logo = 'sft.png';
    public $payment_method = 'SFT';
    public $payment_method_description = 'Germany, Austria, Belgium, Netherlands, Italy, France, Poland and United Kingdom';

}

$obj = new Gateway_Skrill_SFT();

?>
