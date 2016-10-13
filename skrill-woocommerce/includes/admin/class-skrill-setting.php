<?php
/**
* Skrill Plugin Installation Process
*
* This class is used for Skrill Setting Tabs
* Copyright (c) Skrill
*
* @class       Skrill_Settings
* @package     Skrill/Classes
* @located at  /includes/admin/
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Skrill_Settings
{
    public static $skrill_tab = 'skrill_settings';
    public static $mandatory_field = array(
        'skrill_merchant_id', 
        'skrill_merchant_account', 
        'skrill_api_passwd',
        'skrill_secret_word'
    );

    public static function init()
    {
        if ( isset($_REQUEST['page']) 
            && $_REQUEST['page'] == 'wc-settings' 
            && isset($_REQUEST['tab']) 
            && $_REQUEST['tab'] == 'skrill_settings' ) {
            self::validate_skrill_setting();
        }

        add_filter('woocommerce_settings_tabs_array',array(__CLASS__, 'add_settings_tab'), 50);
        add_action('woocommerce_settings_tabs_skrill_settings', array( __CLASS__, 'add_settings_page'));
        add_action('woocommerce_update_options_skrill_settings', array(__CLASS__, 'update_settings'));
    }

    /**
    * Add Skrill Setting Tab
    * from hook "woocommerce_settings_tabs_array"
    * @return array
    */
    public static function add_settings_tab($woocommerce_tab)
    {
        $woocommerce_tab[ self::$skrill_tab ] = __('SKRILL_BACKEND_PM_SETTINGS', 'wc-skrill' );
        return $woocommerce_tab;
    }

    /**
    * Add Skrill Setting Page
    * from hook "woocommerce_settings_tabs_" {tab_name}
    *
    */
    public static function add_settings_page()
    {
        woocommerce_admin_fields(self::render_skrill_general_setting());
    }

    /**
    * Updates Skrill Settings Page
    * from hook "woocommerce_update_options_" {tab_name}
    */
    public static function update_settings()
    {
        woocommerce_update_options(self::render_skrill_general_setting());
    }

    /**
    * Render Skrill General Setting
    * @return string
    */
    public static function render_skrill_general_setting()
    {
        global $skrill_payments;

        $settings = apply_filters('woocommerce_' . self::$skrill_tab, array(
            array(
                'title' => 'Skrill ' . __('BACKEND_CH_GENERAL', 'wc-skrill' ),
                'id'    => 'skrill_general_settings',
                'desc'  => '',
                'type'  => 'title'
            ),
            array(
                'title' => __('SKRILL_BACKEND_MID', 'wc-skrill').' *',
                'id'    => 'skrill_merchant_id',
                'css'   => 'width:25em;',
                'type'  => 'text',
                'desc'  => '<br />'.__('SKRILL_BACKEND_TT_MID', 'wc-skrill')
            ),
            array(
                'title' => __('SKRILL_BACKEND_MERCHANT', 'wc-skrill').' *',
                'id'    => 'skrill_merchant_account',
                'css'   => 'width:25em;',
                'type'  => 'text',
                'desc'  => '<br />'.__('SKRILL_BACKEND_TT_MEMAIL', 'wc-skrill')
            ),
            array(
                'title' => __('SKRILL_BACKEND_RECIPIENT', 'wc-skrill'),
                'id'    => 'skrill_recipient_desc',
                'css'   => 'width:25em;',
                'type'  => 'text',
                'desc'  => '<br />'.__('SKRILL_BACKEND_TT_RECIPIENT', 'wc-skrill')
            ),
            array(
                'title' => __('SKRILL_BACKEND_LOGO', 'wc-skrill'),
                'id'    => 'skrill_logo_url',
                'css'   => 'width:25em;',
                'type'  => 'text',
                'desc'  => '<br />'.__('SKRILL_BACKEND_TT_LOGO', 'wc-skrill')
            ),
            array(
                'title' => __('SKRILL_BACKEND_SHOPURL', 'wc-skrill'),
                'id'    => 'skrill_shop_url',
                'css'   => 'width:25em;',
                'type'  => 'text'
            ),
            array(
                'title' => __('SKRILL_BACKEND_APIPASS', 'wc-skrill').' *',
                'id'    => 'skrill_api_passwd',
                'css'   => 'width:25em;',
                'type'  => 'password',
                'desc'  => '<br />'.__('SKRILL_BACKEND_APIPASS', 'wc-skrill')
            ),
            array(
                'title' => __('SKRILL_BACKEND_SECRETWORD', 'wc-skrill').' *',
                'id'    => 'skrill_secret_word',
                'css'   => 'width:25em;',
                'type'  => 'password',
                'desc'  => '<br />'.__('SKRILL_BACKEND_TT_SECRET', 'wc-skrill')
            ),
            array(
                'title' => __('SKRILL_BACKEND_DISPLAY', 'wc-skrill'),
                'id'    => 'skrill_display',
                'css'   => 'width:25em;',
                'type'  => 'select',
                'options' => array(
                    'IFRAME' => __('SKRILL_BACKEND_IFRAME', 'wc-skrill' ), 
                    'REDIRECT' => __('SKRILL_BACKEND_REDIRECT', 'wc-skrill')
                ),
                'default' => 'IFRAME'
            ),
            array(
                'title' => __('SKRILL_BACKEND_MERCHANTEMAIL', 'wc-skrill'),
                'id'    => 'skrill_merchant_email',
                'css'   => 'width:25em;',
                'type'  => 'text',
                'desc'  => '<br />'.__('SKRILL_BACKEND_TT_MERCHANTEMAIL', 'wc-skrill')
            ),
            array('type' => 'sectionend', 'id' => 'skrill_vendor_script')
        ));
        return apply_filters('woocommerce_' . self::$skrill_tab, $settings);
    }

    /**
    * Validate Skrill Setting
    * validate mandatory fiels and encrypt password
    */
    public static function validate_skrill_setting()
    {
        if (isset($_REQUEST['save'])) {
            $is_mandatory_field = self::is_mandatory_field($_REQUEST); 

            if ($_POST['skrill_api_passwd']) {
                $apiPass = get_option('skrill_api_passwd');
                if ($apiPass != $_POST['skrill_api_passwd']) {
                    $_POST['skrill_api_passwd'] = md5($_POST['skrill_api_passwd']);
                }

            }
            if ($_POST['skrill_secret_word']) {
                $secretWord = get_option('skrill_secret_word');
                if ($secretWord != $_POST['skrill_secret_word']) {
                    $_POST['skrill_secret_word'] = md5($_POST['skrill_secret_word']);
                }
            }

            if (!$is_mandatory_field) {
                $redirect = get_admin_url() . 'admin.php?' . http_build_query($_GET);
                $redirect = remove_query_arg('save');
                $error_message = __('ERROR_GENERAL_MANDATORY', 'wc-skrill');
                $redirect = add_query_arg('wc_error', urlencode(esc_attr($error_message)), $redirect);
                wp_safe_redirect($redirect);
                exit();
            }
        }
    }

    /**
    * Validate mandatory fields from skrill settings
    * @param array $fields
    * @return bool
    */
    public static function is_mandatory_field($fields)
    {
        foreach($fields as $field_name => $field_value) {

            if (in_array($field_name, self::$mandatory_field)) {
                if(trim($field_value) == '')
                    return false;
            }

        }

        return true;
    }  
}

Skrill_Settings::init();

?>
