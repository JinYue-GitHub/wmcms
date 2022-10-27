<?php
/**
* 编辑模块标签处理类
*
* @version        $Id: editor.label.php 2022年05月13日 17:03  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class editorlabel extends editor
{
	public static $lcode;
	public static $data;
	static public $CF = array('editor'=>'GetData');

	function __construct()
	{
		tpl::labelBefore();
		
		//公共url
		self::PublicUrl();
		
		//调用自定义标签
		self::PublicLabel();
	}

	//公共url替换
	static function PublicUrl()
	{
		$arr = array(
			'编辑首页'=>tpl::url('editor_index'),
			'编辑小说审核'=>tpl::url('editor_novel',array('status'=>'','page'=>'1')),
			'编辑小说封面审核'=>tpl::url('editor_novel_cover',array('status'=>'','page'=>'1')),
			'编辑小说章节审核'=>tpl::url('editor_novel_chapter',array('status'=>'','page'=>'1')),
			'编辑基本资料'=>tpl::url('editor_basic'),
		);
		tpl::Rep($arr);
	}
	

	/**
	* 编辑模块公共标签替换
	**/
	static function PublicLabel()
	{
	    $editor = editor::GetEditor();
	    if( $editor )
	    {
    		$arr = array(
    			'编辑id'=>$editor['editor_id'],
    			'编辑名字'=>$editor['editor_name'],
    			'编辑姓名'=>$editor['editor_realname'],
    			'编辑简介'=>$editor['editor_desc'],
    			'编辑qq'=>$editor['editor_qq'],
    			'编辑微信'=>$editor['editor_weixin'],
    			'编辑手机'=>$editor['editor_tel'],
    			'编辑加入时间'=>date('Y-m-d H:i:s',$editor['editor_time']),
    		);
    		tpl::Rep($arr);
	    }
	    
		//数组键：类名，值：方法名
		$repFun['t']['editorlabel'] = 'PublicGroup';
		tpl::Label('{编辑分组:[s]}[a]{/编辑分组}','group', self::$CF, $repFun['t']);
		
		//分组绑定的成员
		$repFun['t']['editorlabel'] = 'PublicGroupBind';
		tpl::Rep( array('{编辑分组成员:'=>'{编辑分组成员:'.'bind_editor_id='.editor::GetEditorId().';') , null , '2' );
		tpl::Label('{编辑分组成员:[s]}[a]{/编辑分组成员}','group_bind', self::$CF, $repFun['t']);
	}


	/**
	 * 公共编辑分组替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的字符串，原始标签代码。
	 * @param 参数3，选填，标签的级别。
	 **/
	static function PublicGroup($data,$blcode,$level='')
	{
		$code = '';
		$i = 1;
		//循环数据
		foreach ($data as $k => $v)
		{
			$arr1 = tpl::GetBeforeLabel('PublicGroup',$k);
			//没组数据循环，以字段名为标签名
			foreach ($v as $key => $val)
			{
				$arr1[L.$level.$key]=$v[$key];
			}
			//每次循环重新调取原始标签
			$lcode = $blcode;
			
			//计数器标签和选中标签替换
			$lcode = tpl::I( $lcode , $i );
			$lcode = tpl::I( $lcode , $i ,$level.'编辑分组i');
			//分割每条数据的字符串
			$lcode = tpl::Segmentation(count($data), $i, $lcode , '编辑分组分隔符');
			$lcode = tpl::Segmentation(count($data), $i, $lcode);
			
			//显示固定字数标签
			$descArr = tpl::Exp('{编辑分组描述:[d]}' , $v['group_desc'] , $lcode);
			//匹配自定义时间标签
			$time = tpl::Tag('{编辑分组时间:[s]}',$lcode);
			//设置自定义中文标签
			$arr2=array(
				$level.'i'=>$i,
				$level.'编辑分组id'=>$v['group_id'],
				$level.'编辑分组名字'=>$v['group_name'],
				$level.'编辑分组描述'=>$v['group_desc'],
				$level.'编辑分组排序'=>$v['group_order'],
				$level.'编辑分组时间戳'=>$v['group_time'],
				$level.'编辑分组时间'=>date("Y-m-d H:i:s",$v['group_time']),
				
				$level.'编辑分组描述:'.GetKey($descArr,'0')=>GetKey($descArr,'1'),
				$level.'编辑分组时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), intval(GetKey($v,'group_time'))),
			);
			//合并两组标签
			$arr = array_merge($arr2,$arr1);
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
		
		//返回最后的结果
		return $code;
	}
	
	
	/**
	 * 公共编辑分组成员替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的字符串，原始标签代码。
	 * @param 参数3，选填，标签的级别。
	 **/
	static function PublicGroupBind($data,$blcode,$level='')
	{
		$code = '';
		$i = 1;
		//循环数据
		foreach ($data as $k => $v)
		{
			$arr1 = tpl::GetBeforeLabel('PublicGroupBind',$k);
			//没组数据循环，以字段名为标签名
			foreach ($v as $key => $val)
			{
				$arr1[L.$level.$key]=$v[$key];
			}
			//每次循环重新调取原始标签
			$lcode = $blcode;
			
			//计数器标签和选中标签替换
			$lcode = tpl::I( $lcode , $i );
			$lcode = tpl::I( $lcode , $i ,$level.'编辑分组成员i');
			//分割每条数据的字符串
			$lcode = tpl::Segmentation(count($data), $i, $lcode , '编辑分组成员分隔符');
			$lcode = tpl::Segmentation(count($data), $i, $lcode);
			
			//匹配自定义时间标签
			$time = tpl::Tag('{编辑分组时间:[s]}',$lcode);
			//设置自定义中文标签
			$arr2=array(
				$level.'i'=>$i,
				$level.'编辑分组成员id'=>$v['bind_id'],
				$level.'编辑分组成员分组名字'=>$v['group_name'],
				$level.'编辑分组成员分组描述'=>$v['group_desc'],
				$level.'编辑分组成员类型'=>parent::GetBindMod()->GetType($v['bind_type']),
				$level.'编辑分组成员分组id'=>$v['group_id'],
				$level.'编辑分组成员编辑id'=>$v['bind_editor_id'],
				$level.'编辑分组成员编辑名字'=>$v['editor_name'],
				$level.'编辑分组成员加入时间戳'=>$v['bind_time'],
				$level.'编辑分组成员加入时间'=>date("Y-m-d H:i:s",$v['bind_time']),
				
				$level.'编辑分组成员加入时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), intval(GetKey($v,'bind_time'))),
			);
			//合并两组标签
			$arr = array_merge($arr2,$arr1);
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
		
		//返回最后的结果
		return $code;
	}
	
	
	/**
	* 首页标签替换
	**/
	static function IndexLabel()
	{
	}
	
	/**
	* 基本资料标签替换
	**/
	static function BasicLabel()
	{
		$arr = array('表单提交地址'=>common::GetUrl('editor.upbasic'),);
		tpl::Rep($arr);
	}
	
	/**
	* 小说审核标签替换
	**/
	static function NovelLabel()
	{
    	$applySer = NewModel('system.apply','author');
    	$remark = $applySer->GetHandleRemark('novel_editnovel');
	    $status = C('page.status');
	    $page = C('page.page');
		//替换标签
		$arr = array(
			'状态'=>$status,
			'拒绝理由'=>$remark,
			'待审核新书'=>tpl::url('editor_novel',array('status'=>0,'page'=>'1')),
			'已审核新书'=>tpl::url('editor_novel',array('status'=>1,'page'=>'1')),
			'未通过新书'=>tpl::url('editor_novel',array('status'=>2,'page'=>'1')),
		);
		tpl::Rep($arr);
		
		$statusWhere = $pageWhere = $orderWhere = '';
		//状态不等于空
		if ( C('page.status') != '' )
		{
			$statusWhere = 'apply_status='.$status.';';
		}
		//页数
		if ( C('page.page') > 0 )
		{
			$pageWhere = 'page='.$page.';';
		}
		//替换标签条件
		tpl::Rep( array('{编辑小说列表:'=>'{编辑小说列表:'.$statusWhere.$pageWhere) , null , '2' );
		
		
		//设置回调标签
		C('page.callback_label',array('editorlabel','NovelCallBackLabel'));
		$repFun['a']['novellabel'] = 'PublicNovel';
		tpl::Label('{编辑小说列表:[s]}[a]{/编辑小说列表}','novel_editnovel', self::$CF, $repFun['a']);
		//清空设置回调标签
		C('page.callback_label','delete');
	}
	//小说审核标签回调
	static function NovelCallBackLabel($v)
	{
	    $updateTime = $v['apply_updatetime'];
	    $updateTimeFormat = date("Y-m-d H:i:s",$v['apply_updatetime']);
	    if( $updateTime == '0' )
	    {
	        $updateTime = '---';
	        $updateTimeFormat = '------';
	    }
		$arr = array(
			'id'=>$v['apply_id'],
			'编辑小说url'=>tpl::url( 'novel_info' , array('nid'=>$v['novel_id'],'tid'=>$v['type_id']) ,1).'&editor=1',
			'编辑小说申请id'=>$v['apply_id'],
			'编辑小说编辑组'=>$v['group_name'],
			'编辑小说申请状态码'=>$v['apply_status'],
			'编辑小说申请数据'=>json_encode(unserialize($v['apply_option'])),
			'编辑小说申请状态'=>parent::GetApplyStatus($v['apply_status']),
			'编辑小说申请时间戳'=>$v['apply_createtime'],
			'编辑小说处理时间戳'=>$updateTime,
			'编辑小说申请时间'=>date("Y-m-d H:i:s",$v['apply_createtime']),
			'编辑小说处理时间'=>$updateTimeFormat,
		);
		return $arr;
	}
	
	
	//小说封面审核标签替换
	static function NovelCoverLabel()
	{
    	$applySer = NewModel('system.apply','author');
    	$remark = $applySer->GetHandleRemark('novel_cover');
	    $status = C('page.status');
	    $page = C('page.page');
		//替换标签
		$arr = array(
			'状态'=>$status,
			'拒绝理由'=>$remark,
			'待审核封面'=>tpl::url('editor_novel_cover',array('status'=>0,'page'=>'1')),
			'已审核封面'=>tpl::url('editor_novel_cover',array('status'=>1,'page'=>'1')),
			'未通过封面'=>tpl::url('editor_novel_cover',array('status'=>2,'page'=>'1')),
		);
		tpl::Rep($arr);
		
		$statusWhere = $pageWhere = $orderWhere = '';
		//状态不等于空
		if ( C('page.status') != '' )
		{
			$statusWhere = 'apply_status='.$status.';';
		}
		//页数
		if ( C('page.page') > 0 )
		{
			$pageWhere = 'page='.$page.';';
		}
		//替换标签条件
		tpl::Rep( array('{编辑小说封面列表:'=>'{编辑小说封面列表:'.$statusWhere.$pageWhere) , null , '2' );
		
		
		//设置回调标签
		C('page.callback_label',array('editorlabel','NovelCoverCallBackLabel'));
		$repFun['a']['novellabel'] = 'PublicNovel';
		tpl::Label('{编辑小说封面列表:[s]}[a]{/编辑小说封面列表}','novel_cover', self::$CF, $repFun['a']);
		//清空设置回调标签
		C('page.callback_label','delete');
	}
	//小说封面审核标签回调
	static function NovelCoverCallBackLabel($v)
	{
	    $updateTime = $v['apply_updatetime'];
	    $updateTimeFormat = date("Y-m-d H:i:s",$v['apply_updatetime']);
	    if( $updateTime == '0' )
	    {
	        $updateTime = '---';
	        $updateTimeFormat = '------';
	    }
		$arr = array(
			'id'=>$v['apply_id'],
			'编辑小说封面url'=>tpl::url( 'novel_info' , array('nid'=>$v['novel_id'],'tid'=>$v['type_id']) ,1).'&editor=1',
			'编辑小说封面申请id'=>$v['apply_id'],
			'编辑小说封面编辑组'=>$v['group_name'],
			'编辑小说封面申请状态码'=>$v['apply_status'],
			'编辑小说封面申请数据'=>json_encode(unserialize($v['apply_option'])),
			'编辑小说封面申请状态'=>parent::GetApplyStatus($v['apply_status']),
			'编辑小说封面申请时间戳'=>$v['apply_createtime'],
			'编辑小说封面处理时间戳'=>$updateTime,
			'编辑小说封面申请时间'=>date("Y-m-d H:i:s",$v['apply_createtime']),
			'编辑小说封面处理时间'=>$updateTimeFormat,
			'编辑小说封面旧地址'=>$v['novel_cover'],
			'编辑小说封面新地址'=>unserialize($v['apply_option'])['file'],
		);
		return $arr;
	}
	
	//小说章节审核标签替换
	static function NovelChapterLabel()
	{
    	$applySer = NewModel('system.apply','author');
    	$remark = $applySer->GetHandleRemark('novel_editchapter');
	    $status = C('page.status');
	    $page = C('page.page');
		//替换标签
		$arr = array(
			'状态'=>$status,
			'拒绝理由'=>$remark,
			'待审核章节'=>tpl::url('editor_novel_chapter',array('status'=>0,'page'=>'1')),
			'已审核章节'=>tpl::url('editor_novel_chapter',array('status'=>1,'page'=>'1')),
			'未通过章节'=>tpl::url('editor_novel_chapter',array('status'=>2,'page'=>'1')),
		);
		tpl::Rep($arr);
		
		$statusWhere = $pageWhere = $orderWhere = '';
		//状态不等于空
		if ( C('page.status') != '' )
		{
			$statusWhere = 'apply_status='.$status.';';
		}
		//页数
		if ( C('page.page') > 0 )
		{
			$pageWhere = 'page='.$page.';';
		}
		//替换标签条件
		tpl::Rep( array('{编辑小说章节列表:'=>'{编辑小说章节列表:'.$statusWhere.$pageWhere) , null , '2' );
		
		
		//设置回调标签
		C('page.callback_label',array('editorlabel','NovelChapterCallBackLabel'));
		$repFun['a']['novellabel'] = 'PublicChapter';
		tpl::Label('{编辑小说章节列表:[s]}[a]{/编辑小说章节列表}','novel_editchapter', self::$CF, $repFun['a']);
		//清空设置回调标签
		C('page.callback_label','delete');
	}
	//小说章节审核标签回调
	static function NovelChapterCallBackLabel($v)
	{
	    $updateTime = $v['apply_updatetime'];
	    $updateTimeFormat = date("Y-m-d H:i:s",$v['apply_updatetime']);
	    if( $updateTime == '0' )
	    {
	        $updateTime = '---';
	        $updateTimeFormat = '------';
	    }
	    $option = unserialize($v['apply_option']);
		$arr = array(
			'id'=>$v['apply_id'],
			'编辑小说url'=>tpl::url( 'novel_info' , array('nid'=>$v['novel_id'],'tid'=>$v['type_id']) ,1).'&editor=1',
			'编辑小说章节url'=>tpl::url( 'novel_read' , array('cid'=>$v['chapter_id'],'nid'=>$v['chapter_nid'],'tid'=>$v['type_id']) ,1).'&editor=1',
			'编辑小说章节申请id'=>$v['apply_id'],
			'编辑小说章节编辑组'=>$v['group_name'],
			'编辑小说章节申请状态码'=>$v['apply_status'],
			'编辑小说章节申请数据'=>json_encode($option),
			'编辑小说章节申请章节名字'=>$option['chapter_name'],
			'编辑小说章节申请状态'=>parent::GetApplyStatus($v['apply_status']),
			'编辑小说章节申请时间戳'=>$v['apply_createtime'],
			'编辑小说章节处理时间戳'=>$updateTime,
			'编辑小说章节申请时间'=>date("Y-m-d H:i:s",$v['apply_createtime']),
			'编辑小说章节处理时间'=>$updateTimeFormat,
		);
		return $arr;
	}
}
?>