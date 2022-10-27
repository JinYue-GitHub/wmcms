<?php
/**
* 申请模型
*
* @version        $Id: apply.model.php 2017年1月11日 19:35  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class ApplyModel
{
	public $applyTable = '@system_apply';
	private $module;
	private $config;
	private $msgMod;
	//对应模块数据表
	private $authorTable = '@author_author';
	private $novelTable = '@novel_novel';
	private $chapterTable = '@novel_chapter';
	private $articleTable ='@article_article';
	
	
	function __construct($module='author')
	{
		//获得配置
		$this->module = $module;
		if( $module != '' )
		{
			$this->config = GetModuleConfig($module);
		}
		$this->msgMod = NewModel('user.msg');
	}
	
	/**
	 * 格式化申请数据
	 * @param 参数1，必须，格式化数据
	 * @param 参数2，选填，默认编译，为true为转义
	 */
	private function FormatOption($data,$e=false)
	{
		if( $e == false && isset($data['apply_option']) )
		{
			$data['apply_option'] = serialize($data['apply_option']);
			return $data;
		}
		else if( !empty($data) && isset($data['apply_option']) )
		{
			if( str::IsSerialized($data['apply_option']) )
			{
				$data['apply_option'] = unserialize($data['apply_option']);
			}
			else
			{
				$data['apply_option'] = array();
			}
		}
		return $data;
	}
	
	
	/**
	 * 从模块配置中获取配置参数
	 * @param 参数1，必须，配置key
	 */
	private function GetConfig($key)
	{
		if( !$this->config || empty($this->config) )
		{
			$this->config = GetModuleConfig($this->module);
		}
		return $this->config[$key];
	}
	
	/**
	 * 获得拒绝申请的备注信息
	 * @param 参数1，必须，申请类型
	 * @param 参数2，选填，处理请求的类型
	 * @param 参数3，选填，申请数据
	 */
	function GetHandleRemark($type , $status=2,$applyData=null)
	{
	    $remark = $this->GetConfig($this->module.'_'.$type.'_'.$status);
	    if( !empty($applyData) )
	    {
	        $repArr = array();
	        if( $type == 'novel_cover' || $type == 'novel_editnovel' || $type == 'novel_editchapter' )
	        {
	            if( $type == 'novel_editchapter' )
	            {
    	            $where['table'] = $this->chapterTable;
    	            $where['field'] = 'novel_name,chapter_name';
    	            $where['left'][$this->novelTable] = 'chapter_nid=novel_id';
    	            $where['where']['chapter_id'] = $applyData['apply_cid'];
    	            $data = wmsql::GetOne($where);
    	            $repArr['{小说名字}'] = isset($data['novel_name'])?$data['novel_name']:'';
    	            $repArr['{小说章节名字}'] = isset($data['chapter_name'])?$data['chapter_name']:'';
	            }
	            else
	            {
    	            $where['table'] = $this->novelTable;
    	            $where['field'] = 'novel_name';
    	            $where['where']['novel_id'] = $applyData['apply_cid'];
    	            $data = wmsql::GetOne($where);
    	            $repArr['{小说名字}'] = isset($data['novel_name'])?$data['novel_name']:'';
	            }
	        }
	        else if( $type == 'article_editarticle' )
	        {
	            $where['table'] = $this->articleTable;
	            $where['field'] = 'article_name';
	            $where['where']['article_id'] = $applyData['apply_cid'];
	            $data = wmsql::GetOne($where);
	            $repArr['{文章标题}'] = isset($data['article_name'])?$data['article_name']:'';
	        }
	        $remark = strtr($remark,$repArr);
	    }
	    return $remark;
	}
	
	
	/**
	 * 插入申请记录
	 * @param 参数1，必须，记录数据
	 * @param 参数2，选填，是否发送消息？
	 */
	function Insert($data , $sendMsg = 1 )
	{
		$data['apply_createtime'] = time();
		//如果是自动审核
		if( $data['apply_status'] == 1 )
		{
			$data['apply_updatetime'] = time();
		}
		
		//是否发送消息给用户
		if( $sendMsg == 1)
		{
			$remark = $this->GetHandleRemark($data['apply_type'] , $data['apply_status']);
			$this->msgMod->Insert($data['apply_uid'] , $remark);
		}
		
		$data = $this->FormatOption($data);
		return wmsql::Insert($this->applyTable, $data);
	}
	
	/**
	 * 修改申请数据
	 * @param 参数1，必须，所属的模块
	 * @param 参数2，必须，所属模块的类型
	 * @param 参数3，必须，需要修改的内容id
	 */
	function UpdateCid($type , $cid)
	{
		$where['apply_module'] = $this->module;
		$where['apply_type'] = $type;
		$where['apply_cid'] = 0;
		$data['apply_cid'] = $cid;
		return wmsql::Update($this->applyTable, $data, $where);
	}

	/**
	 * 修改申请的状态
	 * @param 参数1，必须，申请id
	 * @param 参数2，选填，需要修改的状态
	 */
	function UpdateStatus($id , $status = 0)
	{
		$where['apply_id'] = $id;
		$data['apply_status'] = $status;
		return wmsql::Update($this->applyTable, $data, $where);
	}

	/**
	 * 批量修改申请的状态
	 * @param 参数1，必须，操作类型
	 * @param 参数2，必须，内容的条件
	 */
	function BatchUpdateStatus($type , $cid = 0)
	{
		$data['apply_status'] = 1;
		$where['apply_status'] = 0;
		$where['apply_module'] = $this->module;
		$where['apply_type'] = $type;
		$where['apply_uid'] = array('>',0);
		if( $cid > 0 )
		{
			$where['apply_cid'] = $cid;
		}
		return wmsql::Update($this->applyTable, $data, $where);
	}
	
	
	/**
	 * 获得一条数据
	 * @param 参数1，必须，查询条件
	 */
	function GetById($id)
	{
		$where['apply_id'] = $id;
		return $this->GetOne($where);
	}

	
	/**
	 * 获得一条数据
	 * @param 参数1，必须，查询条件
	 */
	function GetOne($wheresql)
	{
		$where['table'] = $this->applyTable;
		$where['where'] = $wheresql;
		return $this->FormatOption(wmsql::GetOne($where),true);
	}
	
	/**
	 * 根据条件获得所有数据
	 * @param 参数1，必须，查询条件
	 */
	function GetAll($wheresql)
	{
		$where['table'] = $this->applyTable;
		$where['where'] = $wheresql;
		$data = wmsql::GetAll($where);
		if( $data )
		{
			foreach($data as $k=>$v)
			{
				$data[$k] = $this->FormatOption($v,true);
			}
		}
		return $data;
	}
	
	/**
	 * 根据条件删除数据
	 * @param 参数1，必须，删除条件
	 */
	function Delete($wheresql = '')
	{
		$where = array();
		if( is_array($wheresql) )
		{
			$where = $wheresql;
		}
		else if( $wheresql != '' )
		{
			$where['apply_id'] = $wheresql;
		}
		return wmsql::Delete($this->applyTable , $where);
	}
	
	

	/**
	 * 获得最后一条未审核的数据
	 * @param 参数1，必须，申请模块的操作类型
	 * @param 参数2，必须，用户id
	 * @param 参数3，必须，内容id
	 * @param 参数4，必须，申请状态，默认为0的
	 */
	function GetLastData($type , $uid , $cid=0 , $status = 0)
	{
		$where['table'] = $this->applyTable;
		$where['order'] = 'apply_id desc';
		$where['limit'] = 1;
		$where['where']['apply_module'] = $this->module;
		$where['where']['apply_status'] = $status;
		$where['where']['apply_type'] = $type;
		$where['where']['apply_uid'] = $uid;
		$where['where']['apply_cid'] = $cid;
		return $this->FormatOption(wmsql::GetOne($where),true);
	}
	
	
	/**
	 * 处理申请请求操作
	 * @param 参数1，必须，申请id
	 * @param 参数2，必须，申请用户
	 * @param 参数3，必须，内容的id
	 * @param 参数4，必须，处理的操作类型，0为取消审核，1为通过审核
	 * @param 参数5，选填，备注信息
	 * @param 参数6，选填，是否发送消息
	 * @param 参数7，选填，编辑id
	 */
	function HandleApply($id , $uid , $cid=0 , $status = 0 , $remark = '' , $sendMsg = 1,$editorId=0)
	{
		//获得最后一条数据
		$applyData = $this->GetById($id);
		if( $applyData )
		{
    		//备注信息
    		if( $remark == '' )
    		{
    			$remark = $this->GetHandleRemark($applyData['apply_type'] , $status,$applyData);
    		}
		    //管理员处理
			if( $editorId == '0' )
			{
			    $data['apply_manager_id'] = Session('admin_id');
			}
			//编辑处理
			else
			{
			    $data['apply_editor_id'] = $editorId;
			}
			//存在就修改申请状态信息
			$data['apply_updatetime'] = time();
			$data['apply_remark'] = $remark;
			$data['apply_status'] = $status;
			wmsql::Update($this->applyTable, $data, array('apply_id'=>$applyData['apply_id']));

    		//并且发送消息给用户
    		if( $sendMsg == 1)
    		{
    			$this->msgMod->Insert($uid , $remark);
    		}
		}
		return true;
	}
	
	
	/**
	 * 获取申请的内容变更数据
	 * @param 参数1，必须，申请的数据
	 */
	function GetChange($applyData)
	{
	    $data = array();
	    $module = $applyData['apply_module'];
	    $mt = $applyData['apply_type'];
    	$newData = $applyData['apply_option'];
    	//如果存在新的数据
    	if( $newData )
    	{
    		//获得旧的数据、小说
    		if( $module == 'author' && $mt == 'novel_editnovel')
    		{
    			$novelMod = NewModel('novel.novel');
    			$oldData = $novelMod->GetOne($applyData['apply_cid']);
    			//获得语言包
    			$lang = GetModuleLang('novel');
    			//设置字段的名字
    			$keyTitle=array(
    				'novel_process'=>'书籍进程','novel_type'=>'小说类型','type_id'=>'小说分类','novel_info'=>'小说描述',
    				'novel_name'=>'小说名字','novel_pinyin'=>'小说拼音','novel_author'=>'作者笔名','author_id'=>'作者id',
    				'novel_createtime'=>'创建时间','novel_uptime'=>'更新时间','novel_tags'=>'小说标签','novel_status'=>'小说状态',
    			);
    		}
    		//获得旧的数据、小说封面
    		else if( $module == 'author' && $mt == 'novel_cover')
    		{
    			$novelMod = NewModel('novel.novel');
    			$oldData = $novelMod->GetOne($applyData['apply_cid']);
    			$newData['novel_cover'] = $newData['file'];
    			unset($newData['file']);
    			//获得语言包
    			$lang = GetModuleLang('novel');
    			//设置字段的名字
    			$keyTitle=array(
    				'novel_cover'=>'小说封面'
    			);
    		}
    		//小说章节
    		else if($module == 'author' && $mt == 'novel_editchapter')
    		{
    			unset($newData['type']);
    			$chapterMod = NewModel('novel.chapter');
    			$oldData = $chapterMod->GetById($applyData['apply_cid']);
    			unset($newData['chapter_istxt']);
    			unset($newData['chapter_status']);
    			if( $oldData['is_content'] == false )
    			{
    				$oldData['content'] = '';
    			}
    			//获得语言包
    			$lang = GetModuleLang('novel');
    			//设置字段的名字
    			$keyTitle=array(
    				'chapter_number'=>'章节字数','chapter_name'=>'章节标题','content'=>'章节内容',
    				'chapter_ispay'=>'是否需要购买','chapter_nid'=>'书籍id','chapter_vid'=>'分卷id','chapter_cid'=>'章节id',
    				'chapter_order'=>'章节排序','chapter_time'=>'创建时间',
    			);
    		}
    		//获得旧的数据、文章
    		else if( $module == 'author' && $mt == 'article_editarticle')
    		{
    			$articleMod = NewModel('article.article');
    			$typeMod = NewModel('article.type');
    			$oldData = $articleMod->GetOne($applyData['apply_cid']);
    			//获得语言包
    			$lang = GetModuleLang('novel');
    			//设置字段的名字
    			$keyTitle=array(
    				'article_simg'=>'文章封面','article_name'=>'文章标题','article_cname'=>'文章短标题','type_id'=>'文章分类',
    				'article_source'=>'文章来源','article_tags'=>'文章标签','article_info'=>'文章简介','article_content'=>'文章内容',
    			);
    		}
    		
    		foreach ($newData as $k=>$v)
    		{
    			if( !isset($data[$k]) )
    			{
    				$data[$k] = array();
    			}
    			$data[$k]['title'] = GetKey($keyTitle, $k);
    			$data[$k]['old'] = GetKey($oldData, $k);
    			$data[$k]['new'] = $v;
    			
    			//小说字段处理
    			if( $module == 'author' && $mt == 'novel_editnovel')
    			{
    				switch ($k)
    				{
    					case 'novel_process':
    						$data[$k]['old'] = $lang['novel']['par']['novel_process_'.$oldData[$k]];
    						$data[$k]['new'] = $lang['novel']['par']['novel_process_'.$v];
    						break;
    					case 'novel_type':
    						$data[$k]['old'] = $lang['novel']['par']['novel_type_'.$oldData[$k]];
    						$data[$k]['new'] = $lang['novel']['par']['novel_type_'.$v];
    						break;
    					case 'type_id':
    						$data[$k]['old'] = $oldData['type_name'];
    						$data[$k]['new'] = $newData['type_name'];
    						break;
    					case 'novel_createtime':
    						$data[$k]['old'] = date("Y-m-d H:i",$oldData['novel_createtime']);
    						$data[$k]['new'] = date("Y-m-d H:i",$newData['novel_createtime']);
    						break;
    					case 'novel_uptime':
    						$data[$k]['old'] = date("Y-m-d H:i",$oldData['novel_uptime']);
    						$data[$k]['new'] = date("Y-m-d H:i",$newData['novel_uptime']);
    						break;
    					case 'novel_status':
    						$data[$k]['old'] = $lang['novel']['par']['novel_status_'.$oldData[$k]];
    						$data[$k]['new'] = $lang['novel']['par']['novel_status_'.$v];
    						break;
    						
    				}
    				unset($data['type_name']);
    			}
    			//章节字段处理
    			if( $module == 'author' && $mt == 'novel_editchapter')
    			{
    				switch ($k)
    				{
    					case 'chapter_ispay':
    						$data[$k]['old'] = $lang['novel']['par']['chapter_type_'.$oldData[$k]];
    						$data[$k]['new'] = $lang['novel']['par']['chapter_type_'.$v];
    						break;
    					case 'chapter_time':
    						$data[$k]['old'] = date("Y-m-d H:i",$oldData['chapter_time']);
    						$data[$k]['new'] = date("Y-m-d H:i",$newData['chapter_time']);
    						break;
    						
    				}
    			}
    			//文章字段处理
    			if( $module == 'author' && $mt == 'article_edit')
    			{
    				switch ($k)
    				{
    					case 'type_id':
    						$typeData = $typeMod->GetById($newData['type_id']);
    						$data[$k]['old'] = $oldData['type_name'];
    						$data[$k]['new'] = $typeData['type_name'];
    						break;
    				}
    			}
    		}
	    }
	    return $data;
	}
	
	
	/**
	 * 申请处理操作
	 * @param 参数1，必须，申请id
	 * @param 参数2，必须，处理状态
	 * @param 参数3，选填，备注
	 * @param 参数4，选填，模块
	 * @param 参数5，选填，类型
	 */
	function ApplyHandle($id,$status,$remark='',$editorId=0,$module='',$type='')
	{
		$where['apply_id'] = $id;
		if( !empty($module) )
		{
		    $where['apply_module'] = $module;
		}
		if( !empty($type) )
		{
		    $where['apply_type'] = $type;
		}
		$dataList = $this->GetAll($where);
		if( $dataList )
		{
    	    //处理
    	    if( $status == '1' )
    	    {
    	        return $this->ApplyPass($dataList,$editorId);
    	    }
    	    //拒绝
    	    else if( $status == '2' )
    	    {
    	        return $this->ApplyRefuse($dataList[0],$remark,$editorId);
    	    }
		}
		return true;
	}
	/**
	 * 申请通过
	 * @param 参数1，必须，申请数据集
	 * @param 参数2，选填，处理的编辑di
	 */
	private function ApplyPass($dataList,$editorId=0)
	{
	    $status = 1;
	    $novelMod = NewModel('novel.novel');
	    $chapterMod = NewModel('novel.chapter');
		$articleMod = NewModel('article.article');
	    //循环处理数据集
		foreach ($dataList as $k=>$v)
		{
			if( $v['apply_cid'] > 0 && $v['apply_status'] == 0)
			{
			    //设置当前模块
			    $this->module = $v['apply_module'];
				//新的数据
				$newData = $v['apply_option'];
			    //如果是作者编辑小说申请
			    if( $v['apply_module'] == 'author' && $v['apply_type'] == 'novel_editnovel' )
			    {
    				//旧的数据
    				$oldData = $novelMod->GetOne($v['apply_cid']);
    				$newData['novel_status'] = $status;
    				unset($newData['type_name']);
    				$result = $novelMod->Update($newData , $v['apply_cid']);
    				//如果修改小说分类就要移动txt文件路径
    				if( $result && isset($newData['type_id']) )
    				{
    					$novelMod->MoveNovelFolder($oldData['type_id'],$newData['type_id'],$v['apply_cid']);
    				}
			    }
			    //如果是作者编辑封面申请
			    else if( $v['apply_module'] == 'author' && $v['apply_type'] == 'novel_cover' )
			    {
    				$novelMod->Update(array('novel_cover'=>$v['apply_option']['file']) , $v['apply_cid']);
    			}
			    //如果是作者编辑章节申请
			    else if( $v['apply_module'] == 'author' && $v['apply_type'] == 'novel_editchapter' )
			    {
					$content = $newData['content'];
					$type = GetKey($newData,'type');
					$newData['chapter_status'] = 1;
					unset($newData['content']);
					unset($newData['type']);
					//获得小说的数据
					$oldData = $novelMod->GetOne($newData['chapter_nid']);
					//获得现有的章节数据
					$chapterData = $chapterMod->GetOne($v['apply_cid']);
					//现在的章节字数
					$wordNumber = $chapterData['chapter_number'];
					//章节是否修改还是编辑
					$wordNumber = 0;
					$st = 'add';
					if( $type=='edit' )
					{
						$wordNumber = $chapterData['chapter_number'];
						$st = 'edit';
					}
					//修改现在的章节数据
					$chapterMod->Update($newData , $v['apply_cid']);
					//创建小说文章内容
					$chapterMod->CreateChapter( $st , $newData['chapter_nid'] , $v['apply_cid'] , $content);
					//更新小说字数
					$novelMod->UpWordNumber($newData['chapter_nid'] , $oldData['novel_wordnumber'] , $wordNumber , $newData['chapter_number']);
					//更新小说的最新章节信息
					$novelMod->UpNewChapter($oldData , $v['apply_cid'],$newData['chapter_name']);
					//创建HTML
			        $htmlMod = NewModel('system.html' , array('module'=>'novel'));
					$htmlMod->CreateContentHtml($v['apply_cid']);
    			}
			    //如果是文章投稿申请
			    else if( $v['apply_module'] == 'author' && $v['apply_type'] == 'article_editarticle' )
			    {
					$newData['article_status'] = $status;
					//修改现在的文章数据
					$articleMod->Update($newData , $v['apply_cid']);
					//创建HTML
			        $htmlMod = NewModel('system.html' , array('module'=>'article'));
					$htmlMod->CreateContentHtml($v['apply_cid']);
    			}
				//插入消息和修改申请记录
				$this->HandleApply($v['apply_id'] , $v['apply_uid'] , $v['apply_cid'] , $status,'',1,$editorId);
			}
		}
		return true;
	}
	/**
	 * 申请拒绝
	 * @param 参数1，必须，申请数据
	 * @param 参数2，必须，拒绝的理由
	 * @param 参数3，选填，处理的编辑id
	 */
	private function ApplyRefuse($applyData,$remark,$editorId=0)
	{
	    //设置当前模块
	    $this->module = $applyData['apply_module'];
	    //拒绝申请操作
		$this->HandleApply($applyData['apply_id'] , $applyData['apply_uid'] , $applyData['apply_cid'], 2 ,$remark,1,$editorId);
		//拒绝回调方法
		$result = $this->ApplyRefuseCallBack($applyData['apply_module'], $applyData['apply_type'], $applyData['apply_uid'] , $applyData['apply_cid']);
		//修改编辑器上传的内容id
		//$uploadMod = NewModel('upload.upload');
		//$uploadMod->UpdateCid( $applyData['apply_module'],$applyData['apply_type'], $applyData['apply_cid']);
		return $result;
	}
	/**
	 * 拒绝者申请回调方法
	 * @param 参数1，必须，module，模块
	 * @param 参数2，必须，type，操作类型
	 * @param 参数1，必须，uid，用户id
	 * @param 参数2，必须，cid，内容id
	 */
	private function ApplyRefuseCallBack($module , $type , $uid , $cid)
	{
	    $table = '';
	    //作者申请
	    if( $module=='author' && $type=='apply' )
	    {
    		$table = $this->authorTable;
    		$data['author_status'] = 2;
    		$where['author_id'] = $cid;
    		$where['user_id'] = $uid;
    		$info = '拒绝了作者申请！';
	    }
	    //封面小说申请
	    else if( $module=='author' && $type=='novel_cover' )
	    {
    		$table = $this->novelTable;
    		$novelConfig = GetModuleConfig('novel');
    		$data['novel_cover'] = $novelConfig['cover'];
    		$where['novel_id'] = $cid;
    		$info = '拒绝了封面申请！';
	    }
	    //小说编辑申请
	    else if( $module=='author' && $type=='novel_editnovel' )
	    {
    		$table = $this->novelTable;
    		$data['novel_status'] = 2;
    		$where['novel_id'] = $cid;
    		$info = '拒绝了小说修改申请！';
	    }
	    //小说章节申请
	    else if( $module=='author' && $type=='novel_editchapter' )
	    {
    		$table = $this->chapterTable;
    		$data['chapter_status'] = 2;
    		$where['chapter_id'] = $cid;
    		$info = '拒绝了章节修改申请！';
	    }
	    //文章申请
	    else if( $module=='author' && $type=='article_editarticle' )
	    {
    		$table = $this->articleTable;
    		$data['article_status'] = 2;
    		$where['article_id'] = $cid;
    		$info = '拒绝了文章投稿修改申请！';
	    }
	    //存在表数据
	    if( !empty($table) )
	    {
    	    //修改数据
    		wmsql::Update($table, $data, $where);
    		//返回变更的数据
    		$result['table'] = $table;
    		$result['where'] = $where;
    		$result['data'] = $data;
    		$result['info'] = $info;
    		return $result;
	    }
	    return true;
	}
}
?>