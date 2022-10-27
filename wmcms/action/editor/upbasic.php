<?php
/**
* 保存编辑基本资料操作处理
*
* @version        $Id: upbasic.php 2022年05月16日 14:20  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$name = Post('name');
$realName = Post('realname');
$qq = Post('qq');
$weixin = Post('weixin');
$tel = Post('tel');
$desc = str::DelHtml(Post('desc'));
//长度检测
str::CheckLen( $name , '2,10' , $lang['editor']['name_len']  );
str::CheckLen( $realName , '2,10' , $lang['editor']['realname_len']  );
str::CheckLen( $qq , '4,15' , $lang['editor']['qq_len']  );
str::CheckLen( $weixin , '2,50' , $lang['editor']['weixin_len']  );
str::CheckLen( $tel , '10,12' , $lang['editor']['tel_len']  );
str::CheckLen( $desc , '5,200' , $lang['editor']['desc_len']  );
//禁用词判断
CheckShield( $desc , $lang['editor']['disable_desc'] , 'disable' );

//设置修改数据
$data['editor_name'] = $name;
$data['editor_realname'] = $realName;
$data['editor_desc'] = $desc;
$data['editor_qq'] = $qq;
$data['editor_weixin'] = $weixin;
$data['editor_tel'] = $tel;
$result = $editorMod->Update($data,array('editor_id'=>$editor['editor_id']));
//保存成功
if( $result )
{
	ReturnData( $lang['editor']['operate']['upbasic']['success'] , $ajax , 200);
}
else
{
	ReturnData( $lang['editor']['operate']['upbasic']['fail'] , $ajax);
}
?>