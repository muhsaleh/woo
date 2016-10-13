<?php
/**
* Skrill Payments Update Order
*
* The file is for displaying button update order at order detail (admin)
* Copyright (c) Skrill
*
* @package     Skrill/Templates
* @located at  /template/admin/order
*
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

?>
<p class="form-field form-field-wide" style="text-align:right">
	<label for="order_status">&nbsp;</label>
	<a href="<?php echo $update_order_url ?>" class="button save_order button-primary" >
		<?php echo __('BACKEND_TT_UPDATE_ORDER', 'wc-skrill' ) ?>
	</a>
</p>