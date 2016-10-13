<?php
/**
* Skrill Plugin Installation process
*
* This file is used for creating tables while installing the plugins.
* Copyright (c) Skrill
*
* @package Skrill
* @located at  /
*
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
* activate plugin
*/
function activate_skrill_plugin()
{
    create_skrill_table();
}

/**
* deactivate plugin
*/
function deactivate_skrill_plugin()
{
    delete_skrill_table();
}

/**
* Create transaction log table
*/
function create_skrill_table()
{
    global $wpdb;
    $wpdb->hide_errors();
    $charset_collate = $wpdb->get_charset_collate();
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    if (!get_option('skrill_version') || get_option('skrill_version') != SKRILL_PLUGIN_VERSION)
    {
        $transaction_sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}skrill_transaction_log (
            `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
            `order_id` bigint(20) unsigned NOT NULL,
            `transaction_id` varchar(100),
            `mb_transaction_id` varchar(50) NOT NULL,
            `payment_method_id` varchar(30),
            `payment_type` varchar(16) NOT NULL,            
            `payment_status` varchar(30),
            `amount` decimal(17,2) NOT NULL,
            `refunded_amount` decimal(17,2) DEFAULT '0',
            `currency` char(3) NOT NULL,
            `customer_id` int(11) unsigned DEFAULT NULL,
            `date` datetime NOT NULL,
            `additional_information` LONGTEXT NULL,
            `payment_response` LONGTEXT NULL,
            `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
            PRIMARY KEY (`id`)
        ) $charset_collate;";
        dbDelta($transaction_sql);

        if (!get_option('skrill_version')) {
            add_option('skrill_version', SKRILL_PLUGIN_VERSION);
        }elseif (get_option('skrill_version') != SKRILL_PLUGIN_VERSION) {
            update_option('skrill_version', SKRILL_PLUGIN_VERSION);
        }

    }
}

/**
* Delete skrill values
* from the hook "register_deactivation_hook"
*
*/
function delete_skrill_table()
{
    global $wpdb;
    $wpdb->query("delete from $wpdb->options where option_name like '%skrill%'");
}

/**
* Register new order status
* register status : Skrill - Payment Accepted
* from hook init
*/
function register_payment_status()
{

    register_post_status('wc-skrill-accepted', array(
    'label'                     => _x('Skrill - Payment Accepted', 'WooCommerce Order Status', 'wc_skrill'),
    'public'                    => true,
    'exclude_from_search'       => false,
    'show_in_admin_all_list'    => true,
    'show_in_admin_status_list' => true,
    'label_count'               => _n_noop('Skrill - Payment Accepted (%s)', 'Skrill - Payment Accepted (%s)', 'wc_skrill')
    ) );

}
add_filter('init', 'register_payment_status');

/**
* Add new order status to WooCommerce
* from hook wc_order_statuses
* @param array $order_status
* @return array
*/
function add_order_status($order_status)
{
    $order_status['wc-skrill-accepted'] = _x('Skrill - Payment Accepted', 'WooCommerce Order Status', 'wc_skrill');
    return $order_status;
}
add_filter('wc_order_statuses', 'add_order_status');
