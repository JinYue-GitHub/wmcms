<?php
/**
* 后台公共函数库
*
* @version        $Id: function.php 2016年3月25日 14:52	weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2016年4月2日	weimeng
*
*/

/**
 * 管理员请求记录
 */
function RequestLog()
{
	$c = Get('c');
	
	//开启了统计请求
	if ( Session('admin_id') != '' && C('config.web.request_open') == '1' && $c != 'system.safe.request' && $c != 'system.safe.request.detail')
	{
		//检查请求方式
		if( IsAjax() )
		{
			$type = 'AJAX';
		}
		if ( IsGet() )
		{
			$type = 'GET';
		}
		if ( IsPost() )
		{
			$type = 'POST';
		}
		
		//插入记录
	 	$data['request_manager_id'] = Session('admin_id');
	 	$data['request_file'] = $c;
	 	$data['request_type'] = $type;
	 	$data['request_ip'] = GetIp();
	 	$data['request_time'] = time();
	 	$data['request_get'] = str::Escape(serialize(Get()) , 'e');
	 	$data['request_post'] = str::Escape(serialize(Post()) , 'e');

	 	wmsql::Insert('@manager_request', $data);
	}
}



/**
 * 检查管理员登录情况
 * @param 参数1，选填，控制器名字
 */
function CheckLogin($c='')
{
	$cPath = '';
	//否则就执行跳转
	if( Get('name') != '' && Get('psw') != '' )
	{
		//创建code和token
		FormCodeCreate('code_admin_login');
		$_POST['form_token'] = FormTokenCreate(true);
		$_POST['code'] = Session('code_admin_login');
		$_POST['name'] = Get('name');
		$_POST['psw'] = Get('psw');
		$_POST['isAjax'] = '2';
		if( Get('isAjax') != '' )
		{
			$_POST['isAjax'] = Get('isAjax');
		}
		require_once 'action/login.php';
		$cArr = explode('.' , $c);
		//如果控制器的路径大于1
		if( count($cArr) > 1 )
		{
			$cPath = $cArr[0].'/'.$c;
		}
		else
		{
			$cPath = $c;
		}
		return $cPath;
	}
	//有session就检查登录信息
	else if ( Session('admin_account') )
	{
		//解析账号信息
		$arr = str::A( Session('admin_account') );
		$userName = GetKey($arr,'0');
		$passWord = GetKey($arr,'1');
	
		//查询账号信息
		$wheresql['table'] = '@manager_manager';
		$wheresql['where']['manager_name'] = $userName;
		$wheresql['where']['manager_psw'] = $passWord;
		$accountArr = wmsql::GetOne($wheresql);
		$wheresql = '';
		if( !$accountArr )
		{
			Session( 'admin_account' , 'delete' );
			Session( 'admin_cid' , 'delete' );
			Session( 'admin_id' , 'delete' );
			$cPath = 'login';
		}
		//账号密码正确
		else
		{
			C('admin_cid',$accountArr['manager_cid']);
			C('admin_id',$accountArr['manager_id']);

			//默认为首页
			if( $c == '' )
			{
				$cPath = 'index';
			}
			//控制器不为空
			else
			{
				$cArr = explode('.' , $c);
				//如果控制器的路径大于1
				if( count($cArr) > 1 )
				{
					$cPath = $cArr[0].'/'.$c;
				}
				else
				{
					$cPath = $c;
				}
			}
		}
	}
	else
	{
		if ( IsAjax() && $c != 'login')
		{
			Ajax('登录超时！',301);
		}
		else
		{
			$cPath = 'login';
		}
	}
	
	return $cPath;
}


//检查是否开启了后台的域名权限验证
function CheckDomainAccess()
{
	$adminDomain = C('config.web.admin_domain');
	$adminPath = C('config.web.admin_path');
	$adminAccess = C('config.web.admin_domain_access');
	$nowDomain = GetDomain(GetUrl(),false);

	if( $adminDomain != '' && $adminPath != '' && $adminAccess == '1')
	{
		if( $nowDomain != $adminDomain )
		{
			if ( IsAjax() && Get('c') != 'login')
			{
				Ajax(GetLang('system.domain.admin_access'),300);
			}
			else
			{
				die(GetLang('system.domain.admin_access'));
			}
		}
	}
}


/**
 * 页面权限检测
 * @param 参数1，必须，是否是action请求
 * @param 参数2，必须，控制/处理器文件名字
 * @param 参数3，必须，方法类型
 */
function Competence( $a , $c ,$t )
{
	//不检测的控制器
	$noCheck = array('','login','logout','upload','upload.upload','linkage','index','index_main','system.menu.menu',
				'system.menu.search');
	//最新版本的权限不检查
	if( ($c=='cloud.version' && $t=='getnew') || ($c=='system.site.site' && $t=='getsite') )
	{
	}
	else
	{
		//不包含就查询
		if( !in_array($c, $noCheck) )
		{
			$where['table'] = '@system_menu';
			$where['where']['menu_file'] = $c;
			$where['where']['menu_group'] = array('or','1,0');
			//如果是处理请求
			if( $a )
			{
				$where['where']['menu_group'] = '2';
				$where['where']['menu_name'] = $t;
			}
			else if ( !$a && $t != '' )
			{
				$where['where']['menu_name'] = $t;
			}
			//如果不是超级管理员就只查询权限目录
			if( Session('admin_cid') != 0 )
			{
				$where['filed'] = '@system_menu.*';
				$where['table'] = '@system_menu,@system_competence';
				$where['where']['comp_id'] = C('admin_cid');
				$where['where']['menu_id'] = array('string','FIND_IN_SET(menu_id,comp_content)');
			}
			$data = wmsql::GetOne($where);
			//不存在权限
			if( !$data )
			{
				Ajax('对不起，您的操作权限不足！',300);
			}
		}
	}
}



/**
 * 后台ajax处理数据返回提示
 * @param 参数1，选填，返回的消息提示，默认为200，300为错误
 * @param 参数2，选填，操作成功、失败的代码
 * @param 参数3，选填，返回的数据。
 * @param 参数4，选填，共有多少条数据。
 */
function Ajax( $msg = '操作成功！' , $code = 200 , $data = '' , $total = '')
{
	//操作成功
	$res['statusCode'] = $code;
	$res['message'] = $msg;
	$res['data'] = $data;
	$res['total'] = $total;
	$res['tabid'] = '';
	$res['closeCurrent'] = false;
	$res['forward'] = '';
	$res['forwardConfirm'] = '';
	$res['pageSize'] = Request('pageSize');
	$res['pageCurrent'] = Request('pageCurrent');
	$res['orderField'] = Request('orderField');
	$res['orderDirection'] = Request('orderDirection');

	exit( json_encode( $res ) );
}


/**
 * 读取模版的版权信息
 * @param 参数1，必须，模版文件夹名字
 * @param 参数2，必须，模版主题文件夹
 */
function GetTempCopy( $file , $path = '../templates/')
{
	//模版版权
	$copy = $path.$file.'/copyright.xml';
	//模版预览图
	$cover = $path.$file.'/cover.jpg';
	if( !file_exists($cover) )
	{
		$cover = '/files/images/noimage.gif';
	}
	
	//读取版权信息
	$copyArr = file::XmlToArr($copy);
	$copyArr['path'] = $file;
	$copyArr['cover'] = str_replace(WMROOT, '/', $cover);
	$copyArr['floder'] = $path;
	
	return $copyArr;
}


/**
 * 更新版权文件的内容
 * @param 参数1，必须，文件夹路径
 * @param 参数2，必须，需要修改的数据
 */
function UpdateCopyRight($path,$data)
{
	$copyRight = file_get_contents($path.'/copyright.xml');
	foreach ($data as $k=>$v)
	{
		$repArr = array('<'.$k.'>[a]</'.$k.'>'=>'<'.$k.'>'.$v.'</'.$k.'>');
		$copyRight = tpl::Rep($repArr,$copyRight,'3');
	}
	file_put_contents($path.'/copyright.xml', $copyRight);
	//模版或者应用版权文件
	return true;
}



/**
 * 检查当前模版是否已经安装了
 * @param 参数1，必须，模版文件夹
 */
function CheckInstall( $path )
{
	//安装模版检查
	$isInstall = false;
	$installTemplates = C('config.templates');
	if ( is_array($installTemplates) )
	{
		foreach ( $installTemplates as $k=>$v )
		{
			if( $v['path'] == $path )
			{
				$isInstall = true;
				break;
			}
		}
	}

	return $isInstall;
}


//更新模版配置文件
function UpTempConfig()
{
	$config = '';
	$where['table'] = '@templates_templates';
	$tempArr = wmsql::GetAll($where);
	if( $tempArr )
	{
		foreach ($tempArr as $k=>$v)
		{
			$config .= '"'.$v['templates_path'].'"=>array("path"=>"'.$v['templates_path'].'","name"=>"'.$v['templates_name'].'","appid"=>"'.$v['templates_appid'].'"),';
		}
	}
	file_put_contents( WMCONFIG.'templates.config.php' , '<?php $C["config"]["templates"]=array('.$config.');?>');
}



/**
 * 获得分页数据的条件
 * @param 参数1，选填，是否有默认的条件
 */
function GetListWhere( $where = array())
{
	//显示条数检查
	if ( GetKey($where,'limit') == '' )
	{
		$where['limit'] = GetListLimit( true );
	}
	//条件检查
	if ( GetKey($where,'order') == '' )
	{
		$where['order'] = GetListOrder( true );
	}

	return $where;
}

/**
 * 获得分页数据的排序的方式
 * @param 参数1，必填，排序的条件
 * @param 参数2，选填，是否返回字符串。默认为否。
 */
function GetListOrder($isReturn = false)
{
	$orderField = C('page.orderField');
	$orderDirection = C('page.orderDirection');
	if ( $isReturn )
	{
		$where = '';
	}
	else
	{
		$where = array();
	}
	if( Request('orderField') != '' )
	{
		if ( $isReturn )
		{
			$where = $orderField.' '.$orderDirection;
		}
		else
		{
			$where['order'] = $orderField.' '.$orderDirection;
		}
	}
	return $where;
}

/**
 * 获得分页数据的查询的条数
 * @param 参数1，必填，分页的条件
 * @param 参数2，选填，是否返回字符串。默认为否。
 */
function GetListLimit( $isReturn = false)
{
	$pageCurrent = C('page.pageCurrent');
	$pageSize = C('page.pageSize');
	$where = array();
	if( $pageCurrent != '' && $pageSize != '' )
	{
		if ( $isReturn )
		{
			$where = ($pageCurrent-1)*$pageSize.','.$pageSize;
		}
		else
		{
			$where['limit'] = ($pageCurrent-1)*$pageSize.','.$pageSize;
		}
	}
	return $where;
}


/**
 * 后台引入类的函数
 * @param 参数1，必须，类的名字，例如system.safe
 * @param 参数2，选填，传入的数据
 */
function AdminNewClass( $classFile , $data = '')
{
	$fileName = $className = '';
	$classArr = explode('.', $classFile);
	
	if ( count($classArr) >= 2 )
	{
		$file = 'class/'.$classArr[0].'/'.$classArr[0].'.'.$classArr[1].'.class.php';
		foreach ($classArr as $k=>$v)
		{
			$fileName .= $classArr[$k].'.';
			$className .= $classArr[$k];
		}
		$file = 'class/'.$classArr[0].'/'.$fileName.'class.php';
	}
	else
	{
		$file = 'class/'.$classFile.'.class.php';
		$className = $classFile;
	}
	
	if( file_exists($file) )
	{
		require_once $file;
		$ob = new $className($data);
		return $ob;
	}
	else
	{
		return false;
	}
}

/**
 * 获取模块的配置文件
 * @param 参数1，必须，模块的名字
 * @param 参数2，选填，文件路径
 */
function AdminInc( $name , $path = '' )
{
	if ( $path == '' )
	{
		$path = '../module/'.$name.'/'.$name.'.config.php';
	}
	return Inc($path , $name);
}



/**
 * 写入后台管理员增删改的操作记录
 * @param 参数1，必须，操作备注
 * @param 参数2，必须，操作的模块
 * @param 参数3，必须，操作类型
 * @param 参数4，选填，操作的表
 * @param 参数5，选填，数据条件
 * @param 参数6，选填，数据内容
 */
function SetOpLog( $remark , $module , $type , $table = '' , $opWhere = '' , $opData = '' )
{
	//开启了操作日志统计
	if ( C('config.web.operation_open') == '1')
	{
		$data['operation_manager_id'] = Session('admin_id');
		$data['operation_module'] = $module;
		$data['operation_table'] = $table;
		$data['operation_type'] = $type;
		$data['operation_data'] = serialize($opData);
		$data['operation_where'] = serialize($opWhere);
		$data['operation_backdata'] = '';
		$data['operation_remark'] = $remark;
		$data['operation_time'] = time();
		
		wmsql::Insert('@manager_operation', $data);
	}
}


/**
 * 获得当前控制器的独有函数名字
 */
function GetCFun()
{
	$cFun = '';
	$c = Request('c');
	//回调函数名字设置为控制器名字
	$cArr = explode('.', $c);
	$i = 0;
	foreach ($cArr as $k=>$v)
	{
		if( $i > 0)
		{
			$v = ucfirst($v);
		}
		$cFun .= $v;
		$i++;
	}
	return $cFun;
}


/**
 * 获得批量操作的id，
 * @param 参数1，选填，id的字段名
 * @param 参数2，选填，ids的字段名
 * @param 参数3，选填，是否是右外链接
 */
function GetDelId( $idName = 'id' , $idsName = 'ids' , $rin = false)
{
	if( str::Number(Request($idName)) || Request($idName) != '' )
	{
		$id = Request($idName);
	}
	else
	{
		$id = Request($idsName);
		if( Request($idsName) == '' )
		{
			$id = Request($idName);
		}
		if( $rin == false )
		{
			$id = array('lin' , $id);
		}
	}
	if( $rin == true )
	{
		$id = explode(',', $id);
	}
	return $id;
}

/**
 * 删除分类操作
 */
function DelType($module='')
{
	global $tableSer,$conSer;
	$idArr = GetDelId('id','ids',true);
	if( !$idArr )
	{
		Ajax('对不起，请选择分类id！',300);
	}
	else
	{
		if( $module == '' )
		{
			$module = Session('cur_module');
		}
		$table = $tableSer->tableArr[$module.'type']['table'];
		//循环获得id
		foreach ($idArr as $k=>$v)
		{
			$wheresql['table'] = $table;
			$wheresql['field'] = 'type_id';
			$wheresql['where']['type_pid'] = array('and-or',array('rin',$v),array('type_id'=>$v));
			$data = wmsql::GetAll($wheresql);
			if( $data )
			{
				//循环获得的数据
				foreach ($data as $key=>$val)
				{
					//删除分类
					wmsql::Delete($table, array('type_id'=>$val['type_id']));
					//删除当前分类的自定义字段
					if( method_exists($conSer,'DelField') )
					{
						$conSer->DelField($val['type_id']);
					}
					//回调函数
					if( function_exists('TypeDelCallBack') )
					{
						TypeDelCallBack($val['type_id']);
					}
				}
			}
		}
	}
}


/**
 * 获得当前模块分类的pid
 * @param 参数1，必须，查询的表名
 * @param 参数2，必须，父级id
 * @param 参数3，选填，pid字符串
 */
function GetPids( $table , $id , $pid = '')
{
	if ( $id != '0' )
	{
		$where['table'] = $table;
		$where['where']['type_id'] = $id;
		$data = wmsql::GetOne($where);

		//如果上级id不为0
		if( $data['type_topid'] != '0' )
		{
			$pid = ','.$data['type_id'];
			return GetPids( $table , $data['type_topid'] , $pid);
		}
		//如果有数据并且父亲id不为空
		else
		{
			return $data['type_id'].$pid;
		}
	}
	//否则直接返回
	else if( $pid != '' )
	{
		return $id;
	}
	else
	{
		return '0';
	}
}


/**
 * 将数组转换成简单的json格式数组
 * @param 参数1，必须，数组对象
 * @param 参数2，选填，键名
 * @param 参数1，选填，值名
 */
//格式 "data":{"list":"123","content":"456"},
function ToEasyJson($arr , $key = '', $val = '')
{
	$newData = array();

	if( is_array($arr) )
	{
		foreach ($arr as $k=>$v)
		{
			if( $key == '' || $val == '' )
			{
				$newData[$k] = $v;
			}
			else
			{
				$newData[$v[$key]] = $v[$val];
			}
		}
	}

	return $newData;
}


/**
 * 生成百度编辑器
 * @param 参数1，必须，编辑器的样式
 * @param 参数2，必须，编辑器的name值
 * @param 参数3，选填，编辑器的初始化内容
 * @param 参数4，选填，编辑器上传文件的模块配置
 */
function Ueditor($style , $name , $content = '' , $upCon = '')
{
	$id = time().rand(1, 50000);
	$ueditor = $module = $type = '';
	//是否有上传文件的模块配置参数
	if( $upCon != '' )
	{
		$upCon = explode('.', $upCon);
		$module = $upCon[0];
		if( count($upCon) ==  2 )
		{
			$type = $upCon[1];
		}
		$ueditor = '<script>
					var nowPar = "?module='.$module.'&type='.$type.'";
					var serverUrl = window.UEDITOR_CONFIG.serverUrl;
					if( serverUrl.indexOf("?") > -1 ){
						nowPar = "&module='.$module.'&type='.$type.'";
					}
					window.UEDITOR_CONFIG.serverUrl += nowPar;
					</script>';
	}
	$ueditor = $ueditor.'<script style="'.$style.'" id="'.$id.'" name="'.$name.'" type="text/plain">'.$content.'</script>
			<script>ue = UE.getEditor("'.$id.'");</script>';
	return $ueditor;
}


/**
 * 写入版本操作
 * @param 参数1，必须，需要修改的版本号
 * @param 参数2，必须，版本更新的时间
 */
function SetVersion($ver,$time)
{
	$file = WMCONFIG."define.config.php";
	$defineContent=file_get_contents($file);
	$defineContent = preg_replace("/define\('WMVER','(.*?)'\)/", "define('WMVER','{$ver}')", $defineContent);
	$defineContent = preg_replace("/define\('WMVER_TIME','(.*?)'\)/", "define('WMVER_TIME','{$time}')", $defineContent);
	file_put_contents($file,$defineContent);
}


/**
 * 获得当前分类的子级分类id
 * @param 参数1，必须，所属模块
 * @param 参数2，必须，分类id
 */
function GetTypePids($module,$tid)
{
	global $tableSer;
	$pids = '';
	$where['table'] = $tableSer->tableArr[$module.'type']['table'];
	$where['where']['type_pid'] = array('lin',$tid);
	$typeData = wmsql::GetAll($where);
	if( $typeData )
	{
		foreach ($typeData as $k=>$v)
		{
			$pids .= ','.$v['type_id'];
		}
	}
	return '0'.$pids;
}
?>