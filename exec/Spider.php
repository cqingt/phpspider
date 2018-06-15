<?php
/**
 * 图片采集基类
 * Created by PhpStorm.
 * User: cqingt
 * Date: 2018/6/15
 * Time: 14:56
 */
require_once __DIR__ . '/../autoloader.php';
require_once 'Handler.php';

use phpspider\core\requests;

abstract class Spider
{
    protected static $_detailUrl;  // 详情页URL
    protected $_goodsId;    // 商品ID
    protected $_html;       // 详情页html
    protected $_handler;    // 处理对象
    protected $_path;       // 图片保存路径

    public function __construct($goodsId)
    {
        $this->_goodsId  = $goodsId;
        $this->_handler  = new Handler();
        $this->_html     = requests::get($this->_getDetailUrl());
        $this->_path     = "images/jd_{$goodsId}/";

        $this->_makeDir();
    }

    /**
     * 保存缩略图
     */
    public abstract function saveThumb();

    /**
     * 保存详情图
     */
    public abstract function saveDetail();
    /**
     * 同时保存缩略图、详情图
     */
    public function saveAll()
    {
        $thumb  = $this->saveThumb();
        $detail = $this->saveDetail();

        return array_merge($thumb, $detail);
    }

    /**
     * 创建商品目录
     */
    private function _makeDir()
    {
        $this->_handler->mkDir($this->_path);
    }

    /**
     * 获取详情页
     * @return mixed
     */
    protected function _getDetailUrl()
    {
        return str_replace('_goodsId_', $this->_goodsId, static::$_detailUrl);
    }
}