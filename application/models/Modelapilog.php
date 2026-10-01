<?php
    class Modelapilog extends CI_Model{

        function savelog($data){
            $query = "
                        INSERT INTO WEB_MA_API_REQUEST_LOG
                        (REQUEST_ID, SOURCE_CODE, REQ_URL, REQ_METHOD, REQ_HEADER, REQ_BODY, RESPONSE, CODE, STATUS, IP_ADDRESS, CREATED_BY, DIRECTION) 
                        VALUES
                        (?, ?, ?, ?, ?, ?, ?, ?, '0', ?, ?, ?)
                    ";

            $result = $this->db->query($query, [
                $data['reqUniqueID'],
                $data['source'],
                $data['reqURL'],
                $data['reqMethod'],
                $data['reqHeader'],
                $data['reqBody'],
                $data['response'],
                $data['code'],
                $data['ipAddress'],
                $data['createdby'],
                $data['direction'],
            ]);
            return $result;
        }
    }

?>