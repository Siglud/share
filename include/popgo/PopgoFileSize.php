<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/13
 * Time: 17:37
 */

namespace popgo;


class PopgoFileSize {
	private $file_size_in_byte;

	public function __construct($file_size_in_byte){
		$this->file_size_in_byte = $file_size_in_byte;
	}

	/**
	 * 返回可读性较高的文件大小
	 * @return string
	 */
	public function get_man_file_size(){
		$sizes = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
		$extension = $sizes[0];
		$file_size = $this->file_size_in_byte;

		for($i = 1; (($i < count($sizes)) && ($file_size >= 1024)); $i++){
			$file_size /= 1024;
			$extension = $sizes[$i];
		}
		return round( $file_size, 2 ) . ' ' . $extension;
	}

} 