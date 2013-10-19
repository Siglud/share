<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Rince
 * Date: 13-10-19
 * Time: 下午11:58
 * To change this template use File | Settings | File Templates.
 */

namespace popgo;

use config;

include_once 'mysql_access.php';
include_once 'memcache_access.php';


class data_access {
    /**
     * @var $mysql_conn mysql_access
     * @var $memcache_conn memcache_access
     **/
    private $mysql_conn;
    private $memcache_conn;
    private static $_instance = NULL;

    private function __construct($config){
        $this->mysql_conn = new mysql_access($config);
        $this->memcache_conn = new memcache_access($config);
    }

    public static function get_instance(){
        if(is_null(self::$_instance)){
            include_once __DIR__ . '/../config/config.php';
            $config = new config\config();
            self::$_instance = new data_access($config);
        }
        return self::$_instance;
    }

    public function mysql(){
        return $this->mysql_conn;
    }

    public function memcache(){
        return $this->memcache_conn;
    }
}