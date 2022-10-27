<?php
/**
* 文件处理器
*
* @version        $Id: data.file.php 2016年5月12日 20:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//重命名文件或者文件夹操作
if ( $type == 'rename' )
{
	$path = str::ClearPath($post['path'],true);
	$oldName = $post['oldname'];
	$newName = $post['newname'];
	
	if( $oldName == '' || $newName == '' )
	{
		Ajax( '对不起，旧的文件名和新的文件名必填写!' , 300);
	}
	else if( str::in_string('../',$path,1) || str::in_string('..',$path,1))
	{
		Ajax('对不起，文件路径错误！' , 300);
	}
	else
	{
		if( file_exists(WMROOT.$path.$newName) )
		{
			Ajax( '对不起，已存在重名文件或文件夹!' , 300);
		}
		else
		{
			rename(WMROOT.$path.str::EnCoding($oldName,'gb2312'),WMROOT.$path.$newName);
			Ajax( '恭喜您，文件或文件夹重名成功!');
		}
	}
}
//删除文件或者文件夹操作
else if( $type == 'del' )
{
	$path = str::ClearPath(Request('path'));
	$dt = Request('dt');

	if( str::in_string('../',$path,1) || str::in_string('..',$path,1))
	{
		Ajax('对不起，文件路径错误！' , 300);
	}
	else
	{
		//删除文件夹
		if( $dt == 'folder' )
		{
			$path = str::ClearPath($path,true);
			file::DelDir(WMROOT.$path);
			Ajax( '恭喜您，文件夹删除成功!');
		}
		//删除文件
		else
		{
			file::DelFile(WMROOT.$path);
			Ajax( '恭喜您，文件删除成功!');
		}
	}
}
//创建文件夹
else if( $type == 'createfolder' )
{
	$path = str::ClearPath(Request('path'),true);
	$newName = Request('newname');
	
	if( str::in_string('../',$path,1) || str::in_string('..',$path,1))
	{
		Ajax('对不起，文件路径错误！' , 300);
	}
	else
	{
		if( $newName == '' )
		{
			Ajax( '对不起，新的文件夹名必填写!' , 300);
		}
		//创建文件夹
		else
		{
			file::CreateFolder(WMROOT.$path.$newName);
			Ajax( '恭喜您，文件夹创建成功!');
		}
	}
}
//移动文件夹
else if( $type == 'movefile' )
{
	$oldPath = str::ClearPath(Request('oldpath'),true);
	$newPath = str::ClearPath(Request('newpath'),true);
	$fileName = Request('file');
	
	if( $newPath == '' )
	{
		Ajax( '对不起，移动的位置不能为空!' , 300);
	}
	else if( str_replace('../', '', $newPath) != $newPath )
	{
		Ajax( '对不起，禁止使用../符号' , 300);
	}
	//创建文件夹
	else
	{
		//如果新的目录不是以/开头
		if( substr( $newPath, 0, 1 ) != '/' )
		{
			$newPath = $oldPath.$newPath;
		}
		else
		{
			$newPath = ltrim($newPath, "/");
		}
		//如果最后不是以斜杠结尾
		if( substr($newPath, -1) != '/' )
		{
			$newPath = $newPath.'/';
		}
		
		file::MoveFile(WMROOT.$oldPath, WMROOT.$newPath, $fileName);
		Ajax( '恭喜您，文件移动成功!');
	}
}
//创建文件
else if( $type == "create" || $type == "edit" )
{
	$path = str::ClearPath(Request('path'),true);
	$fileName = str::ClearPath(Request('filename'));
	$fileContent = stripslashes($_POST['content']);
	//如果保存的目录是以/开头，就替换掉
	if( substr( $path, 0, 1 ) == '/' )
	{
		$path = ltrim($path, "/");
	}

	if( $fileName == '' )
	{
		Ajax( '对不起，文件名字不能为空' , 300);
	}
	else if( str_replace('../', '', $path) != $path )
	{
		Ajax( '对不起，禁止使用../符号' , 300);
	}
	else if( file_exists(WMROOT.$path.$fileName) && $type == "create")
	{
		Ajax( '对不起，已经存在同名文件！' , 300);
	}
	else if( !file_exists(WMROOT.$path.$fileName) && $type == "edit")
	{
		Ajax( '对不起，此文件不存在！' , 300);
	}
	else
	{
		if( $type == 'create' )
		{
			file::CreateFile(WMROOT.$path.$fileName, $fileContent);
			Ajax( '恭喜您，文件创建成功！' );
		}
		else
		{
			file::CreateFile(WMROOT.$path.$fileName, $fileContent , '1');
			Ajax( '恭喜您，文件修改成功！' );
		}
	}
}
//备份程序
else if( $type == 'backup' )
{
	$zip = NewClass('pclzip','../'.time().'.zip');
	if( $zip->create( WMROOT,PCLZIP_OPT_REMOVE_PATH,WMROOT) == 0)
	{
		Ajax( $zip->errorInfo(true) , 300 );
	}
	else
	{
		Ajax( '备份成功' );
	}
}
//下载文件
else if( $type == 'down' )
{
	$path = str::ClearPath(Request('path'),true);
	$fileName = str::ClearPath(Request('file'));
	
	Header( "Content-type:  application/octet-stream "); 
	Header( "Accept-Ranges:  bytes "); 
	Header( "Accept-Length: " .filesize(WMROOT.$path.$fileName));
	header( "Content-Disposition:  attachment;  filename= {$fileName}"); 
	readfile(WMROOT.$path.$fileName);
}
?>