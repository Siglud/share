<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/14
 * Time: 22:08
 */

namespace popgo;


class userShareTest extends \PHPUnit_Framework_TestCase {

	public function test_user_share(){
		$user = new User(6);

		$user_share = new UserShare($user);

	}

}
 