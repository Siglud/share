<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Rince
 * Date: 13-10-19
 * Time: 下午10:43
 */

namespace popgo;


class group extends data_access {
    private $group_name;
    private $group_id;
    private $group_leader;
    private $group_intro;

    function __construct($group_id="")
    {
        if($group_id){
            $this->group_id = $group_id;
        }
    }

    private function init_from_gid(){

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
        return $this->group_id;
    }

    /**
     * @param mixed $group_intro
     */
    public function setGroupIntro($group_intro)
    {
        $this->group_intro = $group_intro;
    }

    /**
     * @return mixed
     */
    public function getGroupIntro()
    {
        return $this->group_intro;
    }

    /**
     * @param mixed $group_leader
     */
    public function setGroupLeader($group_leader)
    {
        $this->group_leader = $group_leader;
    }

    /**
     * @return mixed
     */
    public function getGroupLeader()
    {
        return $this->group_leader;
    }

    /**
     * @param mixed $group_name
     */
    public function setGroupName($group_name)
    {
        $this->group_name = $group_name;
    }

    /**
     * @return mixed
     */
    public function getGroupName()
    {
        return $this->group_name;
    }
}