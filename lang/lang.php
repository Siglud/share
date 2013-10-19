<?php
/**
 * 语言
 * Created by JetBrains PhpStorm.
 * User: Rince
 * Date: 13-10-19
 * Time: 下午4:06
 * To change this template use File | Settings | File Templates.
 */

namespace lang;


interface lang {
    public function get_lang($name);
}


class zh_cn implements lang {
    private $lang_data = array(
        'title' => '漫游分享站',
    );

    public function get_lang($name)
    {
        return $this->lang_data;
    }
}

class zh_tw implements lang {
    private $lang_data = array(
        'title' => '漫游分享站',
    );

    public function get_lang($name)
    {
        return $this->lang_data;
    }
}

class en_us implements lang {
    private $lang_data = array(
        'title' => 'popgo share',
    );

    public function get_lang($name)
    {
        return $this->lang_data;
    }
}