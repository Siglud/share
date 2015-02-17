<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Rince
 * Date: 13-10-19
 * Time: 下午5:20
 * To change this template use File | Settings | File Templates.
 */
require_once('site-load.php');

$hash = $_GET['hash'];

$share = new \popgo\Share(null, null, $hash);

echo $share->get_share_name()->get_orig_text();