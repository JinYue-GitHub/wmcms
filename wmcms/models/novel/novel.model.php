<?php
/**
* 小说信息模型
*
* @version        $Id: novel.model.php 2016年12月30日 20:07  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class NovelModel
{
	//分类表
	public $typeTable = '@novel_type';
	//内容表
	public $novelTable = '@novel_novel';
	//章节表
	public $chapterTable = '@novel_chapter';

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
		//自定义加密字符串
		$this->enStr = $this->novelConfig['novel_en_str'];
		if( $this->enStr == '' )
		{
			$this->enStr = $this->appid.$this->apikey.$this->sckey;
		}
	}
	
	/**
	 * 获得小说销售的类型
	 * @param 参数1，销售类型，可以为空。默认返回全部
	 */
	function GetSellType($type = '')
	{
		$typeArr = array(
			'sub'=>'订阅销售',
			'props'=>'道具销售',
			'reward'=>'打赏销售',
		);
		if($type == '')
		{
			return $typeArr;
		}
		else
		{
			return $typeArr[$type];
		}
	}
	
	/**
	 * 插入小说信息
	 * @param 参数1，必须，条件
	 */
	function Insert( $data )
	{
		return wmsql::Insert($this->novelTable, $data);
	}
	
	/**
	 * 修改小说内容
	 * @param 参数1，必须，修改的内容
	 */
	function Update($data , $whereArr)
	{
		if( !is_array($whereArr) )
		{
			$where['novel_id'] = $whereArr;
		}
		else
		{
			$where = $whereArr;
		}

		return wmsql::Update($this->novelTable, $data, $where);
	}
	
	
	/**
	 * 检查小说名字是否存在
	 * @param 参数1，必须，小说的名字
	 * @param 参数2，选填，小说的id
	 */
	function CheckName( $name , $id = '0' )
	{
		$where['novel_name'] = $name;
		if( $id > 0 )
		{
			$where['novel_id'] = array( '<>' , $id);
		}
		return $this->GetCount($where);
	}
	

	/**
	 * 检查小说拼音是否存在
	 * @param 参数1，必须，小说的名字
	 * @param 参数2，选填，小说的id
	 */
	function CheckPinYin( $pinyin , $id = '0' )
	{
		$where['novel_pinyin'] = $pinyin;
		if( $id > 0 )
		{
			$where['novel_id'] = array( '<>' , $id);
		}
		return $this->GetCount($where);
	}
	
	/**
	 * 获得数据条数
	 * @param 参数1，必须，查询条件
	 */
	function GetCount($where)
	{
		$wheresql['table'] = $this->novelTable;
		$wheresql['where'] = $where;
		return wmsql::GetCount($wheresql);
	}

	/**
	 * 获得一条数据
	 * @param 参数1，必须，查询条件
	 */
	function GetOne($where)
	{
		$wheresql['table'] = $this->novelTable.' as n';
		$wheresql['left'][$this->typeTable.' as t'] = 'n.type_id=t.type_id';
		if( is_array($where) )
		{
			$wheresql['where'] = $where;
		}
		else
		{
			$wheresql['where']['novel_id'] = $where;
		}
		$data = wmsql::GetOne($wheresql);
		if( $data )
		{
            $clickUpdata = $this->GetIncArr( $data['novel_clicktime'] , 'click' ,0 ,false);
            if( count($clickUpdata) > 1 )
            {
                $this->Update($clickUpdata,$data['novel_id']);
                $data = array_merge($data,$clickUpdata);
            }
            $recUpdata = $this->GetIncArr( $data['novel_rectime'] , 'rec' ,0 ,false);
            if( count($recUpdata) > 1 )
            {
                $this->Update($recUpdata,$data['novel_id']);
                $data = array_merge($data,$recUpdata);
            }
            $collUpdata = $this->GetIncArr( $data['novel_colltime'] , 'coll' ,0 ,false);
            if( count($collUpdata) > 1 )
            {
                $this->Update($collUpdata,$data['novel_id']);
                $data = array_merge($data,$collUpdata);
            }
		}
		return $data;
	}
	

	/**
	 * 获得列表数据
	 * @param 参数1，必须，查询条件
	 */
	function GetList($where)
	{
		$wheresql['table'] = $this->novelTable.' as n';
		$wheresql['left'][$this->typeTable.' as t'] = 'n.type_id=t.type_id';
		if( is_array($where) )
		{
			$wheresql['where'] = $where;
		}
		else
		{
			$wheresql['where']['novel_id'] = $where;
		}
		return wmsql::GetAll($wheresql);
	}
	
	/**
	 * 获得小说文件夹
	 * @param 参数1，必须，小说分类id
	 * @param 参数2，必须，小说id
	 */
	function GetNovelFolder($tid = '', $nid = '')
	{
		$nid = str::E($nid.$this->enStr);
		$pathArr = explode('/',$this->novelSave);
		$fileName = end($pathArr);
		$path = str_replace($fileName, '', $this->novelSave);
		return tpl::Rep(array('tid'=>$tid,'nid'=>$nid) , $path);
	}
	
	
	
	/**
	 * 修改小说字数
	 * @param 参数1，必须，书籍的id
	 * @param 参数2，必填，小说总共的字数
	 * @param 参数3，必填，旧章节的字数
	 * @param 参数4，必填，新章节的字数
	 */
	function UpWordNumber( $nid , $sumNum , $oldNum , $newNum )
	{
		//如果不想等才修改字数
		if( $oldNum != $newNum )
		{
			//如果旧的字数为0，表示是新增数据
			if( $oldNum == '0' )
			{
				$wordNumber = $newNum;
			}
			else
			{
				$wordNumber = $newNum-$oldNum;
			}
			//现在的字数等于总字数+编辑后的章节字数
			$data['novel_wordnumber'] = $sumNum + $wordNumber;
			$where['novel_id'] = $nid;
			
			$this->Update($data, $where);
		}
		return true;
	}


	/**
	 * 修改小说最新章节信息
	 * @param 参数1，必填，书籍的信息数组。
	 * @param 参数2，选填，最新章节的id
	 * @param 参数3，选填，最新章节的标题
	 * @param 参数4，选填，是增加章节还是删除章节。默认为0增加，-1为清空，否则其他数字书删除。
	 */
	function UpNewChapter( $novelData , $cid = '' , $cname = '' , $type = '0')
	{
		//如果第一章数据不存在就写入第一章信息。或者编辑的是第一章
		if( ($novelData['novel_startcid'] == '0' && $cid != '' && $cname != '')
			|| ($novelData['novel_startcid'] == $cid) )
		{
			$data['novel_startcid'] = $cid;
			$data['novel_startcname'] = $cname;
		}
		
		//是增加还是删除章节
		//默认增加章节
		if( $type == '0' )
		{
			//如果新的章节大于最新的章节id才是新增，否则是编辑章节就不进行更新章节数量
			if( $cid > $novelData['novel_newcid'] )
			{
				$data['novel_chapter'] = array('+',1);
			}
			//如果单的内容大于当前小说的最新章节id才进行章节名字更新
			if( $cid >= $novelData['novel_newcid'] )
			{
				$data['novel_newcid'] = $cid;
				$data['novel_newcname'] = $cname;
			}
		}
		//默认删除章节
		else
		{
			$chapterMod = NewModel('novel.chapter');
			$chapterData = $chapterMod->GetNewChapter($novelData['novel_id']);
			//存在章节，并且不等于清空
			if( $chapterData && $type != '-1' )
			{
				$data['novel_newcid'] = $chapterData['chapter_id'];
				$data['novel_newcname'] = $chapterData['chapter_name'];
				//更新章节字数
				$wheresql['table'] = $this->chapterTable;
				$wheresql['field'] = 'chapter_number';
				$wheresql['where']['chapter_nid'] = $novelData['novel_id'];
				$data['novel_wordnumber'] = wmsql::GetSum($wheresql);
			}
			else
			{
				$data['novel_startcid'] = '0';
				$data['novel_startcname'] = '0';
				$data['novel_newcid'] = '0';
				$data['novel_newcname'] = '0';
			}
			
			$data['novel_chapter'] = array('-',$type);
			//如果是清空就设置总章节为0
			if( $type == '-1' )
			{
				$data['novel_chapter'] = '0';
				$data['novel_wordnumber'] = '0';
			}
		}
		
		//现在的字数等于总字数+编辑后的章节字数
		$data['novel_uptime'] = time();
		$where['novel_id'] = $novelData['novel_id'];
		
		return $this->Update($data , $where);
	}
	


	/**
	 * 移动小说的文件夹
	 * @param 参数1，必须，旧的分类id
	 * @param 参数2，必填，新的分类id
	 * @param 参数3，必填，小说的id
	 */
	function MoveNovelFolder($oldTid,$newTid,$nid)
	{
		//如果txt保存，并且分类改变了，就移动txt文件夹
		if( $this->novelConfig['data_type'] == '1' && $oldTid != $newTid )
		{
			$oldFolderName = $this->GetNovelFolder($oldTid,$nid);
			$newFolderName = $this->GetNovelFolder($newTid,$nid);
			file::MoveFolder($oldFolderName, $newFolderName);
		}
	}
	

	/**
	 * 获得自增的数组条件
	 * @param 参数1，必须，操作时间。
	 * @param 参数2，选填，获得条件的类型，click,coll,rec。
	 * @param 参数3，选填，增加的票数。
	 * @param 参数4，选填，是否返回需要更新的数组。
	 */
	function GetIncArr( $setTime , $field = 'click' , $number=1,$returnUpdata=true)
	{
		$nowTime = time();
		//需要返回更新的数组
		$arr['novel_'.$field.'time'] = $nowTime;
		$arr['novel_today'.$field] = $number;
		$arr['novel_week'.$field] = $number;
		$arr['novel_month'.$field] = $number;
		$arr['novel_year'.$field] = $number;
		$arr['novel_all'.$field] = $number;
		//不需要返回更新的数组
		$noUp['novel_'.$field.'time'] = $nowTime;
	
		//不是本年
		if( date('Y',$setTime) <> date('Y',$nowTime) )
		{
			$arr['novel_all'.$field] = array('+',$number);
			$noUp['novel_today'.$field] = $number;
			$noUp['novel_week'.$field] = $number;
			$noUp['novel_month'.$field] = $number;
			$noUp['novel_year'.$field] = $number;
		}
		//不是本月
		else if( date('m',$setTime) <> date('m',$nowTime) )
		{
			$arr['novel_year'.$field] = array('+',$number);
			$arr['novel_all'.$field] = array('+',$number);
			$noUp['novel_today'.$field] = $number;
			$noUp['novel_week'.$field] = $number;
			$noUp['novel_month'.$field] = $number;
		}
		//不是本周
		else if( date('W',$setTime) <> date('W',$nowTime) )
		{
			$arr['novel_month'.$field] = array('+',$number);
			$arr['novel_year'.$field] = array('+',$number);
			$arr['novel_all'.$field] = array('+',$number);
			$noUp['novel_today'.$field] = $number;
			$noUp['novel_week'.$field] = $number;
		}
		//不是本日
		else if( date('d',$setTime) <> date('d',$nowTime) )
		{
			$arr['novel_week'.$field] = array('+',$number);
			$arr['novel_month'.$field] = array('+',$number);
			$arr['novel_year'.$field] = array('+',$number);
			$arr['novel_all'.$field] = array('+',$number);
			$noUp['novel_today'.$field] = $number;
		}
		//否则全部+1
		else
		{
			$arr['novel_today'.$field] = array('+',$number);
			$arr['novel_week'.$field] = array('+',$number);
			$arr['novel_month'.$field] = array('+',$number);
			$arr['novel_year'.$field] = array('+',$number);
			$arr['novel_all'.$field] = array('+',$number);
		}
		
		if( $returnUpdata == false )
		{
			return $noUp;
		}
		return $arr;
	}

	/**
	 * 获得作者字数
	 * @param 参数1，必须，作者ID
	 */
	function GetWordNumber($aid)
	{
		$wheresql['table'] = $this->novelTable;
		$wheresql['field'] = 'novel_wordnumber';
		$wheresql['where']['author_id'] = $aid;
		return wmsql::GetSum($wheresql);
	}
}
?>