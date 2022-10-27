<div class="bjui-pageContent">	                       
    <form action="index.php?a=yes&c=operate.diy&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="id[diy_id]" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>单页编辑</legend>
            <ul class="nav nav-tabs" role="tablist">
            	<li class="active"><a href="#<?php echo $cFun.$type;?>base" role="tab" data-toggle="tab">基本设置</a></li>
            	<li><a href="#<?php echo $cFun.$type;?>seo" role="tab" data-toggle="tab">SEO设置</a></li>
            	<li><a href="#<?php echo $cFun.$type;?>templates" id="<?php echo $cFun.$type;?>templates_tab" role="tab" data-toggle="tab">模版设置</a></li>
            	<li><a href="#<?php echo $cFun.$type;?>html" role="tab" data-toggle="tab">HTML设置</a></li>
            </ul>
            <div class="tab-content">
            	<div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>base">
			        	<table class="table table-condensed table-hover" width="100%">
			            <tbody>
					    <tr>
					      <td width="40%">
						      <b>页 面 名 字：</b>
						      <input name="diy[diy_name]" data-rule="required" type="text" class="input-text" value="<?php echo C('diy_name',null,'data');?>">
						  </td>
					      <td>
						      <b>页 面 拼 音：</b>
						      <input name="diy[diy_pinyin]" type="text" class="input-text" value="<?php echo C('diy_pinyin',null,'data');?>">
						  </td>
						</tr>
						<tr>
							<td>
								<b>页 面 状 态：</b>
								<select data-toggle="selectpicker" name="diy[diy_status]">
								<?php 
								foreach ($statusArr as $k=>$v)
								{
									$select = str::CheckElse($k, C('diy_status',null,'data') , 'selected=""');
									echo '<option value="'.$k.'" '.$select.'>'.$v.'</option>';
								}
								?>
								</select>
							 </td>
							<td>
								<b>页面浏览量：</b><input size="10" name="diy[diy_read]" type="text" class="input-text" value="<?php echo C('diy_read',null,'data');?>">
							</td>
						</tr>
						<tr>
					      <td colspan="2"><b>页 面 内 容：</b>
							<?php echo Ueditor('width: 98%;height:250px' , 'diy[diy_content]' , C('diy_content',null,'data') , 'editor.operate_diy');?>
					    </tr>
					    <tr>
					      <td colspan="2"><button type="submit" class="btn-green" data-icon="save">提交</button></td>
						</tr>
			            </tbody>
			        </table>
				</div>

				<div class="tab-pane fade" id="<?php echo $cFun.$type;?>seo">
					<table class="table table-border table-bordered table-bg table-sort">
					    <tr>
					      <td width="150"><b>自定义标题：</b></td>
					      <td><input name="diy[diy_title]" type="text" class="input-text" size="40" value="<?php echo C('diy_title',null,'data');?>"></td>
						</tr>
					    <tr>
					      <td><b>自定义关键字：</b></td>
					      <td><input name="diy[diy_key]" type="text" class="input-text" size="40" value="<?php echo C('diy_key',null,'data');?>"></td>
						</tr>
					    <tr>
					      <td><b>自定义描述：</b></td>
					      <td><input name="diy[diy_desc]" type="text" class="input-text" size="40" value="<?php echo C('diy_desc',null,'data');?>"></td>
						</tr>
					</table>
				</div>

				<div class="tab-pane fade" id="<?php echo $cFun.$type;?>templates">
					<table class="table table-border table-bordered table-bg table-sort">
					    <tr>
					      <td width="150"><b>内容页模版：</b></td>
					      <td>
					      	<input id="diy_ctempid" name="diy[diy_ctempid]" type="hidden" value="<?php echo C('diy_ctempid',null,'data');?>">
					      	<input id="temp_cname" name="temp[temp_cname]" type="text" value="<?php echo C('cname',null,'temp');?>" data-toggle="lookup" data-url="index.php?c=system.templates.lookup&module=diy&page=content&name=cname&tid=ctempid&rename=diy" data-title="选择当前专题内容页模版" size="30" data-width="700" data-height="500">
					      	<a class="btn btn-default" href="index.php?c=system.templates.edit&t=add&module=diy&page=content&name=cname&tid=ctempid&rename=diy" data-toggle="dialog" data-title="上传新的单页内容页模版" data-width="540" data-height="450" ><i class="fa fa-cloud-upload">&nbsp;</i>上传模板</a>
					      </td>
						</tr>
					    <tr>
					      <td colspan="2">不指定模版，将会使用当前应用的主题的自带模版！</td>
						</tr>
					</table>
				</div>

				<div class="tab-pane fade" id="<?php echo $cFun.$type;?>html">
					<table class="table table-border table-bordered table-bg table-sort">
					    <tr>
					      <td width="150"><b>内容页静态路径：</b></td>
					      <td><input name="html[content]" value="<?php echo C('content',null,'html');?>" type="text" class="input-text" size="40"></td>
						</tr>
					</table>
				</div>
				
	        </div>
		</fieldset>
	</form>
</div>

<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-green" data-icon="save">保存</button></li>
    </ul>
</div>


<script type="text/javascript">
//页面唯一回调函数
function <?php echo $cFun;?>(json){
	var op = operateDiyListGetOp();
	var tabid = 'diy-list';
	op['id'] = tabid;

	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).navtab("reload",op);	// 刷新Tab页面
    $(this).navtab("switchTab",tabid);	// 切换Tab页面
}

//上传模版成功后
function <?php echo $cFun;?>upload_success(file,json,$element){
	json = $.parseJSON(json);
	if ( json.statusCode == 300){
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	}else{
		val = json.data.path.replace('../',"/");
		$element.siblings('input').val(val);
	}
}

$(document).ready(function(){
	$("#<?php echo $cFun.$type;?>templates_tab").click(function(){
		$(".bjui-lookup").css("line-height",'23px');
		$(".bjui-lookup").css("height",'23px');
	});
});
</script>