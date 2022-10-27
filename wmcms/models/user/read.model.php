<?php
/**
* 用户阅读记录操作模型
*
* @version        $Id: read.model.php 2017年7月10日 14:48  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class ReadModel
{
	public $table = '@user_read';
	public $userTable = '@user_user';
	public $logTable = '@user_read_log';
	public $novelTable = '@novel_novel';
	public $novelTypeTable = '@novel_type';
	public $chapterTable = '@novel_chapter';
	//当前模块
	public $module;
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
		global $tableSer;
		$this->moduleTable = $tableSer->GetTable($this->module);
	}
	
	/**
	 * 获取需要记录的内容标题
	 * @param 参数1，必须，模块
	 * @param 参数2，必须，内容所属上级id
	 * @param 参数3，必须，内容id
	 */
	private function GetContentTitle($module,$nid,$cid)
	{
    	//阅读内容的标题
    	if( $module == 'novel' )
    	{
    		$chapterMod = NewModel('novel.chapter');
    		$chapterData = $chapterMod->GetById( $cid );
    		if( $chapterData && $chapterData['novel_id'] == $nid )
    		{
    			$contentTitle = GetKey($chapterData,'chapter_name');
    		}
    	}
    	else
    	{
    		$contentTitle = $tableSer->GetContentName( $module ,$cid );
    	}
    	return $contentTitle;
	}
	/**
	 * 插入阅读记录操作
	 * @param 参数1，必须，模块
	 * @param 参数2，必须，内容所属上级id
	 * @param 参数3，必须，内容id
	 * @param 参数3，必须，内容标题
	 */
	function SetReadLog($module,$nid,$cid,$title='')
	{
	    $config = GetModuleConfig($module);
	    //模块开启阅读记录、用户id大于0，并且不是蜘蛛才记录
	    if( GetKey($config,'read_open') == '1' && Call('user','GetUid') > 0 && !IsSpider() )
	    {
	        $title = !empty($title)?$title:$this->GetContentTitle($module,$nid,$cid);
	        if( empty($title) )
	        {
	            return false;
	        }
	        else
	        {
	            $uid = user::GetUid();
	            //设置阅读记录的数据
			    $data['read_time'] = time();
			    $data['read_module'] = $module;
			    $data['read_cid'] = $cid;
			    $data['read_nid'] = $nid;
			    $data['read_title'] = $title;
			    $data['read_uid'] = $uid;
    			//查询是否存在阅读记录索引
    			$where['table'] = $this->table;
    			$where['where']['read_uid'] = $uid;
    			$where['where']['read_nid'] = $nid;
    			$readData = wmsql::GetOne($where);
    			//存在并且内容id不一样就要修改阅读记录索引
    			if( $readData && $cid != $readData['read_cid'] )
    			{
    			    $saveData = array('read_cid'=>$cid,'read_title'=>$title,'read_lasttime'=>time());
    				wmsql::Update($this->table,$saveData,array('read_id'=>$readData['read_id']));
    			}
    			//不存在就插入新的记录
    			else if( !$readData )
    			{
    			    $insertData = $data;
			        $insertData['read_lasttime'] = time();
    				wmsql::Insert($this->table, $insertData);
    			}
    			//插入阅读详细日志
    			wmsql::Insert($this->logTable, $data);
    			return true;
	        }
	    }
	}
	
	
	/**
	 * 根据内容id获取数据
	 * @param 参数1，必须，查询条件
	 */
	function GetByNid($module,$nid,$uid)
	{
		$where['table'] = $this->table;
		$where['where']['read_module'] = $module;
		$where['where']['read_uid'] = $uid;
		$where['where']['read_nid'] = $nid;
		return wmsql::GetOne($where);
	}
	
	/**
	 * 获得阅读记录列表
	 * @param 参数1，必须，查询条件
	 */
	function GetList($where)
	{
		$where['table'] = $this->table;
		$where['order'] = 'read_lasttime desc';
		switch($where['where']['read_module'])
		{
			//外链接小说模块
			case 'novel':
				$where['left'][$this->chapterTable] = 'read_cid=chapter_id';
				$where['left'][$this->novelTable.' as n'] = 'chapter_nid=novel_id';
				$where['left'][$this->novelTypeTable.' as t'] = 'n.type_id=t.type_id';
				break;
		}
		return wmsql::GetAll($where);
	}
	
	
	
	/**
	 * 获得阅读日志记录行数
	 * @param 参数1，必须，查询条件
	 */
	function GetLogCount($where)
	{
		$where['table'] = $this->logTable;
		return wmsql::GetCount($where,'read_id');
	}
	
	/**
	 * 获得阅读日志记录
	 * @param 参数1，必须，查询条件
	 */
	function GetLogList($where)
	{
		$where['table'] = $this->logTable;
		$where['field'] = 'read_id,read_title,read_cid,read_time,read_uid,novel_name,user_nickname';
		$where['order'] = 'read_time desc';
		$where['left'][$this->userTable] = 'user_id=read_uid';
		switch($where['where']['read_module'])
		{
			//外链接小说模块
			case 'novel':
				$where['field'] .= ',novel_name';
				$where['left'][$this->novelTable] = 'novel_id=read_nid';
				break;
		}
		return wmsql::GetAll($where);
	}


	/**
	 * 删除日志记录行数
	 * @param 参数1，必须，查询条件
	 */
	function DelLog($where)
	{
		return wmsql::Delete($this->logTable,$where);
	}
}
?>