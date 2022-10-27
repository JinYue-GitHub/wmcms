<?php
/**
* 获得小说的章节的信息
*
* @version        $Id: getchapter.php 2016年12月31日 22:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$cid = Request('cid');
$istxt = Request('istxt' , 0);
$format = Request('format' , 0);
$code = 500;
$novelMod = NewModel('novel.novel');
$chapterMod = NewModel('novel.chapter');
$authorMod = NewModel('author.author');
$applyMod = NewModel('system.apply');
$tts = Request('tts' , 0);
$data = array();

//根据小说父级分类查询
if( $cid > 0 )
{
	$code = 200;
    $chapter = $chapterMod->CheckChapterSub($cid,2);
	if( !is_array($chapter) )
	{
		$code = $chapter;
	}
	else
	{
	    $novel = $novelMod->GetOne($chapter['chapter_nid']);
		$novelLang = GetModuleLang('novel');
		$chapter['chapter_type'] = $novelLang['novel']['par']['chapter_type_'.$chapter['chapter_ispay']];
		//如果作者存在，并且为自己就
		if( $chapter['chapter_status'] != 1 && $chapter['is_content'] == false)
		{
			$author = $authorMod->GetAuthor();
			if( $author )
			{
				$where['apply_module'] = 'author';
				$where['apply_type'] = array('or','novel_editchapter');
				$where['apply_cid'] = $cid;
				$applyData = $applyMod->GetOne($where);
				$applyData = unserialize($applyData['apply_option']);
				$chapter['content'] = $applyData['content'];
			}
		}
		//如果是需要格式化为段落
		if( $format == 1 )
		{
		    if( empty($chapter['content']) )
		    {
		        $chapter['content'] = array();
		    }
		    else
		    {  
		        $chapter['content'] = explode('<br/>',$chapter['content']);
		    }
		}
		//是否获取TTSID
		if( $tts == 1 )
		{
            $ttsData['time'] = time();
            $ttsData['module'] = 'novel';
            $ttsData['type'] = 'chapter';
            $ttsData['tid'] = $novel['type_id'];
            $ttsData['cid'] = $cid;
            $ttsData['nid'] = $novel['novel_id'];
            Session('TTS',json_encode($ttsData));
            $data['tts'] = urlencode(str::A(GetSessionId(),md5(json_encode($ttsData))));
		}
		//组装返回值
		$data['chapter'] = $chapter;
		$data['novel'] = str::ShowField($novel,'novel_id,type_id,type_name,novel_author,novel_info,novel_pinyin,novel_process,novel_cover,novel_wordnumber,novel_startcid,novel_startcname,novel_newcid,novel_newcname,novel_chapter');
		//上下章节
		$prveNext = $chapterMod->GetPrveNext($chapter['chapter_nid'],$chapter['chapter_order']);
		$data['prev'] = $prveNext['prev'];
		$data['next'] = $prveNext['next'];
		//章节售价
		$price['sell_number'] = $price['sell_all'] = $price['sell_month'] = '0';
		//小说是否允许出售并且章节为付费章节
		if( $chapter['chapter_ispay'] == 1 && $novel['novel_sell'] == 1)
		{
			//查询出售价格
			$sellMod = NewModel('novel.sell');
			$price = $sellMod->GetNovelSell($novel['novel_id']);
			$price['sell_number'] = round($chapter['chapter_number']/1000 * $price['sell_number'] , 2);
		}
		$data['price'] = $price;
		
		//写入阅读记录
		$readMod = NewModel('user.read');
		$readMod->SetReadLog('novel',$chapter['chapter_nid'],$chapter['chapter_id'],$chapter['chapter_name']);
	}
	$info = $lang['system']['operate']['success'];
}
else
{
	$info = $lang['system']['par']['err'];
}

ReturnData($info , $ajax , $code , $data);
?>