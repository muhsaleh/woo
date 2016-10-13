<?php
/**
* Skrill CartaSi by Visa
*
* This gateway is used for Skrill CartaSi by Visa.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_CSI
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_CSI extends Skrill_Payment_Gateway
{
    var $id = 'skrill_csi';
    public $payment_method_logo = 'csi.png';
    public $payment_method = 'CSI';
    public $payment_method_description = 'Italy';

}

$obj = new Gateway_Skrill_CSI();

?>
