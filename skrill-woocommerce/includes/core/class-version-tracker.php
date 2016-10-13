<?php
/**
* Skrill Version Tracker
*
* This class to send version tracker (every payments transaction)
* Copyright (c) Skrill
*
* @class       SkrillVersionTracker
* @package     Skrill/Classes
* @located at  /includes/core/
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class SkrillVersionTracker
{
    private static function _getVersionTrackerUrl()
    {
        $_versionTrackerUrl = 'http://api.dbserver.payreto.eu/v1/tracker';
        return $_versionTrackerUrl;
    }

    private static function _getResponseData($data, $url) {
        $ch = curl_init ();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec ($ch);
        if (curl_errno($ch)) {
            return false;
        }
        curl_close ($ch);
        return json_decode ($response, true);
    } 

    private static function _getVersionTrackerParameter($versionData)
    {
        $data = 'transaction_mode=' .$versionData['transaction_mode'].
                '&ip_address=' .$versionData['ip_address'].
                '&shop_version=' .$versionData['shop_version'].
                '&plugin_version=' .$versionData['plugin_version'].
                '&client=' .$versionData['client'].
                '&hash=' .md5($versionData['shop_version'].$versionData['plugin_version'].$versionData['client']);

        if ($versionData['shop_system'])
            $data .= '&shop_system=' .$versionData['shop_system'];
        if ($versionData['email'])
            $data .= '&email=' .$versionData['email'];
        if ($versionData['merchant_id'])
            $data .= '&merchant_id=' .$versionData['merchant_id'];
        if ($versionData['shop_url'])
            $data .= '&shop_url=' .$versionData['shop_url'];

        return $data;
    }

    public static function sendVersionTracker($versionData) {
        $postData = self::_getVersionTrackerParameter($versionData);
        $url = self::_getVersionTrackerUrl();
        return self::_getResponseData($postData, $url);
    }     
}