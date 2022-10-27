<?php
/**
* 版本请求处理器
*
* @version        $Id: cloud.version.php 2017年2月25日 13:57  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$cloudSer = NewClass('cloud');

//获得下一版本
if ( $type == 'getnext' )
{
	$rs = $cloudSer->GetVersionNext(1);
	Ajax('请求成功！',200,$rs);
}
//获得最新版本
else if ( $type == 'getnew' )
{
	$rs = $cloudSer->GetNewVer();
	Ajax('请求成功！',200,$rs);
}
//开始升级
else if ( $type == 'update' )
{
	$adminPath = $C['config']['web']['admin_path'];
	
	if( $adminPath == '' )
	{
		Ajax('对不起，请在系统管理-网站设置-基本设置里面设置后台目录，否则无法进行升级操作！',300);
	}
	else if( !is_dir(WMROOT.$adminPath) )
	{
		Ajax('对不起，网站设置的后台目录不存在无法进行升级！',300);
	}
	else
	{
		$rs = $cloudSer->GetVersionNext(1);
		if( isset($rs['data']) )
		{
			if( $rs['data']['version_down'] == '0' )
			{
				Ajax('对不起，该版本不允许后台在线升级，请到官网下载手动更新！',300);
			}
			//总大小
			Session('update_size' , $rs['data']['version_size']);
			if( $rs['data']['version_down'] == 1 )
			{
				//获得文件的名字
				$file = str::GetLast($rs['data']['version_downurl'],'/');
				list($fileName,$fileExt) = explode('.', $file);
				$zipFilePath = WMROOT.'upload/update/'.$fileName;
				
				//检查是否下载过文件了,下载过就删除文件。
				if( file_exists($zipFilePath.'.'.$fileExt) )
				{
					file::DelFile($zipFilePath.'.'.$fileExt);
				}
				//下载服务器升级补丁
				Session('update_downLen' , $rs['data']['version_size']);
				file::DownloadFile($rs['data']['version_downurl'],2,WMROOT.'upload/update','update');
				
				$zip = NewClass('pclzip',$zipFilePath.'.'.$fileExt);
				//解压缩到当前id文件夹下面
				if ( $zip->extract(PCLZIP_OPT_PATH, $zipFilePath) )
				{
					//如果升级文件存在就执行sql。
					if( file_exists($zipFilePath.'/update.php') )
					{
						require_once $zipFilePath.'/update.php';
					}
					//列出code里面的文件并且移动
					file::FileAll($zipFilePath.'/code');
					foreach(file::$fileList as $k=>$v)
					{
						$mpath = str_replace($zipFilePath.'/code','',$v['path']);
						//如果是后台文件,并且当前文件夹不等于后台文件夹
						if( $adminPath != 'admin' && substr($mpath,0,6) == '/admin')
						{
							$mpath = str_replace('/admin',$adminPath,$mpath);
						}
						file::CopyFile($v['path'] , WMROOT.$mpath , $v['file']);
					}
					//修改版本
					SetVersion($rs['data']['version_number'],date('Ymd', strtotime($rs['data']['version_addtime'])));
				}
	
				$cloudSer->SetUpdateLog(WMVER,$rs['data']['version_number']);

				//写入操作记录
				SetOpLog( '对程序进行了升级！' , 'system' , 'update' );
				Ajax('恭喜您，升级成功，请重新登录后台以便获得最新版本的体验!',200,$zipFilePath);
			
			}
			else
			{
				Ajax('对不起，当前版本无需升级！',300);
			}
		}
		else
		{
			Ajax('对不起，没有最新版本了！',300);
		}
	}
}
//升级进度查询
else if ( $type == 'getbarline' )
{
	//总大小
	$size = Session('update_size');
	$downSize = Session('update_downLen');
	
	if($size > 0 )
	{
		Session('update_size',0);
		Session('update_downLen',0);
		
		$data['success'] = 0;
		$data['barline'] = round($downSize/$size,2)*100;
		
		if( $downSize == $size)
		{
			$data['success'] = 1;
			Ajax('下载中...' , 201 , $data);
		}
		Ajax('下载中...' , 200 , $data);
	}
	else
	{
		Ajax('下载失败！',300);
	}
}
?>