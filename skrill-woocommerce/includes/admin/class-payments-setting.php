<?php
/**
* Skrill Plugin Installation Process
*
* This class is used for Payment Methods Configuration
* Copyright (c) Skrill
*
* @class       Skrill_Payment_Configuration
* @package     Skrill/Classes
* @located at  /includes/admin/
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Skrill_Payment_Configuration {

    /**
    * Render payment configuration at backend
    * @param string $payment_method_id
    * @param string $payment_method_description
    * @return array
    */
    public static function render_payment_configuration($payment_method_id, $payment_method_description = '')
    {
        $form_fields = array(
            'enabled' => array(
                'title' => __('BACKEND_CH_ACTIVE', 'wc-skrill'),
                'type' => 'checkbox',
                'default' => '',
                'description' => $payment_method_description
            )
        );

        if ($payment_method_id != 'skrill_flexible') {
            $form_fields['show_separately'] = array(
                'title' => __('SKRILL_BACKEND_PM_MODE', 'wc-skrill'),
                'type' => 'checkbox',
                'default' => ''
            );
        }

        return $form_fields;
    }
}
