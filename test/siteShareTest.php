<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/14
 * Time: 22:23
 */

namespace popgo;

require_once 'site-load.php';


class siteShareTest extends \PHPUnit_Framework_TestCase {
	public function test_site_share(){
		$site_share = new SiteShare();

		$this->assertNotEmpty($site_share->get_recent_category_share(1, 1));
		$this->assertNotNull($site_share->get_recent_category_share(2, 1));
		$this->assertNull($site_share->get_recent_category_share(9, 1));
	}
}
 