<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Rince
 * Date: 13-10-20
 * Time: 上午1:27
 * To change this template use File | Settings | File Templates.
 */

namespace popgo;

require_once 'data_access.php';

class base {
    protected $dao;

    function __construct(){
        $this->dao = data_access::get_instance();
    }
}