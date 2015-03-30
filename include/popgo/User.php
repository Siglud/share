<?php

namespace popgo;
use Exception;


/**
 * Created by JetBrains PhpStorm.
 * User: Rince
 * Date: 13-10-19
 * Time: 下午10:39
 * 用户基础类
 * 可用id进行惰性初始化，也可以传入初始化完成之后的user data字段进行初始化
 */

class User {
	private $dao;

	private $user_id;

	private $group;

	private $user_name;
	/**
	 * @param int $user_id
	 * @param object $user_data
	 */
	function __construct($user_id=null, $user_data=null)
    {
	    $this->dao = Data_access::get_instance();

        if($user_id && is_numeric($user_id)){
            $this->user_id = (int) $user_id;
        }
	    if($user_data){
		    $this->user_data = $user_data;
		    $this->user_id = $user_data->user_id;
	    }
    }

	public function __get( $name ){
		if($name == 'user_data') {
			$this->init_from_database();
			return $this->user_data;
		}
		throw new Exception('no found!');
	}

    /**
     * 从数据库中初始化一个用户
     */
    private function init_from_database(){
	    if(!$this->user_id){
		    $this->user_data = null;
		    return;
	    }
        $sql = 'SELECT user_id, login_name, email, login_pass, upload_count, last_login, deleted  FROM site_user WHERE user_id = ' . $this->dao->mysql()->escape_string($this->user_id);
        $sql_result = $this->dao->mysql()->query($sql);

	    $this->user_data = null;
	    if($sql_result) {
		    $this->user_data = $sql_result->fetch_object();
		    $sql_result->free_result();
	    }
    }

    /**
     * 获取用户是否存在
     * @return bool
     */
    public function exists(){
        return $this->user_id AND $this->user_data;
    }

    /**
     * 获取用户是否被屏蔽
     * @return mixed
     */
    public function getDisabled(){
        return $this->user_data->deleted ? True : False;
    }

    /**
     * 获取用户的id
     * @return mixed
     */
    public function getUserId(){
        return $this->user_id;
    }


    /**
     * 获取用户的邮箱
     * @return mixed
     */
    public function getUserMail(){
        return $this->user_data->email;
    }

    /**
     * 获取用户名
     * @return PopgoText
     */
    public function getUserName(){
        if(!$this->user_name){
	        $this->user_name = new PopgoText($this->user_data->login_name);
        }
	    return $this->user_name;
    }

	/**
     * 获取用户的上传总数量
	 * @return int
	 */
	public function getUploadCount(){
		return (int) $this->user_data->upload_count;
	}

	/**
     * 返回用户所属的组
	 * @return Group
	 */
	public function getGroup(){
		if(!$this->group){
			$this->group = null;
			if($this->exists()){
				$sql_result = $this->dao->mysql()->query("SELECT group_id FROM user_group WHERE user_id = '$this->user_id'");
				if($sql_result){
					$this->group = array();
					while($group_id = $sql_result->fetch_object()){
						array_push($this->group, new Group($group_id));
					}
				}
			}
		}
		return $this->group;
	}
}