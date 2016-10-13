<?php
/**
* Skrill Trustly
*
* This gateway is used for Skrill Trustly.
* Copyright (c) Skrill
*
* @class       Gateway_Skrill_GLU
* @extends     Skrill_Payment_Gateway
* @located at  /includes/gateways
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Gateway_Skrill_GLU extends Skrill_Payment_Gateway
{
    var $id = 'skrill_glu';
    public $payment_method_logo = 'glu.png';
    public $payment_method = 'GLU';
    public $payment_brand = 'GLU';
    public $payment_method_description = 'Sweden, Finland, Estonia, Denmark, Spain, Poland, Italy, France, Germany,
        Portugal, Austria, Latvia, Lithuania, Netherlands';

}

$obj = new Gateway_Skrill_GLU();

?>
