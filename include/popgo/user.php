<?php

namespace popgo;
use Exception;


/**
 * Created by JetBrains PhpStorm.
 * User: Rince
 * Date: 13-10-19
 * Time: 下午10:39
 * 用户基础类
 */

class User {
	private $dao;
	private $group;

	/**
	 * @param string $user_id
	 * @param object $user_data
	 */
	function __construct($user_id="", $user_data="")
    {
	    $this->dao = Data_access::get_instance();

        if(is_int($user_id)){
            $this->user_id = $user_id;
        }
	    if($user_data){
		    $this->user_data = $user_data;
		    $this->user_id = $user_data->userid;
	    }
    }

	function __get( $name ){
		if($name == 'user_data') {
			$this->init_from_database();
			return $this->user_data;
		}
		throw new Exception('no found!');
	}

    private function init_from_database(){
        $sql = 'SELECT userid, username, groupid, advuser, disabled, email, uploaders, lastlogin  FROM user WHERE userid = ' . $this->user_id;
        $sql_result = $this->dao->mysql()->query($sql);

	    $this->user_data = null;
	    if($sql_result) {
		    while ( $userInfo = $sql_result->fetch_object() ) {
			    $this->user_data = $userInfo;
			    break;
		    }
	    }

	    $sql_result -> close();
    }

    public function exists(){
        return $this->user_id AND $this->user_data;
    }

    /**
     * @return mixed
     */
    public function getDisabled(){
        return $this->user_data->disabled ? True : False;
    }

    /**
     * @return mixed
     */
    public function getIsAdvUser(){
        return $this->user_data->advuser ? True : False;
    }

    /**
     * @return mixed
     */
    public function getUserId(){
        return $this->user_id;
    }


    /**
     * @return mixed
     */
    public function getUserMail(){
        return $this->user_data->email;
    }

    /**
     * @return mixed
     */
    public function getUserName(){
        return $this->user_data->username;
    }

	/**
	 * @return int
	 */
	public function getUploadCount(){
		return (int) $this->user_data->uploaders;
	}

	/**
	 * @return Group
	 */
	public function getGroup(){
		if(!$this->group){
			$this->group = new Group($this->user_data->groupid);
		}
		return $this->group;
	}
}