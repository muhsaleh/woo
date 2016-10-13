<?php
/**
* Skrill Payments Form
*
* The file is for displaying the Skrill payment form
* Copyright (c) Skrill
*
* @package     Skrill/Templates
* @located at  /template/admin/order/
*
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

?>
<p>
	<strong><?php echo __('BACKEND_GENERAL_INFORMATION', 'wc-skrill'); ?></strong>
	<br />
	<?php
		echo $payment_method_title.'<br />';
		echo __('SKRILL_BACKEND_ORDER_STATUS', 'wc-skrill').' : '.$payment_status.'<br />';
		echo __('SKRILL_BACKEND_ORDER_PM', 'wc-skrill').' : '.$payment_type.'<br />';
		if (isset($additional_information['order_origin'])) {
			echo  __('SKRILL_BACKEND_ORDER_ORIGIN', 'wc-skrill').' : '.$additional_information['order_origin'].'<br />';
		}
		if (isset($additional_information['order_country'])) {
			echo  __('SKRILL_BACKEND_ORDER_COUNTRY', 'wc-skrill').' : '.$additional_information['order_country'].'<br />';
		}
		echo __('SKRILL_BACKEND_ORDER_CURRENCY', 'wc-skrill').' : '.$transaction['currency'].'<br />';
	?>
</p>