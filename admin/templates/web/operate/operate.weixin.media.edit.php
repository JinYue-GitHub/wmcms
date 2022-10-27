<div class="bjui-pageContent">	                       
    <form action="index.php?a=yes&c=operate.weixin.media&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post"  <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="file_id" type="hidden" class="input-text" value="0">
	<input name="media[media_type]" type="hidden" class="input-text" value="image">
		<fieldset>
			<legend>上传新的素材</legend>
            	<div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>base">
					<table class="table table-border table-bordered table-bg table-sort">
		                 <tr>
					      <td valign="top" width="150"><b>所属公众号：</b></td>
					      <td valign="top">
					      		<select data-toggle="selectpicker" name="media[media_account_id]" data-rule="required">
		                       	<?php 
		                       	if( $aid > 0 )
		                       	{
		                       		echo '<option value="'.$aid.'" '.$select.'>'.$aName.'</option>';
		                       	}
		                       	else if( $accountList )
		                       	{
			                       	foreach ($accountList as $k=>$v)
			                       	{
			                       		$select = str::CheckElse($v['account_id'], C('menu_account_id',null,'data') , 'selected=""');
			                       		echo '<option value="'.$v['account_id'].'" '.$select.'>'.$v['account_name'].'</option>';
			                       	}
		                       	}
		                       	?>
		                        </select>
						  </td>
						</tr>
						<tr>
					      <td valign="top" width="150"><b>素材有效期：</b></td>
					      <td valign="top">
					      		<select data-toggle="selectpicker" name="media[media_islong]" data-rule="required">
		                       		<option value="1">永久有效</option>
		                       		<option value="0">临时-3天有效</option>
		                        </select>
						  </td>
						</tr>
						<tr>
		                    <td width="150"><b>上传素材<br/></b></td>
		                    <td>
		                    	<input type="text" name="media[media_filepath]" size="20">
								<span class="upload" data-uploader="index.php?a=yes&c=upload&t=weixin_media" data-on-upload-success="<?php echo $cFun.$type;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif;*.mpg" data-toggle="upload" data-icon="cloud-upload"></span>
		                    </td>
		                </tr>
						<tr>
					      <td valign="top" width="150"><b>素材名字：</b></td>
					      <td valign="top">
					      	<input type="text" size="20" name="media[media_filename]" value="" data-rule="required">
						  </td>
						</tr>
		            </tbody>
		        </table>
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


<script>
//页面唯一回调函数
function <?php echo $cFun;?>(json){
	var op = operateWeixinMediaListGetOp();
	var tabid = 'weixin-media_list';
	op['id'] = tabid;
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).navtab("reload",op);	// 刷新Tab页面
}
//上传素材
function <?php echo $cFun.$type;?>upload_success(file,json,$element){
	json = $.parseJSON(json);
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	if ( json.statusCode == 200){
		val = json.data.path.replace('../',"/");
		$element.siblings('input').val(val);
		$('[name=file_id]').val(json.data.fileid);
		$('[name=media\\[media_filename\\]]').val(json.data.name_noext);
	}
}
</script>