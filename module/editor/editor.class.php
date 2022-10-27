<?php
/**
* 编辑模块系统类文件
*
* @version        $Id: editor.class.php 2015年10月05日 21:55  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class editor
{
	//编辑数组
	static public $editor;
	//编辑模型
	static public $editorMod;
	//成员绑定模型
	static public $bindMod;
	
	static public $bindTable = '@editor_bind';
	static public $groupTable = '@editor_group';
	static public $editorTable = '@editor';
	static public $worksTable = '@editor_works';
	static public $applyTable = '@system_apply';
	static public $novelTable = '@novel_novel';
	static public $novelTypeTable = '@novel_type';
	static public $novelChapterTable = '@novel_chapter';
	
	/**
	 * 构造函数
	 * @param 参数1，选填，是否自动载入标签类
	 * @param string $labelLoad
	 */
	function __construct( $labelLoad = true )
	{
		if( $labelLoad )
		{
			//调用标签构造函数
			new editorlabel();
		}
	}
	
	//获取编辑模型
	static function GetEditorMod()
	{
	    if( empty(self::$editorMod) )
	    {
	        self::$editorMod = NewModel('editor.editor');
	    }
	    return self::$editorMod;
	}
	//获取成员绑定模型
	static function GetBindMod()
	{
	    if( empty(self::$bindMod) )
	    {
	        self::$bindMod = NewModel('editor.bind');
	    }
	    return self::$bindMod;
	}


	/**
	* 根据所得到的条件查询数据
	* @param 参数1，字符串，type为列表页数据获取，content为内容页数据获取
	* @param 参数2，传递的sql条件
	* @param 参数3，选填，没有数据的提示字符串
	**/
	static function GetData($type,$where='',$errInfo='')
	{
		$wheresql = self::GetWhere($where);
		switch ($type)
		{
			//编辑分组
			case 'group':
				$wheresql['table'] = self::$groupTable;
        		$wheresql['where']['group_module'] = 'novel';
				break;
				
			//编辑分组成员
			case 'group_bind':
				$wheresql['table'] = self::$bindTable;
        		$wheresql['left'][self::$groupTable] = 'bind_group_id=group_id';
        		$wheresql['left'][self::$editorTable] = 'bind_editor_id=editor_id';
				break;
				
			//小说/小说封面/小说章节审核列表
			case 'novel_editnovel':
			case 'novel_cover':
			case 'novel_editchapter':
			    //如果是小说章节就读取pid字段找小说id
			    $cidField = 'apply_cid';
			    if( $type=='novel_editchapter' )
			    {
			        $cidField = 'apply_pid';
			    }
				$wheresql['table'] = self::$worksTable;
        		$wheresql['left'][self::$applyTable] = array('right',$cidField.'=works_cid and apply_module="author" AND apply_type="'.$type.'" and apply_manager_id=0');
        		$wheresql['left'][self::$groupTable] = 'works_group_id=group_id';
        		$wheresql['left'][self::$editorTable] = 'works_editor_id=editor_id';
        		$wheresql['left'][self::$bindTable] = 'works_bind_id=bind_id';
        		$wheresql['left'][self::$novelTable .' as n'] = 'apply_cid=novel_id';
        		$wheresql['left'][self::$novelTypeTable.' as t'] = 't.type_id=n.type_id';
        		if( $type=='novel_editchapter' )
        		{
        		    $wheresql['left'][self::$novelTable .' as n'] = 'apply_pid=novel_id';
        	    	$wheresql['left'][self::$novelChapterTable] = 'apply_cid=chapter_id';
        		}
        		$wheresql['where']['works_module'] = 'novel';
        		$wheresql['where']['works_group_id'] = array('lin',self::GetBindMod()->GetGroupIdByEid(self::GetEditorId()));
				break;
		}
		
		//分页处理
		if( isset($wheresql['list']) )
		{
			$count = wmsql::GetCount($wheresql);
			page::Start( C('page.listurl') , $count , $wheresql['limit'] );
		}
		$data = wmsql::GetAll($wheresql);
        if( !$data && $errInfo != '' )
		{
			tpl::ErrInfo($errInfo);
		}
		return $data;
	}


	/**
	* 获得字符串中的条件sql
	* 返回值字符串
	* @param 参数1：需要查询的字符串。
	**/
	static function GetWhere($where)
	{
		//设置需要替换的字段
		$arr = array(
			'分组id' =>'group_id',
			'分组排序' =>'group_order desc',
			'申请排序' => 'apply_status asc,apply_id asc',
		);
		return tpl::GetWhere($where,$arr);
	}
	
	/**
	 * 获得编辑的基本信息
	 */
	static function GetEditor()
	{
		if( !self::$editor )
		{
			self::$editor = self::GetEditorMod()->GetByUid(user::GetUid());
		}
		return self::$editor;
	}
	/**
	 * 获得编辑的ID
	 */
	static function GetEditorId()
	{
		$editor = self::GetEditor();
		if( $editor )
		{
		    return $editor['editor_uid'];
		}
		return 0;
	}
	
	/**
	* 检测是否有编辑权限
	**/
	static function CheckEditor()
	{
		$editor = self::GetEditor();
		//不存在
		if( !$editor )
		{
			tpl::ErrInfo( GetLang('editor.par.no_exist') );
		    return false;
		}	
        //权限被禁用
        else if( $editor['editor_status'] == '0' )
        {
			tpl::ErrInfo( GetLang('editor.par.disabled') );
		    return false;
        }
		return true;
	}
	
	
	/**
	 * 获得申请状态
	 * @param 参数1，必须，状态码
	 */
	static function GetApplyStatus( $sta )
	{
		switch ( $sta )
		{
			case "1":
				return C( 'editor.par.apply_status_1' , null , 'lang');
				break;
				
			case "2":
				return C( 'editor.par.apply_status_2' , null , 'lang');
				break;
				
			case "0":
				return C( 'editor.par.apply_status_0' , null , 'lang');
				break;
		}
	}
	
}
?>