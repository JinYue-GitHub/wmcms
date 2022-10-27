<?php
/**
* 小说章节处理器
*
* @version        $Id: novel.chapter.php 2016年4月28日 16:24  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@novel_chapter';
$novelSer = AdminNewClass('novel.novel');
$chapterSer = AdminNewClass('novel.chapter');
$novelMod = NewModel('novel.novel');
$chapterMod = NewModel('novel.chapter');
$contentMod = NewModel('novel.content');

//修改分类信息
if ( $type == 'edit' || $type == "add"  )
{
	$htmlMod = NewModel('system.html' , array('module'=>$curModule));
	$novelConfig = AdminInc('novel');

	//小说章节数据
	$data = str::Escape( $post['chapter'], 'e' );
	$post['content'] = str::Escape( $post['content'], 'e' );
	$data['chapter_time'] = strtotime($data['chapter_time']);
	$data['chapter_istxt'] = $novelConfig['data_type'];
	$data['chapter_number'] = str::StrLen($post['content']);
	//条件
	$where['chapter_id'] = Request('chapter_id');
	
	//内容检查
	if( $post['content'] == '' )
	{
		Ajax('对不起，章节内容不能为空！',300);
	}
	
	//小说是否存在检查
	$wheresql['table'] = '@novel_novel';
	$wheresql['where']['novel_name'] = $post['novel_name'];
	$novelList = wmsql::GetAll($wheresql);
	//不存在小说
	if( empty($novelList) || count($novelList) == 0 )
	{
		Ajax('对不起，该小说不存在！',300);
	}
	//只有一条小说
	else if( count($novelList) == 1 )
	{
		$novelData = $novelList[0];
	}
	//小说大于一本，并且作者为空
	else if( count($novelList) > 1 && GetKey($post,'novel_author') == '' )
	{
		Ajax('对不起，存在同名小说，您必须同时输入小说作者才能添加分卷！',300);
	}
	else
	{
		foreach ($novelList as $k=>$v)
		{
			if($v['novel_author']==$post['novel_author'])
			{
				$novelData = $novelList[$k];
				break;
			}
		}
		if( empty($novelData) )
		{
			Ajax('对不起，当前小说不存在该作者，请检查是否输入错误！',300);
		}
	}
	
	//一键发布开始，如果不存在小说就会自动添加小说
	if ( !$novelData )
	{
		//如果小说内容不为空就开始插入小说
		if( is_array(GetKey($post,'novel')) )
		{
			//如果没有进入查询验证
			if( Session(md5($post['novel']['novel_name'])) != '1' )
			{
				Session(md5($post['novel']['novel_name']) , '1');
			}
			//否则就暂停五秒等待当前的任务执行完成。
			else
			{
				sleep(5);
			}

			//用函数为了防止其他参数冲突。
			function AddNovel()
			{
				global $post;
				$isReturn = true;
				$type = 'add';
				return require_once 'action/novel/novel.novel.php';
			}
			$novelReturnArr = AddNovel();
			//删除当前的小说插入锁
			Session(md5($post['novel']['novel_name']) , 'delete');
			if($novelReturnArr['message'] == '对不起，该小说已经存在！' || $novelReturnArr['code'] == '200' )
			{
				$novelData = $novelReturnArr['data'];
				$novelData['novel_wordnumber'] = 0;
			}
			else
			{
				Ajax($novelReturnArr['message'],300);
			}
		}
		else
		{
			Ajax('对不起，该小说不存在！',300);
		}
	}
	//一键发布结束
	
	$data['chapter_nid'] = $novelData['novel_id'];
	
	//没有签约和上架
	if( ($novelData['novel_copyright'] < '1' || $novelData['novel_sell'] < '1') && $data['chapter_ispay'] == '1' )
	{
		Ajax('对不起，该小说暂未签约并且未上架，不能设为收费章节！',300);
	}

	//章节名字和章节内容相似检查
	unset($wheresql);
	$wheresql['table'] = $table;
	$wheresql['field'] = 'chapter_id';
	$wheresql['where']['chapter_name'] = $data['chapter_name'];
	$wheresql['where']['chapter_id'] = array('<>',$where['chapter_id']);
	$wheresql['where']['chapter_nid'] = $novelData['novel_id'];
	$chapterList = wmsql::GetAll($wheresql);
	if ( $chapterList )
	{
		//如果要检测内容
		if( $novelConfig['chapter_compare'] == '2')
		{
			foreach ($chapterList as $k=>$v)
			{
				$content = $chapterMod->GetById($v['chapter_id']);
				similar_text($content['content'],$post['content'], $percent);
				if( round($percent) >= $novelConfig['chapter_compare_number'] )
				{
					Ajax('对不起，该章节已经存在！',300);
				}
			}
		}
		//不检测内容
		else
		{
			Ajax('对不起，该章节已经存在！',300);
		}
	}
	
	//新增数据
	if( $type == 'add' )
	{
		//如果章节顺序为0就查找最新章节的顺序
		if( $data['chapter_order'] == '0' )
		{
			$chapterData = $chapterSer->GetNewChapter($data['chapter_nid']);
			if ( $chapterData )
			{
				$data['chapter_order'] = $chapterData['chapter_order'] + 1;
			}
			else if( !$chapterData )
			{
				$data['chapter_order'] = 1;
			}
		}
		
		$wordNumber = '0';
		$info = '恭喜您，小说章节添加成功！';
		$where['chapter_id'] = wmsql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了小说章节'.$data['chapter_name'] , 'novel' , 'insert' , $table , $where , $data );
	}
	//修改
	else
	{
		//章节字数检查
		$chapterData = $chapterSer->GetById($where['chapter_id']);
		$wordNumber = $chapterData['chapter_number'];
		
		if( !$chapterData )
		{
			Ajax('对不起，章节不存在',300);
		}
		
		$info = '恭喜您，小说章节修改成功！';
		wmsql::Update($table, $data, $where);

		//写入操作记录
		SetOpLog( '修改了小说章节'.$data['chapter_name'] , 'novel' , 'update' , $table , $where , $data );
	}

	//创建小说文章内容
	$chapterMod->CreateChapter( $type , $data['chapter_nid'] , $where['chapter_id'] , $post['content']);
	//更新小说字数
	$novelMod->UpWordNumber($data['chapter_nid'] , $novelData['novel_wordnumber'] , $wordNumber , $data['chapter_number']);
	//更新小说的最新章节信息
	$chapterData = $chapterSer->GetNewChapter($novelData['novel_id']);
	$novelMod->UpNewChapter($novelData , $chapterData['chapter_id'],$chapterData['chapter_name']);
	//创建HTML
	$htmlMod->CreateContentHtml($where['chapter_id']);
	//更新小说主txt文件存储地址
	$chapterMod->SaveChapterPath($novelData['type_id'],$data['chapter_nid'],$where['chapter_id']);
	
	Ajax($info);
}
//删除数据
else if ( $type == 'del' )
{
	//删除申请记录
	$applyMod = NewModel('system.apply');
	$applyWhere['apply_cid'] = GetDelId();
	$applyWhere['apply_module'] = 'author';
	$applyWhere['apply_type'] = 'novel_editchapter';
	$applyMod->Delete($applyWhere);
	
	$where['chapter_id'] = GetDelId();
	if( is_array($where['chapter_id']) )
	{
		$idArr = explode(',', $where['chapter_id'][1]);
		$novelData = $chapterMod->GetOne($idArr[0]);
	}
	else
	{
		$novelData = $chapterMod->GetOne($where);
	}
	//删除内容
	$contentMod->Delete($where);
	//删除章节数据
	$chapterMod->Delete($where);
	//更新小说的最新章节信息
	$novelMod->UpNewChapter($novelData , '' , '' , wmsql::$lastCount);
	//删除文件
	$chapterSer->DelChapterFile($novelData['type_id'],$novelData['novel_id'],GetDelId('id','ids',true));
	//写入操作记录
	SetOpLog( '删除了小说章节' , 'novel' , 'delete' , $table , $where);
	Ajax('小说章节删除成功!');
}
//清空章节
else if ( $type == 'clear' )
{
	if( Request('nid') != '' )
	{
		$nid = Request('nid');
		$novelData = $novelMod->GetOne($nid);
	}
	else if( Request('name') != '' )
	{
		$where['novel_name'] = Request('name');
		$novelData = $novelMod->GetOne($where);
		if( $novelData )
		{
			$nid = $novelData['novel_id'];
		}
		else
		{
			Ajax('对不起，该小说不存在！',300);
		}
	}
	else
	{
		Ajax('请选择，需要清空章节的小说！',300);
	}

	$delWhere['chapter_nid'] = $nid;
	//删除内容
	$contentMod->Delete($delWhere['chapter_nid']);
	//删除章节数据
	$chapterMod->Delete($delWhere);
	//更新小说的最新章节信息
	$novelMod->UpNewChapter($novelData , '' , '' , '-1');
	//删除文件
	$novelSer->DelNovelFile($novelData['type_id'],$novelData['novel_id']);
	
	//写入操作记录
	SetOpLog( '清空了小说章节' , 'novel' , 'delete');
	Ajax('小说章节清空成功！');
}
//移动
else if ( $type == 'order' )
{
	if( !str::Number($post['nid']) || !str::Number($post['cid']) )
	{
		Ajax('对不起，书籍id和章节id错误！',300);
	}
	else if( !str::Number($post['order']) || !str::Number($post['localtion']) )
	{
		Ajax('对不起，移动位置参数错误！',300);
	}
	else
	{
		$data['chapter_order'] = array('+',1);
		//移动到前面的条件
		if( $post['localtion'] == '0' )
		{
			$where['chapter_order'] = array('>=',$post['order']);
			$orderData['chapter_order'] = $post['order'];
		}
		//移动到后面的条件
		else
		{
			$where['chapter_order'] = array('>',$post['order']);
			$orderData['chapter_order'] = $post['order'] + 1;
		}
		//修改前后的章节位置数据
		wmsql::Update($table, $data, $where);
		
		//移动位置
		wmsql::Update($table, $orderData, array('chapter_id'=>$post['cid']));
		
		
		//写入操作记录
		SetOpLog( '移动了小说章节位置' , 'novel' , 'update');
		Ajax('移动了小说章节成功！');
	}
}
//批量修改章节数据
else if ( $type == 'batch' )
{
	$where['chapter_id'] = GetDelId();
	if( Request('chapter_vid') != '' )
	{
		$data['chapter_vid'] = str::Int(Request('chapter_vid'));
	}
	if( Request('chapter_status') != '' )
	{
		$data['chapter_status'] = str::Int(Request('chapter_status'));
	}
	if( Request('chapter_ispay') != '' )
	{
		$data['chapter_ispay'] = str::Int(Request('chapter_ispay'));
	}
	if( Request('chapter_islogin') != '' )
	{
		$data['chapter_islogin'] = str::Int(Request('chapter_islogin'));
	}
	//如果没有需要修改的
	if( empty($data) )
	{
		Ajax('没有任何修改项！',300);
	}
	//修改数据
	wmsql::Update($table, $data, $where);
	//写入操作记录
	SetOpLog( '批量修改了小说章节' , 'novel' , 'update' , $table , $where);
	Ajax('小说章节批量修改成功!');
}
?>