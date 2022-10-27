<?php
/**
* 自定义网页类
*
* @version        $Id: zt.label.php 2015年9月19日 13:41  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2015年12月31日 14:42
*
*/

class ztlabel extends zt
{
	function __construct()
	{
		tpl::labelBefore();
		
		self::PublicUrl();
		
		self::PublicLabel();
	}
	

	//公共url替换
	static function PublicUrl()
	{
		$arr = array(
			'全部专题'=>tpl::url('zt_type', array('tpinyin'=>'all','tid'=>'0','page'=>'1')),
		);
		tpl::Rep($arr);
	}
	
	
	/**
	 * 公共标签替换
	 **/
	static function PublicLabel()
	{
		//数组键：类名，值：方法名
		$CF['zt'] = 'GetData';

		$repFun['t']['ztlabel'] = 'PublicType';
		tpl::Label('{专题分类:[s]}[a]{/专题分类}','type', $CF, $repFun['t']);
		
		$repFun['t']['ztlabel'] = 'PublicZt';
		tpl::Label('{专题:[s]}[a]{/专题}','content', $CF, $repFun['t']);
	}

	/**
	 * 公共分类标签替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的字符串，原始标签代码。
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
			$lcode = tpl::Cur( $v['type_id'] , C('page.tid') , $lcode );
	
			$lcode = parent::GetAttr( '专题分类推荐' , $v['type_rec'] , $lcode );
				
			//显示固定字数标签
			$nameArr = tpl::Exp('{专题分类名字:[d]}' , $v['type_name'] , $lcode);
	
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'url'=>tpl::url('zt_type',array('page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'])),
				'turl'=>tpl::url('zt_type',array('page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'])),
				'专题分类id'=>$v['type_id'],
				'专题分类名字'=>$v['type_name'],
				'专题分类名字:'.GetKey($nameArr,'0')=>GetKey($nameArr,'1'),
				'专题分类简称'=>$v['type_cname'],
				'专题分类拼音'=>$v['type_pinyin'],
				'专题分类图标'=>C('type_ico',null,$v),
				'专题分类简介'=>$v['type_info'],
				'专题分类排序'=>$v['type_order'],
				'专题分类标题'=>$v['type_title'],
				'专题分类关键词'=>$v['type_key'],
				'专题分类描述'=>$v['type_desc'],
			);
			//合并两组标签
			$arr = RepField($arr2,$arr1, $v,1,'专题分类');
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
	
		//返回最后的结果
		return $code;
	}
	
	
	/**
	 * 公共专题内容标签替换
	 */
	static function PublicZt( $data , $blcode )
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
			$lcode = tpl::Cur( $v['zt_id'] , C('page.zid') , $lcode );
			
			$time = tpl::Tag('{专题发布时间:[s]}',$lcode);
			//显示固定字数标签
			$nameArr = tpl::Exp('{专题简介:[d]}' , $v['zt_info'] , $lcode);

			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'url'=>tpl::url( 'zt_zt' , array('zid'=>$v['zt_id'],'pinyin'=>$v['zt_pinyin']) ),
				'zurl'=>tpl::url( 'zt_zt' , array('zid'=>$v['zt_id'],'pinyin'=>$v['zt_pinyin']) ),
				'id'=>$v['zt_id'],
				'专题id'=>$v['zt_id'],
				'专题简介'=>$v['zt_info'],
				'专题横幅'=>$v['zt_banner'],
				'专题图片'=>$v['zt_simg'],
				'专题拼音'=>$v['zt_pinyin'],
				'专题名字'=>$v['zt_name'],
				'专题简介'=>$v['zt_info'],
				'专题评论量'=>$v['zt_replay'],
				'专题浏览量'=>$v['zt_read'],
				'专题缩略图'=>$v['zt_simg'],
				'专题简介:'.GetKey($nameArr,'0')=>GetKey($nameArr,'1'),
				'专题发布时间'=>date("Y-m-d H:i:s",$v['zt_time']),
				'专题发布时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), $v['zt_time']),
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
	
	/**
	 * 列表页标签替换
	 **/
	static function TypeLabel()
	{
		$data = C('page.data');
		$parentData = GetModuleTypeParent('zt',$data);
		$topId = $parentData['top_id'];
		$topName = $parentData['top_name'];
		$topPinYin = $parentData['top_pinyin'];	
		$arr2 = array(
			'分类id'=>$data['type_id'],
			'分类pid'=>$data['type_pid'],
			'一级分类id'=>$topId,
			'一级分类名字'=>$topName,
			'一级分类链接'=>tpl::url('zt_type',array('page'=>1,'tid'=>$topId,'tpinyin'=>$data['type_pinyin'])),
			'分类链接'=>tpl::url('zt_type',array('page'=>1,'tid'=>$data['type_id'],'tpinyin'=>$data['type_pinyin'])),
			'分类名字'=>$data['type_name'],
			'分类简称'=>$data['type_cname'],
			'分类拼音'=>$data['type_pinyin'],
			'分类简介'=>$data['type_info'],
			'分类标题'=>$data['type_title'],
			'分类关键词'=>$data['type_key'],
			'分类描述'=>$data['type_desc'],
		);
		//替换自定义字段
		$arr = RepField($arr2,null,$data);
		tpl::Rep($arr);
	
	
		self::TypeList();
	
		$CF['zt'] = 'GetData';
		$repFun['a']['ztlabel'] = 'PublicZt';
		tpl::Label('{专题列表:[s]}[a]{/专题列表}','content', $CF, $repFun['a']);
	}
	
	
	/**
	 * 列表页条件替换
	 * 给友链列表标签加上各种限制条件。
	 **/
	static function TypeList()
	{
		$pageWhere = $tidWhere =  '';
	
		if ( C('page.tid') > 0 )
		{
			$tidWhere = 'tid='.C('page.tid').';';
		}
	
		if ( C('page.page') > 0 )
		{
			$pageWhere = 'page='.C('page.page').';';
		}
	
		$ztWhere = $pageWhere.$tidWhere;
	
		tpl::Rep( array('{专题列表:'=>'{专题列表:排序=zt_id desc;'.$ztWhere) , null , '2' );
	}
	
	//专题标签
	static function ZtLabel()
	{
		$v = C('page.data');
		
		//匹配自定义时间标签
		$time = tpl::Tag('{发布时间:[s]}');
		//显示固定字数标签
		$nameArr = tpl::Exp('{名字:[d]}' , $v['zt_name'] );
		$infoArr = tpl::Exp('{简介:[d]}' , str::DelHtml($v['zt_info']) );
		
		tpl::rep(array(
			'内容链接'=>tpl::url( 'zt_zt' , array('zid'=>$v['zt_id'],'pinyin'=>$v['zt_pinyin']) ),
			'id'=>$v['zt_id'],
			'tid'=>$v['type_id'],
			'简介'=>$v['zt_info'],
			'横幅'=>$v['zt_banner'],
			'名字'=>$v['zt_name'],
			'拼音'=>$v['zt_pinyin'],
			'缩略图'=>$v['zt_simg'],
			'内容'=>$v['zt_content'],
			'简介'=>$v['zt_info'],
			'评论量'=>$v['zt_replay'],
			'浏览量'=>$v['zt_read'],
			'资源路径'=>'/files/static/'.$v['zt_id'].'/'.GetPtMark(),

			'分类id'=>$v['type_id'],
			'分类名字'=>$v['type_name'],
			'分类简称'=>$v['type_cname'],
			'分类拼音'=>$v['type_pinyin'],
			
			'名字:'.GetKey($nameArr,'0')=>GetKey($nameArr,'1'),
			'简介:'.GetKey($infoArr,'0')=>GetKey($infoArr,'1'),
			'发布时间'=>date("Y-m-d H:i:s",$v['zt_time']),
			'发布时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), $v['zt_time']),
		));
	
		//专题节点标签
		self::ZtNode();
		
		//上下篇
		self::ZtPreNext(); 
	}
	
	/**
	 * 专题内容页的节点标签替换
	 */
	static function ZtNode()
	{
		//匹配节点标签
		$nodeArr = tpl::Tag('{节点:[s]}');

		//如果存在节点标签
		if( GetKey($nodeArr,'1,0') != '' )
		{
			$where['table'] = '@zt_node';
			$where['where']['node_zt_id'] = C('page.zid');
			foreach ($nodeArr[1] as $k=>$v)
			{
				$where['where']['node_pinyin'] = $v;
				$data = wmsql::GetOne($where);
				//标签内容替换
				$content = '';
				if( $data )
				{
					if( $data['node_type'] == '1' )
					{
						$content = $data['node_img'];
					}
					else if( $data['node_type'] == '2' )
					{
						$content = $data['node_content'];
					}
					else if( $data['node_type'] == '3' )
					{
						$content = $data['node_label'];
					}
				}
				//替换的节点标签
				$repArr['节点:'.$v] = $content;
			}
			
			tpl::Rep($repArr);
		}
	}

	//上下一篇应用替换
	static function ZtPreNext(){
		$where['field'] = 'zt_id,zt_name';
		$where['table'] = '@zt_zt';
		$where['where']['zt_id'] = C('page.data.zt_id');
		
		$url = tpl::Url('zt_zt');
		
		common::PreNext( $where , 'zt_id' , C('page.data.zt_id') , $url , 'zid' , 'zt_name' );
	}
}
?>