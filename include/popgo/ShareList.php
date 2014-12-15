<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/13
 * Time: 16:09
 */

namespace popgo;


class ShareList {

	private $share_info;

	private $group_info;

	private $user_info;

	private $category_info;

	private $download_info;

	public function __construct($data_source){

		$this->group_info = new Group(null, $data_source);

		$this->user_info = new User(null, $data_source);

		$this->share_info = new Share(null, $data_source);

		$this->category_info = new Category(null, $data_source);

		$this->download_info = new DownloadStatus(null, $data_source);
	}

	/**
	 * 获取种子相关的分类信息
	 * @return Category
	 */
	public function getCategoryInfo() {
		return $this->category_info;
	}

	/**
	 * 获取种子相关的组信息
	 * @return Group
	 */
	public function getGroupInfo() {
		return $this->group_info;
	}

	/**
	 * 获取种子相关的直接信息
	 * @return Share
	 */
	public function getShareInfo() {
		return $this->share_info;
	}

	/**
	 * 获取种子相关的用户信息
	 * @return User
	 */
	public function getUserInfo() {
		return $this->user_info;
	}

	/**
	 * @return DownloadStatus
	 */
	public function getDownloadInfo() {
		return $this->download_info;
	}

} 