<?php
/**
 * Created by PhpStorm.
 * User: cqingt
 * Date: 2018/6/15
 * Time: 15:17
 */

require_once 'Tianmao.php';

$url = 'https://detail.tmall.com/item.htm?spm=a220o.1000855.0.0.770c3e6duZrf2V&abtest=_AB-LR67-PR67&pvid=bf360f1a-c944-4c25-a39b-a71b57ff2c68&pos=8&abbucket=_AB-M67_B14&acm=03067.1003.1.1977615&id=566681820248&scm=1007.12776.82642.100200300000000&sku_properties=5919063:6536025';
$url = 'https://detail.tmall.com/item.htm?id=45324116115&spm=875.7931836/B.2017077.4.66144265Tj0qv4&scm=1007.12144.81309.73263_0&pvid=f8edac6f-a78a-40e4-8f8e-2a370264997e&utparam={%22x_hestia_source%22:%2273263%22,%22x_mt%22:8,%22x_object_id%22:45324116115,%22x_object_type%22:%22item%22,%22x_pos%22:3,%22x_pvid%22:%22f8edac6f-a78a-40e4-8f8e-2a370264997e%22,%22x_src%22:%2273263%22}';
$url = 'https://detail.tmall.com/item.htm?id=45324116115';
$url = 'https://detail.tmall.com/item.htm?spm=608.7065813.ne.1.45a0c2c3hqPhXJ&id=557890457365&tracelog=jubuybigpic';
$jd = new Tianmao($url, '/[?&]id=(\d+)/');
$result = $jd->saveAll();

echo json_encode($result);
