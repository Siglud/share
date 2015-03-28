<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Rince
 * Date: 13-10-19
 * Time: 下午10:43
 * 用户组类
 */

namespace popgo;

use Exception;

class Group {
	// ** 组名 ** //
    /*private $group_name;
	// ** 组id ** //
    private $group_id;
	// ** 组长的id ** //
    private $group_leader;
	// ** 发布组介绍 ** //
    private $group_intro;
	// ** 发布组权值 ** //
	private $group_right;

	// ** 发布组的url ** //
	private $group_url;
	// ** 发布组进驻的时间 ** //
	private $group_add_time;
	// ** 发布组是否被禁止 ** //
	private $group_is_disable;
	// ** 数据库连接 ** //
	private $data_access;*/
	// ** 数据源 ** //

	/**
	 * @param int $group_id
	 * @param object $group_data
	 */
	function __construct($group_id=null, $group_data=null)
    {
	    $this->data_access = Data_access::get_instance();
        if($group_id){
            $this->group_id = (int) $group_id;
        }
	    if($group_data){
		    $this->group_id = (int) $group_data->groupid;
		    $this->group_data = $group_data;
	    }
    }

	/**
	 * @param $name
	 * 通用的变量获取方法，只侦测group_data这个打包变量，如果它没有被初始化，那么从数据库中初始化，如果已经初始化了，则直接扔出，如果试图访问其他
	 * 的变量——全部都是内部变量，则抛出错误
	 * @return object|string
	 * @throws Exception
	 */
	public function __get( $name ){
		if($name == 'group_data') {
			$this->init_from_database();
			return $this->group_data;
		}
		throw new Exception('no found!');
	}

	/**
	 * 从数据库中初始化全局的group_data变量包，其它的group值均从此变量包中取出，这个函数仅被__get('group_data')触发
	 */
	private function init_from_database(){
		if(!$this->group_id){
			$this->group_data = null;
			return;
		}
		// SQL操作
		$sql_result = $this->data_access->mysql()->query("SELECT groupid, groupname, groupleader, intro, `right`, url, addtime, `disabled`  FROM groups WHERE groupid = '". $this->group_id ."'");

		$this->group_data = null;
		if($sql_result) {
			$this->group_data = $sql_result->fetch_object();
			$sql_result->free_result();
		}
	}

    /**
     * 获取组的全部数据
     * @return null|object
     */
    public function getGroupData(){
		return $this->group_data;
	}

    /**
     * 获取组的id
     * @return int
     */
    public function getGroupId()
    {
        return $this->group_id;
    }


    /**
     * 获取发布组的介绍文字
     * @return PopgoText
     */
    public function getGroupIntro()
    {
        return new PopgoText($this->group_data->intro);
    }

    /**
     * 获取组的组长
     * @return mixed
     */
    public function getGroupLeader()
    {
        return $this->group_data->groupleader;
    }

    /**
     * 获取组的名字
     * @return mixed
     */
    public function getGroupName()
    {
        return $this->group_data->groupname;
    }

	/**
     * 获取组被添加的时间
	 * @return int
	 */
	public function getGroupAddTime() {
		return $this->group_data->addtime;
	}

	/**
     * 确定组是否被屏蔽
	 * @return mixed
	 */
	public function getGroupIsDisable() {
		return !!$this->group_data->disabled;
	}

	/**
     * 获取发布组的权值
	 * @return mixed
	 */
	public function getGroupRight() {
		return $this->group_data->right;
	}

	/**
     * 获取发布组的url
	 * @return mixed
	 */
	public function getGroupUrl() {
		return $this->group_data->url;
	}


	/**
     * 判断组是否存在
	 * @return bool
	 */
	public function exists(){
		return !!$this->getGroupData() AND !$this->getGroupIsDisable();
	}

    /**
     * 新建组
     * @param $group_name
     * @param string $intro
     * @param int $group_right
     * @param string $url
     * @return bool
     */
    public function add_new($group_name, $intro='', $group_right=0, $url=''){
        if($group_name){
            if(!$group_right or $group_right != (int) $group_right){
                $group_right = 0;
            }
            if($this->check_same_group_name($group_name)){
                return false;
            }

            $this->data_access->mysql()->query("INSERT INTO groups (groupname, disabled, `right`, url, intro, addtime) VALUES (' $this->data_access->mysql()->escape_string($group_name) ', 0, ' $group_right ', ' $this->data_access->mysql()->escape_string($url) ', ' $this->data_access->mysql()->escape_string($intro) ', time()");
            $this->data_access->mysql()->commit();
            $this->clear_cache();
            return true;
        }
        return false;
    }

    /**
     * 编辑发布组
     * @param $group_id int
     * @param $group_name string
     * @param $intro string
     * @param $group_right string
     * @param $url string
     * @param $disabled bool
     * @return bool
     */
    public function edit($group_id, $group_name, $intro, $group_right, $url, $disabled){
        $group_id = (int) $group_id;
        if($group_id and $group_name and $group_right){
            if($this->check_same_group_name($group_name)){
                return false;
            }
            $disabled = $disabled ? 1 : 0;
            $this->data_access->mysql()->query("UPDATE groups SET groupname = ' $this->data_access->mysql()->escape_string($group_name)  ', intro = ' $this->data_access->mysql()->escape_string($intro) ', `right` = ' $this->data_access->mysql()->escape_string($group_right) ', url=' $this->data_access->mysql()->escape_string($url) ', `disabled`=' $this->data_access->mysql()->escape_string($disabled) ' WHERE groupid = ' $this->data_access->mysql()->escape_string($disabled) '");
            $this->data_access->mysql()->commit();
            $this->clear_cache();
            return true;
        }
        return false;
    }

    /**
     * 获取全部的发布组列表
     * @return array(Group)
     */
    public static function get_all_group_list(){
        // 首先检查cache的内容
        $data_packet = Data_access::get_instance()->memcache()->get('group_all');
        if(!$data_packet){
            $group_data = Data_access::get_instance()->mysql()->query("SELECT groupid, groupname, intro, `right`, url, addtime, `disabled`  FROM groups ORDER BY `right` DESC");
            $data_packet = array();
            foreach($group_data as $x){
                array_push($data_packet, $x);
            }
            // 存入memcache
            Data_access::get_instance()->memcache()->set('group_all', json_encode($data_packet), false, 286400);
        }

        $res = array();
        if($data_packet){
            foreach($data_packet as $v){
                # 为了兼容json dumps之后的数据，做一下强制类型转换
                $v = (object) $v;
                array_push($res, new Group(null, $v));
            }
        }
        return $res;
    }

    /**
     * 检查是否有重复的组名，True=有重复
     * @param $name
     * @return bool
     */
    public function check_same_group_name($name){
        $same_name_res = $this->data_access->mysql()->query(" SELECT groupid FROM groups WHERE groupname = ' $this->data_access->mysql()->escape_string($name) '");
        if($same_name_res->field_count){
            return true;
        }
        return false;
    }

    /**
     * 清空缓存
     */
    private function clear_cache(){
        $this->data_access->memcache()->delete('group_all');
    }
}