<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/12
 * Time: 23:36
 */

namespace popgo;

require_once '../site-load.php';


class userTest extends \PHPUnit_Framework_TestCase {
	public function test_user_access(){
		$user = new User(6);

		$this->assertEquals($user->user_id, 6);
		$this->assertNotNull($user->user_data);
		$this->assertFalse($user->getDisabled());
		$this->assertFalse($user->getIsAdvUser());
		$this->assertLessThan($user->getUploadCount(), 0);
		$this->assertEquals($user->getUserMail(), 'p14@p14.com');
		$this->assertEquals($user->getUserName(), 'p14');
		$this->assertTrue($user->exists());

		$this->assertEquals($user->getGroup()->getGroupName(), '漫游字幕组');

		$user = new User(19999);
		$this->assertFalse($user->exists());
	}

}
 