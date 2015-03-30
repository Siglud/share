<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/13
 * Time: 16:13
 * 分享内容主类
 * 这个类对应的数据库使用了纵向切表，切出了一个易变字段和三个长的Text字段，以期减少表的大小加快日常排序中的速度
 * 缓存使用了memcache对字段进行缓存，时间为1天，也可以由Memcache自身控制进行LRU
 * 唯一需要注意的是Group，因为新增了一个分享能过对应两个Group的功能，所以Group将切出另外做处理，不会再使用连表查询的方式进行
 */

namespace popgo;


class Share {
	private $sid;

	private $dao;

	private $user;

	private $share_name;

	private $torrent_file_name;

	private $category;

	private $add_time;

	private $group_publish;

	private $description;

	private $file_list;

	private $share_data;

	/**
     * @param int $sid
     * @param object $share_data
     * @param null $share_hash
     */
	public function __construct($sid=null, $share_data=null, $share_hash=null){
		if($sid){
			$this->sid = (int) $sid;
		}
		if($share_data){
			$this->sid = (int) $share_data->sid;
			$this->share_data = $share_data;
		}
		if($share_hash){
			if(preg_match('/^[0-9a-fA-F]{40}$/', $share_hash)){
				$this->sid = null;
				$this->hashCode = $share_hash;
			}
		}

		$this->dao = Data_access::get_instance();
	}

	// ** 用于share_data的惰性初始化，当试图访问未被初始化的share_data的时候自动调用此函数，此函数负责从数据库中初始化share_data，对于其他访问请求均直接抛出异常 ** //
	public function __get($name){
		if($name == 'share_data'){
			$this->init_base_info_from_database();
			return $this->share_data;
		}
		throw new \Exception('no found!');
	}

	// ** 从数据库中初始化一个share的基础信息 **//
	private function init_base_info_from_database(){
		if(!$this->sid and !$this->hashCode){
			$this->share_data = null;
			return;
		}
		if($this->hashCode) {
			$sid = Share::hash_code_to_sid( $this->hashCode );
			if(!$sid){
				$this->share_data = null;
				return;
			}
			$this->sid = $sid;
		}

		// memcache get
		$mem_cache = $this->dao->memcache()->get('share:' . $this->sid);
		if(!$mem_cache) {
			$sql = "SELECT b.sid, user_id, share_name, category_id, add_time, hash_code, global_top, group_top, group_publish, file_name, file_size, file_count, have_zip, upload_ip, deleted, e.description, e.download_count, e.emule_link, e.file_list FROM share_basic b LEFT JOIN share_extend e ON b.sid = e.sid WHERE b.sid = '$this->sid'";

			$sql_res = $this->dao->mysql()->query( $sql );

			if ( $sql_res ) {
				$this->share_data = $sql_res->fetch_object();
				$sql_res->free_result();
				# 存入memcache
				$this->dao->memcache()->set('share:' .$this->sid, json_encode($this->share_data), null, 86400);
			}else {
				$this->share_data = null;
			}
		}else {
			$this->share_data = (object) json_decode( $mem_cache );
		}
	}

	/**
	 * 是否被删除了
	 * @return bool
	 */
	public function is_deleted(){
		return !!$this->share_data->deleted;
	}

	// ** 确定一个分享是否存在 ** //
	public function exists(){
		return $this->sid AND $this->share_data;
	}

	/**
	 * 获取用户分享的emule连接
	 * @return mixed
	 */
	public function get_emule_link(){
		return $this->share_data->emule_link;
	}

	/**
	 * 获取分享的用户
	 * @return User
	 */
	public function get_user(){
		if(!$this->user){
			$this->user = new User($this->share_data->user_id);
		}
		return $this->user;
	}

	/**
	 * 获取分享的标题
	 * @return PopgoText
	 */
	public function get_share_name(){
		if(!$this->share_name){
			$this->share_name = new PopgoText($this->share_data->share_name);
		}
		return $this->share_name;
	}

	/**
	 * 种子文件的名称
	 * @return PopgoText
	 */
	public function get_torrent_file_name(){
		if(!$this->torrent_file_name){
			$this->torrent_file_name = new PopgoText($this->share_data->file_name);
		}
		return $this->torrent_file_name;
	}

	/**
	 * 种子文件的分类
	 * @return Category
	 */
	public function get_category(){
		if(!$this->category){
			$this->category = new Category($this->share_data->category_id);
		}
		return $this->category;
	}

	/**
	 * 发布的时间
	 * @return PopgoTime
	 */
	public function get_add_time(){
		if(!$this->add_time){
			$this->add_time = new PopgoTime($this->share_data->add_time);
		}
		return $this->add_time;
	}

	/**
	 * 分享文件的大小
	 * @return PopgoFileSize
	 */
	public function get_file_size(){
		return $this->share_data->file_size;
	}

	/**
	 * 是否全局置顶
	 * @return bool
	 */
	public function get_is_top(){
		return !!$this->share_data->global_top;
	}

	/**
	 * 获得所在的组的信息
	 * @return bool
	 */
	public function is_group_publish(){
		if(!$this->group_publish){
			$this->group_publish = !! $this->share_data->group_publish;
		}
		return $this->group_publish;
	}

	/**
	 * 获取分享的具体信息
	 * @return PopgoText
	 */
	public function get_description(){
		if(!$this->description){
			$this->description = new PopgoText($this->share_data->description);
		}
		return $this->description;
	}

	/**
	 * 获取分享文件的列表
	 * @return PopgoText
	 */
	public function get_file_list(){
		if(!$this->file_list){
			$this->file_list = new PopgoText($this->share_data->file_list);
		}
		return $this->file_list;
	}

	/**
	 * 是否含有zip或者其他压缩文件
	 * @return bool
	 */
	public function is_have_zip(){
		return !!$this->share_data->have_zip;
	}


	/**
	 * 或者上传时的IP
	 * @return mixed
	 */
	public function get_upload_ip(){
		return $this->share_data->upload_ip;
	}

	/**
	 * 获取文件被下载的次数
	 * @return int
	 */
	public function get_download_times(){
		return $this->share_data->download_count;
	}

	/**
	 * 是否在发布组内部置顶中
	 * @return bool
	 */
	public function get_is_group_top(){
		return !!$this->share_data->group_top;
	}

	/**
	 * 获取种子文件的hash code
	 * @return mixed
	 */
	public function get_hash_code(){
		return $this->share_data->hash_code;
	}

	public function get_detail_link(){
		return 'program-'.$this->get_hash_code().'-'.$this->get_share_name()->get_url_encode_text().'.html';
	}

	/*
	 * 获取种子的磁力链接
	 * */
	public function get_magnet_link(){
		return 'magnet:?xt=urn:btih:' . strtoupper($this::base32_encode(pack('H*', $this->get_hash_code()))) . '&tr=http://t2.popgo.org:7456/annonce';
	}

	public static function base32_encode($inString){
		$outString = "";
		$compBits = "";
		$BASE32_TABLE = array(
		'00000' => 0x61,
		'00001' => 0x62,
		'00010' => 0x63,
		'00011' => 0x64,
		'00100' => 0x65,
		'00101' => 0x66,
		'00110' => 0x67,
		'00111' => 0x68,
		'01000' => 0x69,
		'01001' => 0x6a,
		'01010' => 0x6b,
		'01011' => 0x6c,
		'01100' => 0x6d,
		'01101' => 0x6e,
		'01110' => 0x6f,
		'01111' => 0x70,
		'10000' => 0x71,
		'10001' => 0x72,
		'10010' => 0x73,
		'10011' => 0x74,
		'10100' => 0x75,
		'10101' => 0x76,
		'10110' => 0x77,
		'10111' => 0x78,
		'11000' => 0x79,
		'11001' => 0x7a,
		'11010' => 0x32,
		'11011' => 0x33,
		'11100' => 0x34,
		'11101' => 0x35,
		'11110' => 0x36,
		'11111' => 0x37,
		);

		/* Turn the compressed string into a string that represents the bits as 0 and 1. */
		for ($i = 0; $i < strlen($inString); $i++) {
		$compBits .= str_pad(decbin(ord(substr($inString,$i,1))), 8, '0', STR_PAD_LEFT);
		}

		/* Pad the value with enough 0's to make it a multiple of 5 */
		if((strlen($compBits) % 5) != 0) {
		$compBits = str_pad($compBits, strlen($compBits)+(5-(strlen($compBits)%5)), '0', STR_PAD_RIGHT);
		}

		/* Create an array by chunking it every 5 chars */
		$fiveBitsArray = preg_split("/\n/",rtrim(chunk_split($compBits, 5, "\n")));

		/* Look-up each chunk and add it to $outstring */
		foreach((array)$fiveBitsArray as $fiveBitsString) {
		$outString .= chr($BASE32_TABLE[$fiveBitsString]);
		}

		return $outString;
	}

	/**
	 * 检查hash是否正确，基础检查
	 * @param $hash
	 *
	 * @return bool
	 */
	private static function check_hash_code($hash){
		if(strlen($hash) != 40 or !preg_match('/^[a-fA-F0-9]+$/', $hash)){
			return false;
		}
		return true;
	}

	/**
	 * 从hash转为sid
	 * @param $hash
	 *
	 * @return int|null
	 */
	public static function hash_code_to_sid($hash){
		if(!Share::check_hash_code($hash)){
			return null;
		}
		$hash = Data_access::get_instance()->mysql()->escape_string($hash);

		$res = Data_access::get_instance()->mysql()->query("SELECT sid FROM share_basic WHERE hash_code = '$hash'");

		if($res){
			$sid = $res->fetch_object()->sid;
			$res->free_result();
		}else{
			$sid = null;
		}
		return $sid;
	}

    /**
     * 检查分享名称是否能够使用
     * @param $name
     * @return bool
     */
    public function check_same_share_name($name){
        $res = $this->dao->mysql()->query("SELECT sid FROM share_basic WHERE share_name = '{ $this->dao->mysql()->escape_string($name) }'");
        return $res->field_count ? true : false;
    }

	/**
	 * 检查是否有同样的hash存在
	 * @param $hash_code
	 *
	 * @return bool
	 */
	public function check_same_hash_code($hash_code){
        $res = $this->dao->mysql()->query(" SELECT sid FROM share_basic WHERE hash_code = '{ $this->dao->mysql()->escape_string($hash_code) }' ");
	    return $res->field_count ? true : false;
    }

    /*public static function add_new($user_id, $share_name, $file_name, $category_id, $file_size, $group_id, $description, $files_count, $file_list, $have_zip, $ip, $hash_code){
	    $user = new User($user_id);
	    if(!$user->exists()){
		    throw new \Exception();
	    }
    }*/
}