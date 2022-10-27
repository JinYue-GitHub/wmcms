<?php
/**
* 获得章节的信息
*
* @version        $Id: get_chapter.php 2021年09月05日 13:05  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$nid = str::Int(Request('nid'));
$cid = str::Int(Request('cid'));
$istxt = Request('istxt' , 0);
//根据小说父级分类查询
if( $cid > 0 && $nid>0 )
{
	$code = 201;
	//是否是作者的小说
	$novelMod = NewModel('novel.novel');
	$novelWhere['novel_id'] = $nid;
	$novelWhere['author_id'] = $author['author_id'];
	$novelData = $novelMod->GetOne( $novelWhere );
	if( !$novelData )
	{
		$code = 201;
	}
	else
	{
		//章节数据是否存在
		$chapterMod = NewModel('novel.chapter');
		$data = $chapterMod->GetById( str::Int($cid) );
		if( !$data )
		{
			$code = 201;
		}
		else
		{
			$code = 200;
			$novelLang = GetModuleLang('novel');
			$data['chapter_type'] = $novelLang['novel']['par']['chapter_type_'.$data['chapter_ispay']];
			//如果是审核状态就读取最新的审核中数据
            $applyWhere['apply_module'] = 'author';
            $applyWhere['apply_type'] = array('or','novel_editchapter');
            $applyWhere['apply_cid'] = $cid;
            if( $authorConfig['author_novel_editchapter']!=1 )
            {
                $applyMod = NewModel('system.apply');
                $applyData = $applyMod->GetOne($applyWhere);
                if( $applyData )
                {
                    $applyData = $applyData['apply_option'];
                    $data['content'] = $applyData['content'];
                    $data['chapter_name'] = $applyData['chapter_name'];
                }
            }
			$data['content'] = str::EditorFormat($data['content'],true);
			if( $istxt == 1)
			{
				$data['content'] = str::ToTxt($data['content']);
			}
		}
	}
	$info = $lang['system']['operate']['success'];
}
ReturnData($info , $ajax , $code , $data);
?>