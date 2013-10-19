<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Rince
 * Date: 13-10-19
 * Time: 下午10:39
 * To change this template use File | Settings | File Templates.
 */

namespace popgo;

include_once 'base.php';


class user extends base {
    private $user_id = NULL;
    private $user_name;
    private $group_id;
    private $is_adv_user;
    private $disabled;
    private $user_mail;
    private $need_init;

    function __construct($user_id)
    {
        parent::__construct();
        if(is_int($user_id)){
            $this->user_id = $user_id;
            $this->need_init = True;
        }
    }

    private function init_from_database(){
        if(!is_null($this->user_id) and $this->need_init){
            $sql = 'SELECT userid, username, groupid, advuser, disabled, email FROM user WHERE userid = ' . $this->user_id;
            $userInfo = $this->dao->mysql()->get_result_content($sql);
            if(!$userInfo){
                $this->user_id = 0;
            } else {
                $userInfo = $userInfo[0];
                $this->user_id = (int) $userInfo['userid'];
                $this->user_name = $userInfo['username'];
                $this->group_id = (int) $userInfo['groupid'];
                $this->is_adv_user = $userInfo['advuser'] ? True : False;
                $this->disabled = $userInfo['disabled'] ? True : False;
            }
        }
    }

    public function is_visitor(){
        if(!$this->user_id){
            return True;
        } else {
            return False;
        }
    }

    /**
     * @param mixed $disabled
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
    }

    /**
     * @return mixed
     */
    public function getDisabled()
    {
        $this->init_from_database();
        return $this->disabled;
    }

    /**
     * @param mixed $group_id
     */
    public function setGroupId($group_id)
    {
        $this->group_id = $group_id;
    }

    /**
     * @return mixed
     */
    public function getGroupId()
    {
        $this->init_from_database();
        return $this->group_id;
    }

    /**
     * @param mixed $is_adv_user
     */
    public function setIsAdvUser($is_adv_user)
    {
        $this->is_adv_user = $is_adv_user;
    }

    /**
     * @return mixed
     */
    public function getIsAdvUser()
    {
        $this->init_from_database();
        return $this->is_adv_user;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        $this->init_from_database();
        return $this->user_id;
    }

    /**
     * @param mixed $user_mail
     */
    public function setUserMail($user_mail)
    {
        $this->user_mail = $user_mail;
    }

    /**
     * @return mixed
     */
    public function getUserMail()
    {
        $this->init_from_database();
        return $this->user_mail;
    }

    /**
     * @param mixed $user_name
     */
    public function setUserName($user_name)
    {
        $this->user_name = $user_name;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        $this->init_from_database();
        return $this->user_name;
    }
}