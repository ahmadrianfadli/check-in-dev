<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    $route['default_controller']   = 'menu';
    $route['404_override']         = 'errors';
    $route['translate_uri_dashes'] = FALSE;

    $route['qris'] = 'Paymentgateway/generateqris';
    $route['status_qris'] = 'Paymentgateway/cekstatus';
?>
