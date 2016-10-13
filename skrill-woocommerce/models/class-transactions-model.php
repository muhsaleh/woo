<?php
/**
* Skrill Transactions Log Model
*
* Transactions Log Models available on both the front-end and admin.
* Copyright (c) Skrill
*
* @class       Transactions_Model
* @package     Skrill/Classes
* @located at  /includes/
*
*/

 if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
 }

class Transactions_Model
{
	const TRANSLOG_TABLE = "skrill_transaction_log";

	/**
    * Save transaction log
    * @param array $transaction
    */
    public static function save($transaction)
    {
        global $wpdb;
        $wpdb->insert("{$wpdb->prefix}".self::TRANSLOG_TABLE, array(
                'order_id'          		=> $transaction['order_id'],
                'transaction_id'    		=> $transaction['transaction_id'],
                'mb_transaction_id' 		=> $transaction['mb_transaction_id'],
                'payment_method_id'         => $transaction['payment_method_id'],
                'payment_type'      		=> $transaction['payment_type'],
                'payment_status'    		=> $transaction['payment_status'],
                'amount'            		=> $transaction['amount'],
                'refunded_amount'   		=> $transaction['refunded_amount'],
                'currency'          		=> $transaction['currency'],
                'date'              		=> date('Y-m-d H:i:s'),
                'additional_information'	=> $transaction['additional_information'],
                'payment_response' 			=> $transaction['payment_response'],
                'customer_id'       		=> $transaction['customer_id']
        ),
        array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d' ));	
    }

    /**
    * Save error transaction log
    * @param array $transaction
    */
    public static function save_error_transaction($transaction)
    {
        global $wpdb;
        $wpdb->insert("{$wpdb->prefix}".self::TRANSLOG_TABLE, array(
                'order_id'                  => $transaction['order_id'],
                'payment_method_id'         => $transaction['payment_method_id'],
                'payment_status'            => $transaction['payment_status'],
                'amount'                    => $transaction['amount'],
                'refunded_amount'           => $transaction['refunded_amount'],
                'currency'                  => $transaction['currency'],
                'date'                      => date('Y-m-d H:i:s'),
                'customer_id'               => $transaction['customer_id']
        ),
        array('%d', '%s', '%s', '%s', '%s', '%s', '%d' ));  
    }

    /**
    * Save payment response
    * @param string $transaction_id
    * @param array $payment_response
    */
    public static function save_payment_response($transaction_id, $payment_response)
    {
        global $wpdb;
        $wpdb->insert("{$wpdb->prefix}".self::TRANSLOG_TABLE, array(
                'transaction_id'            => $transaction_id,
                'date'                      => date('Y-m-d H:i:s'),
                'payment_response'          => $payment_response
        ),
        array('%s', '%s', '%s'));  
    }

    /**
    * Check transaction id if exist 
    * @param $transaction_id
    * @return bool 
    */
    public static function is_transaction_id_exist($transaction_id)
    {
        global $wpdb;
        $query =  $wpdb->prepare("SELECT * FROM `{$wpdb->prefix}".self::TRANSLOG_TABLE."` WHERE transaction_id = %s ", $transaction_id);
        $transaction = $wpdb->get_row($query, ARRAY_A);

        if(empty($transaction)) {
            return false;
        }
        return $transaction;
    }

    /**
    * Get transaction by order id 
    * @param int $order_id 
    * @return array 
    */
    public static function get_data($order_id)
    {
        global $wpdb;
        $query =  $wpdb->prepare("SELECT * FROM `{$wpdb->prefix}".self::TRANSLOG_TABLE."` WHERE order_id = %d ", $order_id);
        $transaction = $wpdb->get_row($query, ARRAY_A);

        return $transaction;
    }

    /**
    * Update transaction by transaction id
    * @param string $transaction_id 
    * @param array $transaction 
    */
    public static function update($transaction_id, $transaction)
    {
        global $wpdb; 
        $wpdb->update("{$wpdb->prefix}".self::TRANSLOG_TABLE, array(
                'order_id'                  => $transaction['order_id'],
                'mb_transaction_id'         => $transaction['mb_transaction_id'],
                'payment_method_id'         => $transaction['payment_method_id'],
                'payment_type'              => $transaction['payment_type'],
                'payment_status'            => $transaction['payment_status'],
                'amount'                    => $transaction['amount'],
                'refunded_amount'           => $transaction['refunded_amount'],
                'currency'                  => $transaction['currency'],
                'additional_information'    => $transaction['additional_information'],
                'payment_response'          => $transaction['payment_response'],
                'customer_id'               => $transaction['customer_id']
        ), 
        array('transaction_id' => $transaction_id),
        array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d'),
        array('%s'));  
    }

    /**
    * Update transaction status by order id
    * @param int $order_id 
    * @param string $payment_status 
    */
    public static function update_payment_status($order_id, $payment_status)
    {
        global $wpdb; 
        $wpdb->update("{$wpdb->prefix}".self::TRANSLOG_TABLE, array(
                'payment_status'            => $payment_status
        ), 
        array('order_id' => $order_id),
        array('%s'),
        array('%d'));  
    }

    /**
    * Update refunded status by order id
    * @param int $order_id 
    * @param string $refunded_status
    * @param float $refunded_amount
    */
    public static function update_refunded_status($order_id, $refunded_status, $refunded_amount = '0')
    {
        global $wpdb; 
        $wpdb->update("{$wpdb->prefix}".self::TRANSLOG_TABLE, array(
                'payment_status'            => $refunded_status,
                'refunded_amount'           => $refunded_amount
        ), 
        array('order_id' => $order_id),
        array('%s','%s'),
        array('%d'));
    }
}
