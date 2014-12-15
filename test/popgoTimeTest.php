<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/14
 * Time: 23:13
 */

namespace popgo;


class popgoTimeTest extends \PHPUnit_Framework_TestCase {
	public function test_popgo_time(){
		$time = new PopgoTime(1267373580);

		$this->assertEquals($time->get_man_time(), '03-01 00:13:00');
		$this->assertEquals($time->get_unix_time(), 1267373580);
	}
}
 