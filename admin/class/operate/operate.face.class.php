<?php
use Qiniu\json_decode;
/**
 * 表情的类文件
 *
 * @version        $Id: operate.face.class.php 2017年5月11日 9:20  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class OperateFace
{
	
	/**
	 * 获得表情文件数组
	 */
	function GetFaceArr()
	{
		//获得表情文件夹列表
		$path = '../files/face/';
		$floderArr = file::FloderList( $path );
		$faceArr = array();
		//查询所有模版
		if( $floderArr )
		{
			foreach ($floderArr as $k=>$v)
			{
				$faceArr[] = GetTempCopy( $v['file'] , $path);
			}
		}
		return $faceArr;
	}
	
	
	/**
	 * 创建表情的json配置文件
	 * @param 参数1，必须，需要生成的表情配置文件名
	 * @param 参数2，必须，需要生成的表情数组
	 * @param 参数2，必须，表情文件配置组
	 */
	function CreateFaceJson($faceName , $face , $faceArr = array())
	{
		if( !$faceArr )
		{
			$faceArr = $this->GetFaceArr();
		}

		$json = '[]';
		$jsonArr = array();
		if( $face )
		{
			foreach ($faceArr as $k=>$v)
			{
				if( in_array($v['path'], $face) )
				{
					$jsonArr[] = '{"floder": "'.$v['path'].'","title": "'.$v['name'].'","url": "'.$v['url'].'","number": "'.$v['number'].'"}';
				}
			}
			$json = '['.implode(',', $jsonArr).']';
		}

		file::CreateFile(WMFILE.'face/'.$faceName.'.json' ,  $json , 1);
		return true;
	}
	
	/**
	 * 获得表情的json配置
	 * @param 参数1，必须 表情的名字
	 */
	function GetFaceJson($faceName)
	{
		return file::GetFile(WMFILE.'face/'.$faceName.'.json');
	}
	/**
	 * 获得表情的安装信息
	 * @param 参数1，必须 表情的名字
	 */
	function GetFaceInstall($faceName)
	{
		$data = array();
		$arr = json_decode($this->GetFaceJson($faceName),true);
		foreach($arr as $k=>$v)
		{
			$data[] = $v['floder'];
		}
		return $data;
	}
}
?>