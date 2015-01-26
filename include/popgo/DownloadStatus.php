<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/14
 * Time: 1:59
 * 下载情况类
 */

namespace popgo;


class DownloadStatus {
	private $dao;

	private $sid;

	/**
	 * @param int $sid
	 * @param object $download_data
	 */
	public function __construct($sid=null, $download_data=null){
	    $this->dao = Data_access::get_instance();
        if($sid){
            $this->sid = (int) $sid;
        }
	    if($download_data){
		    $this->sid = (int) $download_data->id;
		    $this->download_data = $download_data;
	    }
    }

	public function __get($name){
		if($name == 'download_data'){
			$this->init_from_database();
			return $this->download_data;
		}
		throw new \Exception('no found!');
	}

	private function init_from_database(){
		$sql = "SELECT a.id, f.completed, f.seeders, f.leechers FROM allowed_ex a LEFT JOIN xbt_files f ON a.bhash = f.info_hash WHERE a.id = '$this->sid'";

		$res = $this->dao->mysql()->query($sql);
		if(!$res){
			$this->download_data = null;
			return;
		}
		$this->download_data = $res->fetch_object();
		$res->free_result();
	}

	/**
	 * 返回完成下载的人数
	 * @return int
	 */
	public function get_complete_number(){
		return $this->download_data->completed;
	}

	/**
	 * 返回做种人数
	 * @return int
	 */
	public function get_seeder_number(){
		return $this->download_data->seeders;
	}

	/**
	 * 返回下载人数
	 * @return int
	 */
	public function get_leechers_number(){
		return $this->download_data->leechers;
	}
} 