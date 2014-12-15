<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/13
 * Time: 18:51
 */

namespace popgo;


class PopgoSEO {
	private $title;
	private $key_word;
	private $description;

	public function __construct($title='漫游', $key_word='漫游', $description='漫游'){
		$this->title = $title;
		$this->key_word = $key_word;
		$this->description = $description;
	}

	/**
	 * @return mixed
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * @return mixed
	 */
	public function get_keywords() {
		return $this->key_word;
	}

	/**
	 * @return mixed
	 */
	public function get_title() {
		return $this->title;
	}
}