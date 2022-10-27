<?php
/**
* 上传模型
*
* @version        $Id: upload.model.php 2016年5月28日 12:11  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class UploadModel
{
	public $table = '@upload';
	public $module;
	public $type;
	public $uploadData;
	public $data;
	public $where;
	//内容id
	public $cid = 0;
	//主内容id
	public $mid = 0;
	//用户id
	public $uid;
	//返回的缩略图
	public $simg;
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
	}
	
	
	/**
	 * 获得一条数据
	 */
	function GetOne($id=0)
	{
		$where['table'] = $this->table;
		if( $id > 0 )
		{
			$where['where']['upload_id'] = $id;
		}
		else
		{
			$where['where'] = $this->where;
		}
		$data = wmsql::GetOne($where);
		
		if( $data && $data['upload_alt'] == '' )
		{
			$data['upload_alt'] = basename($data['upload_img']);
		}
		
		return $data;
	}
	
	/**
	 * 随机一条数据
	 */
	function RandOne()
	{
		$where['table'] = $this->table;
		$where['where'] = $this->where;
		return wmsql::RandOne($where);
	}
	
	
	/**
	 * 插入上传记录
	 */
	function Insert()
	{
		$uid = 0;
		if( class_exists('user') )
		{
			$uid = user::GetUid();
		}
		$data['upload_module'] = $this->module;
		$data['upload_type'] = $this->type;
		$data['upload_mid'] = $this->mid;
		$data['upload_cid'] = $this->cid;
		$data['upload_ext'] = $this->uploadData['ext'];
		$data['upload_img'] = $this->uploadData['file'];
		$data['upload_alt'] = $this->uploadData['alt'];
		$data['user_id'] = $uid;
		$data['upload_size'] = $this->uploadData['size'];
		
		return WMSql::Insert($this->table, $data);
	}
	
	
	/**
	 * 修改附件信息
	 */
	function Save()
	{
		return wmsql::Update($this->table, $this->data, $this->where);
	}
	
	
	/**
	 * 更新上传的内容id
	 * @param 模块 $module
	 * @param 类型 $type
	 * @param 内容id $cid
	 * @return Ambigous <boolean, string>
	 */
	function UpdateCid($module , $type , $cid)
	{
		$where['upload_module'] = $module;
		$where['upload_type'] = $type;
		$where['upload_cid'] = 0;
		$data['upload_cid'] = $cid;
		return wmsql::Update($this->table, $data, $where);
	}
	
	/**
	 * 删除文件
	 * @param 需要删除的文件id $id
	 * @return Ambigous <boolean, string>
	 */
	function Delete($id)
	{
		$where['upload_id'] = $id;
		return wmsql::Delete($this->table,$where);
	}
	


	/**
	 * 2019-07-16 将未使用的上传文件绑定内容id
	 * @param 类型，添加还是编辑 $type
	 * @param 检测的内容，字符串或者数组 $content
	 * @param 缩略图地址 $simg
	 * @param $this->module,$this->type,$this->cid,$this->uid
	 * @return Ambigous <string, number>
	 * @author weimeng
	 */
	function FileBind($type,$content,$simg='')
	{
		$where['upload_cid'] = $this->cid;
		$where['upload_mid'] = $this->mid;
		$where['user_id'] = $this->uid;
		$where['upload_type'] = $this->type;
		$where['upload_module'] = $this->module;
	
		//如果传入的是数组
		if( is_array($content) )
		{
			$matches[1] = $content;
		}
		//在内容中匹配图片。
		else
		{
			preg_match_all("/\bsrc\b\s*=\s*[\'\"]?([^\'\"]*)[\'\"]?/i",$content,$matches);
			/*preg_match_all("/\bsrc\b\s*=\s*[\'\"]?([^\'\"]*)[\'\"]?.*?>/i",$content,$matches);*/
		}
		if( isset($matches[1][0]) )
		{
			$imgSer = NewClass('img');
			$imgArr = array();
			foreach($matches[1] as $k=>$v)
			{
				$imgArr[] = $v;
				$imgInfo = $imgSer->Info($v);
				//宽高大于设定值的时候才加入到缩略图数组
				if( $imgInfo === false || ($imgInfo && $imgInfo['width'] > 350 && $imgInfo['height'] > 300) )
				{
					$simgArr[] = $v;
				}
			}
				
			//如果是修改内容
			if( $type == 'edit' )
			{
				//缩略图不存在了就随机设置一张新的
				if(  empty($simg) || (!empty($simgArr) && !in_array($simg,$simgArr)) )
				{
					$this->simg = $simgArr[rand(0,count($simgArr)-1)];
				}
				//将当前内容的所有图片设置为未使用
				wmsql::Update($this->table,'upload_cid=0',$where);
			}
			//新增内容
			else
			{
				//随机一张图片
				if( !empty($simgArr) )
				{
					$this->simg = $simgArr[rand(0,count($simgArr)-1)];
				}
			}
	
			//修改图片所属的内容
			$where['upload_cid'] = 0;
			$where['upload_img'] = array('in-id',implode(',',$imgArr));
			$data['upload_cid'] = $this->cid;
			$data['upload_mid'] = $this->mid;
			wmsql::Update('@upload',$data,$where);
		}
		else
		{
			//将当前内容的所有图片设置为未使用
			wmsql::Update($this->table,'upload_cid=0',$where);
		}
		return true;
	}
	
	
	/**
	 * 2019-07-17 上传文件解绑内容id
	 * @param $this->module,$this->type,$this->cid,$this->uid
	 * @return Ambigous <string, number>
	 * @author weimeng
	 */
	function UnFileBind()
	{
		$where['upload_cid'] = array('and-or',$this->cid,array('upload_mid'=>$this->mid));
		$where['upload_module'] = $this->module;
		if( isset($this->type) )
		{
			$where['upload_type'] = $this->type;
		}
		//将当前内容的所有图片设置为未使用
		$data['upload_cid'] = 0;
		$data['upload_mid'] = 0;
		wmsql::Update($this->table,$data,$where);
		return true;
	}
}
?>