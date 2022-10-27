<style>
.autoreply_content_box{display:none}
</style>
<div class="bjui-pageContent">                    
    <form action="index.php?a=yes&c=operate.weixin.autoreply&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post"  <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="id[autoreply_id]" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>自动回复编辑</legend>
            	<div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>base">
					<table class="table table-border table-bordered table-bg table-sort">
		                <tr>
					      <td valign="top" width="170"><b>所属公众号：</b></td>
					      <td valign="top" width="300">
					      		<select data-toggle="selectpicker" name="autoreply[autoreply_account_id]">
		                       	<?php 
		                       	if( $aid > 0 )
		                       	{
		                       		echo '<option value="'.$aid.'" '.$select.'>'.$aName.'</option>';
		                       	}
		                       	else if( $accountList )
		                       	{
			                       	foreach ($accountList as $k=>$v)
			                       	{
			                       		$select = str::CheckElse($v['account_id'], C('autoreply_account_id',null,'data') , 'selected=""');
			                       		echo '<option value="'.$v['account_id'].'" '.$select.'>'.$v['account_name'].'</option>';
			                       	}
		                       	}
		                       	?>
		                        </select>
						  </td>
						  <td valign="top" width="220"><b>自动回复名字：</b></td>
					      <td valign="top"><input type="text" size="15" name="autoreply[autoreply_name]" value="<?php echo C('autoreply_name',null,'data');?>" data-rule="required"></td>
						</tr>
						<tr>
					      <td valign="top"><b>使用状态：</b></td>
					      <td valign="top">
				      		<select data-toggle="selectpicker" name="autoreply[autoreply_status]">
	                        	<option value="0" <?php echo str::CheckElse( C('autoreply_status',null,'data') , 0 , 'selected=""' );?>>禁用</option>
		                		<option value="1" <?php echo str::CheckElse( C('autoreply_status',null,'data') , 1 , 'selected=""' );?>>使用</option>
	                        </select>
						  </td>
					      <td valign="top"><b>默认设置：</b><br/>同时只能一条关注回复或默认回复</td>
					      <td valign="top">
				      		<select data-toggle="selectpicker" name="autoreply[autoreply_default]">
	                        	<option value="0" <?php echo str::CheckElse( C('autoreply_default',null,'data') , 0 , 'selected=""' );?>>不设置</option>
	                        	<option value="1" <?php echo str::CheckElse( C('autoreply_default',null,'data') , 1 , 'selected=""' );?>>关注回复</option>
		                		<option value="2" <?php echo str::CheckElse( C('autoreply_default',null,'data') , 2 , 'selected=""' );?>>默认回复</option>
	                        </select>
						  </td>
						</tr>
		                <tr>
		                    <td><b>匹配关键字：</b></td>
		                    <td><input type="text" size="20" name="autoreply[autoreply_key]" value="<?php echo C('autoreply_key',null,'data');?>" data-rule="required"></td>
						    <td valign="top"><b>匹配方式：</b></td>
						      <td valign="top">
					      		<select data-toggle="selectpicker" name="autoreply[autoreply_match]">
			                		<option value="1" <?php echo str::CheckElse( C('autoreply_match',null,'data') , 1 , 'selected=""' );?>>完全匹配</option>
			                		<option value="2" <?php echo str::CheckElse( C('autoreply_match',null,'data') , 2 , 'selected=""' );?>>全文匹配</option>
			                		<option value="3" <?php echo str::CheckElse( C('autoreply_match',null,'data') , 3 , 'selected=""' );?>>开始匹配</option>
			                		<option value="4" <?php echo str::CheckElse( C('autoreply_match',null,'data') , 4 , 'selected=""' );?>>结尾匹配</option>
		                        </select>
							  </td>
		                </tr>
		                <tr>
		                    <td><b>回复类型：</b></td>
		                    <td valign="top" colspan="3">
					      		<select id="<?php echo $cFun.$type;?>autoreply_type" data-toggle="selectpicker" name="autoreply[autoreply_type]">
		                        <?php 
								foreach ($replyMod->type as $k=>$v)
		                       	{
		                       		$select = str::CheckElse($k, C('autoreply_type',null,'data') , 'selected=""');
		                       		echo '<option value="'.$k.'" '.$select.'>'.$v.'</option>';
		                       	}
		                       	?>
		                       	</select>
							</td>
		                </tr>
		                <tr class="autoreply_content_box" id="<?php echo $cFun.$type;?>textBox">
		                    <td><b>回复内容：</b></td>
		                    <td valign="top" colspan="3">
		                    	<textarea cols="60" rows="10" name="autoreply[autoreply_content]" data-rule="required"><?php echo C('autoreply_content',null,'data');?></textarea>
		                    </td>
		                </tr>
		                <tr class="autoreply_content_box" id="<?php echo $cFun.$type;?>imageBox">
		                    <td><b>素材ID：</b></td>
		                    <td valign="top" colspan="3">
		                    	<input type="text" size="30" data-toggle="lookup" data-url="index.php?c=operate.weixin.media.lookup&st=a&aid=<?php echo $aid;?>" data-width="820" data-title="选择素材" id="<?php echo $cFun.$type;?>autoreply_media_id" name="autoreply[autoreply_media_id]"  value="<?php echo C('autoreply_media_id',null,'data');?>">
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
	var op = operateWeixinAutoreplyListGetOp();
	var tabid = 'weixin-autoreply_list';
	op['id'] = tabid;
	
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).navtab("reload",op);	// 刷新Tab页面
    $(this).navtab("switchTab",tabid);	// 切换Tab页面
}
function <?php echo $cFun.$type;?>HideShow(showType){
	$('#<?php echo $cFun.$type;?>imageBox').hide();
	$('#<?php echo $cFun.$type;?>textBox').hide();
	if( showType=='image'){
	    $('#<?php echo $cFun.$type;?>imageBox').show();
	}else{
	    $('#<?php echo $cFun.$type;?>textBox').show();
	}
}

$(function(){
	<?php echo $cFun.$type;?>HideShow('<?php echo C('autoreply_type',null,'data');?>');
	
	$('#<?php echo $cFun.$type;?>autoreply_type').change(function(){
		<?php echo $cFun.$type;?>HideShow($(this).val());
	})
	
	//查找回调
	$(':input').on('afterchange.bjui.lookup', function(e, data) {
		$('#<?php echo $cFun.$type;?>autoreply_media_id').val(data.value);
	})
})
</script>