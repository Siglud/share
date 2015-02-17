<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/14
 * Time: 22:26
 */

namespace popgo;

require_once 'site-load.php';


class ShareTest extends \PHPUnit_Framework_TestCase {
	public function test_share_read(){
		$share = new Share(19);

		$this->assertNotEmpty($share->get_magnet_link());
		$this->assertFalse($share->disabled());
		$this->assertTrue($share->exists());
		$this->assertEquals($share->get_add_time()->get_unix_time(), 1267373580);
		$this->assertEquals($share->get_hash_code(), 'e6ae5ea8acca8a0e8aee8b75973fb9d9129d23eb');
		$this->assertEquals($share->get_category()->get_category_id(), 1);
		$this->assertNotEmpty($share->get_change_log()->get_orig_text());
		$this->assertNotEmpty($share->get_description());
		$this->assertEquals($share->get_download_times(), 418);
		$this->assertNotEmpty($share->get_emule_link());
		$this->assertNotEmpty($share->get_file_list());
		$this->assertEquals($share->get_file_size(), '110.23 MB');
		$this->assertEquals($share->get_is_group_top(), 0);
		$this->assertEquals($share->get_group()->getGroupId(), 1);
		$this->assertEquals($share->get_share_name()->get_orig_text(), '[漫游字幕组] Fullmetal Alchemist 钢之炼金术师 2009 第46话 RMVB');
		$this->assertEquals($share->get_user()->getUserId(), 16);
		$this->assertEquals($share->get_torrent_file_name()->get_orig_text(), '[POPGO][Fullmetal_Alchemist][TV_2009][46][GB][RV10].rmvb.torrent');
		$this->assertFalse($share->get_is_top());
		$this->assertFalse($share->is_have_zip());
		$this->assertEquals($share->get_upload_ip(), '121.237.98.152');

		$share = new Share(327678);
		$this->assertFalse($share->exists());

		$dao = Data_access::get_instance();

		$res = $dao->mysql()->query("SELECT * FROM allowed_ex WHERE id = 19");

		$data = $res->fetch_object();

		$share = new Share(null, $data);

		$this->assertNotEmpty($share->get_magnet_link());
		$this->assertFalse($share->disabled());
		$this->assertTrue($share->exists());
		$this->assertEquals($share->get_add_time()->get_unix_time(), 1267373580);
		$this->assertEquals($share->get_hash_code(), 'e6ae5ea8acca8a0e8aee8b75973fb9d9129d23eb');
		$this->assertEquals($share->get_category()->get_category_id(), 1);
		$this->assertNotEmpty($share->get_change_log()->get_orig_text());
		$this->assertNotEmpty($share->get_description());
		$this->assertEquals($share->get_download_times(), 418);
		$this->assertNotEmpty($share->get_emule_link());
		$this->assertNotEmpty($share->get_file_list());
		$this->assertEquals($share->get_file_size(), '110.23 MB');
		$this->assertEquals($share->get_is_group_top(), 0);
		$this->assertEquals($share->get_group()->getGroupId(), 1);
		$this->assertEquals($share->get_share_name()->get_orig_text(), '[漫游字幕组] Fullmetal Alchemist 钢之炼金术师 2009 第46话 RMVB');
		$this->assertEquals($share->get_user()->getUserId(), 16);

		// 测试从hash中初始化
		$share = new Share(null, null, 'e6ae5ea8acca8a0e8aee8b75973fb9d9129d23eb');

		$this->assertNotEmpty($share->get_magnet_link());
		$this->assertFalse($share->disabled());
		$this->assertTrue($share->exists());
		$this->assertEquals($share->get_add_time()->get_unix_time(), 1267373580);
		$this->assertEquals($share->get_hash_code(), 'e6ae5ea8acca8a0e8aee8b75973fb9d9129d23eb');
		$this->assertEquals($share->get_category()->get_category_id(), 1);
		$this->assertNotEmpty($share->get_change_log()->get_orig_text());
		$this->assertNotEmpty($share->get_description());
		$this->assertEquals($share->get_download_times(), 418);
		$this->assertNotEmpty($share->get_emule_link());
		$this->assertNotEmpty($share->get_file_list());
		$this->assertEquals($share->get_file_size(), '110.23 MB');
		$this->assertEquals($share->get_is_group_top(), 0);
		$this->assertEquals($share->get_group()->getGroupId(), 1);
		$this->assertEquals($share->get_share_name()->get_orig_text(), '[漫游字幕组] Fullmetal Alchemist 钢之炼金术师 2009 第46话 RMVB');
		$this->assertEquals($share->get_user()->getUserId(), 16);
		$this->assertEquals($share->get_detail_link(), 'program-e6ae5ea8acca8a0e8aee8b75973fb9d9129d23eb-'.urlencode('[漫游字幕组] Fullmetal Alchemist 钢之炼金术师 2009 第46话 RMVB').'.html');

		$share = new Share();
		$this->assertFalse($share->exists());
	}
}
 