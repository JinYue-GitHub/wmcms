<?php
//header('Access-Control-Allow-Origin: http://www.baidu.com'); //设置http://www.baidu.com允许跨域访问
//header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
//引入网站文件判断权限。
//设置模块和类文件
$C['module']['inc']['class']=array('file','str');
$isManager = True;
$msg = array();
require_once '../../../../../../../wmcms/inc/common.inc.php';
require_once '../../../../../../function.php';
//检查管理员登录
if( CheckLogin() == 'login' )
{
	$msg['state'] = '对不起，登录超时！';
	die(json_encode($msg));
}

date_default_timezone_set("Asia/chongqing");
error_reporting(E_ERROR);
header("Content-Type: text/html; charset=utf-8");

$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("config.json")), true);
$action = $_GET['action'];
$module = $_GET['module'];
$type = $_GET['type'];

switch ($action) {
    case 'config':
        $result =  json_encode($CONFIG);
        break;

    /* 上传图片 */
    case 'uploadimage':
    /* 上传涂鸦 */
    case 'uploadscrawl':
    /* 上传视频 */
    case 'uploadvideo':
    /* 上传文件 */
    case 'uploadfile':
        $result = include("action_upload.php");
        break;

    /* 列出图片 */
    case 'listimage':
        $result = include("action_list.php");
        break;
    /* 列出文件 */
    case 'listfile':
        $result = include("action_list.php");
        break;

    /* 抓取远程文件 */
    case 'catchimage':
        $result = include("action_crawler.php");
        break;

    default:
        $result = json_encode(array(
            'state'=> '请求地址出错'
        ));
        break;
}

/* 输出结果 */
if (isset($_GET["callback"])) {
    if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
        echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
    } else {
        echo json_encode(array(
            'state'=> 'callback参数不合法'
        ));
    }
} else {
	$result = json_decode($result,true);
	if( $action == 'uploadimage' || $action == 'uploadscrawl' || $action == 'uploadvideo' || $action == 'uploadfile' )
	{
		$imgPath = WMROOT.$result['url'];
		$ext = str_replace('.', '', $result['type']);
		$altArr = explode('.', $result['original']);
		
		//如果上传的是附件，并且上传成功，并且上传的模块不为空。就使用公共下载模块
		if( $action == 'uploadfile' && $result['state'] == 'SUCCESS' && $module != '' )
		{
			//设置文件基础数据
			$uploadData['ext'] = $ext;
			$uploadData['file'] = $result['url'];
			$uploadData['alt'] = $altArr[0];
			$uploadData['size'] = $result['size'];
			
			//new上传模型
			$uploadMod = NewModel('upload.upload');
			//设置参数
			$uploadMod->module = $module;
			$uploadMod->type = $type;
			$uploadMod->cid = 0;
			$uploadMod->uploadData = $uploadData;
			//插入上传记录
			$result['file_id'] = $uploadMod->Insert();
			//重写下载url
			$result['url'] = '[file:'.$result['file_id'].']'.$result['original'].'[/file]';
		}
		//如果是上传图片和下载远程图片
		if( $action == 'uploadimage' || $action == 'catchimage' )
		{
			$imgSer = NewClass('img');
			//图片裁剪
			$imgSer->ImgCut( $imgPath , '0' , '0');
			//图片加水印
			$imgSer->WaterMark( $imgPath );
		}
	}
	$result = json_encode($result);
    die($result);
}