<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Rince
 * Date: 13-10-19
 * Time: 下午11:58
 * To change this template use File | Settings | File Templates.
 */

namespace popgo;


class Data_access {
    /**
     * 数据库连接
     **/

    private $mysql_conn;

    private $memcache_conn;

    private static $_instance = NULL;

	/**
	 * A textual description of the last query/get_row/get_var call
	 *
	 * @since 3.0.0
	 * @access public
	 *
	 * @param $db_user
	 * @param $db_password
	 * @param $db_name
	 * @param $db_host
	 * @param $memcache_host
	 * @param $memcache_port
	 *
	 * @internal param string $
	 */

    private function __construct($db_user, $db_password, $db_name, $db_host, $memcache_host, $memcache_port){
        $this->mysql_conn = new \mysqli($db_host, $db_user, $db_password, $db_name);
        $this->mysql_conn->options(MYSQLI_INIT_COMMAND, 'SET AUTOCOMMIT = 1');
        $this->mysql_conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);

        $this->mysql_conn->set_charset('utf8');

        $this->memcache_conn = new \ Memcache;
	    $this->memcache_conn->connect($memcache_host, $memcache_port);
    }

	public function __destruct() {
		return true;
	}

    public static function get_instance(){
	    // 单例模式
        if(is_null(self::$_instance)){
            self::$_instance = new Data_access(MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB_NAME, MYSQL_HOST, MEMCACHE_HOST, MEMCACHE_PORT);
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