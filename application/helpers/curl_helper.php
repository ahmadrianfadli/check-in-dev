<?php

function curl($config)
{
    $ci = &get_instance();
    $ci->load->model('Modelservicelog', 'mlog');

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL            => $config['url'],
        CURLOPT_CUSTOMREQUEST  => $config['method'],
        CURLOPT_POSTFIELDS     => $config['body'],
        CURLOPT_HTTPHEADER     => $config['header'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 60,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0
    ));

    $response        = curl_exec($curl); 

    /* return json_encode($response);
    die(); */
    /* var_dump($config);
    die(); */
    $response_header       = json_encode(curl_getinfo($curl));
    $request_headers       = json_encode(function_exists('apache_request_headers') ? apache_request_headers() : array());
    $request_url           = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
    $response_status       = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $appconnect_time_us    = !empty(curl_getinfo($curl)['appconnect_time_us']) ? json_encode(curl_getinfo($curl)['appconnect_time_us']) : 0;
    $connect_time_us       = !empty(curl_getinfo($curl)['connect_time_us']) ? json_encode(curl_getinfo($curl)['connect_time_us']) : 0;
    $namelookup_time_us    = !empty(curl_getinfo($curl)['namelookup_time_us']) ? json_encode(curl_getinfo($curl)['namelookup_time_us']) : 0;
    $pretransfer_time_us   = !empty(curl_getinfo($curl)['pretransfer_time_us']) ? json_encode(curl_getinfo($curl)['pretransfer_time_us']) : 0;
    $redirect_time_us      = !empty(curl_getinfo($curl)['redirect_time_us']) ? json_encode(curl_getinfo($curl)['redirect_time_us']) : 0;
    $starttransfer_time_us = !empty(curl_getinfo($curl)['starttransfer_time_us']) ? json_encode(curl_getinfo($curl)['starttransfer_time_us']) : 0;
    $total_time_us         = !empty(curl_getinfo($curl)['total_time_us']) ? json_encode(curl_getinfo($curl)['total_time_us']) : 0;

    // echo "Hasil print_r:\n";
    // echo '<pre>';
    // print_r(curl_getinfo($curl));
    // echo '</pre>';

    $headers = "";

    $headers .= "POST " . $config['url'] . "\n";
    $headers .= $response_status . "\n";
    $headers .= $total_time_us . " ms\n";
    $headers .= str_replace(['"', '{', '}', ',', '[', ']'], ['', '', '', "\n", '', ''], json_encode($config['header']));
    $headers .= "\n";
    $headers .= $config['body'];

    curl_close($curl);

    if ($config['savelog']) {
        $requestlog = [
            'REQUEST_ID'            => round(microtime(true) * 1000),
            'REQUEST_METHOD'        => $_SERVER['REQUEST_METHOD'],
            'REQUEST_URL'           => $request_url,
            'REQUEST_HEADERS'       => $headers,
            'REQUEST_BODY'          => $config['body'],
            //'USER_AGENT'            => $_SERVER['HTTP_USER_AGENT'],
            'REMOTE_ADDRESS'        => $_SERVER['REMOTE_ADDR'],
            'RESPONSE_STATUS'       => $response_status,
            'RESPONSE_HEADERS'      => $response_header,
            'RESPONSE_BODY'         => $response,
            'APPCONNECT_TIME_US'    => $appconnect_time_us,
            'CONNECT_TIME_US'       => $connect_time_us,
            'NAMELOOKUP_TIME_US'    => $namelookup_time_us,
            'PRETRANSFER_TIME_US'   => $pretransfer_time_us,
            'REDIRECT_TIME_US'      => $redirect_time_us,
            'STARTTRANSFER_TIME_US' => $starttransfer_time_us,
            'TOTAL_TIME_US'         => $total_time_us,
            'SOURCE'                => $config['source']
        ];
    } else {
        $requestlog = [
            'REQUEST_ID'            => round(microtime(true) * 1000),
            'REQUEST_METHOD'        => $_SERVER['REQUEST_METHOD'],
            'REQUEST_URL'           => $request_url,
            'REQUEST_HEADERS'       => $headers,
            'REQUEST_BODY'          => "",
            //'USER_AGENT'            => $_SERVER['HTTP_USER_AGENT'],
            'REMOTE_ADDRESS'        => $_SERVER['REMOTE_ADDR'],
            'RESPONSE_STATUS'       => $response_status,
            'RESPONSE_HEADERS'      => $response_header,
            'RESPONSE_BODY'         => "",
            'APPCONNECT_TIME_US'    => $appconnect_time_us,
            'CONNECT_TIME_US'       => $connect_time_us,
            'NAMELOOKUP_TIME_US'    => $namelookup_time_us,
            'PRETRANSFER_TIME_US'   => $pretransfer_time_us,
            'REDIRECT_TIME_US'      => $redirect_time_us,
            'STARTTRANSFER_TIME_US' => $starttransfer_time_us,
            'TOTAL_TIME_US'         => $total_time_us,
            'SOURCE'                => $config['source']
        ];
    }


    // $ci->mlog->savelog($requestlog);
    return $response;
}

function getJWTToken()
{
    $retVal = [
        'success' => false,
        'token' => null,
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => RSUDPM_API_BASE_URL . '/auth/jwt',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode([
            'username' => 'epasien',
            'password' => 'Ji3,xU0*'
        ]),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json'
        ],
    ]);

    /* execute request */
    $result = curl_exec($ch);

    $curlErrorNo = curl_errno($ch);

    /* close cURL resource */
    curl_close($ch);

    if ($curlErrorNo === 0) {
        if (!empty($result)) {
            $resultJSON = json_decode($result, true);

            if (json_last_error() == JSON_ERROR_NONE) {
                if ($resultJSON['metadata']['responCode'] == 200) {
                    $retVal['success'] = true;
                    $retVal['token'] = $resultJSON['response']['token'];
                }
            }
        }
    }

    return $retVal;
}
function execCurl($config)
{
    $ci = &get_instance();
    $ci->load->model('Modelapilog', 'mlog');

    $retVal = [
        'success' => false,
        'result' => null,
        'code' => 0,
        'errorMessage' => '',
        'url' => '',
        'rawRequestHeader' => '',
        'rawRequestBody' => '',
        'rawResponse' => '',
    ];

    if (!isset($config['url'])) {
        throw new Exception('Please provide URL in config');
    }

    $retVal['url'] = $config['url'];

    $curlHeaders = [];

    if (isset($config['headers'])) {
        $curlHeaders = $config['headers'];
    }

    if (isset($config['preloadConfig'])) {
        if (isset($config['preloadConfig']['contentType'])) {
            switch ($config['preloadConfig']['contentType']) {
                case 'json':
                    $curlHeaders['Content-Type'] = 'application/json';
                    break;
            }
        }
    }

    $sendHeaders = [];

    $timeout = isset($config['timeout']) ? $config['timeout'] : 60;
    $method = isset($config['method']) ? strtoupper($config['method']) : 'GET';
    $body = isset($config['body']) ? $config['body'] : null;

    if (count($curlHeaders)) {
        foreach ($curlHeaders as $key => $val) {
            $sendHeaders[] = "{$key}: {$val}";
        }
    }

    if (isset($config['showUrl'])) {
        if ($config['showUrl'] == true) {
            $retVal['url'] = $config['url'];
        }
    }
    if (isset($config['showURL'])) {
        if ($config['showURL'] == true) {
            $retVal['url'] = $config['url'];
        }
    }
    if (isset($config['showRawRequestHeader'])) {
        if ($config['showRawRequestHeader'] == true) {
            $retVal['rawRequestHeader'] = $sendHeaders;
        }
    }
    if (isset($config['showRawRequestBody'])) {
        if ($config['showRawRequestBody'] == true) {
            $retVal['rawRequestBody'] = $body;
        }
    }

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $config['url'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => $sendHeaders,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    /* execute request */
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    $curlErrorNo = curl_errno($ch);
    $curlErrorMsg = curl_error($ch);

    if (isset($config['showRawResponse'])) {
        if ($config['showRawResponse'] === true) {
            $retVal['rawResponse'] = $result;
        }
    }

    if ($curlErrorNo === 0) {
        $retVal['success'] = true;

        if (isset($config['log'])) {
            if (isset($config['log']['useLog'])) {
                $requestLog = [
                    'reqUniqueID' => round(microtime(true) * 1000),
                    'source' => isset($config['log']['source']) ? $config['log']['source'] : '',
                    'reqURL' => $config['url'],
                    'reqMethod' => $method,
                    'reqHeader' => json_encode($curlHeaders, JSON_PRETTY_PRINT),
                    'reqBody' => $body,
                    'response' => $result,
                    'code' => $httpCode,
                    'ipAddress' => '127.0.0.1',
                    'createdby' => isset($config['log']['createdBy']) ? $config['log']['createdBy'] : '',
                    'direction' => 'OUT',
                ];

                $ci->mlog->saveLogV2($requestLog);
            }
        }

        if (!empty($result)) {
            if (isset($config['resultType'])) {
                switch (strtolower($config['resultType'])) {
                    case 'json':
                        $resultJSON = json_decode($result, true);

                        if (json_last_error() == JSON_ERROR_NONE) {
                            $retVal['result'] = $resultJSON;
                        } else {
                            $retVal['code'] = 1;
                            $retVal['success'] = false;
                            $retVal['errorMessage'] = 'Error parsing JSON response';
                        }

                        break;
                    default:
                        $retVal['result'] = $result;
                        break;
                }
            } else {
                $retVal['result'] = $result;
            }
        }
    } else {
        switch ($curlErrorNo) {
            case 7:
                $retVal['code'] = 2;
                $retVal['errorMessage'] = "Couldn't connect";
                break;
            case 28:
                $retVal['code'] = 3;
                $retVal['errorMessage'] = 'Timeout';
                break;
            case 35:
                $retVal['code'] = 4;
                $retVal['errorMessage'] = 'SSL Connect Error';
                break;
            case 47:
                $retVal['code'] = 5;
                $retVal['errorMessage'] = 'Too many redirects';
                break;
            default:
                $retVal['code'] = 'CURL_ERROR_' . $curlErrorNo;
                $retVal['errorMessage'] = $curlErrorMsg;
                break;
        }
    }

    /* close cURL resource */
    curl_close($ch);

    return $retVal;
}
function execCurlWithToken($config)
{
    $try = 0;
    $successRequest = false;
    $tokenString = '';
    $retVal = [
        'success' => false,
        'result' => null,
        'code' => 0,
        'errorMessage' => '',
        'rawResponse' => ''
    ];

    $tokenResult = getJWTToken();

    if ($tokenResult['success']) {
        $tokenString = $tokenResult['token'];

        $config['headers']['X-Token'] = $tokenString;

        $retVal = execCurl($config);
    } else {
        $retVal['code'] = 5;
        $retVal['errorMessage'] = 'Could not get token';
    }

    return $retVal;
}
function getBpjsJwtToken()
{
    $retVal = [
        'success' => false,
        'token' => null,
    ];

    $response = execCurl([
        'url' => 'http://simrs.rsudpm.local/apirsudpm/index.php/mobile-jkn/generate-token',
        'method' => 'GET',
        'resultType' => 'json',
        'headers' => [
            'x-username' => 'bpjs',
            'x-password' => '!12345678*'
        ],
        'preloadConfig' => [
            'contentType' => 'json'
        ],
        'showRawResponse' => true,
    ]);

    if ($response['success']) {
        if ($response['result']['metadata']['code'] == 200) {
            $retVal['token'] = $response['result']['response']['token'];
            $retVal['success'] = true;
        }
    }

    return $retVal;
}

function execCurlBpjsWithToken($config)
{
    $try = 0;
    $successRequest = false;
    $tokenString = '';
    $retVal = [
        'success' => false,
        'result' => null,
        'code' => 0,
        'errorMessage' => '',
        'rawResponse' => ''
    ];

    $tokenResult = getBpjsJwtToken();

    //echo '<pre>TokenResult: ' . print_r($tokenResult, true) . '</pre>';


    if ($tokenResult['success']) {
        $tokenString = $tokenResult['token'];

        //echo '<br>Token: ' . $tokenResult;

        $config['headers']['x-username'] = 'bpjs';
        $config['headers']['x-token'] = $tokenString;

        $retVal = execCurl($config);
    } else {
        $retVal['code'] = 5;
        $retVal['errorMessage'] = 'Could not get token';
    }

    return $retVal;
}
