<?php
/**
 * 京东商城，详情页的缩略图和详情图采集
 * Created by PhpStorm.
 * User: cqingt
 * Date: 2018/6/15
 * Time: 14:56
 */
require_once __DIR__ . '/../autoloader.php';
require_once 'Handler.php';
require_once 'Spider.php';

use phpspider\core\selector;

class Jingdong extends Spider
{
    protected static $_detailUrl = "https://item.jd.com/product/_goodsId_.html";   // 详情页URL

    /**
     * 保存缩略图
     */
    public function saveThumb()
    {
        // 缩略图选择器规则
        $selector = "//*[@id=\"spec-list\"]";

        // 匹配结果
        $result = selector::select($this->_html, $selector);

        // 缩略图爬取
        $thumbs = selector::select($result, '@src="(.*?)"@','regex');

        // 保存图片
        if (! empty($thumbs) && is_array($thumbs)) {
            foreach ($thumbs as $key => $thumb) {
                $thumb = str_replace('s54x54', 's450x450', $thumb); // 450 图片, sku缩略图 40
                $thumb = str_replace('com/n5/', 'com/n1/', $thumb); // 小图换大图, sku缩略图 n5
                $this->_handler->downloadImage('http:' . $thumb, $this->_path, 'thumb_' . $key);
            }
        }

        return ['thumb' => count($thumbs)];
    }

    /**
     * 保存详情图
     */
    public function saveDetail()
    {
        // 从html里获取详情的url
        $descUrl = selector::select($this->_html, '@cd.jd.com\/description\/channel(.*?)\'@', 'regex');

        // 详情页url拼接
        $detailUrl = "http://cd.jd.com/description/channel" . $descUrl;

        // 请求，返回数据：showdesc({})
        $data = $this->_handler->getJsonData($detailUrl, [], 'string');

        // 匹配图片地址
        $details = selector::select($data, '@data-lazyload=\\\"(.*?)\\\"@','regex');

        // 部分产品详情图片，是通过设置 backgroud 的url来展示的
        if (empty($details)) {
            $details = selector::select($data, '@url\((.*?)\)@', 'regex');
        }

        // 保存图片
        if (! empty($details) && is_array($details)) {
            foreach ($details as $key => $detail) {
                $this->_handler->downloadImage('http:' . $detail, $this->_path, 'detail_' . $key);
            }
        }

        return ['detail' => count($details)];
    }
}