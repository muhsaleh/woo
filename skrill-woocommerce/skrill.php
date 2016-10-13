<?php
/*
* Plugin Name: Skrill - WooCommerce
* Plugin URI:  http://www.skrill.com/
* Description: WooCommerce with Skrill payment gateway
* Author:      Skrill
* Author URI:  hhttp://www.skrill.com/
* Version:     1.0.0
*/

/**
* Copyright (c) Skrill
*
*/

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

include_once(dirname(__FILE__) .'/skrill-install.php');
register_activation_hook(__FILE__ , 'activate_skrill_plugin');
register_deactivation_hook(__FILE__ , 'deactivate_skrill_plugin');
add_action('plugins_loaded', 'init_payment_gateway', 0);

ob_start();

define('SKRILL_PLUGIN_VERSION', '1.0.0');
define('SKRILL_PLUGIN_FILE', __FILE__);

/**
* Get notice when woocommerce not active.
*/
function get_notice_woocommerce_activation()
{
    echo '<div id="notice" class="error"><p>';
    echo '<a href="http://www.woothemes.com/woocommerce/" style="text-decoration:none" target="_new">WooCommerce </a>';
    echo __('BACKEND_GENERAL_PLUGINREQ', 'wc-skrill') .'<b> Skrill Payment Gateway for WooCommerce</b>';
    echo '</p></div>';
}

/**
* Add configuration link at plugin installation
* @param array
*/
function add_configuration_links($links)
{
    $configuration_links = array('
        <a href="' . admin_url('admin.php?page=wc-settings&tab=skrill_settings') . '">' . 
        __('SKRILL_BACKEND_PM_SETTINGS','wc-skrill') . '</a>
    ');
    return array_merge($configuration_links , $links);
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'add_configuration_links');

/**
* Init payment gateway
*/
function init_payment_gateway()
{
    /* load plugin language */
    load_plugin_textdomain('wc-skrill', false, dirname(plugin_basename(__FILE__)) . '/i18n/languages/');

    if (!class_exists('WC_Payment_Gateway')) {
        add_action('admin_notices', 'get_notice_woocommerce_activation');
        return;
    }

    include_once(dirname(__FILE__) . '/includes/core/class-configuration.php');
    include_once(dirname(__FILE__) . '/includes/core/class-core.php');
    include_once(dirname(__FILE__) . '/includes/core/class-version-tracker.php');
    include_once(dirname(__FILE__) . '/includes/admin/class-payments-setting.php');
    include_once(dirname(__FILE__) . '/includes/admin/class-skrill-setting.php');
    include_once(dirname(__FILE__) . '/models/class-transactions-model.php');

    if (!class_exists('Skrill_Payment_Gateway')) {

        include_once(dirname(__FILE__) . '/class-skrill-payment-gateway.php');
    }

    /**
    * Add Skrill Payment Methods to WooCommerce
    * @param array
    * @return array
    */
    function add_skrill_payment_methods($payment_methods)
    {
        $payment_methods[] = 'Gateway_Skrill_Flexible';
        $payment_methods[] = 'Gateway_Skrill_WLT';
        $payment_methods[] = 'Gateway_Skrill_PSC';
        $payment_methods[] = 'Gateway_Skrill_ACC';
        $payment_methods[] = 'Gateway_Skrill_VSA';
        $payment_methods[] = 'Gateway_Skrill_MSC';
        $payment_methods[] = 'Gateway_Skrill_VSE';
        $payment_methods[] = 'Gateway_Skrill_MAE';
        $payment_methods[] = 'Gateway_Skrill_AMX';
        $payment_methods[] = 'Gateway_Skrill_DIN';
        $payment_methods[] = 'Gateway_Skrill_JCB';
        $payment_methods[] = 'Gateway_Skrill_GCB';
        $payment_methods[] = 'Gateway_Skrill_DNK';
        $payment_methods[] = 'Gateway_Skrill_PSP';
        $payment_methods[] = 'Gateway_Skrill_CSI';
        $payment_methods[] = 'Gateway_Skrill_OBT';
        $payment_methods[] = 'Gateway_Skrill_GIR';
        $payment_methods[] = 'Gateway_Skrill_DID';
        $payment_methods[] = 'Gateway_Skrill_SFT';
        $payment_methods[] = 'Gateway_Skrill_EBT';
        $payment_methods[] = 'Gateway_Skrill_IDL';
        $payment_methods[] = 'Gateway_Skrill_NPY';
        $payment_methods[] = 'Gateway_Skrill_PLI';
        $payment_methods[] = 'Gateway_Skrill_PWY';
        $payment_methods[] = 'Gateway_Skrill_EPY';
        $payment_methods[] = 'Gateway_Skrill_ALI';
        $payment_methods[] = 'Gateway_Skrill_NTL';
        $payment_methods[] = 'Gateway_Skrill_Astropay';

        return $payment_methods;
    }

    add_filter('woocommerce_payment_gateways', 'add_skrill_payment_methods');
    foreach (glob(dirname( __FILE__ ) . '/includes/gateways/*.php') as $filename) {
        include_once $filename;
    }
}

/**
* Add custom order status icon
*/
function add_custom_order_status_icon()
{ 
    if(!is_admin()) { 
        return; 
    }
    ?> 
    <style>
        .column-order_status mark.skrill-accepted {
            content: url(<?php echo home_url()?>/wp-content/plugins/skrill-woocommerce/assets/images/skrill-accept.png);
        }
    </style>
    <?php
}
add_action('wp_print_scripts', 'add_custom_order_status_icon');

ob_end_flush();
