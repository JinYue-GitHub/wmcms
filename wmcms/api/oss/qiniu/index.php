<?php
require_once 'autoload.php';

// 引入鉴权类
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

// 要上传的空间
$bucket = 'wmcmsser';
// 需要填写你的 Access Key 和 Secret Key
$accessKey = '2U_H0dWKJVwbyWcZaUPq76VevDiCpfALPCoaOs2r';
$secretKey = 'BmcodWcG0mOJGW8GSvlWxlwW6KeuFnCNAtJDMKvg';

// 构建鉴权对象
$auth = new Auth($accessKey, $secretKey);
// 生成上传 Token
$token = $auth->uploadToken($bucket);


// 要上传文件的本地路径
$filePath = './111.jpg';
// 上传到七牛后保存的文件名
$key = '123123.jpg';

// 初始化 UploadManager 对象并进行文件的上传。
$uploadMgr = new UploadManager();
// 调用 UploadManager 的 putFile 方法进行文件的上传。
list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
echo "\n====> putFile result: \n";
if ($err !== null) {
    var_dump($err);
} else {
    var_dump($ret);
}
