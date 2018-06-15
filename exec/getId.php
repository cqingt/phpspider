<?php
/**
 * Created by PhpStorm.
 * User: cqingt
 * Date: 2018/6/15
 * Time: 17:27
 */

$url = 'https://detail.tmall.com/item.htm?spm=a220o.1000855.0.0.770c3e6duZrf2V&abtest=_AB-LR67-PR67&pvid=bf360f1a-c944-4c25-a39b-a71b57ff2c68&pos=8&abbucket=_AB-M67_B14&acm=03067.1003.1.1977615&id=566681820248&scm=1007.12776.82642.100200300000000&sku_properties=5919063:6536025';

preg_match_all('/&id=(\d+)/', $url, $goods);
preg_match_all('/&skuId=(\w+)/', $url, $sku);
preg_match_all('/&sku_properties=(\w+:\w+)/', $url, $property);

$goodsId = $skuId = '0';

if (! empty($goods)) {
    $goodsId = $goods[1];
}

if (! empty($sku)) {
    $skuId = $sku[1];
}

if (! empty($property)) {
    $skuId = $property[1];
}

echo json_encode(['goodsId' => $goodsId, 'skuId' => $skuId]);