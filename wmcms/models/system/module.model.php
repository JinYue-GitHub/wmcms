<?php
/**
* 模块操作模型
*
* @version        $Id: module.model.php 2017年5月27日 11:35  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class ModuleModel
{
	private $menuTable = '@system_menu';
	function __construct( $data = '' )
	{
	}
	
	
	/**
	 * 安装模块
	 * @param 参数1，选填，是否指定获得模块的id
	 */
	function Install($module='')
	{
		if( $module )
		{
			$installModule = $unInstallModule = array();
			//所有模块
			$moduleList = file::FloderList(WMMODULE);
			//安装和卸载的模块
			foreach ($moduleList as $k=>$v)
			{
				if( in_array($v['file'], $module))
				{
					$installModule[] = $v['file'];
					//显示菜单
					$this->ShowHideMenu($v['file'],1);
				}
				else
				{
					$unInstallModule[] = $v['file'];
					//隐藏菜单
					$this->ShowHideMenu($v['file'],0);
				}
			}
			//如果是全部模块
			if( empty($unInstallModule) )
			{
				$installModule = array();
				$installModule[] = 'all';
			}
			
			////修改define不安装使用的module
			$defineContent=file_get_contents(WMCONFIG."define.config.php");
			$defineContent=preg_replace("/define\('NOTMODULE','(.*?)'\);/", "define('NOTMODULE','".@implode(',', $unInstallModule)."');", $defineContent);
			file_put_contents(WMCONFIG."define.config.php",$defineContent);
			
			//设置index安装使用的模块
			$indexContent=file_get_contents(WMROOT."index.php");
			$indexContent=preg_replace("/C\['module'\]\['inc'\]\['module'\]=array\('(.*?)'\);/", "C['module']['inc']['module']=array('".@implode("','", $installModule)."');", $indexContent);
			file_put_contents(WMROOT."index.php",$indexContent);

			//设置404安装使用的模块
			$notfoundContent=file_get_contents(WMROOT."404.php");
			$notfoundContent=preg_replace("/C\['module'\]\['inc'\]\['module'\]=array\('(.*?)'\);/", "C['module']['inc']['module']=array('".@implode("','", $installModule)."');", $notfoundContent);
			file_put_contents(WMROOT."404.php",$notfoundContent);
		}
	}
	
	
	/**
	 * 获得模块的id
	 * @param 参数1，选填，是否指定获得模块的id
	 */
	function GetModuleName( $name = '')
	{
		$arr = array(
			'novel'=>'小说模块',
			'app'=>'应用模块',
			'article'=>'文章模块',
			'picture'=>'图集模块',
			'message'=>'留言模块',
			'bbs'=>'论坛模块',
			'link'=>'友链模块',
			'author'=>'原创模块',
			'user'=>'用户模块',
			'zt'=>'专题模块',
			'about'=>'信息模块',
			'editor'=>'编辑模块',
			'diy'=>'单页模块',
			'down'=>'下载模块',
			'replay'=>'评论模块',
			'search'=>'搜索模块',
		);
		
		if( $name == '' )
		{
			return $arr;
		}
		else
		{
			//判断是否存在删除模块
			$nameArr = explode('.', $name);
			if( count($nameArr) > 1 )
			{
				foreach ($nameArr as $k=>$v)
				{
					unset($arr[$v]);
				}
				return $arr;
			}
			else
			{
				if( isset($arr[$name]) )
				{
					return $arr[$name];
				}
				else
				{
					return $name;
				}
			}
		}
	}
	
	/**
	 * 获得模块的id
	 * @param 参数1，选填，是否指定获得模块的id
	 */
	function GetModuleId($module='')
	{
		$moduleArr = array(
			'novel'=>array('id'=>'74'),
			'user'=>array('id'=>'107'),
			'replay'=>array('id'=>'131'),
			'search'=>array('id'=>'136'),
			'author'=>array('id'=>'92'),
			'article'=>array('id'=>'26'),
			'link'=>array('id'=>'119'),
			'picture'=>array('id'=>'43'),
			'diy'=>array('id'=>'152'),
			'zt'=>array('id'=>'149'),
			'message'=>array('id'=>'144'),
			'app'=>array('id'=>'61'),
			'about'=>array('id'=>'36'),
			'editor'=>array('id'=>'891'),
			'bbs'=>array('id'=>'52'),
			'down'=>array('id'=>'0'),
		);
		
		if($module != '' )
		{
			return $moduleArr[$module];
		}
		else
		{
			return $module;
		}
	}
	
		
	
	/**
	 * 显示隐藏模块的目录
	 * @param 参数1，必填，需要隐藏显示的模块
	 * @param 参数2，选填，是否显示
	 */
	function ShowHideMenu($module,$show=1)
	{
		$moduleArr = $this->GetModuleId($module);
		$where['menu_id'] = $moduleArr['id'];
		$data['menu_status'] = $show;
		return wmsql::update($this->menuTable , $data , $where);
	}
}
?>