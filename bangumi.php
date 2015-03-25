<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2015/2/17
 * Time: 12:39
 */

require_once('site-load.php');

$bangumi_list = \popgo\Bangumi::get_now_playing();

$header = new \popgo\PopgoSEO();

$page_data = array(
	'header' => $header,
	'bangumi_list' => $bangumi_list,
	'today' => jddayofweek(unixtojd(time()))
);

$smarty = new Smarty();

$smarty->debugging = true;

$smarty->caching = false;

$smarty->assign($page_data);

$smarty->display('bangumi.tpl');