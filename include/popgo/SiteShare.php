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
				$sid_query_list = implode( ',', $need_sql_sid );

				$share_group_info = array();
				$group_info_array = array();
				// 先检索出所有的发布组ID
				$group_info_query = $this->dao->mysql()->query(" SELECT sid, group_id FROM share_group WHERE sid IN ($sid_query_list)");
				if($group_info_query){
					$group_id_set = array();
					while( $group_info = $group_info_query->fetch_object()){
						if(!in_array($group_info->group_id, $group_id_set)){
							array_push($group_id_set, $group_info->group_id);
						}
						if(!isset($share_group_info[$group_info->sid])){
							$share_group_info[$group_info->sid] = array();
						}
						array_push($share_group_info[$group_info->sid], $group_info->group_id);
					}
					$group_info_query->free_result();
					// 检索发布组的详细信息
					if($group_id_set){
						$group_id_set_query = implode( ',', $group_id_set);
						$group_info_detail_query = $this->dao->mysql()->query(" SELECT group_name, group_id FROM workgroup WHERE group_id IN ( $group_id_set_query ) ");
						if($group_info_detail_query){
							while($group_info = $group_info_detail_query->fetch_object()){
								$group_info_array[$group_info->group_id] = $group_info;
							}
						}
						$group_info_detail_query->free_result();
					}
				}

				$sql = "SELECT b.*, e.file_list, e.emule_link, e.download_count, e.description, x.completed, x.leechers, x.seeders, u.login_name FROM share_basic b LEFT JOIN share_extend e ON b.sid = e.sid LEFT JOIN xbt_files x ON b.sid = x.sid LEFT JOIN site_user u ON b.user_id = u.user_id LEFT JOIN category c ON b.category_id = c.category_id WHERE b.deleted = 0 AND b.sid IN ( $sid_query_list )";
				$res = $this->dao->mysql()->query( $sql );

				if ( $res ) {
					# 可惜浪费了一个生成器的效率提高
					while ( $data_packet = $res->fetch_object() ) {
						if(array_key_exists( $data_packet->sid, $share_group_info)){
							foreach ($share_group_info[$data_packet->sid] as $group_id ) {
								if(!isset($data_packet->group_info)){
									if(array_key_exists($group_id, $group_info_array)){
										$data_packet->group_info = array($group_info_array[$group_id]);
									}
								}else{
									if(array_key_exists($group_id, $group_info_array)){
										array_push($data_packet->group_info, $group_info_array[$group_id]);
									}
								}
							}
						}
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