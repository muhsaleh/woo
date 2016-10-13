<?php
/**
* Skrill Payments Form
*
* The file is for displaying the Skrill payment form
* Copyright (c) Skrill
*
* @package     Skrill/Templates
* @located at  /template/checkout/
*
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

?>

<iframe style = "border:none; width:100%; height:1000px;" src="<?php echo $payment_url?>"></iframe>
