<?php
/**
* 小说章节模型
*
* @version        $Id: chapter.model.php 2017年1月8日 13:07  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class ChapterModel
{
	//分类表
	public $chapterTable = '@novel_chapter';
	//内容表
	public $contentTable = '@novel_content';
	//小说表
	public $novelTable = '@novel_novel';
	//分卷表
	public $volumeTable = '@novel_volume';
	//小说配置
	private $novelConfig;
	//保存路径
	private $novelSave;
	private $chapterSave;
	//加密字符串
	private $enStr;
	//网站api信息
	private $appid;
	private $apikey;
	private $sckey;
	
	//构造函数
	public function __construct()
	{
		global $C;
		$this->appid = $C['config']['api']['system']['api_appid'];
		$this->apikey = $C['config']['api']['system']['api_apikey'];
		$this->sckey = $C['config']['api']['system']['api_secretkey'];
		//获取配置文件
		$this->novelConfig = GetModuleConfig('novel');
		//保存路径
		$this->novelSave = WMROOT.$this->novelConfig['novel_save'];
		$this->chapterSave = WMROOT.$this->novelConfig['chapter_save'];
		//语音合成保存路径
		$this->novelMp3Save = WMROOT.$this->novelConfig['novel_mp3_save'];
		$this->chapterMp3Save = WMROOT.$this->novelConfig['chapter_mp3_save'];
		//自定义加密字符串
		$this->enStr = $this->novelConfig['novel_en_str'];
		if( $this->enStr == '' )
		{
			$this->enStr = $this->appid.$this->apikey.$this->sckey;
		}
	}
	
	
	/**
	 * 插入章节
	 * @param 参数1，必须，条件
	 */
	function Insert( $data )
	{
		return wmsql::Insert($this->chapterTable, $data);
	}
	
	/**
	 * 修改小说内容
	 * @param 参数1，必须，修改的内容
	 */
	function Update($data , $whereArr)
	{
		if( !is_array($whereArr) )
		{
			$where['chapter_id'] = $whereArr;
		}
		else
		{
			$where = $whereArr;
		}

		return wmsql::Update($this->chapterTable, $data, $where);
	}

	/**
	 * 删除一条数据
	 */
	function Delete($wheresql)
	{
		if( !is_array($wheresql) )
		{
			$where['chapter_id'] = $wheresql;
		}
		else
		{
			//使用小说id删除
			if( isset($wheresql['chapter_nid']) && is_array($wheresql['chapter_nid']) && count($wheresql) == 1 )
			{
				$where = 'chapter_nid IN('.$wheresql['chapter_nid'][1].')';
			}
			//使用id删除
			else if( isset($wheresql['chapter_id']) && is_array($wheresql['chapter_id']) && count($wheresql) == 1 )
			{
				$where = 'chapter_id IN('.$wheresql['chapter_id'][1].')';
			}
			else
			{
				$where = $wheresql;
			}
		}
		return wmsql::Delete($this->chapterTable , $where);
	}
	
	/**
	 * 根据id删除章节
	 */
	function DeleteById($cid)
	{
		$applyMod = NewModel('system.apply');
		$novelMod = NewModel('novel.novel');
		$contentMod = NewModel('novel.content');
		$saidMod = NewModel('novel.said');
		
		$data = $this->GetOne($cid);
		
		//删除一条数据
		$where['chapter_id'] = $cid;
		wmsql::Delete($this->chapterTable , $where);
		
		//删除申请记录
		$applyWhere['apply_cid'] = $cid;
		$applyWhere['apply_module'] = 'author';
		$applyWhere['apply_type'] = 'novel_editchapter';
		$applyMod->Delete($applyWhere);
		
		//删除内容
		$contentMod->Delete($where);
		
		//更新小说的最新章节信息
		$novelMod->UpNewChapter($data , '' , '' , 1);

		//删除作者说
		$saidMod->DeleteByCid($data['chapter_nid'],$cid);
		
		//删除文件
		if( !empty($data['chapter_path']) )
		{
			file::DelFile(WMROOT.$data['chapter_path']);
		}
		return true;
	}
	
	
	/**
	 * 检查小说在章节名字是否存在
	 * @param 参数1，必须，小说章节的名字
	 * @param 参数2，必填，小说的id
	 * @param 参数3，选填，小说章节deid
	 */
	function CheckName( $name , $nid , $cid = '0' )
	{
		$where['chapter_name'] = $name;
		$where['chapter_nid'] = $nid;
		$where['chapter_id'] = array('<>',$cid);
		return $this->GetCount($where);
	}

	
	/**
	 * 获得数据条数
	 * @param 参数1，必须，查询条件
	 */
	function GetCount($where)
	{
		$wheresql['table'] = $this->chapterTable;
		$wheresql['where'] = $where;
		return wmsql::GetCount($wheresql);
	}

	
	/**
	 * 获得小说的最新章节数据
	 * @param 参数1，必须，小说的id
	 */
	function GetNewChapter($nid)
	{
		$where['table'] = $this->chapterTable;
		$where['where']['chapter_nid'] = $nid;
		$where['order'] = 'chapter_status,chapter_order desc';
		$where['limit'] = '1';
		return wmsql::GetOne($where);
	}
	
	/**
	 * 获得小说的最新顺序
	 * @param 参数1，必须，小说的id
	 */
	function GetChapterOrder($nid)
	{
		$where['table'] = $this->chapterTable;
		$where['where']['chapter_nid'] = $nid;
		$where['order'] = 'chapter_order desc';
		$where['limit'] = '1';
		$data = wmsql::GetOne($where);
		if ( $data )
		{
			$order = $data['chapter_order'] + 1;
		}
		else
		{
			$order = 1;
		}
		return $order;
	}

	/**
	 * 获取小说配置
	 * @param 参数1，选填，参数名字，不填则返回全部
	 */
	function GetConfig($key = '')
	{
		if( $key != '' )
		{
			return $this->novelConfig[$key];
		}
		else
		{
			return $this->novelConfig;
		}
	}

	
	/**
	 * 创建小说txt
	 * @param 参数1，必填，分类id
	 * @param 参数2，必填，内容id
	 * @param 参数3，必填，小说名字
	 * @param 参数4，必填，简介内容
	 */
	function CreateNovel( $tid , $nid , $name , $info )
	{
		if( $this->GetConfig('data_type') == '1' )
		{
			$fileName = $this->GetNovelFileName($tid,$nid);
			if( !file_exists($fileName) )
			{
				$fileContent = str::ToTxt($name)."\r\n".$this->GetConfig('novel_head')."\r\n".str::ToTxt($info)."\r\n";
				file::CreateFile($fileName, $fileContent);
			}
		}
		return true;
	}
	
	
	/**
	 * 更新小说txt
	 * @param 参数1，必填，分类id
	 * @param 参数2，必填，内容id
	 * @param 参数3，必填，小说名字
	 * @param 参数4，必填，简介内容
	 */
	function UpdateNovel( $file , $data )
	{
		//章节列表和章节内容
		$content = '';
		$chapterList = array();
		//不存在文件就创建文件。
		if( !file_exists($file) )
		{
			$this->CreateNovel($data['type_id'],$data['novel_id'],$data['novel_name'],$data['novel_info'] );
			$chapterList = $this->GetByNid($data['novel_id']);
		}
		//存在，但是不是最新的
		else if( file_exists($file) && $data['novel_uptime'] > filemtime($file) )
		{
			$where['novel_uptime'] = array('>',filemtime($file));
			$chapterList = $this->GetByNid($data['novel_id'],$where);
		}
		//如果数据不为空就写入
		if( !empty($chapterList) )
		{
			//循环查询数据
			foreach ($chapterList as $k=>$v)
			{
				if( $v && $v['chapter_path'] != '' && file_exists(WMROOT.$v['chapter_path']) )
				{
					$txtContent = str::ToHtml(file::GetFile(WMROOT.$v['chapter_path']));
				}
				else
				{
					$txtContent = $this->GetTxtContent($v['type_id'],$v['novel_id'],$v['chapter_id'],$v['chapter_istxt']);
				}
				
				$content = $content.$this->GetConfig('chapter_start')."\r\n".
						$v['chapter_name']."\r\n".
						str::ToTxt($txtContent)."\r\n".
						$this->GetConfig('chapter_end')."\r\n";
			}
			//将章节内容写入到完整的txt文件
			file::CreateFile($file, $content , '1');
		}
		return true;
	}
	
	
	/**
	 * 写入章节信息
	 * @param 参数1，必填，操作类型，是增加add还是修改edit
	 * @param 参数2，必填，小说id
	 * @param 参数3，必填，章节id
	 * @param 参数4，必填，章节内容
	 * @param 参数5，选填，创建章节是否需要审核，默认是需要审核
	 */
	function CreateChapter( $type , $nid , $cid , $content , $status = 0)
	{
		$where['table'] = $this->chapterTable;
		$where['left']['@novel_novel'] = 'chapter_nid=novel_id';
		$where['where']['chapter_id'] = $cid;
		$arr = WMSql::GetOne($where);
	
		if( $arr && $arr['novel_name'] != '' )
		{
			//数据入库模式为生成txt
			if( $this->GetConfig('data_type') == '1' )
			{
				//创建新的章节路径
				$fileName = $this->GetChapterFileName($arr['type_id'],$arr['novel_id'],$arr['chapter_id']);
				
				//小说章节标题和内容
				$title = str::ToTxt($arr['chapter_name']);
				$content = str::ToTxt($content);
				//将章节内容写入到txt文件
				file::CreateFile($fileName, $content , '1');
			}
			//数据模式为入库
			else
			{
				//章节内容
				$contentData['content'] = $content;
				//新增数据
				if( $type == 'add' || $arr['chapter_cid'] == '0' )
				{
					//插入数据
					$chapterData['chapter_cid'] = wmsql::Insert($this->contentTable, $contentData);
					//将最新的内容id写入章节里面
					wmsql::Update($this->chapterTable, $chapterData, $where['where']);
				}
				//修改数据
				else
				{
					//修改章节内容
					$contentWhere['content_id'] = $arr['chapter_cid'];
					wmsql::Update($this->contentTable, $contentData, $contentWhere);
				}
			}
		}
		return true;
	}
	
	
	/**
	 * 获得章节的内容
	 * @param 参数1，必须，字符串，章节id
	 */
	function GetById( $cid )
	{
		$where['table'] = $this->chapterTable;
		$where['field'] = 'type_id,author_id,chapter_status,chapter_isvip,chapter_ispay,chapter_istxt,
				chapter_time,chapter_nid,chapter_id,chapter_vid,chapter_islogin,chapter_cid,chapter_name,chapter_number,chapter_order,novel_id,author_id,
				volume_name,volume_desc';
		$where['left'][$this->novelTable] = 'novel_id=chapter_nid';
		$where['left'][$this->volumeTable] = 'volume_id=chapter_vid';
		$where['where']['chapter_id'] = $cid;
		$data = wmsql::GetOne($where);
		
		if( $data )
		{
			$data['is_content'] = true;
			
			if( $data['chapter_istxt'] == '1' )
			{
				$data['content'] = $this->GetTxtContent($data['type_id'],$data['chapter_nid'],$data['chapter_id']);
				if( $data['content'] == false )
				{
					$data['is_content'] = false;
				}
			}
			else
			{
				$contentWhere['table'] = $this->contentTable;
				$contentWhere['where']['content_id'] = $data['chapter_cid'];
				$contentData = wmsql::GetOne($contentWhere);
				$data['content'] = $contentData['content'];
			}
		}
		
		return $data;
	}

	/**
	 * 获得一条数据
	 * @param 参数1，必须，查询条件
	 */
	function GetOne($where)
	{
		$wheresql['table'] = $this->chapterTable;
		$wheresql['left'][$this->novelTable] = 'chapter_nid=novel_id';
		if( is_array($where) )
		{
			if( isset($where['chapter_id']) && is_array($where['chapter_id']) )
			{
				$where['chapter_id'][0] = 'in_id';
			}
			$wheresql['where'] = $where;
		}
		else
		{
			$wheresql['where']['chapter_id'] = $where;
		}
		return wmsql::GetOne($wheresql);
	}
	

	/**
	 * 获得小说的全部章节
	 * @param 参数1，必须，小说id
	 * @param 参数2，选填，其他的参数条件
	 * @param 参数3，选填，查询的字段
	 */
	function GetByNid($nid , $where = array() , $field ='')
	{
		//字段
		if( $field == '' )
		{
			$field = 'chapter_istxt,type_id,novel_id,chapter_id,chapter_cid,chapter_path,chapter_name';
		}
		$wheresql['table'] = $this->chapterTable;
		$wheresql['field'] = $field;
		$wheresql['left'][$this->novelTable] = 'chapter_nid=novel_id';
		$wheresql['where']['chapter_nid'] = $nid;
		$wheresql['order'] = 'chapter_order';
		if( !empty($where) )
		{
			$wheresql['where'] = $where;
		}
		return wmsql::GetAll($wheresql);
	}
	
	
	/**
	 * 获得小说章节txt内容
	 * @param 参数1，必须，小说分类id
	 * @param 参数2，必须，小说id
	 * @param 参数3，必须，小说章节id
	 * @param 参数4，选填，是否是生成txt
	 */
	function GetTxtContent($tid = '', $nid = '' , $cid = '' , $isTxt = '1')
	{
		if( $isTxt == '1' )
		{
			//查询数据库的章节路径是否存在内容
			$where['table'] = $this->chapterTable;
			$where['field'] = 'chapter_path';
			$where['where']['chapter_id'] = $cid;
			$data = wmsql::GetOne($where);
			if( $data && $data['chapter_path'] != '' && file_exists(WMROOT.$data['chapter_path']) )
			{
				$file = WMROOT.$data['chapter_path'];
			}
			else
			{
				$file = $this->GetChapterFileName($tid,$nid,$cid);
				//如果保存路径为空就修改保存路径
				$saveWhere['chapter_id'] = $cid;
				$saveData['chapter_path'] = str_replace(WMROOT, '', $file);
				wmsql::Update($this->chapterTable, $saveData, $saveWhere);
			}
			$content = str::ToHtml(file::GetFile($file));
			if( !file_exists($file) )
			{
				return false;
			}
			else
			{
				return $content;
			}
		}
		else
		{
			$content = '';
			$chapterWhere['table'] = $this->chapterTable;
			$chapterWhere['field'] = 'chapter_cid';
			$chapterWhere['where']['chapter_id'] = $cid;
			$chapterData = wmsql::GetOne($chapterWhere);
			if( $chapterData )
			{
				$contentWhere['table'] = $this->contentTable;
				$contentWhere['where']['content_id'] = $chapterData['chapter_cid'];
				$contentData = wmsql::GetOne($contentWhere);
				if( $contentData )
				{
					$content = $contentData['content'];
				}
			}
			return $content;
		}
	}
	
	
	/**
	 * 获得章节文件名字
	 * @param 参数1，必须，小说分类id
	 * @param 参数2，必须，小说id
	 * @param 参数3，必须，小说章节id
	 * @param 参数4，选填，文件类型，默认txt，可选mp3
	 */
	function GetChapterFileName($tid , $nid , $cid,$type='txt')
	{
		$nid = str::E($nid.$this->enStr);
		$cid = str::E($cid.$this->enStr);
		$filePath = $this->chapterSave;
		if( $type == 'mp3' )
		{
		    $filePath = $this->chapterMp3Save;
		}
		return tpl::Rep(array('tid'=>$tid,'nid'=>$nid,'cid'=>$cid) , $filePath);
	}
	/**
	 * 获得小说文件名字
	 * @param 参数1，必须，小说分类id
	 * @param 参数2，必须，小说id
	 * @param 参数3，选填，文件类型，默认txt，可选mp3
	 */
	function GetNovelFileName($tid , $nid ,$type='txt')
	{
		$nid = str::E($nid.$this->enStr);
		$filePath = $this->novelSave;
		if( $type == 'mp3' )
		{
		    $filePath = $this->novelMp3Save;
		}
		return tpl::Rep(array('tid'=>$tid,'nid'=>$nid) , $filePath);
	}
	
	
	/**
	 * 保存小说章节的TXT路径
	 * @param 参数1，必须，小说分类id
	 * @param 参数2，必须，小说id
	 * @param 参数3，必须，小说章节id
	 */
	function SaveChapterPath($tid,$nid,$cid)
	{
		$where['chapter_id'] = $cid;
		$data['chapter_path'] = str_replace(WMROOT,'',$this->GetChapterFileName($tid,$nid,$cid));
		return wmsql::Update($this->chapterTable,$data,$where);
	}
	/**
	 * 保存小说整本的TXT路径
	 * @param 参数1，必须，小说分类id
	 * @param 参数2，必须，小说id
	 */
	function SaveNovelPath($tid,$nid)
	{
		//修改章节保存路径
		$chapterWhere['chapter_nid'] = $nid;
		$chapterData['chapter_path'] = '';
		wmsql::Update($this->chapterTable, $chapterData, $chapterWhere);
		
		$where['novel_id'] = $nid;
		$data['novel_path'] = str_replace(WMROOT,'',$this->GetNovelFileName($tid,$nid));
		return wmsql::Update($this->novelTable,$data,$where);
	}
	
	/**
	 * 检查小说章节的订阅状态
	 * @param 参数1，必须,章节的内容数组
	 * @param 参数2，选填,参数类型，1为传入的数组，2为传入的cid
	 */
	function CheckChapterSub($data,$type='1')
	{
	    if( $type=='2' )
	    {
	        $data = $this->GetById($data);
	        $data['is_sub'] = 1;
	    }
		$authorData = array();
		//关联了用户模块才检测
		if( class_exists('user') )
		{
			//检查是否限时免费
			$TLMod = NewModel('novel.timelimit');
			$TLData = $TLMod->GetByNid($data['novel_id']);
			
			//不存在限时免费数据，或者暂停免费，或者限时开始时间大于当前时间，或者限时结束时间小于当前时间
			if( !$TLData || $TLData['timelimit_status']==0 || 
				$TLData['timelimit_starttime'] > time() || $TLData['timelimit_endtime'] < time() )
			{
				$uid = user::GetUid();
		
				$authorData['user_id'] = 0;
				//获得作者信息
				if( $data['author_id'] > 0 )
				{
					$authorMod = NewModel('author.author');
					$authorData = $authorMod->GetAuthor($data['author_id'] , 2);
				}
				
				//判断章节审核状态
				if( $data['chapter_status'] !='1' && $authorData['user_id'] != $uid )
				{
					return 201;
				}
				//判断是否需要登录
				else if( $data['chapter_islogin']=='1'  && $uid == 0 )
				{
					return 202;
				}
				//判断是否需要付费
				else if( $data['chapter_ispay']=='1' )
				{
					//没有登录
					if( $uid == 0 )
					{
						return 202;
					}
					//作者不是自己
					else if ( empty($authorData) || $authorData['user_id'] != $uid )
					{
						//是否买了全本、包月、单章
						$subMod = NewModel('novel.sublog');
						$isSub = $subMod->IsSub($uid , $data['novel_id'] , $data['chapter_id'] , $data);
						//如果没有订阅
						if( !$isSub )
						{
							$data['is_sub'] = 0;
		                    $data['content'] = '';
						}
					}
				}
			}
		}
		
		if($data['content'] == '' && (!isset($data['is_sub']) || (isset($data['is_sub']) && $data['is_sub'] !=0) ) )
		{
			if( $data['chapter_path'] != '' && file_exists(WMROOT.$data['chapter_path']) )
			{
				$data['content'] = str::ToHtml(file::GetFile(WMROOT.$data['chapter_path']));
			}
			else
			{
				$data['content'] = $this->GetTxtContent($data['type_id'], $data['novel_id'] , $data['chapter_id'],$data['chapter_istxt']);
			}
		}
		$data['content'] = strtr($data['content'],array('<br />'=>'<br/>'));
		return $data;
	}
	
	
	/**
	 * 获得小说章节列表
	 * @param 参数1，必须，小说id
	 * @param 参数2，必须，分卷id
	 */
	function GetList( $wheresql )
	{
		$where['table'] = $this->chapterTable;
		$where['field'] = 'chapter_id,chapter_name,chapter_status,chapter_isvip,chapter_ispay,chapter_number,chapter_nid,chapter_vid,chapter_order,chapter_time';
		$where['order'] = 'chapter_order asc';

		$where = MergeWhere($where , $wheresql);
		return wmsql::GetAll($where);
	}
	
	
	/**
	 * 获得小说当前章节顺序的上下章节
	 * @param 参数1，必须，小说id
	 * @param 参数2，必须，分卷id
	 */
	function GetPrveNext($nid,$order)
	{
        $data['prev'] = ['chapter_id'=>0,'chapter_name'=>''];
        $data['next'] = ['chapter_id'=>0,'chapter_name'=>''];
        
	    //上下章查询
		$where['field'] = 'chapter_id,chapter_order,chapter_name';
		$where['table'] = $this->chapterTable;
		$where['where']['chapter_nid'] = $nid;
		$where['where']['chapter_status'] = 1;
		//上一篇查询
		$where['where']['chapter_order'] = array('<',$order);
		$dataList = wmsql::GetAll($where);
		if( $dataList )
		{
			$dataList = ArrSort($dataList , 'chapter_order' , 'desc');
			$data['prev']['chapter_id'] = $dataList[0]['chapter_id'];
			$data['prev']['chapter_name'] = $dataList[0]['chapter_name'];
		}
		//下一篇查询
		$where['where']['chapter_order'] = array('>',$order);
		$dataList = wmsql::GetAll($where);
		if( $dataList )
		{
			$dataList = ArrSort($dataList , 'chapter_order' , 'asc');
			$data['next']['chapter_id'] = $dataList[0]['chapter_id'];
			$data['next']['chapter_name'] = $dataList[0]['chapter_name'];
		}
		return $data;
	}
}
?>