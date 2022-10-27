<div class="bjui-pageContent">
    <form action="index.php?a=yes&c=article.author&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="id[author_id]" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>文章作者来源编辑</legend>
            	<div class="tab-pane fade active in">
					<table class="table table-border table-bordered table-bg table-sort">
					<tr>
				      <td valign="top"><b>数据类型：</b></td>
				      <td valign="top">
				      	<select data-toggle="selectpicker" name="author[author_type]" data-width="100">
		                	<?php
		                	foreach ($authorArr as $k=>$v)
		                	{
		                		$checked = str::CheckElse( $k , C('author_type',null,'data') , 'selected=""' );
		                		echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
		                	}
		                	?>
		                </select>
		               </td>
					</tr>
				    <tr>
				      <td valign="top"><b>数据名字：</b></td>
				      <td valign="top"><input data-rule="数据名字:required;" name="author[author_name]" type="text" class="input-text" value="<?php echo C('author_name',null,'data');?>"></td>
					</tr>
				    <tr>
				      <td valign="top"><b>是否默认：</b></td>
				      <td valign="top">
				      	<select data-toggle="selectpicker" name="author[author_default]" data-width="100">
				      		<option value="0" <?php if( C('author_default',null,'data')== '0'){echo 'selected=""';}?>>非默认</option>
				      		<option value="1" <?php if( C('author_default',null,'data')== '1'){echo 'selected=""';}?>>默认数据</option>
		                </select>
		             </td>
					</tr>
				    <tr>
				      <td valign="top"><b>数据条数：</b></td>
				      <td valign="top"><input data-rule="digits;" data-rule="required;number;" name="author[author_data]" type="text" class="input-text" value="<?php echo C('author_data',null,'data');?>"></td>
					</tr>
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
function <?php echo $cFun;?>(json){
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).dialog("closeCurrent");//关闭
    $(this).navtab("reload",articleAuthorListGetOp());	// 刷新当前Tab页面
}
</script>
