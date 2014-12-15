<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/13
 * Time: 17:08
 */

namespace popgo;

require_once 'site-load.php';


class categoryTest extends \PHPUnit_Framework_TestCase {
	public function test_category_read(){
		$category = new Category(1);

		$this->assertEquals($category->get_category_id(), 1);
		$this->assertEquals($category->get_category_name()->get_orig_text(), '动画');
		$this->assertEquals($category->get_category_right(), 1);
		$this->assertTrue($category->exists());

		$category = new Category(999);
		$this->assertFalse($category->exists());

		$dao = Data_access::get_instance();
		$sql_res = $dao->mysql()->query('SELECT * FROM sort WHERE sortid = 1');

		$category_data = $sql_res->fetch_object();

		$category = new Category(null, $category_data);

		$this->assertEquals($category->get_category_id(), 1);
		$this->assertEquals($category->get_category_name()->get_orig_text(), '动画');
		$this->assertEquals($category->get_category_right(), 1);
		$this->assertTrue($category->exists());
	}
}
 