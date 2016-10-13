<?php
/**
* Skrill Payment Gateway
*
* Copyright (c) Skrill
*
* @class       Skrill_Payment_Gateway
* @extends     WC_Payment_Gateway
* @package     Skrill/Classes
* @located at  /
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Skrill_Payment_Gateway extends WC_Payment_Gateway
{
    var $payment_method_id;
    public $payment_method = '';
    public $payment_method_logo = '';
    public $payment_method_description = '';
    public $language;
    public $plugin_directory;

    protected $wc_cancelled_status = 'wc-cancelled';
    protected $wc_failed_status = 'wc-failed';
    protected $wc_processing_status = 'wc-processing';
    protected $wc_pending_status = 'wc-pending';
    protected $wc_payment_accepted = 'wc-skrill-accepted';
    protected $wc_refunded_status = 'wc-refunded';

    protected $prepare_only = 1;
    protected $processed_status = '2';
    protected $pending_status = '0';
    protected $failed_status = '-2';
    protected $refunded_status = '-4';
    protected $refund_failed_status = '-5';
    protected $statusUrl = false;

    protected $wc_order;

    private static $added_meta_boxes = false;
    private static $updated_meta_boxes = false;
    private static $saved_meta_boxes = false;

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $this->payment_method_id = $this->id;
        $this->language     = Configuration::get_language();
        $this->plugins_url  = Configuration::get_plugin_url();
        $this->plugin_directory = plugin_dir_path( __FILE__ );
        $this->form_fields = Skrill_Payment_Configuration::render_payment_configuration(
            $this->payment_method_id, 
            $this->payment_method_description
        );
        $this->method_title = 'Skrill - ' . Configuration::get_backend_payment_method_title($this->payment_method_id);
        $this->method_description = $this->get_payment_method_logo();
        $this->init_settings();

        //save admin configuration from woocomerce checkout tab
        add_action(
            'woocommerce_update_options_payment_gateways_' . $this->payment_method_id,
            array($this, 'process_admin_options')
        );
        //frontend hook
        add_action('woocommerce_receipt_' . $this->payment_method_id, array(&$this, 'set_receipt_page'));
        add_action('woocommerce_thankyou_' . $this->payment_method_id, array(&$this, 'set_thankyou_page'));
        //backend hook
        add_action(
            'woocommerce_admin_order_data_after_shipping_address',
            array(&$this, 'render_additional_information')
        );
        add_action('woocommerce_admin_order_data_after_order_details', array(&$this, 'update_order'));
        add_action('woocommerce_process_shop_order_meta', array( &$this, 'save_order_meta'));

        // enable woocommerce refund for {payment gateway}
        $this->supports = array('refunds');

        if (isset(WC()->session->skrill_thankyou_page)) {
            unset( WC()->session->skrill_thankyou_page );
        }
        if (isset(WC()->session->skrill_receipt_page)) {
            unset(WC()->session->skrill_receipt_page);
        }
        if (isset(WC()->session->skrill_status_url)) {
            unset(WC()->session->skrill_status_url);
        }
    }

    /**
    * Payment title.
    * from class WC_Payment_Gateway
    * @return string
    */
    public function get_title()
    {
        if (is_admin()) {
            return Configuration::get_backend_payment_method_title($this->payment_method_id);
        } 
        return false;
    }

    /**
    * Payment icon.
    * from class WC_Payment_Gateway
    * @return string
    */
    public function get_icon()
    {
        $title = Configuration::get_frontend_payment_method_title($this->payment_method_id);
        $icon_src = $this->plugins_url . '/assets/images/'.$this->payment_method_logo;
        $icon_html = '';
        $icon_html .= '<img src="' . $icon_src . '" alt="' . $title  . '" title="' . $title . '"
            style="height:40px; margin:15px 10px" />';

        return apply_filters('woocommerce_gateway_icon', $icon_html, $this->payment_method_id);
    }

    /**
    * Validate if payment methods is available.
    * From class WC_Payment_Gateway
    * @return bool
    */
    public function is_available()
    {
        $is_available = parent::is_available();

        if ($is_available) {
            if ($this->payment_method != 'FLEXIBLE') {
                $payment_settings = get_option('woocommerce_'.$this->payment_method_id.'_settings');
                $acc_settings = get_option('woocommerce_skrill_acc_settings');
                if ($payment_settings['show_separately'] != 'yes'
                    ||(($this->payment_method == 'VSA'
                        || $this->payment_method == 'MSC'
                        || $this->payment_method == 'AMX'
                        || $this->payment_method == 'JCB'
                        || $this->payment_method == 'DIN')
                        && ($acc_settings['enabled'] == 'yes' 
                            && $acc_settings['show_separately'] == 'yes'))) {
                    return false;
                }
            }

            global $woocommerce; 
            $country_code = $woocommerce->customer->country;
            $supported_payments = SkrillPayment::getSupportedPaymentsByCountryCode($country_code);

            return in_array($this->payment_method, $supported_payments);
        }

        return $is_available;
    }

    /**
    * Get payment method logo
    * @return string
    */
    protected function get_payment_method_logo()
    {
        $payment_method_title = Configuration::get_backend_payment_method_title($this->payment_method_id);
        $image_src = $this->plugins_url . '/assets/images/'.$this->payment_method_logo;

        return '<img src="'.$image_src.'" alt="'.$payment_method_title.'" 
            title="'.$payment_method_title.'" style="height:40px;">';
    }

    /**
    * Process payment and return success result
    * from class WC_Payment_Gateway
    * @param int $order_id
    * @return array
    */
    public function process_payment($order_id)
    {
        $this->wc_order = new WC_Order($order_id);
        return array(
            'result'   => 'success',
            'redirect' => $this->wc_order->get_checkout_payment_url(true)
        );
    }

    /**
    * Thankyou Page
    * from hook "woocommerce_thankyou_{gateway_id}"
    */
    public function set_thankyou_page()
    {
        if (!isset( WC()->session->skrill_thankyou_page)) {
            WC()->session->set('skrill_thankyou_page', true);
        }
    }

    /**
    * Process payment
    * from hook "woocommerce_receipt_{gateway_id}"
    * @param int $order_id
    */
    public function set_receipt_page($order_id)
    {
        $this->wc_order = new WC_Order($order_id);

        if (isset($_REQUEST['status']) && isset($_REQUEST['status_url'])) {
            if (!isset(WC()->session->skrill_status_url)) {
                $this->process_status_response();
                WC()->session->set('skrill_status_url', true);
            }
        } else {
            if (get_current_user_id() != $this->wc_order->user_id) {
                wp_safe_redirect(home_url()); 
            } else {
                if (!isset(WC()->session->skrill_receipt_page)) {
                    $this->render_payment_form();
                    WC()->session->set('skrill_receipt_page', true);
                } elseif (isset($_REQUEST['transaction_id'])) {
                    $this->process_payment_response();  
                } elseif (isset($_REQUEST['cancelled'])) {
                    $this->process_error_payment($this->wc_cancelled_status, 'ERROR_GENERAL_CANCEL');
                }  
            }  
        }
    }

    /**
    * Render Payment Form
    */
    protected function render_payment_form()
    {
        $checkout_parameters = $this->get_checkout_parameters();
        $payment_url = SkrillPayment::getPaymentUrlByCheckoutParameters($checkout_parameters);
        if (!$payment_url) {
            $this->process_error_payment($this->wc_cancelled_status, 'ERROR_GENERAL_REDIRECT');
        }

        if (get_option('skrill_display') == 'REDIRECT') {
            wp_redirect($payment_url);
        }

        wc_get_template('payment-form.php', 
            array('payment_url' => $payment_url), 
            $this->plugin_directory . 'templates/checkout/', 
            $this->plugin_directory . 'templates/checkout/'
        );
    }

    /**
    * Get checkout parameters
    * @return array
    */
    protected function get_checkout_parameters()
    {
        $date_time = SkrillPayment::getCurrentDateTime();
        $random_number = SkrillPayment::getRandomNumber(4);

        $checkout_parameters = array();
        $checkout_parameters['pay_to_email'] = get_option('skrill_merchant_account');
        $checkout_parameters['recipient_description'] = get_option('skrill_recipient_desc');
        $checkout_parameters['transaction_id'] = date('ymd').$this->wc_order->id.$date_time.$random_number;
        $checkout_parameters['return_url'] = $this->get_receipt_page_url();
        $status_url = $this->get_receipt_page_url('status_url');
        if (Configuration::is_url_secured($status_url)) {
            $checkout_parameters['status_url'] = $status_url;
        }
        $checkout_parameters['status_url2'] = 'mailto:'.get_option('skrill_merchant_email');
        $checkout_parameters['cancel_url'] = $this->get_receipt_page_url('cancelled');
        $checkout_parameters['language'] = $this->language;
        $checkout_parameters['logo_url'] = get_option('skrill_logo_url');
        $checkout_parameters['prepare_only'] = $this->prepare_only;
        $checkout_parameters['pay_from_email'] = $this->wc_order->billing_email;
        $checkout_parameters['firstname'] = $this->wc_order->billing_first_name;
        $checkout_parameters['lastname'] = $this->wc_order->billing_last_name;
        $checkout_parameters['address'] = $this->wc_order->billing_address_1.', '. $this->wc_order->billing_address_2;
        $checkout_parameters['postal_code'] = $this->wc_order->billing_postcode;
        $checkout_parameters['city'] = $this->wc_order->billing_city;
        $checkout_parameters['country'] = SkrillPayment::getCountryIso3ByIso2($this->wc_order->billing_country);
        $checkout_parameters['amount'] = $this->wc_order->order_total;
        $checkout_parameters['currency'] = get_woocommerce_currency();
        $checkout_parameters['detail1_description'] = "Order pay from " . $this->wc_order->billing_email;;

        if ($this->payment_method_id != 'skrill_flexible') {
            $checkout_parameters['payment_methods'] = $this->payment_method;
        }

        return $checkout_parameters;
    }

    /**
    * Get receipt page url
    * @param string $status
    * @return string
    */
    protected function get_receipt_page_url($status = false)
    {
        global $wp;

        $transaction_status = '';
        if ($status) {
            $transaction_status = '&'.$status.'='.true;
        }

        if (isset($wp->request)) {
            return home_url($wp->request).'/?key='.$_REQUEST['key'].$transaction_status;
        } else {
            return get_page_link().'&order-pay='.$_REQUEST['order-pay'].'&key='.$_REQUEST['key'].$transaction_status;
        }
    }

    /**
    * Process payment response from 'status_url'
    * process payment response and save to database
    */
    protected function process_status_response()
    {
        $payment_response = $this->get_payment_response();
        $skrill_settings['merchant_id'] = get_option('skrill_merchant_id');
        $skrill_settings['secret_word'] = get_option('skrill_secret_word');
        $is_fraud = SkrillPayment::isFraud($skrill_settings, $payment_response, $this->wc_order->order_total);
        if (!$is_fraud) {
            $transaction = Transactions_Model::get_data($this->wc_order->id);
            if (!$transaction) {
                Transactions_Model::save_payment_response(
                    $payment_response['transaction_id'], 
                    serialize($payment_response)
                );
            } elseif ($transaction['payment_status'] == $this->pending_status) {
                $this->update_order_status($payment_response['status']);
            }
        }
    }

    /**
    * Get payment response from 'status_url'
    * @return array
    */
    protected function get_payment_response()
    {
        $payment_response = array();
        foreach ($_REQUEST as $parameter => $value) {
            $parameter = strtolower($parameter);
            $payment_response[$parameter] = $value;
        }
        return $payment_response;
    }

    /**
    * Process payment response
    * process payment response from 'status_url' or 'status_trn'
    */
    protected function process_payment_response()
    {
        $transaction_id = $_REQUEST['transaction_id'];
        $this->add_customer_note();
        $is_transaction_id_exist = Transactions_Model::is_transaction_id_exist($transaction_id);
        $payment_response = '';
        if ($is_transaction_id_exist) {
            $payment_response = unserialize($is_transaction_id_exist['payment_response']);
            $transaction_log = $this->set_transaction_log($payment_response);
            Transactions_Model::update($transaction_id, $transaction_log);
        } else {
            $payment_parameters = array();
            $payment_parameters['email'] = get_option('skrill_merchant_account');
            $payment_parameters['password'] = get_option('skrill_api_passwd');
            $payment_parameters['action'] = 'status_trn';
            $payment_parameters['trn_id'] = $transaction_id;
            $is_payment_accepted = SkrillPayment::isPaymentAccepted($payment_parameters, $payment_response);
            if (!$is_payment_accepted) {
                $this->process_error_payment($this->wc_pending_status, 'ERROR_GENERAL_NORESPONSE');
            }
            $transaction_log = $this->set_transaction_log($payment_response);
            Transactions_Model::save($transaction_log);
        }
        $this->validate_payment_response($payment_response);
    }

    /**
    * Validate payment response
    * check fraud and status response
    * @param array $payment_response
    */
    protected function validate_payment_response($payment_response)
    {
        $version_tracker_parameters = $this->get_version_tracker_parameters();
        SkrillVersionTracker::sendVersionTracker($version_tracker_parameters);

        $skrill_settings['merchant_id'] = get_option('skrill_merchant_id');
        $skrill_settings['secret_word'] = get_option('skrill_secret_word');
        $is_fraud = SkrillPayment::isFraud($skrill_settings, $payment_response, $this->wc_order->order_total);
        if ($is_fraud) {
            $this->process_fraud_payment($payment_response);
        } else {
            if ($payment_response['status'] == $this->processed_status 
                || $payment_response['status'] == $this->pending_status) {
                $this->process_success_payment($payment_response);
            } else {
                $error_identifier = 'SKRILL_ERROR_99_GENERAL';
                if ($payment_response['status'] == $this->failed_status) {
                    $error_identifier = 
                        SkrillPayment::getSkrillErrorMapping($payment_response['failed_reason_code']);
                }
                $this->process_error_payment(
                    $this->wc_failed_status,
                    $error_identifier,
                    $payment_response['status']
                );
            }  
        }                
    }

    /**
    * Process fraud payment
    * refund the payment, update status and redirect to fraud page
    * @param array $payment_response
    */
    protected function process_fraud_payment($payment_response)
    {
        $order_id = $this->wc_order->id;
        $refund_parameters['email'] = get_option('skrill_merchant_account');
        $refund_parameters['password'] = get_option('skrill_api_passwd');
        $refund_parameters['mb_transaction_id'] = $payment_response['mb_transaction_id'];
        $refund_parameters['amount'] = $payment_response['amount'];

        $refund_response = SkrillPayment::doRefund($refund_parameters);
        $refund_status = (string) $refund_response->status;
        if ($refund_status == $this->processed_status
            || $refund_status == $this->pending_status) {
            $order_status = $this->wc_refunded_status;
            $payment_status = $this->refunded_status;
        } else {
            $order_status = $this->wc_failed_status;
            $payment_status = $this->refund_failed_status;
        }

        $this->process_error_payment($order_status, 'ERROR_GENERAL_FRAUD_DETECTION', $payment_status);
    }

    /**
    * Set transaction log
    * @param array $payment_response
    * @return array
    */
    protected function set_transaction_log($payment_response = false)
    {
        $transaction_log = array();
        $transaction_log['order_id'] = $this->wc_order->id;
        $transaction_log['payment_method_id'] = $this->payment_method_id;
        $transaction_log['amount'] = 
            $payment_response['amount'] ? $payment_response['amount'] : $this->wc_order->order_total;
        $transaction_log['refunded_amount'] = '0';
        $transaction_log['currency'] = get_woocommerce_currency();
        $transaction_log['customer_id'] = ($this->wc_order->user_id) ? $this->wc_order->user_id  : 0;
        
        if ($payment_response) {
            $transaction_log['transaction_id'] = $payment_response['transaction_id'];
            $transaction_log['mb_transaction_id'] = $payment_response['mb_transaction_id'];
            $transaction_log['payment_type'] = $this->get_payment_type($payment_response);
            $transaction_log['payment_status'] = $payment_response['status'];
            $transaction_log['additional_information'] = $this->set_additional_information($payment_response);
            $transaction_log['payment_response'] = serialize($payment_response);
        }

        return $transaction_log;
    }

    /**
    * Set additional information from payment response
    * add order_origin and order country
    * @param array $payment_response
    * @return array
    */
    protected function set_additional_information($payment_response)
    {
        $additional_information = '';
        if ($payment_response["ip_country"] && $payment_response['payment_instrument_country']) {
            $information['order_origin'] = $payment_response["ip_country"];
            $information['order_country'] = $payment_response["payment_instrument_country"];
            $additional_information = serialize($information);
        }
        return $additional_information;
    }

    /**
    * Get payment type from payment response
    * @param array $payment_response
    * @return string
    */
    protected function get_payment_type($payment_response)
    {
        if (!empty($payment_response['payment_type'])) {
            if ($payment_response['payment_type'] == 'NGP') {
                return 'OBT';
            } else {
                return $payment_response['payment_type'];
            }
        }
        return $this->payment_method;
    }
 
    /**
    * Add customer notes at order detail
    * add payment methods title
    */
    protected function add_customer_note()
    {
        $new_line = "\n";

        $payment_method_title = __('BACKEND_GENERAL_PAYMENT_METHOD', 'wc-skrill');
        $payment_comments  = $payment_method_title .
            ' : ' .
            Configuration::get_frontend_payment_method_title($this->payment_method_id) .
            $new_line;

        if ($this->wc_order->customer_note) {
            $this->wc_order->customer_note .= $new_line;
        }

        $this->wc_order->customer_note .= html_entity_decode($payment_comments, ENT_QUOTES, 'UTF-8');
        $order_notes = array(
                'ID'            => $this->wc_order->id ,
                'post_excerpt'  => $this->wc_order->customer_note
        );
        wp_update_post($order_notes);
    }

    /**
    * Get version tracker parameters
    * @return array
    */
    protected function get_version_tracker_parameters()
    {
        $version_parameters['transaction_mode'] = 'LIVE';
        $version_parameters['ip_address'] = $_SERVER['SERVER_ADDR'];
        $version_parameters['shop_version'] = WC()->version;
        $version_parameters['plugin_version'] = constant('SKRILL_PLUGIN_VERSION');
        $version_parameters['client'] = 'Skrill';
        $version_parameters['merchant_id'] = get_option('skrill_merchant_id');
        $version_parameters['shop_system'] = 'WOOCOMMERCE';
        $version_parameters['email'] = get_option('skrill_merchant_account');
        $version_parameters['shop_url'] = get_option('skrill_shop_url');

        return $version_parameters;
    }

    /**
    * Succes payment action
    * update order status, reduce order stock, clear the cart and redirect to success page
    * @param array $payment_response
    */
    protected function process_success_payment($payment_response)
    {

        $order_status = $this->wc_pending_status;
        if ($payment_response['status'] == $this->processed_status) {
            $order_status = $this->wc_payment_accepted;
        }

        $this->wc_order->update_status('wc-processing', 'order_note');
        $this->wc_order->update_status($order_status, 'order_note');

        if (!empty ( WC()->session->order_awaiting_payment ))
            unset( WC()->session->order_awaiting_payment );

        $this->wc_order->reduce_order_stock();

        WC()->cart->empty_cart();
        wp_safe_redirect($this->get_return_url($this->wc_order));
        exit();
    }

    /**
    * Error payment action
    * update order status, cancel the order and redirect to error page
    * @param string $order_status
    * @param string $error_identifier
    * @param string $payment_status
    */
    protected function process_error_payment($order_status, $error_identifier, $payment_status = false)
    {
        $error_translated = Configuration::translate_error_identifier($error_identifier);
        if ($payment_status) {
            Transactions_Model::update_payment_status($this->wc_order->id, $payment_status); 
        } else {
            $transaction_log = $this->set_transaction_log();
            $transaction_log['payment_status'] = NULL; 
            if ($order_status == $this->wc_pending_status) {
               $transaction_log['payment_status'] = $this->pending_status; 
            }
            Transactions_Model::save_error_transaction($transaction_log);       
        }

        $this->wc_order->cancel_order($error_translated);
        $this->wc_order->update_status($order_status, 'order_note');
        
        if (!empty ( WC()->session->order_awaiting_payment )) {
            unset( WC()->session->order_awaiting_payment );
        }

        if ($order_status == $this->wc_pending_status) {
            $this->wc_order->reduce_order_stock();
        }

        WC()->session->errors = $error_translated;
        wc_add_notice($error_translated, 'error');

        wp_safe_redirect(WC()->cart->get_checkout_url());
        exit(); 
    }

    /**
    * Update order status
    * @param string $payment_status
    */
    protected function update_order_status($payment_status)
    {
        $order_status = false;
        if ($payment_status == $this->processed_status) {
            $order_status = $this->wc_payment_accepted;
        } elseif ($payment_status == $this->pending_status) {
            $order_status = $this->wc_pending_status;
        } elseif ($payment_status == $this->failed_status) {
            $order_status = $this->wc_failed_status;
        }

        Transactions_Model::update_payment_status($this->wc_order->id, $payment_status);
        $this->wc_order->update_status($order_status, 'order_note');
    }

    /**
    * [BACKEND] Render additional information at backend
    * show payment title, payment status, order_origin, order_country and currency
    * from hook "woocommerce_admin_order_data_after_shipping_address"
    */
    public function render_additional_information()
    {
        if (!self::$added_meta_boxes) {
            $order_id = false;
            if (!empty($_REQUEST['post'])) {
                $order_id = $_REQUEST['post'];
            }

            $transaction =Transactions_Model::get_data($order_id);
            $is_skrill_payment = Configuration::is_skrill_payment($transaction['payment_method_id']);
            if ($is_skrill_payment) {
                $additional_information = '';
                if (isset($transaction['additional_information'])) {
                    $additional_information = 
                        $this->validate_additional_information($transaction['additional_information']);
                }

                $payment_status_identifier =
                    SkrillPayment::getTransactionStatus($transaction['payment_status']);

                wc_get_template('additional-information.php', 
                    array(
                        'payment_method_title' => 
                            Configuration::get_backend_payment_method_title($transaction['payment_method_id']),
                        'payment_type' => Configuration::get_backend_payment_method_title(
                                                'skrill_'.strtolower($transaction['payment_type'])
                                            ),
                        'payment_status' =>
                            Configuration::translate_transaction_status_identifier($payment_status_identifier),
                        'transaction' => $transaction,
                        'additional_information' => $additional_information
                    ), 
                    $this->plugin_directory . 'templates/admin/order/', 
                    $this->plugin_directory . 'templates/admin/order/'
                );
            }
            self::$added_meta_boxes = true;
        }
    }

    /**
    * [BACKEND] Validate additional information
    * add order origin and order country if exist
    * @param array $additional_information
    * @return array
    */
    protected function validate_additional_information($additional_information)
    {
        $additional_information = unserialize($additional_information);
        if (isset($additional_information['order_origin'])) {
            $additional_information['order_origin'] =
                WC()->countries->countries[$additional_information['order_origin']];
        }
        if (isset($additional_information['order_country'])) {
            $origin_iso2 = SkrillPayment::getCountryIso2ByIso3($additional_information['order_country']);
            $additional_information['order_country'] =
                WC()->countries->countries[$origin_iso2];  
        }

        return $additional_information;
    }

    /**
    * [BACKEND] Update order from backend
    * render update order button and process update order from gateway
    * from hook "woocommerce_admin_order_data_after_order_details"
    */
    public function update_order()
    {
        $post_type = false;
        if (isset($_GET['post_type'])) {
            $post_type = $_GET['post_type'];
        }

        if(!self::$updated_meta_boxes && $post_type != 'shop_order') {

            $order_id = $_REQUEST['post'];
            $transaction = Transactions_Model::get_data($order_id);
            $is_skrill_payment = Configuration::is_skrill_payment($transaction['payment_method_id']);
            if ($is_skrill_payment) {

                $this->wc_order = wc_get_order($order_id);
                $order_status = 'wc-'.$this->wc_order->get_status();
                if ($transaction['payment_status'] == $this->pending_status) {

                    $request_section = false;
                    if (isset($_REQUEST['section'])) {
                        $request_section = $_REQUEST['section'];
                    }

                    if ($order_id && $request_section == 'update-order') {
                        $this->process_updated_order($transaction);
                    }

                    $update_order_url =
                        get_admin_url() . 'post.php?post=' . $order_id . '&action=edit&section=update-order';

                    wc_get_template('update-order.php', 
                        array('update_order_url' => $update_order_url), 
                        $this->plugin_directory . 'templates/admin/order/', 
                        $this->plugin_directory . 'templates/admin/order/'
                    );
                }
            }
            self::$updated_meta_boxes = true;
        }
    }

    /**
    * [BACKEND] Process Updated Order
    * @param array $transaction
    */
    protected function process_updated_order($transaction)
    {
        $payment_status = $this->get_payment_status($transaction);
        if (isset($payment_status['status'])) {
            $this->update_order_status($payment_status['status']);
            if ($payment_status['status'] == $this->failed_status) {
                $this->increase_order_stock();
            }
            $redirect = get_admin_url() . 'post.php?post=' . $this->wc_order->id . '&action=edit';
            wp_safe_redirect($redirect);
            exit;
        } else {
            $error_message = __('ERROR_UPDATE_BACKEND', 'wc-skrill');
            $this->redirect_order_detail($error_message); 
        }  
    }

    /**
    * [BACKEND] Get payment status
    * get payemnt status from gateway
    * @param array $transaction
    * @return bool
    */
    protected function get_payment_status($transaction)
    {
        $parameters['action'] = 'status_trn';
        $parameters['email'] = get_option('skrill_merchant_account');
        $parameters['password'] = get_option('skrill_api_passwd');
        $parameters['mb_trn_id'] = $transaction['mb_transaction_id'];

        $payment_status = '';
        $is_payment_accepted = SkrillPayment::isPaymentAccepted($parameters, $payment_status);
        if ($is_payment_accepted) {
            return $payment_status;
        }
        return false;
    }

    /**
    * [BACKEND] Process refund from refund button at backend
    * from class WC_Payment_Gateway
    * @param int $order_id
    * @param float $amount
    * @param string $reason
    * @return boolean
    */
    public function process_refund($order_id, $amount = null, $reason = '')
    {
        $this->wc_order = wc_get_order($order_id);
        $transaction = Transactions_Model::get_data($order_id);
        $is_skrill_payment = Configuration::is_skrill_payment($transaction['payment_method_id']);

        if ($is_skrill_payment
            && $amount == $this->wc_order->order_total
            && ($transaction['payment_status'] == $this->processed_status
                || $transaction['payment_status'] == $this->refund_failed_status)) {
            $is_refunded_payment = $this->is_refunded_payment($transaction['mb_transaction_id']);
            return $is_refunded_payment;
        }
        return false;
    }

    /**
    * [BACKEND] Save Order Meta Boxes when change order status at backend
    * from hook "woocommerce_process_shop_order_meta"
    */
    public function save_order_meta()
    {
        if(!self::$saved_meta_boxes){

            $original_post_status = $_REQUEST['original_post_status'];

            if($original_post_status != 'auto-draft'){
                $order_id = $_REQUEST['post_ID'];
                $transaction = Transactions_Model::get_data($order_id);
                $is_skrill_payment = Configuration::is_skrill_payment($transaction['payment_method_id']);

                if ($is_skrill_payment){
                    $this->wc_order = wc_get_order($order_id);
                    $this->change_order_status();
                }
            }
            self::$saved_meta_boxes = true;
        }
    }

    /**
    * [BACKEND] Change Order Status at backend
    * validate order status and get update status from gateway
    */
    protected function change_order_status()
    {
        $order_id = $this->wc_order->id;
        $transaction =Transactions_Model::get_data($order_id);
        $original_post_status = $_REQUEST['original_post_status'];
        $order_post_status = $_REQUEST['order_status'];

        if ($original_post_status == $this->wc_pending_status
            && $transaction['payment_status'] == $this->pending_status
            && $order_post_status == $this->wc_payment_accepted) {
            $this->update_pending_status($order_post_status, $transaction);
        } elseif ($original_post_status == $this->wc_payment_accepted
            && ($transaction['payment_status'] == $this->processed_status
                || $transaction['payment_status'] == $this->refund_failed_status)
            && $order_post_status == $this->wc_refunded_status ) {
            $is_refunded_payment = $this->is_refunded_payment($transaction['mb_transaction_id']);
            if (!$is_refunded_payment) {
                $error_message = __('ERROR_GENERAL_REFUND_PAYMENT', 'wc-skrill');
                $this->redirect_order_detail($error_message); 
            }
            $this->increase_order_stock();
        } elseif ($original_post_status == $this->wc_pending_status
            && $transaction['payment_status'] == $this->pending_status
            && ($order_post_status == $this->wc_cancelled_status
                || $order_post_status == $this->wc_failed_status)) {
            $this->update_pending_status($order_post_status, $transaction);
        }
    }

    /**
    * [BACKEND] Update pending status
    * @param string $order_post_status
    * @param array $transaction
    */
    protected function update_pending_status($order_post_status, $transaction)
    {
        $payment_status = $this->get_payment_status($transaction);
        $transaction_status = $this->processed_status;
        if ($order_post_status != $this->wc_payment_accepted) {
            $transaction_status = $this->failed_status;
        }
        if ($payment_status && $payment_status['status'] == $transaction_status) {
            $this->update_order_status($payment_status['status']);
            if ($order_post_status != $this->wc_payment_accepted) {
                $this->increase_order_stock(); 
            }
        } else {
            $error_message = __('ERROR_UPDATE_BACKEND', 'wc-skrill');
            $this->redirect_order_detail($error_message); 
        }
    }

    /**
    * [BACKEND] Refund Payment at backend
    * refund payment and update status
    * @param string mb_transaction_id
    * @return bool
    */
    protected function is_refunded_payment($mb_transaction_id)
    {
        $order_id = $this->wc_order->id;
        $refund_parameters['email'] = get_option('skrill_merchant_account');
        $refund_parameters['password'] = get_option('skrill_api_passwd');
        $refund_parameters['mb_transaction_id'] = $mb_transaction_id;
        $refund_parameters['amount'] = $this->wc_order->order_total;

        $refund_response = SkrillPayment::doRefund($refund_parameters);
        $refund_status = (string) $refund_response->status;
        if ($refund_status == $this->processed_status 
            || $refund_status == $this->pending_status) {
            $this->wc_order->update_status($this->wc_refunded_status, 'order_note');
            Transactions_Model::update_refunded_status(
                $order_id, 
                $this->refunded_status, 
                $refund_response->mb_amount
            );
            return true;
        } else {
            Transactions_Model::update_refunded_status($order_id, $this->refund_failed_status);

        }
        return false;
    }

    /**
    * [BACKEND] Increase Order Stock
    * increase order stock and add note to order detail
    * @return bool
    */
    protected function increase_order_stock()
    {
        $items = $this->wc_order->get_items();
        foreach ($items as $item) {
            $wc_product = $this->wc_order->get_product_from_item($item);
            $is_managed_stock = $this->is_managed_stock($item['product_id']);
            if ($is_managed_stock) {
                $product_stock = $wc_product->get_stock_quantity();
                $wc_product->increase_stock($item['qty']);
                $order_note = 'Item #'.$item['product_id'].
                    ' stock increased from '.$product_stock.' to '.($product_stock + 1);
                $this->wc_order->add_order_note($order_note);  
            }    
        }
    }

    /**
    * [BACKEND] Is managed stock
    * @param int $product_id
    * @return bool
    */
    protected function is_managed_stock($product_id)
    {
        $is_managed_stock = get_post_meta($product_id, '_manage_stock', true);
        if ($is_managed_stock == 'yes') {
            return true;
        }
        return false;
    }

    /**
    * [BACKEND] Redirect Order Detail
    * redirect to order detail and show error message if exist
    * @param string $error_message
    */
    protected function redirect_order_detail($error_message = false)
    {
        $redirect = get_admin_url() . 'post.php?post=' . $this->wc_order->id . '&action=edit';
        if ($error_message ) {
            WC_Admin_Meta_Boxes::add_error($error_message);
        }
        wp_safe_redirect($redirect);
        exit;
    }

}
