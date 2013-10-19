<?php
namespace config;


class config{
    /** MySQL配置 */
    public $mysql_host = '192.168.0.11';
    public $mysql_port = '3306';
    public $mysql_user = 'root';
    public $mysql_password = '';
    public $mysql_db = 'test';

    /** memcache配置 */
    public $memcache_host = '192.168.0.14';
    public $memcache_port = '11211';

    /** 系统设置 */
    // 是否关闭整站
    public $site_close = False;
    // 每页显示多少个种子
    public $torrents_per_page = 50;
    // 间隔多少时间的种子可以顶(单位：秒)
    public $up_torrents_time_limit = 86400;
    // 是否关闭在凌晨的种子上传
    public $close_torrents_upload_in_night = True;
    // 设置上传种子的路径
    public $torrens_file_path = '/torrents/';
    // 关键字过滤
    public $block_word_list = '法轮|胡锦涛|XPSP|黑鹰vip|无量寿|语中字|QQ代码|淘宝购物|安装说明.url|影子|美容师培训|GHOSTXP|最新美国|教程网|2012最新|韩国美女|么么虎|电影大片';
    // 是否允许注册
    public $open_reg = True;
    // 是否关闭普通用户上传种子的权限
    public $close_torrents_upload_for_normal_user = False;
    // IP屏蔽列表
    // 用;间隔
    // 可使用通配符*
    public $ip_filter = '';
}