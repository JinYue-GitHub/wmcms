<?php
/**
* 分类检索标签处理类
*
* @version        $Id: retrieval.label.php 2017年6月18日 16:22  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class retrievalLabel extends retrieval
{
	static public $lcode;
	static public $data;
	//当前url和url参数
	static public $url;
	static public $urlPar;
	//完整url和url参数
	static public $pageType;
	static public $pageUrl;
	static public $pageUrlPar;
	static public $CF = array('retrieval'=>'GetData');
	
	function __construct()
	{
		//当前页面的url
		self::$url = str_replace('{page}', C('page.page'), C('page.list_url'));
		//当前参数
		self::$urlPar = UrlFormat();
		
		//当前页面的名字
		self::$pageType = C('page.pagetype').'_retrieval';
		//完整url
		self::$pageUrl = tpl::url(self::$pageType,null,'1');
		//完整url参数
		self::$pageUrlPar = UrlFormat(self::$pageUrl);
		self::PublicLabel();
	}
	
	/**
	* 关于信息标签公共标签替换
	**/
	static function PublicLabel()
	{
		$repFun['a']['retrievalLabel'] = 'PublicType';
		tpl::Label('{检索分类:[s]}[a]{/检索分类}','type', self::$CF, $repFun['a']);
		
		$repFun['a']['retrievalLabel'] = 'PublicRetrieval';
		tpl::Label('{检索条件:[s]}[a]{/检索条件}','content', self::$CF, $repFun['a']);
	}


	/**
	* 公共分类标签替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的标签
	**/
	static function PublicType($data,$blcode)
	{
		$code = '';
		$i = 1;

		//循环数据
		foreach ($data as $k => $v)
		{
			//没组数据循环，以字段名为标签名
			foreach ($v as $key => $val)
			{
				$arr1[L.$key]=$v[$key];
			}
			//每次循环重新调取原始标签
			$lcode = $blcode;
			//计数器标签和选中标签替换
			$lcode = tpl::I( $lcode , $i );
			
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'检索分类id'=>$v['type_id'],
				'检索分类参数'=>$v['type_par'],
				'检索分类名字'=>$v['type_name'],
			);

			//合并两组标签
			$arr = array_merge($arr1 , $arr2);
			//替换标签
			$code .= tpl::rep($arr,$lcode);
			
			$i++;
		}
		//返回最后的结果
		return $code;
	}
	

	/**
	 * 公共检索条件标签替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的标签
	 **/
	static function PublicRetrieval($data,$blcode)
	{
		$nowPar = $isCur = $code = '';
		$i = 1;
	
		//循环数据
		foreach ($data as $k => $v)
		{
			//没组数据循环，以字段名为标签名
			foreach ($v as $key => $val)
			{
				$arr1[L.$key]=$v[$key];
			}
			//每次循环重新调取原始标签
			$lcode = $blcode;
			//计数器标签和选中标签替换
			$lcode = tpl::I( $lcode , $i );
			$nowPar = Get($v['type_par'],'0');
			$isCur = $v['retrieval_id'];
			if( $v['retrieval_value'] == '-1' )
			{
				$isCur = '0';
			}
			$lcode = tpl::Cur( $nowPar , $isCur , $lcode );
				
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'url'=>self::Url($v),
				'条件分类id'=>$v['type_id'],
				'条件分类参数'=>$v['type_par'],
				'条件分类名字'=>$v['type_name'],
				'检索条件id'=>$v['retrieval_id'],
				'检索条件id值'=>$isCur,
				'检索条件名字'=>$v['retrieval_title'],
			);
	
			//合并两组标签
			$arr = array_merge($arr1 , $arr2);
			//替换标签
			$code .= tpl::rep($arr,$lcode);
				
			$i++;
		}
		//返回最后的结果
		return $code;
	}
	
	
	/**
	 * 获得筛选条件的url
	 * @param 参数1，必须，替换url的数据
	 */
	static function Url($v)
	{
		$urlArr = self::$pageUrlPar;
		foreach ($urlArr as $key=>$val)
		{
			$value = isset(self::$urlPar[$key])?self::$urlPar[$key]:'';
			if( $v['type_par'] == $key )
			{
				if( $v['retrieval_value'] == '-1' )
				{
					$value = '0';
				}
				else
				{
					$value = $v['retrieval_id'];
				}
			}
			else if( $value == '' )
			{
				$value = '0';
			}
			$urlArr[$key] = $value;
		}
		return tpl::Url(self::$pageType,$urlArr);
	}
}
?>