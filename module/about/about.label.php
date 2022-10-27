<?php
/**
* 关于信息类标签处理类
*
* @version        $Id: about.label.php 2015年10月09日 21:18  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class aboutlabel extends about
{
	public static $lcode;
	public static $data;

	function __construct()
	{
		tpl::labelBefore();
		
		//调用自定义标签
		self::PublicLabel();
	}


	/**
	* 关于信息标签公共标签替换
	**/
	static function PublicLabel()
	{
		//数组键：类名，值：方法名
		$CF['about'] = 'GetData';

		$repFun['t']['aboutlabel'] = 'PublicType';
		tpl::Label('{关于分类:[s]}[a]{/关于分类}','type', $CF, $repFun['t']);
		
		$repFun['a']['aboutlabel'] = 'PublicAbout';
		tpl::Label('{关于信息:[s]}[a]{/关于信息}','content', $CF, $repFun['a']);
	}

	/**
	 * 公共标签替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的标签
	 **/
	static function PublicType($data,$blcode){
		$code = '';
		$i = 1;
		//循环数据
		foreach ($data as $k => $v)
		{
			$lcode = $blcode;
			//没组数据循环，以字段名为标签名
			foreach ($v as $key => $val)
			{
				$arr1[L.$key]=$v[$key];
			}
			
			//计数器标签和选中标签替换
			$lcode = tpl::I( $lcode , $i );
			$lcode = tpl::Cur( $v['type_id'] , C('page.id') , $lcode );
			//分割每条数据的字符串
			$lcode = tpl::Segmentation(count($data), $i, $lcode);
			$lcode = tpl::Segmentation(count($data), $i, $lcode , '关于分类分隔符');
			
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'url'=>tpl::url('about_type',array('tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'])),
				'关于分类id'=>$v['type_id'],
				'关于分类名字'=>$v['type_name'],
				'关于分类简称'=>$v['type_cname'],
				'关于分类拼音'=>$v['type_pinyin'],
				'关于分类简介'=>$v['type_info'],
				'关于分类排序'=>$v['type_order'],
				'关于分类标题'=>$v['type_title'],
				'关于分类关键词'=>$v['type_key'],
				'关于分类描述'=>$v['type_desc'],
			);
			//合并两组标签
			$arr = RepField($arr2,$arr1, $v,1,'关于分类');
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
		//返回最后的结果
		return $code;
	}
	
	
	/**
	* 公共标签替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的标签
	**/
	static function PublicAbout($data,$blcode)
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
			
			$lcode = $blcode;
			//匹配自定义时间标签
			$time = tpl::Tag('{关于发布时间:[s]}',$lcode);
			
			//计数器标签和选中标签替换
			$lcode = tpl::I( $lcode , $i );
			$lcode = tpl::Cur( $v['about_id'] , C('page.aid') , $lcode );
			//分割每条数据的字符串
			$lcode = tpl::Segmentation(count($data), $i, $lcode);
			$lcode = tpl::Segmentation(count($data), $i, $lcode , '关于分隔符');
			
			//显示固定字数标签
			$contentArr = tpl::Exp('{关于内容:[d]}' , $v['about_content'] , $lcode);
			
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'url'=>tpl::url('about_about',array('aid'=>$v['about_id'],'tid'=>$v['type_id'],'apinyin'=>$v['about_pinyin'],'tpinyin'=>$v['type_pinyin'])),
				'aurl'=>tpl::url('about_about',array('aid'=>$v['about_id'],'tid'=>$v['type_id'],'apinyin'=>$v['about_pinyin'],'tpinyin'=>$v['type_pinyin'])),
				'turl'=>tpl::url('about_tindex',array('tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'])),
				'关于id'=>$v['about_id'],
				'关于名字'=>$v['about_name'],
				'关于拼音'=>$v['about_pinyin'],
				'关于内容'=>$v['about_content'],
				'关于内容:'.GetKey($contentArr,'0')=>GetKey($contentArr,'1'),
				'关于分类id'=>$v['type_id'],
				'关于分类名字'=>$v['type_name'],
				'关于分类简称'=>$v['type_cname'],
				'关于分类拼音'=>$v['type_pinyin'],
				'关于分类简介'=>$v['type_info'],
				'关于发布时间'=>date("Y-m-d H:i:s",$v['about_time']),
				'up:年'=>date("Y",$v['about_time']),
				'up:月'=>date("m",$v['about_time']),
				'up:日'=>date("d",$v['about_time']),
				'up:时'=>date("H",$v['about_time']),
				'up:分'=>date("i",$v['about_time']),
				'up:秒'=>date("s",$v['about_time']),
				'关于发布时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), $v['about_time']),
			);
			//合并两组标签
			$arr = RepField($arr2,$arr1, $v,2,'关于');
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
		//返回最后的结果
		return $code;
	}

	

	/**
	* 列表页标签替换
	**/
	static function TypeLabel()
	{
		$data = C('page.data');
		$arr2 = array(
			'分类id'=>$data['type_id'],
			'分类名字'=>$data['type_name'],
			'分类拼音'=>$data['type_pinyin'],
			'分类描述'=>$data['type_info'],
		);
		//替换自定义字段
		$arr = RepField($arr2,null,$data);
		tpl::Rep($arr);
	}

	
	/**
	* 内容页标签替换
	**/
	static function ContentLabel()
	{
		$data = C('page.data');
		
		//匹配自定义时间标签
		$time = tpl::Tag('{时间:[s]}');
		
		$arr2 = array(
			'名字'=>$data['about_name'],
			'id'=>$data['about_id'],
			'分类id'=>$data['type_id'],
			'分类名字'=>$data['type_name'],
			'拼音'=>$data['about_pinyin'],
			'内容'=>$data['about_content'],
			'标题'=>$data['about_title'],
			'关键词'=>$data['about_key'],
			'描述'=>$data['about_desc'],
			'时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), $data['about_time']),
		);
		//替换自定义字段
		$arr = RepField($arr2,null,$data,2,array('about','content'));
		tpl::Rep($arr);
	}
}
?>