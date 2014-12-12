<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/12
 * Time: 0:46
 */

namespace popgo;

require_once '../site-load.php';

use popgo\Data_access;
use PHPUnit_Framework_TestCase;

class data_accessTest extends PHPUnit_Framework_TestCase {
	public function test_data_access(){
		$data_access = Data_access::get_instance();

		$this->assertEquals($data_access->mysql()->get_connection_stats()['connect_success'], true);
		$test_result = $data_access->mysql()->query('show tables');

		$this->assertNotEquals($test_result, false);

		$this->assertNotEquals($data_access->memcache()->getstats(), false);
	}

}
 