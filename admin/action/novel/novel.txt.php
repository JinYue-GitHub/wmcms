<?php
/**
* 小说导入TXT处理器
*
* @version        $Id: novel.txt.php 2019年02月20日 18:05  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$txtMod = NewModel('novel.txt');
$novelSer = AdminNewClass('novel.novel');
$chapterSer = AdminNewClass('novel.chapter');
$novelMod = NewModel('novel.novel');
$chapterMod = NewModel('novel.chapter');
$volumeMod = NewModel('novel.volume');

//初始化TXT
if ( $type == 'init' )
{
	$expData['exp_path'] = WMROOT.Post('path');
	$expData['exp_type'] = Post('exp_type');
	$expData['exp_str'] = Post('exp_str');
	$expData['exp_title'] = Post('exp_title');
	$expData['exp_volume'] = Post('exp_volume');
	$expData['exp_indent'] = Post('exp_indent');
	if( !file_exists($expData['exp_path']) )
	{
		Ajax('对不起，需要导入的TXT不存在！',300);
	}
	else
	{
		$result = $txtMod->ExpChapter($expData);
		Ajax('TXT初始化成功!',200,$result);
	}
}
//删除TXT
else if ( $type == 'del' )
{
	$path = Post('path');
	if( file::DelFile(WMROOT.$path) == false )
	{
		Ajax('对不起，需要删除的TXT不存在或已被删除！',300);
	}
	else
	{
		Ajax('TXT删除成功!');
	}
}
//导入txt操作
else if ( $type == 'import' )
{
	$nid = Post('nid');
	$expData['exp_path'] = WMROOT.Post('path');
	$expData['exp_type'] = Post('exp_type');
	$expData['exp_str'] = Post('exp_str');
	$expData['exp_title'] = Post('exp_title');
	$expData['exp_volume'] = Post('exp_volume');
	$expData['exp_indent'] = Post('exp_indent');
	
	if( !file_exists($expData['exp_path']) )
	{
		Ajax('对不起，该TXT已经导入或者文件不存在！',300);
	}
	else
	{
		//取消时间限制
		set_time_limit(0);
		//分割处理章节
		$result = $txtMod->ExpChapter($expData);
		if( $result['chapter'] < 1 || empty($result['list']))
		{
			//删除小说
			file::DelFile($expData['exp_path']);
			Ajax('对不起，导入的TXT无法读取章节！',300);
		}
		else
		{
    		##分卷处理开始##
    		$volumeMod->InsertNoExist($nid,array_column($result['list'],'volume'));
    		$volumeList = $volumeMod->GetByNid($nid);
            $volumeList = array_column($volumeList,'volume_id','volume_name');
            ##分卷处理结束##
            //小说配置和html模型
			$novelConfig = AdminInc('novel');
			$htmlMod = NewModel('system.html' , array('module'=>$curModule));
			$isTxt = $novelConfig['data_type'];
    		//小说书籍信息
    		$novelData = $novelMod->GetOne($nid);
			$wordNumber = $novelData['novel_wordnumber'];
			//章节开始的顺序
			$chapterOrder = 0;
			
			//循环匹配到的章节
			foreach ($result['list'] as $key=>$val)
			{
    			foreach ($val['chapter'] as $k=>$v)
    			{
    			    $title = $v['title'];
    				$content = str::ToTxt($v['content']);
    				$data['chapter_istxt'] = $isTxt;
    				$data['chapter_number'] = str::StrLen(str::DelSymbol($content));
    				$data['chapter_name'] = $title;
    				$data['chapter_vid'] = isset($volumeList[$val['volume']])?$volumeList[$val['volume']]:1;
    				$data['chapter_nid'] = $nid;
    				$data['chapter_time'] = time();
    				$data['chapter_order'] = $chapterOrder;
    				//插入章节
    				$insertId = $chapterMod->Insert($data);
    				//创建小说文章内容
    				$chapterMod->CreateChapter( 'add' , $nid , $insertId , $content);
    				//创建HTML
    				$htmlMod->CreateContentHtml($insertId);
    				//更新小说主txt文件存储地址
    				$chapterMod->SaveChapterPath($novelData['type_id'],$nid,$insertId);
    	
    				//计算累积字数
    				$wordNumber += $data['chapter_number'];
    				//第一章的id
    				if( $chapterOrder == 1 )
    				{
    					$firstId = $insertId;
    					$firstTitle = $title;
    				}
    				//最新章节和顺序自增
    				$lastId = $insertId;
    				$lastTitle = $title;
    				$chapterOrder++;
    			}
			}
			
			//更新小说字数
			if( $novelData['novel_startcid'] == '0'  )
			{
				$novelUpdate['novel_startcid'] = $firstId;
				$novelUpdate['novel_startcname'] = $firstTitle;
			}
			$novelUpdate['novel_newcid'] = $lastId;
			$novelUpdate['novel_newcname'] = $lastTitle;
			$novelUpdate['novel_chapter'] = array('+',$result['chapter']);
			$novelUpdate['novel_wordnumber'] = array('+',$wordNumber);
			$novelUpdate['novel_uptime'] = time();
			$novelMod->Update($novelUpdate, $nid);
		}
		
		//删除TXT
		file::DelFile($expData['exp_path']);
		//写入操作记录
		SetOpLog( '导入了小说'.$novelData['novel_name'] , 'novel' , 'insert');
		Ajax('恭喜您，TXT导入成功！');
	}
}
?>