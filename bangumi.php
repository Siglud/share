<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2015/2/17
 * Time: 12:39
 */

require_once('site-load.php');

$bangumi_list = \popgo\Bangumi::get_now_playing();

$page_data = array(
	'header' => $header,
	'anime_list' => $anime_list
);

$smarty = new Smarty();

$smarty->debugging = true;

$smarty->caching = false;

$smarty->assign($page_data);

$smarty->display('main_body.tpl');