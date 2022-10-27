<?php
function classLoader($class)
{
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . DIRECTORY_SEPARATOR .'src'. DIRECTORY_SEPARATOR . $path . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}
spl_autoload_register('classLoader');

use OSS\OssClient;
use OSS\Core\OssException;

$accessKeyId = "LTAI1M09yc9Fc1OG";
$accessKeySecret = "l3j19YkMtwhtBeEIylkwdY21Q5AOXH";
$endpoint = "http://oss-cn-shenzhen.aliyuncs.com";
$bucket= "wmcmstest1";
$object = "example.jpg";
try {
    $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
    $ossClient->uploadFile($bucket, $object, __FILE__);
} catch (OssException $e) {
    print_r($e->getMessage());
}