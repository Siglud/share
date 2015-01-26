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
				$this->category_data = $sql_res->fetch_object();
				$sql_res->free_result();
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

	public function add_new($sort_name, $sort_right){
		if($sort_name AND $sort_right AND (int) $sort_right == $sort_right){
			$this->dao->mysql()->query("INSERT INTO sort (sortname, `right`) VALUES ('". $this->dao->mysql()->escape_string($sort_name) ."', $sort_right)");
			return $new_id = $this->dao->mysql()->insert_id;
		}
		return null;
	}

	public function edit($sort_id, $sort_name, $sort_right){
		if($sort_id AND $sort_name AND $sort_right AND (int) $sort_right == $sort_right AND (int) $sort_id == $sort_id){
			$this->dao->mysql()->query( "UPDATE sort SET sortname = '" . $this->dao->mysql()->escape_string( $sort_name ) . "', `right`='" . $this->dao->mysql()->escape_string( $sort_right ) . "' WHERE sortid = '" . $this->dao->mysql()->escape_string( $sort_id ) . "'" );
		}
	}
}