<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/13
 * Time: 16:30
 * POPGO文字处理类，处理各类的文字信息
 */

namespace popgo;


class PopgoText {
	private $orig_text;

	public function __construct($orig_text){
		$this->orig_text = $orig_text;
	}

	public function get_orig_text(){
		return $this->orig_text;
	}

	public function get_html_escape_text(){
		return htmlspecialchars($this->orig_text);
	}

	public function get_url_encode_text(){
		return urlencode($this->orig_text);
	}
} 