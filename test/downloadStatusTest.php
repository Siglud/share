<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/15
 * Time: 15:09
 */

namespace popgo;


class downloadStatusTest extends \PHPUnit_Framework_TestCase {
	public function test_download_status(){
		$download_status = new DownloadStatus(136690);

		$this->assertEquals($download_status->get_complete_number(), 43);
		$this->assertEquals($download_status->get_leechers_number(), 10);
		$this->assertEquals($download_status->get_seeder_number(), 34);
	}
}
 