<?php
/**
 * Created by PhpStorm.
 * User: cqingt
 * Date: 2018/6/15
 * Time: 15:17
 */

require_once 'Taobao.php';

$jd = new Taobao('564532057504');
$result = $jd->saveAll();

echo json_encode($result);
