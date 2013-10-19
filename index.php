<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Rince
 * Date: 13-10-19
 * Time: 下午4:42
 * To change this template use File | Settings | File Templates.
 * @var $global_config \config\config
 */

include_once 'init.php';
include_once 'include/user.php';

$this_user = new popgo\user(1);


print_r($this_user->getUserId());