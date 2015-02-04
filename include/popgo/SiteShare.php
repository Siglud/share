<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/13
 * Time: 16:00
 * 全局分享物检索类
 */

namespace popgo;


class SiteShare {
	// ** 数据连接 **  //
	private $dao;

	public function __construct(){
		$this->dao = Data_access::get_instance();
	}

	/**
	 * @param $category int
	 * @param $number int
	 */
	public function get_recent_category_share($category, $number){
		$category = (int) $category;
		$number = (int) $number;
		if($number > 0){
			$sql = "SELECT id FROM allowed_ex WHERE sortid = '$category' LIMIT 0, $number";

			$sql_res = $this->dao->mysql()->query($sql);

			$sid_list = array();
			if($sql_res){
				while($sid = $sql_res->fetch_object()){
					array_push($sid_list, $sid->id);
				}
			}

			if($sid_list){
				return $this->get_share_detail_from_sid_list($sid_list);
			}
		}

		return null;
	}

	/**
	 * @param $sid_list
	 *
	 * @return array|null
	 */
	private function get_share_detail_from_sid_list($sid_list){
		if($sid_list){
			$sql = "SELECT a.addedtime, a.bhash, a.bname, a.filesize, t.seeders, t.leechers, a.userid, a.settop, a.ingroup, a.description, u.username, g.groupname, s.sortname, a.havezip, a.downtimes, a.changelog, a.fileslist, a.grouptop, a.id, a.emule, a.hashCode FROM allowed_ex as a LEFT JOIN user as u ON a.userid=u.userid LEFT JOIN groups as g ON a.ingroup=g.groupid LEFT JOIN xbt_files as t ON a.bhash=t.info_hash LEFT JOIN sort as s ON a.sortid = s.sortid WHERE a.disabled != 1 AND a.id IN (" . implode(',', $sid_list) . ")";
			$res = $this->dao->mysql()->query($sql);

			$data = array();
			if($res){
				while($data_packet = $res->fetch_object()){
					array_push($data, new ShareList($data_packet));
				}
			}
			$res->free_result();
			return $data;
		}
		return null;
	}
} 