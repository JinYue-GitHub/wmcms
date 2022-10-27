<?php
/**
 * 预设模版类类文件
 *
 * @version        $Id: system.templates.class.php 2016年4月8日 13:49  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class SystemTemplates
{
	public $table = '@system_templates';
	
	/**
	 * 管理员账号检测
	 * @param 参数1，选填，如果指定了模块
	 * @param 参数1，选填，如果指定了类型
	 */
	function GetTempType( $module = '' , $val='')
	{
		$tempType['article'] = array('tindex'=>'分类首页','list'=>'列表页','content'=>'内容页');
		$tempType['novel'] = array('list'=>'列表页','content'=>'信息页','meun'=>'目录页','read'=>'阅读页');
		$tempType['picture'] = array('tindex'=>'分类首页','list'=>'列表页','content'=>'内容页');
		$tempType['bbs'] = array('list'=>'列表页','content'=>'内容页','replay'=>'回帖页');
		$tempType['link'] = array('list'=>'列表页','content'=>'内容页');
		$tempType['app'] = array('list'=>'列表页','content'=>'内容页');
		$tempType['about'] = array('list'=>'列表页','content'=>'内容页');
		$tempType['zt'] = array('list'=>'列表页','content'=>'内容页');
		$tempType['diy'] = array('content'=>'内容页');
		
		if ( $val != '' && $module != '')
		{
			$tempType = GetKey($tempType,$module.','.$val);
		}
		else if( $module != '' )
		{
			$tempType = GetKey($tempType,$module);
		}
		return $tempType;
	}
	
	
	/**
	 * 获取能设置预设模版的模块
	 * @param 参数1，必须，所有模块。
	 */
	function GetModuleName( $arr )
	{
		unset($arr['all']);
		unset($arr['message']);
		unset($arr['user']);
		unset($arr['author']);
		unset($arr['down']);
		
		return $arr;
	}
	
	
	/**
	 * 根据条件查询模版的数据
	 * @param 参数1，选填，传入的条件
	 */
	function GetTempList( $where = array() )
	{
		//获取列表条件
		$where['table'] = $this->table;
		
		//数据条数
		$data['total'] = wmsql::GetCount($where);
		
		//当前页的数据
		$where = GetListWhere($where);
		$data['data'] = wmsql::GetAll($where);
		
		return $data;
	}
	
	
	/**
	 * 查询模版信息
	 * @param 参数1，必须，模板的id
	 */
	function GetTemp( $id , $field = 'temp_temp4' )
	{
		$data = '';
		if ( $id > 0 && $id != '' )
		{
			$where['table'] = $this->table;
			$where['where']['temp_id'] = $id;
			$data = wmsql::GetOne($where);
			if( $field != '' && $data )
			{
				if( $field != 'temp_temp1' && $field != 'temp_temp2'  && $field != 'temp_temp3' && $field != 'temp_temp4' )
				{
					$data = $data[$field];
				}
				else
				{
					if( $data['temp_address'] != '0' )
					{
						$data = WMROOT.$data[$field];
					}
					else
					{
						$data = WMTEMPLATE.C('config.web.tp'.Request('id')).'/'.$data[$field];
					}
				}
			}
		}
		
		return $data;
	}
	
	
	/**
	 * 获得模版的类型
	 * @param 参数1，必须，模板的id
	 */
	function GetTempAddress()
	{
		$arr = array(
			'0'=>'主题路径',
			'1'=>'上传路径',
		);
		
		return $arr;
	}
}
?>