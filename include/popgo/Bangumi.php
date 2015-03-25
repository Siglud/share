<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2015/2/10
 * Time: 16:38
 */

namespace popgo;


use SebastianBergmann\Exporter\Exception;

class Bangumi {
	private $dao;

	private $title;
	private $start_time;
	private $end_time;
	private $website;
	private $image;
	private $search_name;
	private $play_time;

	/**
	 * @param int $bid
	 * @param object $bangumi_data
	 * 注意这个初始化有一个坑，当$bid为0或者空变量的时候，不会被正确的初始化，那么exists会报告500错误，解决它最简单的办法就是——不要设置任何为0的
	 * bid
	 */
	public function __construct($bid=null, $bangumi_data=null){
		if($bid){
			$this->bid = (int) $bid;
		}
		if($bangumi_data){
			$this->bid = (int) $bangumi_data->id;
			$this->bangumi_data = $bangumi_data;
		}

		$this->dao = Data_access::get_instance();
	}

	/*
	 * 惰性初始化
	 * */
	public function __get($name){
		if($name == 'bangumi_data'){
			$this->init_from_database();
			return $this->bangumi_data;
		}
		throw new Exception('attrib '. $name .' no found!');
	}

	private function init_from_database(){
		if(!$this->bid){
			$this->bangumi_data = null;
			return;
		}
		$sql = "SELECT id, title, start_time, end_time, website, image, search_name, play_time FROM bangumi WHERE id = '$this->bid'";

		$sql_res = $this->dao->mysql()->query($sql);

		if($sql_res){
			$this->bangumi_data = $sql_res->fetch_object();
			$sql_res->free_result();
		}else{
			$this->bangumi_data = null;
		}
	}

	/*判断是否存在
	 * */
	function exists(){
		return $this->bid AND $this->bangumi_data;
	}

	/*
	 * 番组名称
	 * */
	function get_title(){
		if(!$this->title) {
			$this->title = new PopgoText( $this->bangumi_data->title );
		}
		return $this->title;
	}

	/*
	 * 番组开始的时间
	 * */
	function get_start_time(){
		if(!$this->start_time){
			$this->start_time = new PopgoTime($this->bangumi_data->start_time);
		}
		return $this->start_time;
	}

	/*
	 * 番组结束的时间
	 * */
	function get_end_time(){
		if(!$this->end_time){
			$this->end_time = new PopgoTime($this->bangumi_data->end_time);
		}
		return $this->end_time;
	}

	/*番组的官网
	 * */
	function get_website(){
		if(!$this->website){
			$this->website = $this->bangumi_data->website;
		}
		return $this->website;
	}

	/*番组的介绍图片
	 * */
	function get_image(){
		if(!$this->image){
			$this->image = $this->bangumi_data->image;
		}
		return $this->image;
	}

	/*番组的搜索关键字
	 * */
	function get_search_name(){
		if(!$this->search_name){
			$this->search_name = new PopgoText($this->bangumi_data->search_name);
		}
		return $this->search_name;
	}

	/*番组的播放时间，0=周日，6=周六
	 * */
	function get_play_time(){
		if(!$this->play_time){
			$this->play_time = $this->bangumi_data->play_time;
		}
		return $this->play_time;
	}

	/*
	 * 番组的具体时间
	 * */
	function get_play_time_word($play_time){
		switch ($play_time){
			case 0:
				return _('Sunday');
			case 1:
				return _('Monday');
			case 2:
				return _('Tuesday');
			case 3:
				return _('Wednesday');
			case 4:
				return _('Thursday');
			case 5:
				return _('Friday');
			case 6:
				return _('Saturday');
		}
		return '';
	}

	/*获取搜索番组的url链接
	 * */
	function get_search_url(){
		if($this->exists()){
			return '/bangumi/' . $this->get_search_name()->get_url_encode_text();
		}
		return '';
	}

	/* 批量初始化所有正在播放的番组
	 * 整合成Array = (0=>Array(Bangumi, Bangumi...), 1=>Array(Bangumi, Bangumi...))
	 * */
	public static function get_now_playing(){
		$now = time();
		// 快取
		$cache_data = Data_access::get_instance()->memcache()->get('bangumi');
		$bangumi_data = null;
		if($cache_data){
			$bangumi_data = json_decode($cache_data);
		}else {
			$sql = "SELECT id, title, start_time, end_time, website, image, search_name, play_time FROM bangumi WHERE start_time < $now AND (end_time > $now OR end_time = 0)";
			$bangumi_data = Data_access::get_instance()->mysql()->query( $sql );

			$data_packet = array();
			foreach($bangumi_data as $x){
				array_push($data_packet, $x);
			}
			Data_access::get_instance()->memcache()->set('bangumi', json_encode($data_packet), false, 86400);
		}

		$res = array();
		if($bangumi_data){
			foreach($bangumi_data as $v){
				# 为了兼容json dumps之后的数据，做一下强制类型转换
				$v = (object) $v;
				if(!array_key_exists($v->play_time, $res)){
					$res[$v->play_time] = array();
				}
				array_push($res[$v->play_time], new Bangumi(null, $v));
			}
		}
		return $res;
	}
}