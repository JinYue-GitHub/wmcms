<?php
/**
* 下载模型
*
* @version        $Id: down.model.php 2017年4月28日 10:11  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class DownModel
{
	public $table = '@upload';
	private $uploadMod;
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
		$this->uploadMod = NewModel('upload.upload');
	}
	
	
	/**
	 * 获得下载内容的信息
	 * @param 参数1，选填，所属的模块
	 * @param 参数2，选填，模块内容id
	 * @param 参数3，选填，下载的文件id
	 * @param 参数4，选填，是否生成文件
	 */
	function GetDownInfo( $module = '' , $cid = 0 , $fid = 0 , $isCreate = '0' )
	{
		$info = array();
		if( $fid > 0 )
		{
			$uploadMod = NewModel('upload.upload');
			$data = $uploadMod->GetOne($fid);
			$info['name'] = $data['upload_alt'];
			$info['file'] = WMROOT.$data['upload_img'];
		}
		else
		{
			global $tableSer;
			$data = $tableSer->GetData($module,$cid);
			switch ($module)
			{
				//小说模块
				case 'novel':
					$chapterMod = NewModel('novel.chapter');
					$info['name'] = $data['novel_name'];
					if( $data && $data['novel_path'] != '' && file_exists(WMROOT.$data['novel_path']) )
					{
						$info['file'] = WMROOT.$data['novel_path'];
					}
					else
					{
						$info['file'] = $chapterMod->GetNovelFileName($data['type_id'],$data['novel_id']);
					}
					//小说是已经开始写作了的。并且是生成txt
					if( $isCreate == '1' && $data['novel_chapter'] > 0 && $chapterMod->GetConfig('data_type') == '1')
					{
						$chapterMod->UpdateNovel($info['file'],$data);
					}
					break;
			}
		}

		if( $data  )
		{
			//判断是否是本地文件
			$info['is_local'] = true;
			//如果是url就设置不是本地文件
			if ( str::CheckUrl( $info['file']) )
			{
				$info['is_local'] = false;
			}
			$info['file_name'] = basename($info['file']);
			list($name,$info['ext']) = explode('.', $info['file_name']);
			return $info;
		}
		else
		{
			return false;
		}
	}
	

	/**
	 * 加密下载参数
	 * @param 参数1，选填，所属的模块
	 * @param 参数2，选填，模块内容id
	 * @param 参数3，选填，下载的文件id
	 */
	function E($module='', $cid='' , $fid='')
	{
		$time = time();
		return urlencode(str::Encrypt( $module.'|||'.$cid.'|||'.$fid.'|||'.$time , 'E' , C('config.api.system.api_apikey')));
	}
	/**
	 * 解密下载参数
	 * @param 参数1，必须，需要解密的字符串
	 */
	function D($str)
	{
		$data = array();
		$str = str::Encrypt( $str , 'D' , C('config.api.system.api_apikey'));
		$strArr = explode('|||', $str);
		$data['module'] = GetKey($strArr,'0');
		$data['cid'] = GetKey($strArr,'1');
		$data['fid'] = GetKey($strArr,'2');
		$data['time'] = GetKey($strArr,'3');
		return $data;
	}
	
	
	/**
	 * 替换内容里面的file标签
	 * @param 参数1，必须，内容模块
	 * @param 参数2，必须，内容分类
	 * @param 参数3，必须，内容id
	 * @param 参数4，必须，需要替换标签的内容字符串
	 * @param 参数5，必须，内容作者id
	 */
	function RepContent($module='',$type='',$content='' , $cid='', $uid='')
	{
		//检查是否存在附件
		$fileArr = tpl::Tag('[file:[s]][a][/file]' ,$content);
		//存在附件
		if( tpl::$labelCount > 0 )
		{
			//设置条件
			$this->uploadMod->where['upload_cid'] = $cid;
			//上传文件模块
			if( $module != '' )
			{
				$this->uploadMod->where['upload_module'] = $module;
			}
			//上传文件方式
			if( $type != '' )
			{
				$this->uploadMod->where['upload_type'] = $type;
			}
			//是否存在用户id
			if( $uid != '' )
			{
				$this->uploadMod->where['user_id'] = $uid;
			}
	
			foreach($fileArr[0] as  $k=>$v)
			{
				$this->uploadMod->where['upload_id'] = $fileArr[1][$k];
				$uploadData = $this->uploadMod->GetOne();
				if( $uploadData )
				{
					//如果是图片
					if( str::IsImg($uploadData['upload_ext']) )
					{
						$repContent = '<img src="'.$uploadData['upload_img'].'" alt="'.$uploadData['upload_alt'].'"/>';
					}
					else
					{
						//如果是ue编辑器，那么模块就重写为type；
						if( $module == 'editor' )
						{
							$module = $type;
						}
						$url = tpl::url('down_down',array('module'=>$module,'fid'=>$fileArr[1][$k],'cid'=>'0'));
						if( $module == 'bbs' )
						{
							$repContent = '<a target="_blank" class="wmcms_download" href="'.$url.'" alt="'.$uploadData['upload_alt'].'"/>'.$uploadData['upload_alt'].'</a>';
						}
						else
						{
							$repContent = $url;
						}
					}
					
					$content = tpl::Rep(array('[file:'.$fileArr[1][$k].'][a][/file]'=>$repContent) , $content , 3);
				}
			}
		}
		return $content;
	}
}
?>