<div class="bjui-pageContent">
	<fieldset>
		<legend>插件开发工具</legend>
        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#<?php echo $cFun.$type;?>plugin_add" role="tab" data-toggle="tab">添加插件</a></li>
            <li><a href="#<?php echo $cFun.$type;?>config_add" role="tab" data-toggle="tab">添加配置</a></li>
            <li><a href="#<?php echo $cFun.$type;?>config_edit" role="tab" data-toggle="tab">删除配置</a></li>
        </ul>
		<div class="tab-content">
            <div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>plugin_add">
				<form name="plugin_add" action="index.php?a=yes&c=system.dev.addplugin&t=plugin_add" method="post" data-toggle="validate" data-confirm-msg="是否添加插件的？">
				<table class="table table-border table-bordered table-bg table-sort">
					<tr>
				      <td valign="top" width="100"><b>插件名字：</b></td>
				      <td valign="top"><input data-rule="required;" name="data[plugin_name]" type="text" class="input-text"> 插件中文名字，如：留言本</td>
					</tr>
				    <tr>
				      <td valign="top"><b>插件标识：</b></td>
				      <td valign="top"><input data-rule="required;" name="data[plugin_floder]" type="text" class="input-text"> 插件唯一id标识(全字母)，必须格式如：开发者_插件名（wmcms_message）</td>
					</tr>
				    <tr>
				      <td valign="top"><b>插件作者：</b></td>
				      <td valign="top"><input data-rule="required;" name="data[plugin_author]" type="text" class="input-text"> 插件的作者，如：未梦</td>
					</tr>
				    <tr>
				      <td valign="top"><b>插件版本：</b></td>
				      <td valign="top"><input data-rule="required;" name="data[plugin_version]" type="text" class="input-text"> 插件版本，如：v1.0</td>
					</tr>
				    <tr>
				      <td valign="top"><b>插件官网：</b></td>
				      <td valign="top"><input name="url" type="text" class="input-text"> 插件官网，如：<?php echo WMURL;?></td>
					</tr>
				    <tr>
				      <td colspan="2"><button type="submit" class="btn-green" data-icon="save">添加插件</button></td>
					</tr>
				</table>
				</form>
			</div>
			
			<div class="tab-pane fade" id="<?php echo $cFun.$type;?>config_add">
				<form name="plugin_add" action="index.php?a=yes&c=system.dev.addplugin&t=config_add" method="post" data-toggle="validate" data-confirm-msg="是否添加该插件的配置？" data-callback="<?php echo $cFun;?>">
				<table class="table table-border table-bordered table-bg table-sort">
					<tr>
				      <td valign="top" width="150"><b>选择插件：</b></td>
				      <td valign="top">
			        	<select data-toggle="selectpicker" data-rule="required;" name="config_plugin_id">
			        		<option value="">请选择插件</option>
				        	<?php 
				        	foreach ($data as $k=>$v)
				        	{
				        		echo '<option value="'.$v['plugin_id'].'">'.$v['plugin_name'].'</option>';
				        	}
				        	?>
			        	</select>
					</tr>
				    <tr>
				      <td valign="top" width="100"><b>配置键名：</b></td>
				      <td valign="top"><input data-rule="required;" name="config_key" type="text" class="input-text"> 配置键名，如：site_open</td>
					</tr>
				    <tr>
				      <td valign="top"><b>配置值：</b></td>
				      <td valign="top"><input data-rule="required;" name="config_val" type="text" class="input-text"> 键值，如：1</td>
					</tr>
				    <tr>
				      <td colspan="2"><button type="submit" class="btn-green" data-icon="save">添加配置</button></td>
					</tr>
				</table>
				</form>
			</div>
			
			<div class="tab-pane fade" id="<?php echo $cFun.$type;?>config_edit">
				<form name="plugin_add" action="index.php?a=yes&c=system.dev.addplugin&t=config_del" method="post" data-toggle="validate" data-confirm-msg="是否删除当前插件的配置？">
				<table class="table table-border table-bordered table-bg table-sort">
					<tr>
				      <td valign="top" width="150"><b>选择插件：</b></td>
				      <td valign="top">
			        	<select name="config_plugin_id" data-rule="required;" data-toggle="selectpicker" data-nextselect="#<?php echo $cFun;?>config_id" data-refurl="index.php?a=yes&c=system.dev.addplugin&t=getpluginconfig&id={value}">
                        	<option value="">--请选择插件--</option>
                            <?php 
				        	foreach ($data as $k=>$v)
				        	{
				        		echo '<option value="'.$v['plugin_id'].'">'.$v['plugin_name'].'</option>';
				        	}
				        	?>
                        </select>
                        <select name="config_key" data-rule="required;" id="<?php echo $cFun;?>config_id" data-toggle="selectpicker" data-emptytxt="--当前插件暂无配置--">
                        	<option value="">--请选择配置--</option>
                        </select>
					</tr>
				    <tr>
				      <td colspan="2"><button type="submit" class="btn-red" data-icon="close">删除配置</button></td>
					</tr>
				</table>
				</form>
			</div>
		</div>
	</fieldset>
</div>

<script>
</script>
