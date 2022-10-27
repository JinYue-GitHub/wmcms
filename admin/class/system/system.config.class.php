<?php
/**
 * 网站配置类文件
 *
 * @version        $Id: system.config.class.php 2016年4月22日 17:24  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class SystemConfig
{
	private $pinyinSer;
	
	function __construct()
	{
		NewClass('pinyin');
		$this->pinyinSer = new pinyin();
	}
	
	
	/**
	 * 获取能设置预设模版的模块
	 */
	function GetModule()
	{
		$arr = GetModuleName();
		
		$arr['domain'] = '域名设置';
		$arr['templates'] = '模版配置';
		$arr['email'] = '邮件配置';
		$arr['system'] = '系统配置';
		$arr['tongji'] = '统计配置';
		$arr['upload'] = '上传配置';
		$arr['logo'] = 'LOGO配置';
		$arr['cache'] = '缓存配置';
		$arr['finance'] = '财务配置';
		$arr['code'] = '验证码配置';
		$arr['site'] = '站群配置';
		$arr['urlmode'] = '路由配置';
		$arr['dev'] = '开发者配置';
		
		unset($arr['all']);
		return $arr;
	}
	
	
	/**
	 * 获取能设置预设模版的模块
	 * @param 参数1，必须，获得模块名字
	 */
	function GetModuleName( $key )
	{
		$arr = $this->GetModule();
		
		return $arr[$key];
	}	
	
	
	/**
	 * 获取所有的配置分组信息
	 */
	function GetGroup()
	{
		$where['table'] = '@config_group';
		$data = wmsql::GetAll($where);
		return $data;
	}
	
	
	/**
	 * 获得表单类型
	 * @param 参数1，选填，填了就指定返回的键值对
	 */
	function GetFromType( $key = '' )
	{
		$arr = array(
			"input"=>'文本框',
			"select"=>'下拉选项',
			"radio"=>'单选按钮',
			"check"=>'复选按钮',
			"textarea"=>'文本域',
		);
		
		if( $key != '' )
		{
			return $arr[$key];
		}
		else
		{
			return $arr;
		}
	}
	
	

	/**
	 * 获取自定义字段能够使用的模块
	 */
	function GetFieldModule()
	{
		$arr = GetModuleName();
	
		unset($arr['all']);
		unset($arr['message']);
		unset($arr['author']);
		unset($arr['user']);
		unset($arr['zt']);
		unset($arr['diy']);
		unset($arr['down']);
		unset($arr['replay']);
		unset($arr['search']);
		return $arr;
	}
	
	
	/**
	 * 获得自定义字段的数据
	 * @param 参数1，必须，请求的参数数组
	 */
	function GetFieldData($arr)
	{
		global $tableSer;
		$strSql = '';
		$arr['ft']  = str::CheckElse(GetKey($arr,'ft') , '' , '1' , GetKey($arr,'ft'));
		if( $arr['ft'] == '2' )
		{
			$arr['pid'] = $arr['tid'];
		}
		//查询指定模块的分类数据
		$typeArr = $tableSer->GetType($arr['module'] , $arr['pid']);
		if( is_array($typeArr) )
		{
			$strSql = " OR (FIND_IN_SET(field_type_id,'{$typeArr['type_pid']}') and field_type_child=1)";
		}
		
		//根据分类的信息查询出自定义字段的标题信息
		$where['table'] = '@config_field';
		$where['field'] = 'field_id,field_module,field_type,field_option';
		$where['where'] = "field_type='{$arr['ft']}' and field_module='{$arr['module']}' and (field_type_id='{$arr['tid']}'{$strSql} OR field_type_id=0)";
		$where['order'] = 'field_type_id DESC';
		$data = wmsql::GetOne($where);
		return $data;
	}
	
	
	/**
	 * 获得自定义字段的值
	 * @param 参数1，必须，参数数组
	 */
	function GetFieldValueData($arr)
	{
		$valueData = false;
		$arr['ft']  = str::CheckElse(GetKey($arr,'ft') , '' , '1' , GetKey($arr,'ft'));
			
		//如果tid不为空就是修改，那么就要查询值
		if( ($arr['ft'] == '1' && GetKey($arr,'tid') != '' && GetKey($arr,'tid') != '0') || ($arr['ft'] == '2' && GetKey($arr,'cid') != '' && GetKey($arr,'cid') != '0'))
		{
			$arr['cid'] = str::CheckElse(GetKey($arr,'cid') ,'' , '0' , GetKey($arr,'cid'));
			if( $arr['ft'] == '1' )
			{
				$arr['cid'] = $arr['tid'];
			}

			$wheresql['table'] = '@config_field_value';
			$wheresql['field'] = 'value_id,value_option';
			$wheresql['left'] = array('@config_field'=>array('INNER','field_id=value_field_id AND value_content_id='.$arr['cid']));
			$wheresql['where']['field_type'] = $arr['ft'];
			$wheresql['where']['field_module'] = $arr['module'];
			$valueData = wmsql::GetOne($wheresql);
		}
		return $valueData;
	}
	
	
	/**
	 * 获得自定义字段的内容
	 * @param 参数1，必须，请求的参数数组
	 */
	function GetFieldOption($arr)
	{
		$data = $this->GetFieldData($arr);
		
		if( $data )
		{
			$newData = unserialize($data['field_option']);
			$valueData = $this->GetFieldValueData($arr);
			if( GetKey($valueData,'value_option') != '' )
			{
				$valueData['value_option'] = unserialize($valueData['value_option']);
				foreach ($newData as $k=>$v)
				{
					$newData[$k]['value'] = GetKey($valueData,'value_option,'.$k);
				}
			}
			return $newData;
		}
	}
	
	/**
	 * 写入自定义字段的内容
	 * @param 参数1，必须，模块
	 * @param 参数2，必须，数据值
	 * @param 参数3，必须，分类id
	 * @param 参数4，选填，插入类型
	 * @param 参数5，选填，插入类型为2的时候cid必须大于0
	 */
	function SetFieldOption($arr)
	{
		$option = GetKey($arr,'option');
		if( $option )
		{
			$tid = str::CheckElse(GetKey($arr,'tid') ,'' , '0' , GetKey($arr,'tid'));
			if( GetKey($arr,'ft') == '2' )
			{
				$tid = $arr['cid'];
			}
			//把数据的键都删除
			$option = serialize(array_values($option));
			$data = $this->GetFieldData($arr);
			$valueData = $this->GetFieldValueData($arr);

			//存在数据，并且值为空
			if( $data && !$valueData )
			{
				$valueArr['value_field_id'] = $data['field_id'];
				$valueArr['value_field_module'] = $data['field_module'];
				$valueArr['value_field_type'] = $data['field_type'];
				$valueArr['value_content_id'] = $tid;
				$valueArr['field_option'] = $data['field_option'];
				$valueArr['value_option'] = $option;
				wmsql::Insert('@config_field_value', $valueArr);
			}
			//存在数据，并且值不为空
			else if( $data && $valueData )
			{
				wmsql::Update('@config_field_value', array('value_option'=>$option), array('value_id'=>$valueData['value_id']));
			}
		}
		return true;
	}
	
	
	/**
	 * 删除自定义字段的值
	 * @param 参数1，必须，删除的分类id
	 */
	function DelField($where)
	{
		$wheresql['table'] = '@config_field';
		$wheresql['where']['field_module'] = Session('cur_module');
		$wheresql['where']['field_type_id'] = $where;
		$data = wmsql::GetAll($wheresql);

		if( $data )
		{
			foreach ($data as $k=>$v)
			{
				//删除标题
				wmsql::Delete('@config_field', array('field_id'=>$v['field_id']));
				//删除值
				wmsql::Delete('@config_field_value', array('value_filed_id'=>$v['field_id']));
			}
		}
	}


	/**
	 * 获取自定义表单内容
	 * @param 参数1，必须，配置项
	 */
	function GetForm( $option )
	{
		$formHtml = $selected ='';

		//如果有值就设置默认值
		if( GetKey($option,'value') != '' )
		{
			$option['default'] = $option['value'];
		}

		$valArr = explode(',',$option['option']);
		$name = $this->pinyinSer->topy($option['title']);

		switch ( $option['formtype'] )
		{
			//下拉列表
			case 'select':
				foreach ($valArr as $k=>$v)
				{
					$selected = '';
					if ( $option['default'] == $v )
					{
						$selected = 'selected=""';
					}
					$formHtml .= '<option value="'.$v.'" '.$selected.'>'.$v.'</option>';
				}
				$formHtml = '<select name="field['.$name.']" data-toggle="selectpicker">'.$formHtml.'</select>';
				break;

			//文本域
			case 'textarea':
				$formHtml = '<textarea name="field['.$name.']">'.$option['default'].'</textarea>';
				break;

			//单选按钮
			case 'radio':
				foreach ($valArr as $k=>$v)
				{
					$selected = '';
					if ( $option['default'] == $v )
					{
						$selected = 'checked="1"';
					}
					$formHtml .= '<input name="field['.$name.']" type="radio" data-toggle="icheck" data-label="'.$v.'" value="'.$v.'" '.$selected.' />&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				break;

			//多选按钮
			case 'check':
				foreach ($valArr as $k=>$v)
				{
					$selected = '';
					if ( in_array($v, $option['default']) )
					{
						$selected = 'checked="1"';
					}
					$formHtml .= '<input name="field['.$name.'][]" type="checkbox" data-toggle="icheck" data-label="'.$v.'" value="'.$v.'" '.$selected.' />&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				break;

			default:
				$formHtml = '<input type="text" name="field['.$name.']" class="form-control" value="'.htmlspecialchars($option['default']).'">';
				break;
		}
	
		return $formHtml;
	}
	
}
?>