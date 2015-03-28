<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/13
 * Time: 16:38
 */

namespace popgo;


class Category {
	private $cid;

	private $dao;

	private $sort_name;

	/**
	 * @param int $cid
	 * @param object $c_data
	 */
	public function __construct($cid=null, $c_data=null){
		$this->dao = Data_access::get_instance();

		if($cid){
			$this->cid = $cid;
		}
		if($c_data){
			$this->cid = $c_data->sortid;
			$this->category_data = $c_data;
		}
	}

    /**
     * 获取惰性初始化的值，如果不存在则抛出错误
     * @param $name
     * @return null|object
     * @throws \Exception
     */
    public function __get($name){
		if($name == 'category_data'){
			$this->init_from_database();
			return $this->category_data;
		}
		throw new \Exception('no found!');
	}

    /**
     * 从数据库中初始化
     */
    private function init_from_database(){
		if(!$this->cid){
			$this->category_data = null;
		}else{
			$sql = "SELECT sortid, sortname, `right` FROM sort WHERE sortid = '$this->cid'";

			$sql_res = $this->dao->mysql()->query($sql);

			if($sql_res){
				$this->category_data = $sql_res->fetch_object();
				$sql_res->free_result();
			}else{
				$this->category_data = null;
			}
		}
	}

	/**
     * 获取分类名称
	 * @return PopgoText
	 */
	public function get_category_name() {
		if(!$this->sort_name){
			$this->sort_name = new PopgoText($this->category_data->sortname);
		}
		return $this->sort_name;
	}

	/**
     * 获取分类权重
	 * @return mixed
	 */
	public function get_category_right() {
		return $this->category_data->right;
	}

    /**
     * 获取分类id
     * @return mixed
     */
    public function get_category_id(){
		return $this->cid;
	}

    /**
     * 判断是否存在
     * @return bool
     */
    public function exists(){
		return $this->cid AND $this->category_data;
	}

    /**
     * 增加一个新的分类
     * @param $sort_name
     * @param $sort_right
     * @return mixed|null
     */
    public function add_new($sort_name, $sort_right){
		if($sort_name AND $sort_right AND (int) $sort_right == $sort_right){
			$this->dao->mysql()->query("INSERT INTO sort (sortname, `right`) VALUES ('". $this->dao->mysql()->escape_string($sort_name) ."', $sort_right)");
            // 删除快取，让前端自主刷新
            $this->clear_category_cache();
			return $new_id = $this->dao->mysql()->insert_id;
		}
		return null;
	}

    /**
     * 编辑一个现有的分类值
     * @param $sort_id
     * @param $sort_name
     * @param $sort_right
     */
    public function edit($sort_id, $sort_name, $sort_right){
		if($sort_id AND $sort_name AND $sort_right AND (int) $sort_right == $sort_right AND (int) $sort_id == $sort_id){
			$this->dao->mysql()->query( "UPDATE sort SET sortname = '" . $this->dao->mysql()->escape_string( $sort_name ) . "', `right`=' $this->dao->mysql()->escape_string( $sort_right ) ' WHERE sortid = ' $this->dao->mysql()->escape_string( $sort_id ) '" );
            $this->clear_category_cache();
		}
	}

    /**
     * 获取所有的分类
     * @return array(Category)
     */
    public static function get_all_category(){
        // 先从memcache中进行快取
        $data_packet = Data_access::get_instance()->memcache()->get('cg_all');
        if(!$data_packet){
            $all_category = Data_access::get_instance()->mysql()->query("SELECT sortid, sortname, `right` FROM sort ORDER BY `right` DESC");
            $data_packet = array();
            foreach($all_category as $x){
                array_push($data_packet, $x);
            }
            // 存入memcache
            Data_access::get_instance()->memcache()->set('cg_all', json_encode($data_packet), false, 286400);
        }

        $res = array();
        if($data_packet){
            foreach($data_packet as $v){
                # 为了兼容json dumps之后的数据，做一下强制类型转换
                $v = (object) $v;
                array_push($res, new Category(null, $v));
            }
        }
        return $res;

    }

    /**
     * 删除非分类列表的快取镜像
     */
    private function clear_category_cache(){
        $this->dao->memcache()->delete('cg_all');
    }
}