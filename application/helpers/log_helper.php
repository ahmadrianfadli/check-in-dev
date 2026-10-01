<?php

    function savelog($data){
        $ci = &get_instance();
        $ci->load->model('Modelservicelog', 'mlog');

        $enablelog = $data['enablelog'];

        if($enablelog){
            $servicelog['REQUEST_ID']  = round(microtime(true) * 1000);
            $servicelog['REQ_URL']     = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $servicelog['REQ_HEADER']  = json_encode(getallheaders());
            $servicelog['REQ_METHOD']  = $_SERVER['REQUEST_METHOD'];
            $servicelog['IP_ADDRESS']  = $_SERVER['REMOTE_ADDR'];
            $servicelog['SOURCE_CODE'] = $data['source'];
            $servicelog['REQ_BODY']    = $data['body'];
            $servicelog['RESPONSE']    = json_encode($data['response']);
            $servicelog['CODE']        = $data['code'];
            $servicelog['CREATED_BY']  = $data['createdby'];
            $servicelog['DIRECTION']   = $data['direction'];
    
            $ci->mlog->savelog($servicelog);
        }
    }

?>