<?php
/**
* Skrill Neteller
*
* This gateway is used for Skrill Neteller.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_NTL
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_NTL extends Skrill_Payment_Gateway
{
    var $id = 'skrill_ntl';
    public $payment_method_logo = 'ntl.png';
    public $payment_method = 'NTL';

}

$obj = new Gateway_Skrill_NTL();

?>
