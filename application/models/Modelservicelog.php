<?php
    class Modelservicelog extends CI_Model{

        function savelog($data){           
            $sql =   $this->db->insert("WEB_API_LOGS_OUT",$data);
            return $sql;
        }

    }

?>