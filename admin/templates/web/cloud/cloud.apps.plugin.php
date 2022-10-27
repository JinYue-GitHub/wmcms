<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=system.set.templates&t=update" method="post" data-toggle="validate">
	<fieldset>
		<legend>我的插件管理</legend>
		<ul class="nav nav-tabs" role="tablist">
           <li class="active" id="<?php echo $cFun;?>tab_install"><a href="#<?php echo $cFun;?>install" role="tab" data-toggle="tab">已安装插件</a></li>
           <li id="<?php echo $cFun;?>tab_list"><a href="#<?php echo $cFun;?>list" role="tab" data-toggle="tab">安装本地插件</a></li>
		</ul>
		
             
		<div class="design_page">
		    <div class="tab-content">
		    	<div class="tab-pane fade active in" id="<?php echo $cFun;?>install">  
			    	<ul>
		    		<?php
		    		if( $installPlugin )
		    		{
			    		foreach ($installPlugin as $k=>$v)
			    		{
			    			$BGR = '';
							if( $v['now_ver'] < $v['new_ver'] )
							{
								$v['ver'] = '有更新';
			    				$BGR = 'background:#5cb85c';
							}
			        		echo '<li><div class="img"><img src="'.$v['cover'].'" width="210" height="210" class="hoverZoomLink"></div>
		    				<div class="ft" style="text-align:center;">
								<a href="index.php?d=yes&c=cloud.apps.plugin.manager&t=manager&id='.$v['plugin_id'].'" data-toggle="navtab" data-id="cloud-apps-plugin-'.$v['plugin_floder'].'" data-title="插件《'.$v['plugin_name'].'》管理" style="color:red">管理插件</a>
           						<a href="index.php?a=yes&c=cloud.apps.plugin&t=uninstall&path='.$v['plugin_floder'].'" data-toggle="doajax" data-confirm-msg="是否卸载此插件？" style="color:#ccc6c6">点击卸载</a>
							</div>
		    				<div class="ft"><span class="label label-danger radius">'.$v['name'].'</span></div>
							<span class="badge '.$cFun.'AppUpdate" style="'.$BGR.'" data-new="'.$v['new_ver'].'" data-now="'.$v['now_ver'].'" data-id="'.$v['plugin_floder'].'">'.$v['ver'].'</span></li>';
			    		}
		    		}
		    		else
		    		{
		    			echo '您暂时没有安装任何插件，可以前往云商店下载或者本地安装';
		    		}
					?>
		    		</ul>
		    		<div style="clear:both"></div>
				</div>
				
				<div class="tab-pane fade" id="<?php echo $cFun;?>list">  
			    	<ul>
		    		<?php 
		    		if( $pluginList )
		    		{
			    		foreach ($pluginList as $k=>$v)
			    		{
			    			$list = '<div class="ft" style="text-align:center;"><a href="index.php?a=yes&c=cloud.apps.plugin&t=install&path='.$v['path'].'" data-toggle="doajax" data-confirm-msg="是否安装此插件？" style="color:green">点击安装</a></div>';
			        		echo '<li><div class="img"><img src="'.$v['cover'].'" width="210" height="210" class="hoverZoomLink"></div>
			    				'.$list.'<div class="ft"><span class="label label-danger radius">'.$v['name'].'</span></div><span class="badge badge-danger">'.$v['ver'].'</span></li>';
			    		}
		    		}
		    		else
		    		{
		    			echo '您本地没有未使用的插件！';
		    		}
					?>
		    		</ul>
		    		<div style="clear:both"></div>
				</div>
			</div>
		</div>
	</fieldset>
	</form>
</div>

<script>
$(document).ready(function(){
	//应用更新
	$(".<?php echo $cFun;?>AppUpdate").click(function(){
		var nowVer = $(this).data('now');
		var newVer = $(this).data('new');
		if( nowVer == newVer )
		{
			return false;
		}
		else
		{
			var ajaxOptions=new Array();
			var ajaxData=new Object();
			ajaxData.id = $(this).data('id');
			ajaxData.ver = ''+nowVer+'';
			ajaxOptions['type'] = 'GET';
			ajaxOptions['loadingmask'] = true;
			ajaxOptions['data'] = ajaxData;
			ajaxOptions['url'] = "index.php?a=yes&c=cloud.apps&t=update";
			ajaxOptions['confirmMsg'] = "更新前请确认做好数据、代码备份。更新详情请到应用详情页查看。是否更新？";
			$(this).bjuiajax('doAjax', ajaxOptions);
		}
	});
});
</script>