<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/14
 * Time: 22:55
 */

namespace popgo;

require_once 'site-load.php';


class shareListTest extends \PHPUnit_Framework_TestCase {
	public function test_share_list(){
		$site_share = new SiteShare();

		$data = $site_share->get_recent_category_share(1, 1);

		$this->assertNotEmpty($data[0]->getCategoryInfo());
		$this->assertNotEmpty($data[0]->getGroupInfo());
		$this->assertNotEmpty($data[0]->getShareInfo());
		$this->assertNotEmpty($data[0]->getUserInfo());
		$this->assertNotEmpty($data[0]->getDownloadInfo());
	}
}
 