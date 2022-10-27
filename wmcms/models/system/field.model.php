<?php
/**
* 自定义字段模型
*
* @version        $Id: field.model.php 2017年2月5日 19:35  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class FieldModel
{
	public $fieldTable = '@config_field';
	public $valueTable = '@config_field_value';
	private $pinyinSer;
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
		$this->pinyinSer = NewClass('pinyin');
	}
	

	/**
	 * 获得内容的自定义字段
	 * @param 参数1，必须，字段的模块
	 * @param 参数2，必须，分类id
	 * @param 参数3，必须，内容id，可以为0
	 */
	function GetFiled($module , $tid , $cid = 0)
	{
		global $tableSer;
		
		//是否是子分类
		$typeWhere['table']= $tableSer->tableArr[$module.'type']['table'];
		$typeWhere['where'][$tableSer->tableArr[$module.'type']['id']] = array('string',
			'( FIND_IN_SET('.$tid.',`'.$tableSer->tableArr[$module.'type']['pid'].'`) ) OR `'.$tableSer->tableArr[$module.'type']['id'].'`='.$tid
		);
		$typeIdData= wmsql::GetAll($typeWhere);
		$typeIds = '';
		if( $typeIdData )
		{
			foreach ($typeIdData as $k=>$v)
			{
				if( $v['type_id'] == $tid )
				{
					$pids = explode(',', $v[$tableSer->tableArr[$module.'type']['pid']] );
					foreach ($pids as $key=>$val)
					{
						$typeIds .= 'field_type_id='.$val.' or ';
					}
				}
				$typeIds .= 'field_type_id='.$v[$tableSer->tableArr[$module.'type']['id']].' or ';
			}
		}
		$strWhere = '( (field_type_child=1 and ('.$typeIds.'field_type_id=0)) or ( field_type_child =0 and field_type_id='.$tid.') )';
		
		$fieldData['type_field'] = array();
		$fieldData['content_field'] = array();
		$fieldData['type_field']['data'] = array();
		$fieldData['content_field']['data'] = array();
		
		//查询分类的自定义字段
		$where['table'] = $this->fieldTable.' as ft';
		$where['field'] = 'fv.value_id,ft.field_id,ft.field_module,ft.field_type,ft.field_option,fv.value_option';
		$where['left']['@config_field_value as fv'] = 'fv.value_field_id=field_id and fv.value_content_id = '.$tid;
		$where['where']['field_module'] = $module;
		$where['where']['field_type'] = 1;
		$where['where']['field_type_id'] = array('string',$strWhere);
		$where['order'] = 'field_type_child asc,field_type_id desc';
		$typeData = wmsql::GetOne($where);
		if( $typeData )
		{
			$fieldData['type_field']['field_id'] = $typeData['field_id'];
			$fieldData['type_field']['field_module'] = $typeData['field_module'];
			$fieldData['type_field']['field_type'] = $typeData['field_type'];
			$fieldData['type_field']['field_option'] = $typeData['field_option'];
			$fieldData['type_field']['value_id'] = $typeData['value_id'];
			
			if( $typeData['field_option'] != '' )
			{
				$typeData['field_option'] = unserialize($typeData['field_option']);
			}
			if( $typeData['value_option'] != '' )
			{
				$typeData['value_option'] = unserialize($typeData['value_option']);
			}
			//循环替换分类和内容的自定义字段
			foreach ($typeData['field_option'] as $k=>$v)
			{
				$fieldData['type_field']['data'][$k] = $v;
				$fieldData['type_field']['data'][$k]['value'] = GetKey($typeData,'value_option,'.$k);
				$fieldData['type_field']['data'][$k]['name'] = $this->pinyinSer->topy($v['title']);
				
				$formType = $fieldData['type_field']['data'][$k]['formtype'];
				if( $v['formtype'] == 'check' || $v['formtype'] == 'radio' || $v['formtype'] == 'select' )
				{
					$fieldData['type_field']['data'][$k]['option'] = explode(',', $fieldData['type_field']['data'][$k]['option']);
				}
			}
		}

		//查询内容的自定义字段的值
		$where['where']['field_type'] = 2;
		$where['left']['@config_field_value as fv'] = 'fv.value_field_id=field_id and fv.value_content_id = '.$cid;
		$contentData = wmsql::GetOne($where);
		if( $contentData )
		{
			$fieldData['content_field']['field_id'] = $contentData['field_id'];
			$fieldData['content_field']['field_module'] = $contentData['field_module'];
			$fieldData['content_field']['field_type'] = $contentData['field_type'];
			$fieldData['content_field']['field_option'] = $contentData['field_option'];
			$fieldData['content_field']['value_option'] = $contentData['value_option'];
			$fieldData['content_field']['value_id'] = $contentData['value_id'];
			
			if( $contentData['field_option'] != '' )
			{
				$contentData['field_option'] = unserialize($contentData['field_option']);
			}
			if( $contentData['value_option'] != '' )
			{
				$contentData['value_option'] = unserialize($contentData['value_option']);
			}
			
			//循环替换分类和内容的自定义字段
			foreach ($contentData['field_option'] as $k=>$v)
			{
				$fieldData['content_field']['data'][$k] = $v;
				$fieldData['content_field']['data'][$k]['value'] = GetKey($contentData,'value_option,'.$k);
				$fieldData['content_field']['data'][$k]['name'] = $this->pinyinSer->topy($v['title']);

				if( $v['formtype'] == 'check' || $v['formtype'] == 'radio' || $v['formtype'] == 'select' )
				{
					$fieldData['content_field']['data'][$k]['option'] = explode(',', $fieldData['content_field']['data'][$k]['option']);
				}
			}
		}
		return $fieldData;
	}
	
	
	/**
	 * 写入自定义字段的内容
	 * @param 参数1，必须，模块
	 * @param 参数2，必须，数据值
	 * @param 参数3，必须，分类id
	 * @param 参数4，选填，插入类型
	 * @param 参数5，选填，插入类型为2的时候cid必须大于0
	 */
	function SetFieldOption($module , $tid , $cid = '' , $option='' , $type = 2)
	{
		if( $option == '' )
		{
			return false;
		}
		else
		{
			$option = serialize(array_values($option));
			$data = $this->GetFiled($module, $tid , $cid);
			$data = GetKey($data,'content_field');
			//存在键就修改数据
			if( $data['value_id'] != '' )
			{
				wmsql::Update($this->valueTable, array('value_option'=>$option), array('value_id'=>$data['value_id']));
			}
			//不存在就插入数据
			else
			{
				$valueArr['value_field_id'] = $data['field_id'];
				$valueArr['value_field_module'] = $data['field_module'];
				$valueArr['value_field_type'] = $data['field_type'];
				$valueArr['value_content_id'] = $cid;
				$valueArr['field_option'] = $data['field_option'];
				$valueArr['value_option'] = $option;
				wmsql::Insert($this->valueTable, $valueArr);
			}
		}
		return true;
	}
}
?>