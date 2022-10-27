<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo C('config.web.webname');?> - WMCMS 后台管理系统</title>
<!-- bootstrap - css -->
<link href="<?php echo $tempPath;?>BJUI/themes/css/bootstrap.css" rel="stylesheet">
<!-- core - css -->
<link href="<?php echo $tempPath;?>BJUI/themes/css/style.css" rel="stylesheet">
<link href="<?php echo $tempPath;?>BJUI/themes/blue/core.css" id="bjui-link-theme" rel="stylesheet">
<link href="<?php echo $tempPath;?>BJUI/plugins/colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
<link href="<?php echo $tempPath;?>BJUI/plugins/niceValidator/jquery.validator.css" rel="stylesheet">
<link href="<?php echo $tempPath;?>BJUI/plugins/bootstrapSelect/bootstrap-select.css" rel="stylesheet">
<link href="<?php echo $tempPath;?>BJUI/themes/css/FA/css/font-awesome.min.css" rel="stylesheet">
<!--[if lte IE 7]>
<link href="<?php echo $tempPath;?>BJUI/themes/css/ie7.css" rel="stylesheet">
<![endif]-->
<!--[if lte IE 9]>
    <script src="<?php echo $tempPath;?>BJUI/other/html5shiv.min.js"></script>
    <script src="<?php echo $tempPath;?>BJUI/other/respond.min.js"></script>
<![endif]-->
<!-- jquery -->
<script src="<?php echo $tempPath;?>BJUI/js/jquery-1.7.2.min.js"></script>
<script src="<?php echo $tempPath;?>BJUI/js/jquery.cookie.js"></script>
<!--[if lte IE 9]>
<script src="<?php echo $tempPath;?>BJUI/other/jquery.iframe-transport.js"></script>    
<![endif]-->
<!-- BJUI.all 分模块压缩版 -->
<script src="<?php echo $tempPath;?>BJUI/js/bjui-all.min.js"></script>
<!-- plugins -->
<!-- swfupload for uploadify && kindeditor -->
<script src="<?php echo $tempPath;?>BJUI/plugins/swfupload/swfupload.min.js"></script>
<!-- ueditor -->
<script src="<?php echo $tempPath;?>BJUI/plugins/ueditor/ueditor.config.js"></script>
<script src="<?php echo $tempPath;?>BJUI/plugins/ueditor/ueditor.all.min.js"></script>
<script src="<?php echo $tempPath;?>BJUI/plugins/ueditor/lang/zh-cn/zh-cn.js"></script>
<!-- colorpicker -->
<script src="<?php echo $tempPath;?>BJUI/plugins/colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- ztree -->
<script src="<?php echo $tempPath;?>BJUI/plugins/ztree/jquery.ztree.all-3.5.min.js"></script>
<!-- nice validate -->
<script src="<?php echo $tempPath;?>BJUI/plugins/niceValidator/jquery.validator.js"></script>
<script src="<?php echo $tempPath;?>BJUI/plugins/niceValidator/jquery.validator.themes.js"></script>
<!-- bootstrap plugins -->
<script src="<?php echo $tempPath;?>BJUI/plugins/bootstrap.min.js"></script>
<script src="<?php echo $tempPath;?>BJUI/plugins/bootstrapSelect/bootstrap-select.min.js"></script>
<script src="<?php echo $tempPath;?>BJUI/plugins/bootstrapSelect/defaults-zh_CN.min.js"></script>
<!-- icheck -->
<script src="<?php echo $tempPath;?>BJUI/plugins/icheck/icheck.min.js"></script>
<!-- dragsort -->
<script src="<?php echo $tempPath;?>BJUI/plugins/dragsort/jquery.dragsort-0.5.1.min.js"></script>
<!-- other plugins -->
<script src="<?php echo $tempPath;?>BJUI/plugins/other/jquery.autosize.js"></script>
<link href="<?php echo $tempPath;?>BJUI/plugins/uploadify/css/uploadify.css" rel="stylesheet">
<script src="<?php echo $tempPath;?>BJUI/plugins/uploadify/scripts/jquery.uploadify.min.js"></script>
<script src="<?php echo $tempPath;?>BJUI/plugins/download/jquery.fileDownload.js"></script>
<!-- bigautocomplete -->
<link href="/files/js/autocomplete/autocomplete.css" rel="stylesheet">
<script src="/files/js/autocomplete/autocomplete.js"></script>
<!-- acharts -->
<script src="<?php echo $tempPath;?>BJUI/plugins/acharts/acharts-min.js"></script>
<!-- init -->
<script type="text/javascript">
$(function() {
    BJUI.init({
        JSPATH       : '<?php echo $tempPath;?>/BJUI/',         //[可选]框架路径
        PLUGINPATH   : '<?php echo $tempPath;?>/BJUI/plugins/', //[可选]插件路径
        loginInfo    : {url:'index.php?c=login_out', title:'登录', width:400, height:250}, // 会话超时后弹出登录对话框
        statusCode   : {ok:200, error:300, timeout:301}, //[可选]
        ajaxTimeout  : 50000, //[可选]全局Ajax请求超时时间(毫秒)
        pageInfo     : {total:'total', pageCurrent:'pageCurrent', pageSize:'pageSize', orderField:'orderField', orderDirection:'orderDirection'}, //[可选]分页参数
        alertMsg     : {displayPosition:'topcenter', displayMode:'slide', alertTimeout:3000}, //[可选]信息提示的显示位置，显隐方式，及[info/correct]方式时自动关闭延时(毫秒)
        keys         : {statusCode:'statusCode', message:'message'}, //[可选]
        ui           : {
                         windowWidth      : 0,    //框架可视宽度，0=100%宽，> 600为则居中显示
                         showSlidebar     : true, //[可选]左侧导航栏锁定/隐藏
                         clientPaging     : true, //[可选]是否在客户端响应分页及排序参数
                         overwriteHomeTab : false //[可选]当打开一个未定义id的navtab时，是否可以覆盖主navtab(我的主页)
                       },
        debug        : true,    // [可选]调试模式 [true|false，默认false]
        theme        : 'sky' // 若有Cookie['bjui_theme'],优先选择Cookie['bjui_theme']。皮肤[五种皮肤:default, orange, purple, blue, red, green]
    })
    
    // main - menu
    $('#bjui-accordionmenu')
        .collapse()
        .on('hidden.bs.collapse', function(e) {
            $(this).find('> .panel > .panel-heading').each(function() {
                var $heading = $(this), $a = $heading.find('> h4 > a')
                
                if ($a.hasClass('collapsed')) $heading.removeClass('active')
            })
        })
        .on('shown.bs.collapse', function (e) {
            $(this).find('> .panel > .panel-heading').each(function() {
                var $heading = $(this), $a = $heading.find('> h4 > a')
                
                if (!$a.hasClass('collapsed')) $heading.addClass('active')
            })
        })
        
    $(document).on('click', 'ul.menu-items li > a', function(e) {
    	var $a = $(this);
        if(  typeof($a.data('options')) == 'undefined' ){
            return false;
        }
        var $li = $a.parent(), options = $a.data('options').toObj(), $children = $li.find('> .menu-items-children')
        var onClose = function() {
            $li.removeClass('active')
        }
        var onSwitch = function() {
            $('#bjui-accordionmenu').find('ul.menu-items li').removeClass('switch')
            $li.addClass('switch')
        }
        
        $li.addClass('active')
        if (options) {
            options.url      = $a.attr('href')
            options.onClose  = onClose
            options.onSwitch = onSwitch
            if (!options.title) options.title = $a.text()
            
            if (!options.target)
                $a.navtab(options)
            else
                $a.dialog(options)
        }
        if ($children.length) {
            $li.toggleClass('open')
        }
        
        e.preventDefault()
    })

    
    //时钟
    var today = new Date(), time = today.getTime()
    $('#bjui-date').html(today.formatDate('yyyy/MM/dd'))
    setInterval(function() {
        today = new Date(today.setSeconds(today.getSeconds() + 1))
        $('#bjui-clock').html(today.formatDate('HH:mm:ss'))
    }, 1000)
})

//菜单-事件
function MainMenuClick(event, treeId, treeNode) {
    event.preventDefault()
    
    if (treeNode.isParent) {
        var zTree = $.fn.zTree.getZTreeObj(treeId)
        
        zTree.expandNode(treeNode, !treeNode.open, false, true, true)
        return
    }
    
    if (treeNode.target && treeNode.target == 'dialog')
        $(event.target).dialog({id:treeNode.tabid, url:treeNode.url, title:treeNode.name})
    else
        $(event.target).navtab({id:treeNode.tabid, url:treeNode.url, title:treeNode.name, fresh:treeNode.fresh, external:treeNode.external})
}
//搜索功能
function SerachMenu(){
	var key = $("#searchMenuKey").val();
	if( key == '' ){
		$(this).alertmsg('error', '搜索词不能为空!')
	}else{
		var options = {id:'search-menu',title:'搜索菜单',url:'index.php?d=yes&c=system.menu.search&key='+key};
		$(this).navtab(options)
	}
}
//快捷菜单添加或取消
function QuickMenu( $this , id ){
	var obj = $($this);
	var cs = obj.attr("class");
	var options={url:'index.php?a=yes&c=system.menu.menu&t=quick&id='+id,reload:false}
	obj.bjuiajax('doAjax', options);
	if( cs== 'fa fa-star'){
		obj.attr("class" , 'fa fa-star-o');
	}else{
		obj.attr("class" , 'fa fa-star');
	}
}
<?php 
$apiAppid = intval($C["config"]["api"]['system']['api_appid']);
$apiApikey = $C["config"]["api"]['system']['api_apikey'];
$apiSecretkey = $C["config"]["api"]['system']['api_secretkey'];
if( $apiAppid == 0 || $apiApikey == '0000' || $apiApikey == '0000' )
{
	echo '$(document).ready(function(){$(this).alertmsg("error", "你的网站通信APPID没有注册，请重新安装程序，否则无法正常使用程序!")});';
}
?>
</script>
</head>
<body>
    <!--[if lte IE 7]>
        <div id="errorie"><div>您还在使用老掉牙的IE，正常使用系统前请升级您的浏览器到 IE8以上版本 <a target="_blank" href="http://windows.microsoft.com/zh-cn/internet-explorer/ie-8-worldwide-languages">点击升级</a>&nbsp;&nbsp;强烈建议您更改换浏览器：<a href="http://down.tech.sina.com.cn/content/40975.html" target="_blank">谷歌 Chrome</a></div></div>
    <![endif]-->
    <div id="bjui-window">
    <header id="bjui-header">
        <div class="bjui-navbar-header">
            <button type="button" class="bjui-navbar-toggle btn-default" data-toggle="collapse" data-target="#bjui-navbar-collapse">
                <i class="fa fa-bars"></i>
            </button>
            <a class="bjui-navbar-logo" href="#"><img src="<?php echo $tempPath;?>/BJUI/images/logo.png" style="width:179px"></a>
        </div>
        <nav id="bjui-navbar-collapse">
            <ul class="bjui-navbar-right">
				<li><span class="wrap_bjui_btn_box" style="position: relative; display: inline-block;"><input type="text" placeholder="搜索菜单" id="searchMenuKey" class="doc_lookup form-control" size="10" ><a class="bjui-lookup" href="javascript:;" onClick="SerachMenu()" style="height: 22px; line-height: 22px;"><i class="fa fa-search"></i></a></span></li>
                <li class="datetime"><div><span id="bjui-date"></span> <span id="bjui-clock"></span></div></li>
                <!--<li><a href="#">消息 <span class="badge">4</span></a></li>-->
                <li><a href="/" target="_blank">返回首页 </a></li>
                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo Session('admin_name');?>，您好！ <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="index.php?a=yes&c=logout" class="red">&nbsp;<span class="glyphicon glyphicon-off"></span> 注销登陆</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a href="#" class="dropdown-toggle theme blue" data-toggle="dropdown" title="切换皮肤"><i class="fa fa-tree"></i></a>
                    <ul class="dropdown-menu" role="menu" id="bjui-themes">
                        <li><a href="javascript:;" class="theme_default" data-toggle="theme" data-theme="default">&nbsp;<i class="fa fa-tree"></i> 黑白分明&nbsp;&nbsp;</a></li>
                        <li><a href="javascript:;" class="theme_orange" data-toggle="theme" data-theme="orange">&nbsp;<i class="fa fa-tree"></i> 橘子红了</a></li>
                        <li><a href="javascript:;" class="theme_purple" data-toggle="theme" data-theme="purple">&nbsp;<i class="fa fa-tree"></i> 紫罗兰</a></li>
                        <li class="active"><a href="javascript:;" class="theme_blue" data-toggle="theme" data-theme="blue">&nbsp;<i class="fa fa-tree"></i> 天空蓝</a></li>
                        <li><a href="javascript:;" class="theme_green" data-toggle="theme" data-theme="green">&nbsp;<i class="fa fa-tree"></i> 绿草如茵</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
		<div style="padding-top: 8px;margin-left: 205px;">
			<select data-toggle="selectpicker" id="index-domain-select">
				<?php
				foreach($siteList as $k=>$v)
				{
					$selected = '';
					if( $v['type'] == '1' && $siteId == $v['id'])
					{
						$selected = 'selected=""';
					}
					echo '<option '.$selected.' data-type="'.$v['type'].'" data-id="'.$v['id'].'">'.$v['name'].'【'.GetDomain($v['domain'],false).'】</option>';
				}
				?>
			</select>
		</div>
        <div id="bjui-hnav">
            <button type="button" class="btn-default bjui-hnav-more-left" title="导航菜单左移"><i class="fa fa-angle-double-left"></i></button>
            <div id="bjui-hnav-navbar-box">
                <ul id="bjui-hnav-navbar">
                
                	<li class="active"><a href="javascript:;" data-toggle="slidebar"><i class="fa fa-home"></i> 管理首页</a>
                        <div class="items" data-noinit="true">
                            <ul class="menu-items" data-faicon="star-o" data-tit="快捷菜单">
                            <?php
                            if ( $quickMenuArr )
                            {
	                            foreach ($quickMenuArr as $k=>$v)
	                            {
	                            	$quickIco = $v['menu_ico'];
	                            	if( empty($quickIco) )
	                            	{
	                            		$quickIco = 'fa-caret-right';
	                            	}
	                            	echo '<li><a style="width:20%;float:right" title="取消快捷菜单" class="fa fa-star" onClick="QuickMenu(this,'.$v['menu_id'].')" href="javascript:;"></a> <a href="index.php?c='.$v['menu_file'].'" data-options="{id:\'quick-'.$v['menu_id'].'\', faicon:\''.$quickIco.'\'}" data-title="快捷菜单">'.$v['menu_title'].'</a></li>';
	                            }
                            }
                            else
                            {
                            	echo '<li><a href="javascript:void()" >您暂未添加快捷菜单</a></li>';
                            }
                            ?>
                            </ul>
                        </div>
                    </li>
                	<?php
                	//一级菜单
                	foreach ($menuArr as $key=>$val)
                	{
                		echo '<li><a href="javascript:;" data-toggle="slidebar"><i class="fa '.$val['menu_ico'].'"></i> '.$val['menu_title'].'</a>';
                		echo '<div class="items hide" data-noinit="true">';
                		
                		//二级菜单
                		foreach ($val['child'] as $k=>$v)
                		{
                			//判断是否有分组的三级菜单
                			$isGroup = false;
                			foreach ($v['child'] as $k1=>$v1)
                			{
                				if ( $v1['menu_group'] == '1' )
                				{
                					$isGroup = true;
                					break;
                				}
                			}
                			//分组菜单
                			if ( $isGroup )
                			{
                				echo '<ul id="bjui-hnav-tree'.$v['menu_id'].'" data-tit="'.$v['menu_title'].'" class="ztree ztree_main" data-on-click="MainMenuClick" data-toggle="ztree" data-expand-all="true" data-faicon="fa '.$v['menu_ico'].'">';

                				foreach ($v['child'] as $k1=>$v1)
                				{
                					echo '<li data-id="'.$v1['menu_id'].'" data-pid="0" data-faicon="folder-open-o" data-faicon-close="folder-o">'.$v1['menu_title'].'</li>';
	                				foreach ($v1['child'] as $k2=>$v2)
	                				{
	                					$t = str::CheckElse( $v2['menu_url'] , '1' , '&t='.$v2['menu_name']);
		                				//如果快捷菜单存在
		                				if ( in_array($v2['menu_id'], $newQuickArr))
		                				{
		                					$title = '取消';
		                					$className='fa fa-star';
		                				}
		                				else
		                				{
		                					$title = '添加';
		                					$className='fa fa-star-o';
		                				}
		                				echo '<li data-id="'.$v2['menu_id'].'" data-QuickMenu=true data-QuickMenuClass="'.$className.'" data-QuickMenuTitle="'.$title.'" data-pid="'.$v2['menu_pid'].'" data-url="index.php?c='.$v2['menu_file'].$t.'" data-tabid="'.$v['menu_name'].'-'.$v1['menu_name'].'-'.$v2['menu_name'].'" data-faicon="caret-right">'.$v2['menu_title'].'</li>';
	                				}
                				}
                			}
                			//普通菜单
                			else
                			{
                				echo '<ul class="menu-items" data-tit="'.$v['menu_title'].'" data-faicon="fa '.$v['menu_ico'].'">';
                				foreach ($v['child'] as $k1=>$v1)
                				{
	                				$t = str::CheckElse( $v1['menu_url'] , '1' , '&t='.$v1['menu_name']);
	                				//如果快捷菜单存在
	                				if ( in_array($v1['menu_id'], $newQuickArr))
	                				{
	                					$title = '取消';
	                					$className='fa fa-star';
	                				}
	                				else
	                				{
	                					$title = '添加';
	                					$className='fa fa-star-o';
	                				}
                					echo '<li><a style="width:20%;float:right" title="'.$title.'快捷菜单" class="'.$className.'" onClick="QuickMenu(this,'.$v1['menu_id'].')" href="javascript:;"></a> <a href="index.php?c='.$v1['menu_file'].$t.'" data-options="{id:\''.$v['menu_name'].'-'.$v1['menu_name'].'\', faicon:\''.$v1['menu_ico'].'\'}">'.$v1['menu_title'].'</a></li>';
                				}
                			}
                			echo '</ul>';
                		}
                		
                		echo '</div></li>';
                	}
                	?>
                    <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-question-circle"></i> 帮助中心 <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a target="_blank" href="<?php echo WMURL?>">程序官网</a></li>
                            <li class="divider"></li>
                            <li><a target="_blank" href="<?php echo WMLABEL?>">标签帮助</a></li>
                            <li class="divider"></li>
                            <li><a target="_blank" href="<?php echo WMHELP?>">使用帮助</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <button type="button" class="btn-default bjui-hnav-more-right" title="导航菜单右移"><i class="fa fa-angle-double-right"></i></button>
        </div>
    </header>
    <div id="bjui-container">
        <div id="bjui-leftside">
            <div id="bjui-sidebar-s">
                <div class="collapse"></div>
            </div>
            <div id="bjui-sidebar">
                <div class="toggleCollapse"><h2><i class="fa fa-bars"></i> 导航栏 <i class="fa fa-bars"></i></h2><a href="javascript:;" class="lock"><i class="fa fa-lock"></i></a></div>
                <div class="panel-group panel-main" data-toggle="accordion" id="bjui-accordionmenu" data-heightbox="#bjui-sidebar" data-offsety="26">
                </div>
            </div>
        </div>
		
        <div id="bjui-navtab" class="tabsPage">
            <div class="tabsPageHeader">
                <div class="tabsPageHeaderContent">
                    <ul class="navtab-tab nav nav-tabs">
						<!--默认首页-->
                        <li data-url="index.php?c=<?php echo $defaultIndex;?>"><a href="javascript:;"><span><i class="fa fa-home"></i> #maintab#</span></a></li>
                    </ul>
                </div>
                <div class="tabsLeft"><i class="fa fa-angle-double-left"></i></div>
                <div class="tabsRight"><i class="fa fa-angle-double-right"></i></div>
                <div class="tabsMore"><i class="fa fa-angle-double-down"></i></div>
            </div>
            <ul class="tabsMoreList">
                <li><a href="javascript:;">#maintab#</a></li>
            </ul>
            <div class="navtab-panel tabsPageContent">
                <div class="navtabPage unitBox">
                    <div class="bjui-pageContent" style="background:#FFF;">
                        Loading...
                    </div>
                </div>
            </div>
        </div>
    </div>
	
    <footer id="bjui-footer">Powered by <a href="<?php echo WMURL;?>" target="_blank"><?php echo WMCMS;?></a> Inc.</footer>
</div>

<script type="text/javascript">
function indexAjaxCallBack(json)
{
	if(json.statusCode=='300'){
		$(this).alertmsg('error', json.message);
		return false;
	}else{
		if(json.data.type=='2'){
			window.open(json.data.url);
			return false;
		}else{
			$(this).navtab('closeAllTab');
			$(this).alertmsg('ok', '站点切换成功！');
		}
	}
}
$(document).ready(function(){
	//域名类型选择
	$("#index-domain-select").change(function(){
		var id = $(this).find("option:selected").data('id');
		var type = $(this).find("option:selected").data('type');
		var siteOp = new Array();
		siteOp['type'] = 'GET';
		siteOp['reload'] = 'false';
		siteOp['loadingmask'] = 'true';
		siteOp['url'] = "index.php?a=yes&c=system.site.site&t=getsite&st="+type+"&id="+id;
		siteOp['callback'] = "indexAjaxCallBack";
		$(this).bjuiajax("doAjax",siteOp);// 显示处理结果
	});

});
</script>
</body>
</html>