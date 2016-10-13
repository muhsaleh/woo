<?php
/**
* Skrill Dankort by Visa
*
* This gateway is used for Skrill Dankort by Visa.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_DNK
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_DNK extends Skrill_Payment_Gateway
{
    var $id = 'skrill_dnk';
    public $payment_method_logo = 'dnk.png';
    public $payment_method = 'DNK';
    public $payment_method_description = 'Denmark';

}

$obj = new Gateway_Skrill_DNK();

?>
