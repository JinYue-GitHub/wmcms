<?php
/**
* 图集排行页
*
* @version        $Id: toplist.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月5日 11:29 weimeng
*
*/
$ClassArr = array('page');
//引入模块公共文件
require_once 'picture.common.php';


//当前页面的参数检测
$tid = str::IsEmpty( Get('tid') , $lang['picture']['par']['tid_no'] );
$page = str::Page( Get('page') );

//参数验证
$where = picture::GetPar( 'type' , $tid);

//获得页面的标题等信息
$data = picture::GetData( 'type' , $where , $lang['system']['type']['no'] );

C('page' ,  array(
	'pagetype'=>'picture_toplist' ,
	'data'=>$data[0] ,
	'tempid'=>'tempid' ,
	'dtemp'=>'picture/toplist.html',
	'label'=>'picturelabel',
	'label_fun'=>'ToplistLabel',
	'tid'=>$tid,
	'page'=>$page,
	'listurl'=>tpl::url('picture_toplist',array('tid'=>$tid,'tpinyin'=>$data[0]['type_pinyin'],)),
));

//设置seo信息
tpl::GetSeo();

//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>

<?php
/**


preg_match_all('/{分类排行:(\d*):(\d*)}([\s\S]*?){\/分类排行}/', $templates, $lable);
$count=count($lable[0]);	//该标签共有多少个
////循环替换掉该标签
for($i=1;$i<=$count;$i++){
	$ltid=$lable[1][$i-1];	//分类的id
	$isthis=$lable[2][$i-1];	//是否只读取当前的id分类
	$lcode=$lable[3][$i-1];	//标签里面的代码
	
	//如果只读取当前分类
	if($isthis==1 && $ltid<>'0'){
		$rs=$wmsql->getall("`@picture_type`","`id`=".$ltid." order by `order`");
	}else{
		$rs=$wmsql->getall("`@picture_type`","`topid`='".$ltid."' order by `order`");
	}

	if(!$rs){
		$templates=str_replace('{分类排行:'.$ltid.':'.$isthis.'}'.$lcode.'{/分类排行}','暂无图集分类',$templates);
	}else{
		$j=1;
		$rowcount=count($row);
		foreach ($rs as $row) {
			$classtop=str_replace('{分类名}',$row['name'],$lcode);
			
			preg_match_all('/{分类名:(\d*)}/', $classtop, $ilable);
			$icount=count($ilable[0]);	//该标签共有多少个
			for($k=1;$k<=$icount;$k++){
				$jname=mb_substr($row['name'], 0, $ilable[1][$k-1],'utf-8');
				$classtop=str_replace('{分类名:'.$ilable[1][$k-1].'}',$jname,$classtop);
			}
			$classtop=str_replace('{url}',pictureurl('',$row['id'],'','1','','','picture_toplist_url'),$classtop);
			$classtop=str_replace('{tid}',$row['id'],$classtop);
			$classtop=str_replace('{分类图标}',$row['simg'],$classtop);
			$classtops.=$classtop;
		}
		$templates=str_replace('{分类排行:'.$ltid.':'.$isthis.'}'.$lcode.'{/分类排行}',$classtops,$templates);
	}
	$classtops='';
}
*/
?>