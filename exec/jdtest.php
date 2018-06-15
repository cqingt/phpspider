<?php
/**
 * Created by PhpStorm.
 * User: cqingt
 * Date: 2018/6/15
 * Time: 15:17
 */

require_once 'Jingdong.php';

$url = '';
$pattern = '/\/(\w+)\.html/';
$jd = new Jingdong($url, $pattern);
$result = $jd->saveAll();

echo json_encode($result);