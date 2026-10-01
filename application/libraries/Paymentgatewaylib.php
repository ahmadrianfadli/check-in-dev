<?php
class Paymentgatewaylib
{
    protected static $app;
    public static $privatekey;
    public static $publickey;
    public static $convertkey;
    public static $clientid;
    public static $secretid;
    public static $merchantid;
    public static $baseurltoken;
    public static $baseurltransaction;
    public static $namamerchant;
    public static $nmid;

    public static function init()
    {
        self::$app = &get_instance();
        self::$app->load->helper('curl');

        self::$privatekey         = PATHPRIVATE;
        self::$publickey          = PATHPUBLIC;
        self::$convertkey         = PATHCONVERT;
        self::$clientid           = BANKDKI_CLIENT_ID;
        self::$secretid           = BANKDKI_CLIENT_SECRET;
        self::$merchantid         = BANKDKI_MERCHANT_ID;
        self::$baseurltoken       = BANKDKI_BASE_URL_TOKEN;
        self::$baseurltransaction = BANKDKI_BASE_URL_TRANSACTION;
        self::$namamerchant       = RSUDPM_NAMA_MERCHANT;
        self::$nmid               = RSUDPM_NMID;
    }

    public static function oauth($signature, $timestamp)
    {
        $body = array("grantType" => "client_credentials", "additionalInfo" => self::$secretid);

        $header = array(
            "Content-Type: application/json",
            "X-SIGNATURE: " . $signature,
            "X-CLIENT-KEY: " . self::$clientid,
            "X-TIMESTAMP: " . $timestamp
        );

        $responsecurl = curl([
            'url' => self::$baseurltoken,
            'method' => "POST",
            'header' => $header,
            'body' => json_encode($body),
            'savelog' => true,
            'source' => "BANKDKI"
        ]);

        return json_decode($responsecurl, true)['accessToken'];
    }

    public static function generateqris($body,$oauth,$timestamp,$signature){
        $header = array(
            "Content-Type: application/json",
            "Authorization: Bearer ".$oauth,
            "X-TIMESTAMP: ".$timestamp,
            "X-SIGNATURE: ".$signature,
            "ORIGIN: https://rsudpasarminggu.jakarta.go.id/",
            "X-PARTNER-ID: ".self::$clientid,
            "X-EXTERNAL-ID: ".date('dmYHis'),
            "CHANNEL-ID: "."95231"
        );

        $responsecurl = curl([
        'url'     => self::$baseurltransaction.'qr-mpm-generate',
        'method'  => "POST",
        'header'  => $header,
        'body'    => json_encode($body),
        'savelog' => true,
        'source'  => "BANKDKI"
        ]);

        return json_decode($responsecurl,TRUE);
    }

    public static function cekstatus($body,$oauth,$timestamp,$signature){
        $header = array(
            "Content-Type: application/json",
            "Authorization: Bearer ".$oauth,
            "X-TIMESTAMP: ".$timestamp,
            "X-SIGNATURE: ".$signature,
            "ORIGIN: https://rsudpasarminggu.jakarta.go.id/",
            "X-PARTNER-ID: ".self::$clientid,
            "X-EXTERNAL-ID: ".date('dmYHis'),
            "CHANNEL-ID: "."95231"
        );

        $responsecurl = curl([
        'url'     => self::$baseurltransaction.'qr-mpm-status',
        'method'  => "POST",
        'header'  => $header,
        'body'    => json_encode($body),
        'savelog' => true,
        'source'  => "BANKDKI"
        ]);

        return json_decode($responsecurl,TRUE);
    }
}
