<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Rince
 * Date: 13-10-19
 * Time: 下午11:06
 * @var $config \config\config
 */

namespace popgo;


class mysql_access {
    private $mysql_conn;
    private $mysql_host;
    private $mysql_port;
    private $mysql_user;
    private $mysql_password;
    private $mysql_db;

    function __construct($config){
        /**
         * @var $config \config\config
        **/
        $this->mysql_host = $config->mysql_host;
        $this->mysql_port = $config->mysql_port;
        $this->mysql_user = $config->mysql_user;
        $this->mysql_password = $config->mysql_password;
        $this->mysql_db = $config->mysql_db;
        $this->mysql_conn = new \mysqli($this->mysql_host, $this->mysql_user, $this->mysql_password, $this->mysql_db, $this->mysql_port);
        if($this->mysql_conn->connect_error){
            printf('database connect failed: %s\n', $this->mysql_conn->connect_error);
            exit();
        }
    }

    private function query($sql){
        $sql = $this->mysql_conn->real_escape_string($sql);
        return $this->mysql_conn->real_query($sql);
    }

    public function get_result_content($sql){
        $this->query($sql);
        if($this->mysql_conn->field_count){
            $res = $this->mysql_conn->store_result();
            $return_array = array();
            $i = 0;
            while($row = $res->fetch_array(MYSQLI_ASSOC)){
                $return_array[$i] = $row;
                $i++;
            }
            $res->free();
            return $return_array;
        } else {
            return array();
        }
    }

}