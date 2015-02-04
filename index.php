<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Rince
 * Date: 13-10-19
 * Time: 下午4:42
 * To change this template use File | Settings | File Templates.
 * @var $global_config \config\config
 */

require_once('site-load.php');

$site = new \popgo\SiteShare();

$anime_list = $site->get_recent_category_share(1, 20);

$header = new \popgo\PopgoSEO();

$page_data = array(
	'header' => $header,
	'anime_list' => $anime_list
);

$smarty = new Smarty();

$smarty->debugging = true;

$smarty->caching = false;

$smarty->assign($page_data);

$smarty->display('main_body.tpl');