<?php
date_default_timezone_set("Asia/Jakarta");
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

require APPPATH . '/libraries/REST_Controller.php';
// require FCPATH.'/plugins/jaspersoft/rest-client/autoload.dist.php';

// include FCPATH."plugins/phpqrcode/qrlib.php";

use Restserver\Libraries\REST_Controller;

// 1. 


class Paymentgateway extends REST_Controller
{
    public static $privatekey;
    public static $publickey;
    public static $convertkey;
    public static $clientid;
    public static $secretid;
    public static $merchantid;
    public static $baseurltoken;
    public static $baseurltransaction;

    public function __construct()
    {
        parent::__construct();
        self::$privatekey         = PATHPRIVATE;
        self::$publickey          = PATHPUBLIC;
        self::$convertkey         = PATHCONVERT;
        self::$clientid           = BANKDKI_CLIENT_ID;
        self::$secretid           = BANKDKI_CLIENT_SECRET;
        self::$merchantid         = BANKDKI_MERCHANT_ID;
        self::$baseurltoken       = BANKDKI_BASE_URL_TOKEN;
        self::$baseurltransaction = BANKDKI_BASE_URL_TRANSACTION;
       
        Paymentgatewaylib::init();
    }

    private function generateTimestamp()
    {
        // Set the client's local time to Jakarta timezone
        $clientLocalTime = new DateTime('now', new DateTimeZone('Asia/Jakarta'));

        // Format the timestamp
        $timestamp = $clientLocalTime->format('Y-m-d\TH:i:sP');

        return $timestamp;
    }

    private function validdateqris()
    {
        $clientLocalTime = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $clientLocalTime->add(new DateInterval('PT5M'));
        $timestamp = $clientLocalTime->format('Y-m-d\TH:i:sP');
        return $timestamp;
    }

    private function generateXSignature($clientID, $timestamp, $privateKeyPath)
    {
        $stringtosign = $clientID . '|' . $timestamp;
        $privateKey = openssl_pkey_get_private(file_get_contents($privateKeyPath));

        if ($privateKey === false) {
            echo 'gagal';
            return;
        }

        $signature = '';
        openssl_sign($stringtosign, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        $base64signature = base64_encode($signature);
        openssl_free_key($privateKey);

        return $base64signature;
    }

    private function generateSymetricSignature($clientSecret, $httpMethod, $endpointUrl, $accessToken, $requestBody, $timeStamp)
    {
        $requestBody       = json_encode($requestBody, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $minifyrequestBody = preg_replace('/\s+/', ' ', $requestBody);
        $hashminifiedbody  = hash('sha256', $minifyrequestBody);

        // Compose the string to sign
        $stringToSign = $httpMethod . ":" . $endpointUrl . ":" . $accessToken . ":" . $hashminifiedbody . ":" . $timeStamp;

        $signature  = hash_hmac('sha512', $stringToSign, $clientSecret, true);
        $xsignature = base64_encode($signature);

        return $xsignature;
    }

    private function getXSignature_get()
    {
        $clientID = self::$clientid;
        $timestamp = $this->generateTimestamp();
        $privateKeyPath = self::$convertkey;

        $signature = $this->generateXSignature($clientID, $timestamp, $privateKeyPath);
        return $signature;
    }

    public function oauth_post()
    {
        $timestamp  = $this->generateTimestamp();
        $signature = $this->getXSignature_get();
        $res = Paymentgatewaylib::oauth($signature, $timestamp);
        $this->response($res, REST_Controller::HTTP_OK);
    }

    public function generateqris_post()
    {
        $timestamp = $this->generateTimestamp();
        $validdate = $this->validdateqris();
        $xSignature = $this->getXSignature_get();
        $xMerchantId = self::$merchantid;
        date_default_timezone_set("Asia/Jakarta");
        $data = json_decode($this->input->raw_input_stream, true);
        $body['partnerReferenceNo']          = date('dmYHis');
        $body['amount']['value']             = $data['nominal'];
        $body['amount']['currency']          = "IDR";
        $body['feeAmount']['value']          = "0.00";
        $body['feeAmount']['currency']       = "IDR";
        $body['merchantId']                  = $xMerchantId;
        $body['subMerchantId']               = "310928924949487";
        $body['storeId']                     = "AADC";
        $body['terminalId']                  = "8766541234";
        $body['validityPeriod']              = $validdate;
        $body['additionalInfo']['qr_type']   = "dynamic";
        $body['additionalInfo']['deviceId']  = "12345679237";
        $body['additionalInfo']['channel']   = "Mobile POS";
        $body['additionalInfo']['tip_type']  = "02";
        $body['additionalInfo']['tip_value'] = "0";

        $oauth             = Paymentgatewaylib::oauth($xSignature, $timestamp);
        $symetricSignature = $this->generateSymetricSignature(self::$secretid, "POST", "/v1.0/qr/qr-mpm-generate", $oauth, $body, $timestamp);

        $response = Paymentgatewaylib::generateqris($body, $oauth, $timestamp, $symetricSignature);

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function cekstatus_post()
    {
        $timestamp = $this->generateTimestamp();
        $xSignature = $this->getXSignature_get();
        $xMerchantId = self::$merchantid;

        $data = json_decode($this->input->raw_input_stream, true);

        $body['originalReferenceNo'] = $data['refnumber'];
        $body['serviceCode'] = "47";
        $body['transactionDate'] = $data['tgljam'];
        $body['amount']['value'] = $data['nominal'];
        $body['amount']['currency'] = "IDR";
        $body['additionalInfo']['deviceId'] = "12345679237";
        $body['additionalInfo']['channel'] = "Mobile POS";
        $body['additionalInfo']['bill_number'] = $data['tagihanid'];
        $body['additionalInfo']['merchantId'] = $xMerchantId;

        /* echo json_encode($body);
        die(); */
        $oauth             = Paymentgatewaylib::oauth($xSignature, $timestamp);
        $symetricSignature = $this->generateSymetricSignature(self::$secretid, "POST", "/v1.0/qr/qr-mpm-status", $oauth, $body, $timestamp);

        $response = Paymentgatewaylib::cekstatus($body, $oauth, $timestamp, $symetricSignature);

        $this->response($response, REST_Controller::HTTP_OK);
    }
}
