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
	function __construct($group_id='', $group_data='')
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

	public function getGroupData(){
		return $this->group_data;
	}

    /**
     * @return mixed
     */
    public function getGroupId()
    {
        return $this->group_id;
    }


    /**
     * @return string
     */
    public function getGroupIntro()
    {
        return $this->group_data->intro;
    }

    /**
     * @return mixed
     */
    public function getGroupLeader()
    {
        return $this->group_data->groupleader;
    }

    /**
     * @return mixed
     */
    public function getGroupName()
    {
        return $this->group_data->groupname;
    }

	/**
	 * @return int
	 */
	public function getGroupAddTime() {
		return $this->group_data->addtime;
	}

	/**
	 * @return mixed
	 */
	public function getGroupIsDisable() {
		return !!$this->group_data->disabled;
	}

	/**
	 * @return mixed
	 */
	public function getGroupRight() {
		return $this->group_data->right;
	}

	/**
	 * @return mixed
	 */
	public function getGroupUrl() {
		return $this->group_data->url;
	}


	/**
	 * @return bool
	 */
	public function exists(){
		return !!$this->getGroupData() AND !$this->getGroupIsDisable();
	}
}