<?php
/**
* 作品关联模型
*
* @version        $Id: workds.model.php 2022年05月13日 14:13  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class WorksModel
{
	//作品表
	public $table = '@editor_works';
	//编辑分组表
	public $groupTable = '@editor_group';
	//编辑绑定表
	public $bindTable = '@editor_bind';
	//编辑表
	public $editorTable = '@editor';
	//申请表
	public $applyTable = '@system_apply';
	//小说表
	public $novelTable = '@novel_novel';
	//小说分类表
	public $novelTypeTable = '@novel_type';
	//小说章节表
	public $novelChapterTable = '@novel_chapter';
		

	/**
	 * 构造函数
	 */
	function __construct(){}
	
	
	/**
	 * 根据id查找分组数据
	 * @param 参数1，必须，条件
	 */
	function GetById( $id )
	{
		$where['table'] = $this->table;
		$where['left'][$this->bindTable] = 'works_bind_id=bind_id';
		$where['left'][$this->groupTable] = 'bind_group_id=group_id';
		$where['left'][$this->editorTable] = 'works_editor_id=editor_id';
		$where['where']['works_id'] = $id;
		return wmsql::GetOne($where);
	}
	
	
	/**
	 * 插入数据
	 * @param 参数1，必须，插入的内容
	 */
	function Insert($data)
	{
	    $data['works_time'] = time();
    	return wmsql::Insert($this->table,$data);
	}
	
	/**
	 * 修改数据
	 * @param 参数1，必须，修改的内容
	 * @param 参数2，必须，修改的条件
	 * @param 参数3，选填，所属模块
	 */
	function Update($data,$where)
	{
	    return wmsql::Update($this->table,$data,$where);
	}
	
	/**
	 * 修改数据
	 * @param 参数1，必须，删除的条件
	 */
	function Del($where)
	{
	    return wmsql::Delete($this->table,$where);
	}
	
	/**
	 * 根据获得全部相关数据条数
	 * @param 参数1，必须，查询条件
	 */
	function GetCount($where)
	{
		$where['table'] = $this->table;
		//数据条数
		return wmsql::GetCount($where , 'works_id');
	}
	
	/**
	 * 根据获得全部相关数据
	 * @param 参数1，选填，查询条件
	 */
	function GetAll($wheresql='')
	{
		if( is_array($wheresql) )
		{
			$where = $wheresql;
		}
		$where['table'] = $this->table;
		$where['left'][$this->bindTable] = 'works_bind_id=bind_id';
		$where['left'][$this->groupTable] = 'bind_group_id=group_id';
		$where['left'][$this->editorTable] = 'works_editor_id=editor_id';
		if( !isset($where['order']) || empty($where['order']) )
		{
			$where['order'] = 'works_id desc';
		}
		return wmsql::GetAll($where);
	}
	
	/**
	 * 检查是否有存在相同的数据
	 * @param 参数1，必填，查询条件
	 * @param 参数2，选填，id
	 */
	function CheckExist($wheresql,$id='')
	{
		$where['table'] = $this->table;
		$where['where'] = $wheresql;
		$data = wmsql::GetOne($where);
		if( !$data || ($data && $data['works_id']==$id) )
		{
		    return false;
		}
		return true;
	}
	
	
	/**
	 * 检查是否有存在相同的数据
	 * @param 参数1，必填，申请id
	 * @param 参数2，必填，编辑id
	 * @param 参数3，选填，申请类型
	 */
	function GetApply($applyId,$editorId,$type='')
	{
	    $mt = '';
	    $cidField = 'apply_cid';
	    //如果是小说章节就读取pid字段找小说id
	    if( $type=='novel_editchapter' )
	    {
	        $cidField = 'apply_pid';
	    }
	    //判断是否为空
	    if( !empty($type) )
	    {
	        $mt = 'AND apply_type="'.$type.'"';
	    }
		$wheresql['table'] = $this->table.' as w';
		$wheresql['field'] = 'w.*,a.*,n.*,t.*,g.*,e.*,b.*,o.editor_name as o_editor_name';
		$wheresql['left'][$this->applyTable.' as a'] = array('right',$cidField.'=works_cid and apply_module="author" and apply_manager_id=0 '.$mt);
		$wheresql['left'][$this->groupTable . ' as g'] = 'works_group_id=group_id';
		$wheresql['left'][$this->editorTable.' as e'] = 'works_editor_id=e.editor_id';
		$wheresql['left'][$this->bindTable.' as b'] = 'works_bind_id=bind_id';
		$wheresql['left'][$this->editorTable.' as o'] = 'apply_editor_id=o.editor_id';
		$wheresql['left'][$this->novelTable .' as n'] = 'apply_cid=novel_id';
		$wheresql['left'][$this->novelTypeTable.' as t'] = 't.type_id=n.type_id';
		if( $type=='novel_editchapter' )
		{
		    $wheresql['left'][$this->novelTable .' as n'] = 'apply_pid=novel_id';
	    	$wheresql['left'][$this->novelChapterTable] = 'apply_cid=chapter_id';
		}
		$wheresql['where']['works_module'] = 'novel';
		$wheresql['where']['works_group_id'] = array('lin',NewModel('editor.bind')->GetGroupIdByEid($editorId));
		$wheresql['where']['apply_id'] = $applyId;
		$data = wmsql::GetOne($wheresql);
		if( $data )
		{
		    $data['apply_option'] = unserialize($data['apply_option']);
		}
		return $data;
	}
	
	
	/**
	 * 绑定编辑和内容id
	 * @param 参数1，必填，分组id
	 * @param 参数2，必填，内容id
	 */
	function BindGroup($groupId,$cid)
	{
        $groupMod = NewModel('editor.group');
        $groupData = $groupMod->GetById($groupId);
        $data['works_module'] = $groupData['group_module'];
        $data['works_group_id'] = $groupId;
        $data['works_cid'] = $cid;
        return $this->Insert($data);
	}
}
?>