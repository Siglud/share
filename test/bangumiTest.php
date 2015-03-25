<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2015/2/16
 * Time: 15:55
 */

namespace popgo;

require_once 'site-load.php';


class BangumiTest extends \PHPUnit_Framework_TestCase {
	protected function setup(){
		Data_access::get_instance()->memcache()->delete('bangumi');
	}

	public function test_bangumi(){
		$bangumi = new Bangumi(1);

		$this->assertTrue($bangumi->exists());
		$this->assertEquals($bangumi->get_search_name()->get_orig_text(), '高达G 复国');
		$this->assertEquals($bangumi->get_title()->get_orig_text(), '高达G之复国');
		$this->assertEquals($bangumi->get_start_time()->get_unix_time(), 1412179200);
		$this->assertEquals($bangumi->get_end_time()->get_unix_time(), 0);
		$this->assertEquals($bangumi->get_website(), 'http://www.g-reco.net/');
		$this->assertEquals($bangumi->get_image(), '');
		$this->assertEquals($bangumi->get_play_time(), 4);
		$this->assertEquals($bangumi->get_search_url(), '/bangumi/'.urlencode('高达G 复国'));

		$res = Bangumi::get_now_playing();
		$this->assertLessThanOrEqual(count($res), 1);
		$this->assertInstanceOf('popgo\Bangumi', $res[0][0]);
		# read from cache
		$res = Bangumi::get_now_playing();
		$this->assertLessThanOrEqual(count($res), 1);
		$this->assertInstanceOf('popgo\Bangumi', $res[0][0]);

		$bangumi = new Bangumi(9999);
		$this->assertFalse($bangumi->exists());
		$this->assertEquals($bangumi->get_search_url(), '');
	}
}