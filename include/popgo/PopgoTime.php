<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/13
 * Time: 17:04
 */

namespace popgo;


class PopgoTime {
	private $unixTime;

	public function __construct($unixTime){
		$this->unixTime = $unixTime;
	}

	/**
	 * 展示适合人观看的时间字段
	 * @return bool|string
	 */
	public function get_man_time(){
		return date('m-d H:i:s', $this->unixTime);
	}

	/**
	 * 返回UNIX时间戳
	 * @return mixed
	 */
	public function get_unix_time(){
		return $this->unixTime;
	}
} 