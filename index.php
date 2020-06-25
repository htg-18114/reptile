<?php
/**
 * 随机图片远程采集案例
 * @author **
 * @url    http://baiduhtg.qicp.vip
 * @time   2020-6-23
 */
header('Content-type: application/json');
     
//图片接口地址
$url = 'https://cdn.mom1.cn/?mom=302';
// 图片存放文件夹
$path = 'images/';
//获取图片真实地址
$url = url_get($url);
//获取文件名
$filenames = basename($url);
     
$file_c = $path . $filenames;
     
if (file_exists($file_c)) {
        //文件已经存在
        echo json_encode(array('url' => $url, 'filename' => $filenames, 'state' => '202'));
} else {
    if (download($url, $path)) {
        //采集成功
        echo json_encode(array('url' => $url, 'filename' => $filenames, 'state' => '200'));
    } else {
            //采集失败
        echo json_encode(array('url' => $url, 'filename' => $filenames, 'state' => '201'));
    }
    
}
     
function url_get($url) {
        // 获取图片真实地址
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // 下面两行为不验证证书和 HOST，建议在此前判断 URL 是否是 HTTPS
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // $ret 返回跳转信息
        $ret = curl_exec($ch);
        // $info 以 array 形式返回跳转信息
        $info = curl_getinfo($ch);
     
        // 记得关闭 curl
        curl_close($ch);
        // 跳转后的 URL 信息
        return $info['url'];
}
     
    function download($url, $path = 'images/') {
        //远程下载保存
        if (!file_exists($path)) {
            mkdir("$path", 0777, true);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $file = curl_exec($ch);
        curl_close($ch);
        $filename = pathinfo($url, PATHINFO_BASENAME);
        $resource = fopen($path . $filename, 'a');
        fwrite($resource, $file);
        fclose($resource);
        return true;
}
