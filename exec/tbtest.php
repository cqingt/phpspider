<?php
/**
 * Created by PhpStorm.
 * User: cqingt
 * Date: 2018/6/15
 * Time: 15:17
 */

require_once 'Taobao.php';

$url = '';
$pattern = '/&id=(\d+)/';
$jd = new Taobao($url, $pattern);
$result = $jd->saveAll();

echo json_encode($result);
