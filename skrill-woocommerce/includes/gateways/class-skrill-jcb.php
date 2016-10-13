<?php
/**
* Skrill JCB
*
* This gateway is used for Skrill JCB.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_JCB
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_JCB extends Skrill_Payment_Gateway
{
    var $id = 'skrill_jcb';
    public $payment_method_logo = 'jcb.png';
    public $payment_method = 'JCB';

}

$obj = new Gateway_Skrill_JCB();

?>
