<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Rince
 * Date: 13-10-19
 * Time: 下午4:48
 * To change this template use File | Settings | File Templates.
 */
if (preg_match('/program\-([a-f0-9]+)-.*.html/', $_SERVER["REQUEST_URI"], $matches)) {
    $_GET['hash'] = $matches[1];
    require 'torrent_info.php';
} else {
    return false;
}