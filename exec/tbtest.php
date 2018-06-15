<?php
/**
 * Created by PhpStorm.
 * User: cqingt
 * Date: 2018/6/15
 * Time: 15:17
 */

require_once 'Taobao.php';

$url = 'https://item.taobao.com/item.htm?id=555597675310&ali_refid=a3_430406_1007:120728858:T:1606819119895735509_0_100:106ede04fb53d2f2ea13cef869cd8a7c&ali_trackid=31_106ede04fb53d2f2ea13cef869cd8a7c&spm=a21bo.2017.201874-sales.33';
$pattern = '/[&?]id=(\d+)/';
$jd = new Taobao($url, $pattern);
$result = $jd->saveAll();

echo json_encode($result);
