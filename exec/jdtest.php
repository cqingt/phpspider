<?php
/**
 * Created by PhpStorm.
 * User: cqingt
 * Date: 2018/6/15
 * Time: 15:17
 */

require_once 'Jingdong.php';

$jd = new Jingdong('10186809346');
$result = $jd->saveAll();

echo json_encode($result);