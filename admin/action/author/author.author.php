<?php
/**
* 作者处理器
*
* @version        $Id: author.author.php 2017年1月12日 19:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@author_author';
$authorMod = NewModel('author.author');
$applySer = NewModel('system.apply' , 'author');

//修改分类信息
if ( $type == 'edit' || $type == "add"  )
{
	$data = str::Escape( $post['author'], 'e' );
	$where = $post['id'];
	$data['author_time'] = strtotime($data['author_time']);

	if ( $data['user_id'] == '' || $data['author_nickname'] == '' || $data['author_info'] == '')
	{
		Ajax('对不起，用户id、作者昵称和作者简介必须填写！',300);
	}
	else if( !str::Number($data['user_id']) || !str::Number($where['author_id']) || !str::Number($data['author_status']))
	{
		Ajax('对不起，用户id、作者id和作者状态必须为数字！',300);
	}
	else if( str::LNC( $data['author_nickname'], '' , '2,12') == false)
	{
		Ajax( '对不起，笔名长度只能为2到12个字符！',300);
	}

	//验证用户是否存在。
	$userMod = NewModel('user.user');
	$expMod = NewModel('author.exp');
	if( !$userMod->GetOne($data['user_id']) )
	{
		Ajax('对不起，用户id不存在！',300);
	}
	//检查昵称是否存在
	if( !$authorMod->CheckNickName( $data['author_nickname'], $data['user_id']) )
	{
		Ajax('对不起，改笔名已经存在！',300);
	}

	//新增作者
	if( $type == 'add' )
	{
		$authorData = $authorMod->GetAuthor($data['user_id']);
		if( $authorData )
		{
			Ajax('对不起，该用户已经是作者了！',300);
		}
		
		//插入作者
		$where['author_id'] = $authorMod->Insert($data);
		//插入作者经验值
		$expMod->Insert($where['author_id']);
		
		//插入消息
		$msgMod = NewModel('user.msg');
		$msgMod->Insert($data['user_id'] , '您已经成为作者了!');

		$info = '恭喜您，作者添加成功！';
		//写入操作记录
		SetOpLog( '新增了作者'.$data['author_nickname'] , 'author' , 'insert' , $table , $where , $data );
	}
	//修改作者
	else
	{
		$info = '恭喜您，作者修改成功！';
		$authorMod->UpdateAuthor($data, $where);
		//修改作者经验值
		$expMod->Update('novel' , $where['author_id'] , $post['exp']['novel']['exp_number']);
		$expMod->Update('article' , $where['author_id'] , $post['exp']['article']['exp_number']);
		
		//写入操作记录
		SetOpLog( '修改了作者'.$data['author_nickname'] , 'author' , 'update' , $table , $where , $data );
	}
	
	Ajax($info);
}
//删除数据
else if ( $type == 'del' )
{
	//配置文件，删除数据方式是否是直接删除
	$where['author_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了作者' , 'author' , 'delete' , $table , $where);
	$authorMod->Delete($where);
	
	Ajax('作者删除成功!');
}
//审核数据
else if ( $type == 'status' )
{
	$data['author_status'] = Request('status');
	$where['author_id'] = GetDelId();
	
	$dataList = $authorMod->GetAll($where);
	if( $dataList )
	{
		foreach ($dataList as $k=>$v)
		{
			$authorMod->UpdateAuthor($data , $where);
			//插入消息和修改申请记录
			$applySer->HandleApply('apply' , $v['user_id'] , $v['author_id'] , $data['author_status']);
		}
	
		//写入操作记录
		$msg = '取消审核';
		if( Request('status') == '1')
		{
			$msg = '审核通过';
		}
		SetOpLog( $msg.'了作者' , 'author' , 'update' , $table , $where);
		Ajax('作者'.$msg.'成功!');
	}
	else
	{
		Ajax('对不起，作者不存在！');
	}
}
?>