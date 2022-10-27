<?php
/**
* 自定义网页类
*
* @version        $Id: diy.label.php 2015年9月19日 13:41  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2015年12月31日 10:00
*
*/

class diylabel
{
	function __construct()
	{
		tpl::labelBefore();
		
		self::PublicLabel();
	}
	

	/**
	 * 公共标签替换
	 **/
	static function PublicLabel()
	{
		//数组键：类名，值：方法名
		$CF['diy'] = 'GetData';
	
		$repFun['t']['diylabel'] = 'PublicDiy';
		tpl::Label('{单页:[s]}[a]{/单页}','content', $CF, $repFun['t']);
	}

	
	/**
	 * 公共diy内容标签替换
	 */
	static function PublicDiy( $data , $blcode )
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
			$lcode = tpl::Cur( $v['diy_id'] , C('page.did') , $lcode );
			
			//显示固定字数标签
			$nameArr = tpl::Exp('{单页名字:[d]}' , $v['diy_name'] , $lcode);
			$creatTime = tpl::Tag('{单页发布时间:[s]}',$lcode);

			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'url'=>tpl::url( 'diy_diy' , array('did'=>$v['diy_id'],'pinyin'=>$v['diy_pinyin']) ),
				'单页id'=>$v['diy_id'],
				'单页拼音'=>$v['diy_pinyin'],
				'单页名字'=>$v['diy_name'],
				'单页发布时间'=>date("Y-m-d H:i:s",$v['diy_time']),
				'单页名字:'.GetKey($nameArr,'0')=>GetKey($nameArr,'1'),
				'单页发布时间:'.GetKey($creatTime,'1,0')=>tpl::Time(GetKey($creatTime,'1,0'), $v['diy_time']),
			);
			//合并两组标签
			$arr = array_merge($arr1,$arr2);
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
		
		//返回最后的结果
		return $code;
	}
	
	
	//diy标签
	static function DiyLabel()
	{
		$v = C('page.data');
		
		//匹配自定义时间标签
		$time = tpl::Tag('{发布时间:[s]}');

		tpl::rep(array(
			'内容链接'=>tpl::url( 'diy_diy' , array('did'=>$v['diy_id'],'pinyin'=>$v['diy_pinyin']) ),
			'id'=>$v['diy_id'],
			'名字'=>$v['diy_name'],
			'拼音'=>$v['diy_pinyin'],
			'关键词'=>$v['diy_key'],
			'描述'=>$v['diy_desc'],
			'标题'=>$v['diy_title'],
			'内容'=>$v['diy_content'],
			'资源路径'=>'/files/static/'.$v['diy_id'].'/'.GetPtMark(),
			'发布时间'=>date("Y-m-d H:i:s",$v['diy_time']),
			'发布时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), $v['diy_time']),
		));
	
		/* //评分标签
		common::ScoreLabel( 'score' , C('page.data.diy_id') );

		//上下篇
		self::DiyPreNext(); */
	}

	//上下一篇应用替换
	/*static function DiyPreNext(){
		$where['field'] = 'diy_id,diy_name';
		$where['table'] = '@diy_diy';
		$where['where']['diy_id'] = C('page.data.diy_id');
		
		$url = tpl::Url('diy' );
		
		common::PreNext( $where , 'diy_id' , C('page.data.diy_id') , $url , 'did' , 'diy_name' );
	}*/
}
?>