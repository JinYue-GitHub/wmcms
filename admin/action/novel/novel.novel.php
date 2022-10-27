<?php
/**
* 小说处理器
*
* @version        $Id: novel.novel.php 2016年4月27日 20:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$conSer = AdminNewClass('system.config');

$curModule = 'novel';
$table = '@novel_novel';
$code = 300;

//修改分类信息
if ( $type == 'edit' || $type == "add"  )
{
	$pinyinSer = NewClass('pinyin');
	$novelMod = NewModel('novel.novel');
	$chapterMod = NewModel('novel.chapter');
	$recSer = AdminNewClass('novel.rec');
	
	//小说数据
	$data = str::Escape( $post['novel'], 'e' );
	//推荐数据
	$rec = str::Escape( Request('rec/a') , 'e' );

	$where['novel_id'] = Request('novel_id');
	$data['novel_cover'] = file::GetImg($data['novel_cover'], GetKey($post,'down_cover'));
	$data['novel_wordname'] = str::DelSymbol($data['novel_name']);
	$data['novel_clicktime'] = strtotime(GetKey($data,'novel_clicktime'));
	$data['novel_uptime'] = strtotime(GetKey($data,'novel_uptime'));
	$data['novel_colltime'] = strtotime(GetKey($data,'novel_colltime'));
	$data['novel_rectime'] = strtotime(GetKey($data,'novel_rectime'));
	$data['novel_createtime'] = strtotime(GetKey($data,'novel_createtime'));
	//检查拼音是否为空
	if( $data['novel_pinyin'] == '' )
	{
		$data['novel_pinyin'] = $pinyinSer->topy($data['novel_name']);
	}
	
	//小说名字检查
	$wheresql['table'] = $table;
	$wheresql['where']['novel_id'] = array('<>',$where['novel_id']);
	$wheresql['where']['novel_name'] = $data['novel_name'];
	$wheresql['where']['novel_author'] = $data['novel_author'];
	$novelData = wmsql::GetOne($wheresql);

	
	if ( $data['novel_name'] == '' || $data['novel_author'] == '' )
	{
		$info = '对不起，小说名字和小说作者必须填写！';
	}
	else if( !str::Number($data['type_id']) )
	{
		$info = '对不起，小说分类必须选择！';
	}
	else if ( $novelData )
	{
		$info = '对不起，该小说已经存在！';
	}
	else
	{
		//拼音是否存在查询
		unset($wheresql['where']['novel_name']);
		$wheresql['where']['novel_pinyin'] = $data['novel_pinyin'];
		$pinyinCount = wmsql::GetCount($wheresql);
		
		//新增数据
		if( $type == 'add' )
		{
			//引入小说配置
			$novelConfig = AdminInc('novel');
			//写入新小说作者和来源的数据
			$mangerSer = AdminNewClass('manager');
			//写入标签
			$mangerSer->SetTags('novel' , $data['novel_tags']);

			$code = 200;
			$info = '恭喜您，小说添加成功！';
			$where['novel_id'] = wmsql::Insert($table, $data);
			$novelData['novel_id'] = $where['novel_id'];
			$novelData['chapter_number'] = 0;
			
			//写入操作记录
			SetOpLog( '新增了小说'.$data['novel_name'] , 'novel' , 'insert' , $table , $where , $data );
		}
		//修改分类
		else
		{
			//原始小说数据
			$oldData = $novelMod->GetOne($where['novel_id']);
			
			$code = 200;
			$info = '恭喜您，小说修改成功！';
			$result = wmsql::Update($table, $data, $where);

			//修改小说分类移动文件
			if( $result )
			{
				$novelMod->MoveNovelFolder($oldData['type_id'],$data['type_id'],$where['novel_id']);
			}
			
			//写入操作记录
			SetOpLog( '修改了小说'.$data['novel_name'] , 'novel' , 'update' , $table , $where , $data );
		}
		
		//拼音已经存在就重新设置
		if ( $pinyinCount > 0 )
		{
			$pinyinData['novel_pinyin'] = $data['novel_pinyin'].$where['novel_id'];
			wmsql::Update($table, $pinyinData, $where);
		}
		
		//写入自定义字段
		$fieldArr['module'] = $curModule;
		$fieldArr['option'] = GetKey($post,'field');
		$fieldArr['tid'] = $data['type_id'];
		$fieldArr['cid'] = $where['novel_id'];
		$fieldArr['ft'] = '2';
		$conSer->SetFieldOption($fieldArr);
		
		//修改小说推荐-先检查是否存在推荐的信息
		$recSer->SetRec( $where['novel_id'] , $rec , false);
		//更新小说主txt文件存储地址
		$chapterMod->SaveNovelPath($data['type_id'],$where['novel_id']);
	}
	if( isset($isReturn) && $isReturn == true )
	{
		return array('message'=>$info,'code'=>$code,'data'=>$novelData);
	}
	else
	{
		Ajax($info , $code);
	}
}
//删除数据和永久删除数据
else if ( $type == 'del' )
{
	$applyMod = NewModel('system.apply' , 'author');
	$chapterMod = NewModel('novel.chapter');
	$signMod = NewModel('novel.sign');
	$sellMod = NewModel('novel.sell');
	$recMod = NewModel('novel.rec');
	$timelimitMod = NewModel('novel.timelimit');
	$welfareMod = NewModel('novel.welfare');
	$contentMod = NewModel('novel.content');
	$outlineMod = NewModel('novel.outline');
	$saidMod = NewModel('novel.said');
	
	$where['novel_id'] = GetDelId();
	$wheresql['table'] = $table;
	$wheresql['field'] = 'type_id,novel_id,novel_cover';
	$wheresql['where'] = $where;
	$novelData = wmsql::GetAll($wheresql);
	if( $novelData )
	{
		//删除申请记录
		$applyWhere['apply_cid'] = GetDelId();
		$applyWhere['apply_module'] = 'author';
		$applyWhere['apply_type'] = array('or','novel_editnovel,novel_cover,novel_editchapter');
		$applyMod->Delete($applyWhere);

		//删除内容
		$contentMod->Delete($where['novel_id']);
		//删除章节
		$chapterMod->Delete(array("chapter_nid"=>$where['novel_id']));
		//删除签约
		$signMod->Delete(array("sign_novel_id"=>$where['novel_id']));
		//删除上架
		$sellMod->Delete(array("sell_novel_id"=>$where['novel_id']));
		//删除限时
		$timelimitMod->Delete(array("timelimit_nid"=>$where['novel_id']));
		//删除福利
		$welfareMod->Delete(array("welfare_nid"=>$where['novel_id']));
		//删除推荐小说
		$recMod->Delete(array("rec_nid"=>$where['novel_id']));
		//删除大纲
		$outlineMod->Delete(array("novel_id"=>$where['novel_id']));
		//删除作者说
		$saidMod->Delete(array("novel_id"=>$where['novel_id']));
		
		//写入操作记录
		SetOpLog( '删除了小说' , 'novel' , 'delete' , $table , $where);
		wmsql::Delete($table , $where);
		
		foreach ($novelData as $k=>$v)
		{
			//删除文件
			$novelSer = AdminNewClass('novel.novel');
			$novelSer->DelNovelFile($v['type_id'],$v['novel_id']);
			//不是系统封面才删除封面
            if( !strpos($v['novel_cover'],'images/nocover.jpg') )
            {
			    file::DelFile(WMROOT.$v['novel_cover']);
            }
		}
	}
	
	
	Ajax('小说删除成功!');
}
//审核数据
else if ( $type == 'status' )
{
	$data['novel_status'] = Request('status');
	$where['novel_id'] = GetDelId();

	if( Request('status') == '1')
	{
		//修改申请记录
		$applyMod = NewModel('system.apply','author');
		$applyMod->BatchUpdateStatus('novel_editnovel' , $where['novel_id']);
		
		$msg = '审核通过';
	}
	else
	{
		$msg = '取消审核';
	}
	//写入操作记录
	SetOpLog( $msg.'了小说' , 'novel' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('小说'.$msg.'成功!');
}
//移动数据
else if ( $type == 'move' )
{
	$data['type_id'] = Request('tid');
	$where['novel_id'] = GetDelId();

	//写入操作记录
	SetOpLog( '移动了小说' , 'novel' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('小说移动成功!');
}
//小说搜索操作
else if ( $type == 'search' )
{
	$st = Request('st');
	$keyword = Request('keyword');
	//返回类型。默认全部，否则是键值对
	$rt = Request('rt');
	
	$where['table'] = $table;
	//判断是否搜索标题
	switch ($st)
	{
		case 'author':
			$field = 'novel_author';
			$where['where']['novel_author'] = array('like',$keyword);
			break;
			
		default:
			$field = 'novel_name';
			$where['where']['novel_name'] = array('like',$keyword);
			break;
	}
	$where['limit'] = '0,50';
	
	$data = wmsql::GetAll($where);
	if( $data && $rt == 'key')
	{
		foreach ($data as $k=>$v)
		{
			$data[$k] = array('key'=>$v['novel_id'],'val'=>$v[$field]);
		}
	}
	
	Ajax('查询成功!',null,$data);
}
?>