<?php
/**
 * upload表操作类文件
 *
 * @version        $Id: upload.class.php 2016年5月15日 11:43  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class upload{
	private $table = '@upload';
	

	/**
	 * 更新最后上传的文件数据
	 * @param 参数1，必须，所属的模块
	 * @param 参数2，必须，分类
	 * @param 参数3，必须，内容的id
	 * @param 参数4，必须，图片的描述
	 */
	function UpLast($module , $type , $cid , $alt='')
	{
		$where['table'] = $this->table;
		$where['filed'] = 'upload_id';
		$where['where']['upload_module'] = $module;
		$where['where']['upload_type'] = $type;
		$data = wmsql::GetAll($where);
		if( $data )
		{
			$upData['upload_cid'] = $cid;
			$upData['upload_alt'] = $alt;
			foreach ($data as $k=>$v)
			{
				WMSql::Update($this->table, $upData, array('upload_id'=>$v['upload_id']));
			}
		}
	}
	
	
	/**
	 * 更新上传文件的数据
	 * @param 参数1，必须，图片数组
	 * @param 参数2，必须，所属的模块
	 * @param 参数3，必须，内容的id
	 */
	function UpUpload( $picArr , $module , $cid)
	{
		if( !empty($picArr) )
		{
			foreach ($picArr['src'] as $k=>$v)
			{
				if( $v != '' )
				{
					$uploadData['upload_cid'] = $cid;
					$uploadData['upload_alt'] = $picArr['alt'][$k];
					$uploadData['upload_module'] = $module;
					if( is_int($k) )
					{
						$where['upload_id'] = $k;
						$where['upload_cid'] = 0;
						wmsql::Update($this->table, $uploadData, $where);
					}
				}
			}
		}
	}
	
	
	//删除文件
	/**
	 * 删除文件
	 * @param 参数1，必须，模块
	 * @param 参数2，必须，所属的内容id
	 */
	function DelUpload($module ,$cid)
	{
		//查询应用的图片记录并且删除
		$uploadWhere['table'] = $this->table;
		$uploadWhere['where']['upload_cid'] = $cid;
		$uploadWhere['where']['upload_module'] = $module;
		$uploadData = wmsql::GetAll($uploadWhere);
		if( $uploadData )
		{
			foreach ($uploadData as $key=>$val)
			{
				//删除应用记录
				$uploadWhereSql['upload_id'] = $val['upload_id'];
				wmsql::Delete($this->table , $uploadWhereSql);
				//删除图片文件
				file::DelFile(WMROOT.$val['upload_simg']);
				file::DelFile(WMROOT.$val['upload_img']);
			}
		}
	}
}
?>