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
    protected $_detailUrl;  // 详情页URL
    protected $_goodsId;    // 商品ID
    protected $_skuId;
    protected $_html;       // 详情页html
    protected $_handler;    // 处理对象
    protected $_path;       // 图片保存路径
    protected $_pattern;    // goodId 的正则

    /**
     * Spider constructor.
     * @param $url string 请求地址
     * @param $pattern
     * @param $goodsId
     * @param $skuId
     */
    public function __construct($url, $pattern, $goodsId = 0, $skuId = 0)
    {
        $this->_detailUrl = $url;
        $this->_pattern  = $pattern;
        $this->_goodsId  = $this->_getGoodsId();
        $this->_skuId    = $skuId;
        $this->_handler  = new Handler();
        $this->_html     = requests::get($this->_detailUrl);

        $platform = strtolower(static::class);
        $this->_path     = "images/$platform/{$this->_goodsId}/";

        $this->_makeDir();
    }

    /**
     * 保存缩略图
     */
    public abstract function saveThumb();

    /**
     * 保存详情图
     */
    public abstract function saveProduct();
    /**
     * 同时保存缩略图、详情图
     */
    public function saveAll()
    {
        $thumb  = $this->saveThumb();
        $detail = $this->saveProduct();

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
        $url = $this->_detailUrl;

        if (stripos($url, '_sku_')) {
            $url = str_replace('_sku_', $this->_skuId, $url);
        }

        return str_replace('_goodsId_', $this->_goodsId, $url);
    }

    protected function _getGoodsId()
    {
        preg_match_all($this->_pattern , $this->_detailUrl, $output);

        $goodsId = 0;
        if (! empty($output)) {
            $goodsId = $output[1][0];
        }

        return $goodsId;
    }
}