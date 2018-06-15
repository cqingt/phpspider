<?php
/**
 * 天猫商城，详情页的缩略图和详情图采集
 * Created by PhpStorm.
 * User: cqingt
 * Date: 2018/6/15
 * Time: 14:56
 */
require_once __DIR__ . '/../autoloader.php';
require_once 'Handler.php';
require_once 'Spider.php';

use phpspider\core\phpspider;
use phpspider\core\requests;
use phpspider\core\selector;

class Tianmao extends Spider
{
    // 详情页URL
    //protected $_detailUrl = "https://detail.tmall.com/item.htm?id=_goodsId_&skuId=_sku_";

    /**
     * 保存缩略图
     */
    public function saveThumb()
    {
        // 选择器规则 淘宝中，使用以下方法 获取缩略图有问题，直接过滤了html.找不到图片，故使用正则
         $selector = "//*[@id=\"J_UlThumb\"]";

        // 匹配结果
         $result = selector::select($this->_html, $selector);

        // 获取缩略图
        $thumbs = selector::select($result, '@src=\"(.*?)\"@', "regex");

        // 保存缩略图
        if (! empty($thumbs) && is_array($thumbs)) {
            foreach ($thumbs as $key => $thumb) {
                $thumb = str_replace('50x50', '400x400', $thumb);
                $thumb = str_replace('60x60', '430x430', $thumb);
                $this->_handler->downloadImage('http:' . $thumb, $this->_path, 'thumb_' . $key);
            }
        }

        return ['thumb' => count($thumbs)];
    }

    /**
     * 保存详情产品图
     */
    public function saveProduct()
    {
        // 从html里获取详情的url
        $descUrl = selector::select($this->_html, '@httpsDescUrl\":\"(.*?)\"@', 'regex');

        if (empty($descUrl)) {
            exit('请求详情页图片的URL出错');
        }

        // 详情页爬取
        $detailUrl = 'https:' . $descUrl;
        $data = $this->_handler->getJsonData($detailUrl, [], 'string');

        $details = selector::select($data, '@src=\"(.*?)\"@','regex');

        // 产品详情图片作为 backgroud
        if (empty($details)) {
            $details = selector::select($data, '@url\((.*?)\)@', 'regex');
        }

        if (! empty($details) && is_array($details)) {
            foreach ($details as $key => $detail) {
                $this->_handler->downloadImage($detail, $this->_path, 'detail_' . $key);
            }
        }

        return ['product' => count($details)];
    }
}