<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Rince
 * Date: 13-10-19
 * Time: 下午6:58
 * To change this template use File | Settings | File Templates.
 */

namespace popgo;


class memcache_access {
    private $memcache_conn;
    private $memcache_host;
    private $memcache_port;
    private $memcache_useUnixConnect;

    function __construct($config){
        /**
         * @var $config \config\config
         **/
        $this->memcache_host = $config->memcache_host;
        $this->memcache_port = $config->memcache_port;
        $this->memcache_conn = new \Memcache;
        $this->memcache_conn->connect($this->memcache_host, $this->memcache_port);
    }

    public function get_memcache_status(){
        return $this->memcache_conn->get('aaa');
    }

}