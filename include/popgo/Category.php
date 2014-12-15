<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/13
 * Time: 16:38
 */

namespace popgo;


class Category {
	private $cid;

	private $dao;

	private $sort_name;

	/**
	 * @param int $cid
	 * @param object $c_data
	 */
	public function __construct($cid=null, $c_data=null){
		$this->dao = Data_access::get_instance();

		if($cid){
			$this->cid = $cid;
		}
		if($c_data){
			$this->cid = $c_data->sortid;
			$this->category_data = $c_data;
		}
	}

	public function __get($name){
		if($name == 'category_data'){
			$this->init_from_database();
			return $this->category_data;
		}
		throw new \Exception('no found!');
	}

	private function init_from_database(){
		if(!$this->cid){
			$this->category_data = null;
		}else{
			$sql = "SELECT sortid, sortname, `right` FROM sort WHERE sortid = '$this->cid'";

			$sql_res = $this->dao->mysql()->query($sql);

			if($sql_res){
				while($sql_data = $sql_res->fetch_object()){
					$this->category_data = $sql_data;
					return;
				}
			}else{
				$this->category_data = null;
			}
		}
	}

	/**
	 * @return PopgoText
	 */
	public function get_category_name() {
		if(!$this->sort_name){
			$this->sort_name = new PopgoText($this->category_data->sortname);
		}
		return $this->sort_name;
	}

	/**
	 * @return mixed
	 */
	public function get_category_right() {
		return $this->category_data->right;
	}

	public function get_category_id(){
		return $this->cid;
	}

	public function exists(){
		return $this->cid AND $this->category_data;
	}
}