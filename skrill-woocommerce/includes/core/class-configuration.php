<?php
/**
* General Configuration
*
* General functions for Plugin.
* Copyright (c) Skrill
*
* @class       Skrill_General_Functions
* @package     Skrill/Classes
* @located at  /includes/
*
*/

 if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
 }

class Configuration {

    /**
    * Get the plugin url.
    * @return string
    */
    public static function get_plugin_url()
    {
        $plugin_url = plugins_url('/', SKRILL_PLUGIN_FILE);
        return untrailingslashit($plugin_url);
    }

    /**
    * Get wordpress language
    * @return string
    */
    public static function get_language()
    {
        $ietf_language = get_bloginfo('language');
        $language = substr($ietf_language, 0, 2);
        return ($language == 'de') ? 'de' : 'en' ;
    }

    /**
    * validate administrator user
    * @return bool
    */
    public  function is_site_admin() {
        return in_array('administrator',  wp_get_current_user()->roles);
    }

    /**
    * set number format
    * @return bool
    */
    public static function set_number_format($number)
    {
        $number = (float) str_replace(',', '.', $number);
        return number_format($number, 2, '.', '');
    }

    /**
    * Validate skrill payment
    * @param string $payment_method_id
    * @return bool
    */
    public static function is_skrill_payment($payment_method_id)
    {
        if (strpos($payment_method_id, 'skrill') !== false) {
            return true;
        }
        return false;
    }

    /**
    * validate URL is secure or not
    * @param string $url
    * @return bool
    */
    public function is_url_secured($url)
    {
        return preg_match("@^https://@i", $url);
    }

    /**
    * Get frontend payment name.
    * @param string $payment_method_id
    * @return string
    */
    public static function get_frontend_payment_method_title($payment_method_id)
    {
        switch ($payment_method_id) {
            case 'skrill_flexible':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_FLEXIBLE', 'wc-skrill');
                break;
            case 'skrill_wlt':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_WLT', 'wc-skrill');
                break;
            case 'skrill_psc':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_PSC', 'wc-skrill');
                break;
            case 'skrill_acc':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_ACC', 'wc-skrill');
                break;
            case 'skrill_vsa':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_VSA', 'wc-skrill');
                break;
            case 'skrill_msc':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_MSC', 'wc-skrill');
                break;
            case 'skrill_vse':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_VSE', 'wc-skrill');
                break;
            case 'skrill_mae':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_MAE', 'wc-skrill');
                break;
            case 'skrill_amx':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_AMX', 'wc-skrill');
                break;
            case 'skrill_gcb':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_GCB', 'wc-skrill');
                break;
            case 'skrill_dnk':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_DNK', 'wc-skrill');
                break;
            case 'skrill_psp':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_PSP', 'wc-skrill');
                break;
            case 'skrill_csi':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_CSI', 'wc-skrill');
                break;
            case 'skrill_obt':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_OBT', 'wc-skrill');
                break;
            case 'skrill_gir':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_GIR', 'wc-skrill');
                break;
            case 'skrill_did':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_DID', 'wc-skrill');
                break;
            case 'skrill_sft':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_SFT', 'wc-skrill');
                break;
            case 'skrill_ebt':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_EBT', 'wc-skrill');
                break;
            case 'skrill_idl':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_IDL', 'wc-skrill');
                break;
            case 'skrill_npy':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_NPY', 'wc-skrill');
                break;
            case 'skrill_pli':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_PLI', 'wc-skrill');
                break;
            case 'skrill_pwy':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_PWY', 'wc-skrill');
                break;
            case 'skrill_epy':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_EPY', 'wc-skrill');
                break;
            case 'skrill_glu':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_GLU', 'wc-skrill');
                break;
            case 'skrill_ali':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_ALI', 'wc-skrill');
                break;
            case 'skrill_ntl':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_NTL', 'wc-skrill');
                break;
            case 'skrill_astropay':
                $payment_method_title =  __('SKRILL_FRONTEND_PM_ASTROPAY', 'wc-skrill');
                break;
        }
        return $payment_method_title;
    }

    /**
    * Get backend payment name.
    * @param string $payment_method_id
    * @return string
    */
    public static function get_backend_payment_method_title($payment_method_id)
    {
        switch ($payment_method_id) {
            case 'skrill_flexible':
                $payment_method_title =  __('SKRILL_BACKEND_PM_FLEXIBLE', 'wc-skrill');
                break;
            case 'skrill_wlt':
                $payment_method_title =  __('SKRILL_BACKEND_PM_WLT', 'wc-skrill');
                break;
            case 'skrill_psc':
                $payment_method_title =  __('SKRILL_BACKEND_PM_PSC', 'wc-skrill');
                break;
            case 'skrill_acc':
                $payment_method_title =  __('SKRILL_BACKEND_PM_ACC', 'wc-skrill');
                break;
            case 'skrill_vsa':
                $payment_method_title =  __('SKRILL_BACKEND_PM_VSA', 'wc-skrill');
                break;
            case 'skrill_msc':
                $payment_method_title =  __('SKRILL_BACKEND_PM_MSC', 'wc-skrill');
                break;
            case 'skrill_vse':
                $payment_method_title =  __('SKRILL_BACKEND_PM_VSE', 'wc-skrill');
                break;
            case 'skrill_mae':
                $payment_method_title =  __('SKRILL_BACKEND_PM_MAE', 'wc-skrill');
                break;
            case 'skrill_amx':
                $payment_method_title =  __('SKRILL_BACKEND_PM_AMX', 'wc-skrill');
                break;
            case 'skrill_gcb':
                $payment_method_title =  __('SKRILL_BACKEND_PM_GCB', 'wc-skrill');
                break;
            case 'skrill_dnk':
                $payment_method_title =  __('SKRILL_BACKEND_PM_DNK', 'wc-skrill');
                break;
            case 'skrill_psp':
                $payment_method_title =  __('SKRILL_BACKEND_PM_PSP', 'wc-skrill');
                break;
            case 'skrill_csi':
                $payment_method_title =  __('SKRILL_BACKEND_PM_CSI', 'wc-skrill');
                break;
            case 'skrill_obt':
                $payment_method_title =  __('SKRILL_BACKEND_PM_OBT', 'wc-skrill');
                break;
            case 'skrill_gir':
                $payment_method_title =  __('SKRILL_BACKEND_PM_GIR', 'wc-skrill');
                break;
            case 'skrill_did':
                $payment_method_title =  __('SKRILL_BACKEND_PM_DID', 'wc-skrill');
                break;
            case 'skrill_sft':
                $payment_method_title =  __('SKRILL_BACKEND_PM_SFT', 'wc-skrill');
                break;
            case 'skrill_ebt':
                $payment_method_title =  __('SKRILL_BACKEND_PM_EBT', 'wc-skrill');
                break;
            case 'skrill_idl':
                $payment_method_title =  __('SKRILL_BACKEND_PM_IDL', 'wc-skrill');
                break;
            case 'skrill_npy':
                $payment_method_title =  __('SKRILL_BACKEND_PM_NPY', 'wc-skrill');
                break;
            case 'skrill_pli':
                $payment_method_title =  __('SKRILL_BACKEND_PM_PLI', 'wc-skrill');
                break;
            case 'skrill_pwy':
                $payment_method_title =  __('SKRILL_BACKEND_PM_PWY', 'wc-skrill');
                break;
            case 'skrill_epy':
                $payment_method_title =  __('SKRILL_BACKEND_PM_EPY', 'wc-skrill');
                break;
            case 'skrill_glu':
                $payment_method_title =  __('SKRILL_BACKEND_PM_GLU', 'wc-skrill');
                break;
            case 'skrill_ali':
                $payment_method_title =  __('SKRILL_BACKEND_PM_ALI', 'wc-skrill');
                break;
            case 'skrill_ntl':
                $payment_method_title =  __('SKRILL_BACKEND_PM_NTL', 'wc-skrill');
                break;
            case 'skrill_astropay':
                $payment_method_title =  __('SKRILL_BACKEND_PM_ASTROPAY', 'wc-skrill');
                break;
        }
        return $payment_method_title;
    }

    /**
    * Translate error identifier.
    * @param string $error_identifier
    * @return string
    */
    public static function translate_error_identifier($error_identifier)
    {
        switch ($error_identifier) {
            case 'ERROR_GENERAL_NORESPONSE':
                $error = __('ERROR_GENERAL_NORESPONSE', 'wc-skrill');
                break;
            case 'ERROR_GENERAL_REDIRECT':
                $error = __('ERROR_GENERAL_REDIRECT', 'wc-skrill');
                break;
            case 'ERROR_GENERAL_FRAUD_DETECTION':
                $error = __('ERROR_GENERAL_FRAUD_DETECTION', 'wc-skrill');
                break;
            case 'ERROR_GENERAL_CANCEL':
                $error = __('ERROR_GENERAL_CANCEL', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_01':
                $error = __('SKRILL_ERROR_01', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_02':
                $error = __('SKRILL_ERROR_02', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_03':
                $error = __('SKRILL_ERROR_03', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_04':
                $error = __('SKRILL_ERROR_04', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_05':
                $error = __('SKRILL_ERROR_05', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_08':
                $error = __('SKRILL_ERROR_08', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_09':
                $error = __('SKRILL_ERROR_09', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_10':
                $error = __('SKRILL_ERROR_10', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_12':
                $error = __('SKRILL_ERROR_12', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_15':
                $error = __('SKRILL_ERROR_15', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_19':
                $error = __('SKRILL_ERROR_19', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_24':
                $error = __('SKRILL_ERROR_24', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_28':
                $error = __('SKRILL_ERROR_28', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_32':
                $error = __('SKRILL_ERROR_32', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_37':
                $error = __('SKRILL_ERROR_37', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_38':
                $error = __('SKRILL_ERROR_38', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_42':
                $error = __('SKRILL_ERROR_42', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_44':
                $error = __('SKRILL_ERROR_44', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_51':
                $error = __('SKRILL_ERROR_51', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_63':
                $error = __('SKRILL_ERROR_63', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_70':
                $error = __('SKRILL_ERROR_70', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_71':
                $error = __('SKRILL_ERROR_71', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_80':
                $error = __('SKRILL_ERROR_80', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_98':
                $error = __('SKRILL_ERROR_98', 'wc-skrill');
                break;
            case 'SKRILL_ERROR_99_GENERAL':
                $error = __('SKRILL_ERROR_99_GENERAL', 'wc-skrill');
                break;
            default:
                $error = __('SKRILL_ERROR_99_GENERAL', 'wc-skrill');
                break;
        }

        return $error;
    }

    /**
    * Translate Order Information Identifier.
    * @param string $error_identifier
    * @return string
    */
    public static function translate_transaction_status_identifier($status_identifier)
    {
        switch ($status_identifier) {
            case 'BACKEND_TT_PROCESSED':
                $status = __('BACKEND_TT_PROCESSED', 'wc-skrill');
                break;
            case 'BACKEND_TT_PENDING':
                $status = __('BACKEND_TT_PENDING', 'wc-skrill');
                break;
            case 'BACKEND_TT_CANCELLED':
                $status = __('BACKEND_TT_CANCELLED', 'wc-skrill');
                break;
            case 'BACKEND_TT_FAILED':
                $status = __('BACKEND_TT_FAILED', 'wc-skrill');
                break;
            case 'BACKEND_TT_CHARGEBACK':
                $status = __('BACKEND_TT_CHARGEBACK', 'wc-skrill');
                break;
            case 'BACKEND_TT_REFUNDED':
                $status = __('BACKEND_TT_REFUNDED', 'wc-skrill');
                break;
            case 'BACKEND_TT_REFUNDED_FAILED':
                $status = __('BACKEND_TT_REFUNDED_FAILED', 'wc-skrill');
                break;
            case 'BACKEND_TT_FRAUD':
                $status = __('BACKEND_TT_FRAUD', 'wc-skrill');
                break;
            case 'ERROR_GENERAL_ABANDONED_BYUSER':
                $status = __('ERROR_GENERAL_ABANDONED_BYUSER', 'wc-skrill');
                break;
            default:
                $status = __('ERROR_GENERAL_ABANDONED_BYUSER', 'wc-skrill');
                break;
        }
        return $status;
    }

}
