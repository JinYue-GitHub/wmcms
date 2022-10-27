<?php
/**
 * 小说章节的类文件
 *
 * @version        $Id: novel.chapter.class.php 2016年4月28日 17:00  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class NovelChapter
{
	//章节表
	public $chapterTable = '@novel_chapter';
	//内容表
	public $contentTable = '@novel_content';
	//小说配置
	private $novelConfig;
	//保存路径
	private $novelSave;
	private $chapterSave;
	private $chapterMod;
	
	//构造函数
	public function __construct()
	{
		$this->chapterMod = NewModel('novel.chapter');
		//获取配置文件
		$this->novelConfig = AdminInc('novel');
		//保存路径
		$this->novelSave = '..'.$this->novelConfig['novel_save'];
		$this->chapterSave = '..'.$this->novelConfig['chapter_save'];
	}
	
	
	/**
	 * 获取小说配置
	 * @param 参数1，选填，参数名字，不填则返回全部
	 */
	private function GetConfig($key = '')
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
	 * 获得最新的章节
	 * @param 参数1，必须，小说的id
	 */
	function GetNewChapter($nid)
	{
		$where['table'] = $this->chapterTable;
		$where['where']['chapter_nid'] = $nid;
		$where['order'] = 'chapter_order desc';
		$where['limit'] = '1';
		$data = wmsql::GetOne($where);
		return $data;
	}
	
	
	/**
	 * 根据章节id查找内容
	 * @param 参数1，必须,章节的id
	 */
	function GetById($cid)
	{
		$where['table'] = $this->chapterTable;
		$where['where']['chapter_id'] = $cid;
		$data = wmsql::GetOne($where);
		return $data;
	}
	
	/**
	 * 获得章节txt
	 * @param 参数1，必填，操作类型，添加还是修改数据
	 * @param 参数2，必填，小说id
	 * @param 参数3，必填，章节id
	 * @param 参数4，必填，章节内容
	 */
	function CreateChapter( $type , $nid , $cid , $content )
	{
		$where['table'] = $this->chapterTable;
		$where['left']['@novel_novel'] = 'chapter_nid=novel_id';
		$where['where']['chapter_id'] = $cid;
		$arr = WMSql::GetOne($where);
		
		if( $arr && $arr['novel_name'] != '' )
		{
			//生成txt
			if( $this->GetConfig('data_type') == '1' )
			{
				//创建新的章节
				$fileName = $this->chapterMod->GetChapterFileName($arr['type_id'],$nid,$arr['chapter_id']);
				//章节内容
				$content = str::ToTxt($content)."\r\n";
				
				//章节开始字符串
				$startStr = $this->GetConfig('chapter_start');
				if ( $startStr != '' )
				{
					$startStr = $startStr."\r\n";
				}
				//章节结束字符串
				$endStr = $this->GetConfig('chapter_end');
				if ( $endStr != '' )
				{
					$endStr = $endStr."\r\n";
				}
				//小说全文内容
				$fileContent = str::ToTxt($arr['chapter_name'])."\r\n".$endStr.$content.$endStr;
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
	 * @param 参数1，必须，数组，章节的信息
	 */
	function GetContent( $data )
	{
		if( $data['chapter_istxt'] == '1' )
		{
			$file = tpl::Rep(array('nid'=>$data['chapter_nid'],'cid'=>$data['chapter_id']) , $this->chapterSave);
			$content = file::GetFile($file);
		}
		else
		{
			$contentWhere['table'] = $this->contentTable;
			$contentWhere['where']['content_id'] = $data['chapter_cid'];
			$contentData = wmsql::GetOne($contentWhere);
			$content = $contentData['content'];
		}

		return $content;
	}
	

	/**
	 * 删除小说章节文件
	 * @param 参数1，必须，分类id
	 * @param 参数2，必须，小说id
	 * @param 参数3，必须，章节id
	 */
	function DelChapterFile($tid,$nid,$cid)
	{
		if( is_array($cid) )
		{
			$cidArr = $cid;
		}
		else
		{
			$cidArr = explode(',',$cid);
		}
		
		foreach ($cidArr as $k=>$v)
		{
			$novelFile = $this->chapterMod->GetChapterFileName($tid , $nid,$v);
			file::DelFile($novelFile);
		}
		return true;
	}
}
?>