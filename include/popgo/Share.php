<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/13
 * Time: 16:13
 */

namespace popgo;


use Exception;

class Share {
	private $sid;

	private $dao;

	private $user;

	private $share_name;

	private $torrent_file_name;

	private $category;

	private $add_time;

	private $file_size;

	private $group;

	private $description;

	private $file_list;

	private $change_log;
	/**
	 * @param int $sid
	 * @param object $share_data
	 */
	public function __construct($sid=null, $share_data=null){
		if($sid){
			$this->sid = (int) $sid;
		}
		if($share_data){
			$this->sid = (int) $share_data->id;
			$this->share_data = $share_data;
		}

		$this->dao = Data_access::get_instance();
	}

	// ** 用于share_data的惰性初始化，当试图访问未被初始化的share_data的时候自动调用此函数，此函数负责从数据库中初始化share_data，对于其他访问请求均直接抛出异常 ** //
	public function __get($name){
		if($name == 'share_data'){
			$this->init_from_database();
			return $this->share_data;
		}
		throw new Exception('no found!');
	}

	// ** 从数据库中初始化一个share的相关信息 **//
	private function init_from_database(){
		if(!$this->sid){
			$this->share_data = null;
			return;
		}

		$sql = "SELECT emule, userid, bname, filename, sortid, addedtime, filesize, settop, ingroup, description, files, fileslist, havezip, ip, changelog, downtimes, disabled, grouptop, hashCode FROM allowed_ex WHERE id = '$this->sid'";

		$sql_res = $this->dao->mysql()->query($sql);

		if($sql_res){
			$this->share_data = $sql_res->fetch_object();
			$sql_res->free_result();
		}else{
			$this->share_data = null;
		}
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
		return $this->share_data->emule;
	}

	/**
	 * 获取分享的用户
	 * @return User
	 */
	public function get_user(){
		if(!$this->user){
			$this->user = new User($this->share_data->userid);
		}
		return $this->user;
	}

	/**
	 * 获取分享的标题
	 * @return PopgoText
	 */
	public function get_share_name(){
		if(!$this->share_name){
			$this->share_name = new PopgoText($this->share_data->bname);
		}
		return $this->share_name;
	}

	/**
	 * 种子文件的名称
	 * @return PopgoText
	 */
	public function get_torrent_file_name(){
		if(!$this->torrent_file_name){
			$this->torrent_file_name = new PopgoText($this->share_data->filename);
		}
		return $this->torrent_file_name;
	}

	/**
	 * 种子文件的分类
	 * @return Category
	 */
	public function get_category(){
		if(!$this->category){
			$this->category = new Category($this->share_data->sortid);
		}
		return $this->category;
	}

	/**
	 * 发布的时间
	 * @return PopgoTime
	 */
	public function get_add_time(){
		if(!$this->add_time){
			$this->add_time = new PopgoTime($this->share_data->addedtime);
		}
		return $this->add_time;
	}

	/**
	 * 分享文件的大小
	 * @return PopgoFileSize
	 */
	public function get_file_size(){
		return $this->share_data->filesize;
	}

	/**
	 * 是否全局置顶
	 * @return bool
	 */
	public function get_is_top(){
		return !!$this->share_data->settop;
	}

	/**
	 * 获得所在的组的信息
	 * @return Group
	 */
	public function get_group(){
		if(!$this->group){
			$this->group = new Group($this->share_data->ingroup);
		}
		return $this->group;
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
			$this->file_list = new PopgoText($this->share_data->fileslist);
		}
		return $this->file_list;
	}

	/**
	 * 是否含有zip或者其他压缩文件
	 * @return bool
	 */
	public function is_have_zip(){
		return !!$this->share_data->havezip;
	}


	/**
	 * 或者上传时的IP
	 * @return mixed
	 */
	public function get_upload_ip(){
		return $this->share_data->ip;
	}

	/**
	 * 获取文件改动的信息
	 * @return PopgoText
	 */
	public function get_change_log(){
		if(!$this->change_log){
			$this->change_log = new PopgoText($this->share_data->changelog);
		}
		return $this->change_log;
	}

	/**
	 * 获取文件被下载的次数
	 * @return int
	 */
	public function get_download_times(){
		return $this->share_data->downtimes;
	}

	/**
	 * 文件是否已经被打上了删除标记
	 * @return bool
	 */
	public function disabled(){
		return !!$this->share_data->disabled;
	}

	/**
	 * 是否在发布组内部置顶中
	 * @return bool
	 */
	public function get_is_group_top(){
		return !!$this->share_data->grouptop;
	}

	/**
	 * 获取种子文件的hash code
	 * @return mixed
	 */
	public function get_hash_code(){
		return $this->share_data->hashCode;
	}

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
}