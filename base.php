<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 2015/3/28
 * Time: 20:26
 */

trait BaseHandler{
    /**
     * 获取番组列表的公共方法
     * @return array
     */
    public function get_bangumi_list(){
        $bangumi_list = \popgo\Bangumi::get_now_playing();
        $today = jddayofweek(unixtojd(time()));
        return array($bangumi_list, $today);
    }

    public function get_category_list(){

    }
}