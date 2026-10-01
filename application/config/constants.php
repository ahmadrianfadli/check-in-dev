<?php
defined('BASEPATH') or exit('No direct script access allowed');
defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', TRUE);
defined('FILE_READ_MODE')  or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  or define('DIR_WRITE_MODE', 0755);
defined('FOPEN_READ')                           or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb');        // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b');  // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');
defined('EXIT_SUCCESS')        or define('EXIT_SUCCESS', 0);         // no errors
defined('EXIT_ERROR')          or define('EXIT_ERROR', 1);           // generic error
defined('EXIT_CONFIG')         or define('EXIT_CONFIG', 3);          // configuration error
defined('EXIT_UNKNOWN_FILE')   or define('EXIT_UNKNOWN_FILE', 4);    // file not found
defined('EXIT_UNKNOWN_CLASS')  or define('EXIT_UNKNOWN_CLASS', 5);   // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6);  // unknown class member
defined('EXIT_USER_INPUT')     or define('EXIT_USER_INPUT', 7);      // invalid user input
defined('EXIT_DATABASE')       or define('EXIT_DATABASE', 8);        // database error
defined('EXIT__AUTO_MIN')      or define('EXIT__AUTO_MIN', 9);       // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      or define('EXIT__AUTO_MAX', 125);     // highest automatically-assigned error code


$env = 'production';

switch ($env) {

    case 'production':
        define('BPJS_CONSID', '11148');
        define('BPJS_SECRET_KEY', 'NSvl8ORbIx7nRcVGrkSF');
        define('BPJS_USER_KEY', 'f76a89365f8c920aa05712ef77e0ea51');
        define('BPJS_BASE_URL_VCLAIM', 'https://apijkn.bpjs-kesehatan.go.id/vclaim-rest');
        define('RSUDPM_API_BASE_URL', 'http://simrs.rsudpm.local/apirsudpm/index.php/api');

        define('PATHPRIVATE', FCPATH . "dist/rsa/rsa_private_key.pem");
        define('PATHPUBLIC', FCPATH . "dist/rsa/rsa_public_key.pem");
        define('PATHCONVERT', FCPATH . "dist/rsa/pkcs8_rsa_private_key.pem");

        define('BANKDKI_BASE_URL_TOKEN', "https://snap.bankdki.co.id/v1.0/access-token/b2b");
        define('BANKDKI_BASE_URL_TRANSACTION', "https://snap.bankdki.co.id/v1.0/qr/");
        define('BANKDKI_MERCHANT_ID', "100023624");
        define('BANKDKI_CLIENT_ID', "rsudpasarminggu");
        define('BANKDKI_CLIENT_SECRET', "BOImYi2yBQ1UsvGQK9mBdezCwKZLGfMr");

        define('RSUDPM_NAMA_MERCHANT', 'RSUD PASAR MINGGU');
        define('RSUDPM_NMID', 'ID2024329869836');
		define('ESIGN_API_BASE_URL', 'http://192.168.200.85/apirsudpm/index.php');

        break;

    case 'development':
        define('BPJS_CONSID', '11148');
        define('BPJS_SECRET_KEY', '1uADAB4568');
        define('BPJS_USER_KEY', 'f76a89365f8c920aa05712ef77e0ea51');
        define('BPJS_BASE_URL_VCLAIM', 'https://apijkn.bpjs-kesehatan.go.id/vclaim-rest');
        define('RSUDPM_API_BASE_URL', 'http://localhost/apirsudpmdev/api');

        define('PATHPRIVATE', FCPATH . "dist/rsa/rsa_private_key.pem");
        define('PATHPUBLIC', FCPATH . "dist/rsa/rsa_public_key.pem");
        define('PATHCONVERT', FCPATH . "dist/rsa/pkcs8_rsa_private_key.pem");

        define('BANKDKI_BASE_URL_TOKEN', "https://dkilinksnap.bankdki.co.id/v1.0/access-token/b2b");
        define('BANKDKI_BASE_URL_TRANSACTION', "https://dkilinksnap.bankdki.co.id/v1.0/qr/");
        define('BANKDKI_MERCHANT_ID', "100000275");
        define('BANKDKI_CLIENT_ID', "rsudpasarminggudevelopment");
        define('BANKDKI_CLIENT_SECRET', "lgVYa40dNQMziHRs4hmRHrAjkkhaeVhn");

        define('RSUDPM_NAMA_MERCHANT', 'RSUD PASAR MINGGU');
        define('RSUDPM_NMID', 'ID240325083345194');
		define('ESIGN_API_BASE_URL', 'http://10.12.120.85/apirsudpm/index.php');

        break;
}
