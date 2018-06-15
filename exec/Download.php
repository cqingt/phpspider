<?php
/**
 * Created by PhpStorm.
 * User: cqingt
 * Date: 2018/6/14
 * Time: 18:19
 */

class Download {

    function getJsonData($url, $header = [], $response = 'json') {
        $this->insertLog($url);

        if(function_exists('curl_init')) {
            $urlArr = parse_url($url);
            $ch = curl_init();

            $header = [
                "Content-type: text/xml;charset=\"utf-8\""
            ];

            if(is_array($header) && !empty($header)){
                $setHeader = array();
                foreach ($header as $k=>$v){
                    $setHeader[] = "$k:$v";
                }
                curl_setopt($ch, CURLOPT_HTTPHEADER, $setHeader);
            }

            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_HEADER,0);

            if (strnatcasecmp($urlArr['scheme'], 'https') == 0) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
            }

            //执行并获取HTML文档内容
            $output = curl_exec($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);

            if (is_array($info) && $info['http_code'] == 200) {
                return $response == 'json' ? json_decode($output, true, JSON_UNESCAPED_UNICODE) : $output;
            } else {
                exit('请求失败（code）：' . $info['http_code']);
            }
        } else {
            throw new Exception('请开启CURL扩展');
        }
    }

    public function downloadImage($url, $path='images/', $prefix = '')
    {
        $ch = curl_init();
        $urlArr = parse_url($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

        if (strnatcasecmp($urlArr['scheme'], 'https') == 0) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        }

        $file = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if (is_array($info) && $info['http_code'] == 200) {
            $this->saveAsImage($url, $file, $path, $prefix);
        } else {
            exit('请求失败（code）：' . $info['http_code']);
        }
    }

    public function mkDir($path)
    {
        $dirs = explode('/', $path);
        $dir = '';
        foreach ($dirs as $val) {
            $dir .= $val . '/';
            if (! is_dir($dir) && strlen($dir)>0)
                mkdir($dir);
        }
    }

    private function insertLog($url) {
        $log = date('Y-m-d H:i:s') . ' request url: ' . $url . PHP_EOL;
        error_log($log, 3, './request.txt');
    }

    private function saveAsImage($url, $file, $path, $prefix)
    {
        //$filename = pathinfo($url, PATHINFO_BASENAME);
        $ext = pathinfo($url, PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;

        if ($prefix) {
            $filename = $prefix . '_' . $filename;
        }

        $resource = fopen($path . $filename, 'a');
        fwrite($resource, $file);
        fclose($resource);
    }
}


function crabImage($imgUrl, $saveDir='./images', $fileName=null){
    if(empty($imgUrl)){
        return false;
    }

    //获取图片信息大小
    $imgSize = getImageSize($imgUrl);
    if(!in_array($imgSize['mime'],array('image/jpg', 'image/gif', 'image/png', 'image/jpeg'),true)){
        return false;
    }

    //获取后缀名
    $_mime = explode('/', $imgSize['mime']);
    $_ext = '.'.end($_mime);

    if(empty($fileName)){  //生成唯一的文件名
        $fileName = uniqid(time(),true).$_ext;
    }

    //开始攫取
    ob_start();
    readfile($imgUrl);
    $imgInfo = ob_get_contents();
    ob_end_clean();

    if(!file_exists($saveDir)){
        mkdir($saveDir,0777,true);
    }
    $fp = fopen($saveDir.$fileName, 'a');
    $imgLen = strlen($imgInfo);    //计算图片源码大小
    $_inx = 1024;   //每次写入1k
    $_time = ceil($imgLen/$_inx);
    for($i=0; $i<$_time; $i++){
        fwrite($fp,substr($imgInfo, $i*$_inx, $_inx));
    }
    fclose($fp);

    return array('file_name'=>$fileName,'save_path'=>$saveDir.$fileName);
}