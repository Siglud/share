<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/13
 * Time: 0:08
 */

namespace popgo;


class UserShare {
	private $user;

	/**
	 * @param $user User
	 */
	public function __construct($user){
		$this->user = $user;
	}
} 