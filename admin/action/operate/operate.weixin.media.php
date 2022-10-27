<?php
/**
* 微信公众号消息单处理器
*
* @version        $Id: operate.weixin.media.php 2019年03月10日 20:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$mediaMod = NewModel('operate.weixin_media');
$accountMod = NewModel('operate.weixin_account');
$table = '@weixin_media';

//上传素材
if ( $type == 'add' )
{
	$fid = Post('file_id/i');
	$data = str::Escape( $post['media'], 'e' );

	$accountData = $accountMod->GetById($data['media_account_id']);
	if( !$accountData )
	{
		Ajax('对不起，请先选择公众号！',300);
	}
	else
	{
		$configData['appid'] = $accountData['account_appid'];
		$configData['secret'] = $accountData['account_secret'];
		$wxSer = NewClass('weixin_platform',$configData);
		
		if ( $data['media_filepath'] == '' )
		{
			Ajax('对不起，请上传文件！',300);
		}
		else if( !str::Number(GetKey($data,'media_account_id')) || !str::Number(GetKey($data,'media_islong')))
		{
			Ajax('对不起，所属公众号和有效期必须选择！',300);
		}
		
		//新增数据
		if( $type == 'add' )
		{
			//检查文件宽高信息
			if( $fid > 0 )
			{
				$uploadMod = NewModel('upload.upload');
				$uploadData = $uploadMod->GetOne($fid);
				if( $uploadData )
				{
					$data['media_width'] = $uploadData['upload_width'];
					$data['media_height'] = $uploadData['upload_height'];
				}
			}
			//上传素材
			$rs = $wxSer->MediaAdd($data['media_islong'],$data['media_type'],$data['media_filepath']);
			if( isset($rs['media_id']) )
			{
				$data['media_media_id'] = $rs['media_id'];
				$where['media_id'] = $mediaMod->Insert($data);
			}
			else
			{
				Ajax($rs['errmsg'],300);
			}
		}
		
		//写入操作记录
		SetOpLog( '添加了微信素材' , 'system' , 'insert' , $table , $where);
		Ajax('微信素材添加成功!');
	}
}
//删除数据
else if ( $type == 'del' )
{
	$where['media_id'] = GetDelId();
	$mediaMod->Del($where);
	//写入操作记录
	SetOpLog( '删除了微信素材' , 'system' , 'delete' , $table , $where);
	Ajax('微信素材删除成功!');
}
?>