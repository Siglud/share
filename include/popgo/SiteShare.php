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
     * @return array|null
     */
	public function get_recent_category_share($category, $number){
		$category = (int) $category;
		$number = (int) $number;
		if($number > 0){
			$sql = "SELECT sid FROM share_basic WHERE category_id = '$category' LIMIT 0, $number";

			$sql_res = $this->dao->mysql()->query($sql);

			$sid_list = array();
			if($sql_res){
				while($sid = $sql_res->fetch_object()){
					array_push($sid_list, $sid->sid);
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
			# check cache first
			$cache_res = array();
			$need_sql_sid = array();
			foreach($sid_list as $sid){
				$cache_data = $this->dao->memcache()->get('seed:'. $sid);
				if($cache_data){
					$cache_data = json_decode($cache_data);
					$cache_res[$cache_data['id']] = (object) $cache_data;
				}else{
					array_push($need_sql_sid, $sid);
				}
			}
			# do query if not all get
			if($need_sql_sid) {
				$sql = "SELECT * FROM share_basic b LEFT JOIN share_extend e ON b.sid = e.sid LEFT JOIN xbt_files x ON b.sid = x.sid LEFT JOIN site_user u ON b.user_id = u.user_id LEFT JOIN category c ON b.category_id = c.category_id WHERE b.deleted = 0 AND b.sid IN (" . implode( ',', $need_sql_sid ) . ")";
				$res = $this->dao->mysql()->query( $sql );

				if ( $res ) {
					# 可惜浪费了一个生成器的效率提高
					while ( $data_packet = $res->fetch_object() ) {
						$cache_res[$data_packet->id] = $data_packet;
						# 存入redis, 存入的时候可以考虑更长一点时间，如果主体信息发生变化，由变更端主动删除此键值
						$this->dao->memcache()->set('seed:'. $data_packet->id, json_encode($data_packet), false, 86400);
					}
				}
				$res->free_result();
			}
			# 重新拼凑为顺序的数组输出
			$data = array();
			foreach($sid_list as $sid){
				array_push($data, new ShareList($cache_res[$sid]));
			}
			return $data;
		}
		return null;
	}
} 