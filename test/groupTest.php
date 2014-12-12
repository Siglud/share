<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/12
 * Time: 14:40
 */

namespace popgo;

require_once '../site-load.php';

use popgo\Group;


class groupTest extends \PHPUnit_Framework_TestCase {
	public function test_group_access(){
		$group = new Group(1);

		$this->assertEquals($group->getGroupId(), 1);
		$this->assertEquals($group->getGroupName(), '漫游字幕组');

		$this->assertEquals($group->getGroupIsDisable(), false);
		$this->assertNotEquals($group->getGroupAddTime(), '');
		$this->assertNotEquals($group->getGroupIntro(), '');
		$this->assertNotEquals($group->getGroupLeader(), '');
		$this->assertEquals($group->getGroupRight(), 900);
		$this->assertEquals($group->getGroupUrl(), 'http://popgo.net/bbs');

		$this->assertEquals($group->exists(), true);

		$group = new Group(10000);
		$this->assertEquals($group->exists(), false);
	}

}
 