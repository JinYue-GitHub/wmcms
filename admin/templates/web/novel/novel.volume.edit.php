<div class="bjui-pageContent">
    <form action="index.php?a=yes&c=novel.volume&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="volume_id" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>小说分卷编辑</legend>
            <div class="tab-pane fade active in">
				<table class="table table-border table-bordered table-bg table-sort">
					<tr>
					    <td valign="top" width="250"><b>所属小说：</b></td>
						<td valign="top">
                         	<input data-rule="小说名字:required;" data-url="index.php?a=yes&c=novel.novel&t=search&st=name&rt=key" data-toggle="autocomplete" name="name" type="text" class="input-text" value="<?php echo C('novel_name',null,'data');?>">
                        </td>
					</tr>
					<tr>
					    <td valign="top"><b>小说作者：(有同名小说的时候必填)</b></td>
						<td valign="top">
                         	<input data-url="index.php?a=yes&c=novel.novel&t=search&st=author&rt=key" data-toggle="autocomplete" name="author" type="text" class="input-text" value="<?php echo C('novel_author',null,'data');?>">
                        </td>
					</tr>
					<tr>
					    <td valign="top"><b>分卷名字：</b></td>
						<td valign="top"><input data-rule="分卷名字:required;" name="volume[volume_name]" type="text" class="input-text" value="<?php echo C('volume_name',null,'data');?>"></td>
					</tr>
				    <tr>
				      <td valign="top"><b>分卷排序：</b></td>
				      <td valign="top"><input data-rule="分卷排序:required;digits" name="volume[volume_order]" type="text" class="input-text" value="<?php echo C('volume_order',null,'data');?>"></td>
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
	var op = novelVolumeListGetOp();
	var tabid = 'novel-volume-list';
	op['id'] = tabid;

	if( json.statusCode == 300 ){
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	}else{
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	    $(this).dialog("closeCurrent");//关闭
	    $(this).navtab("reload",op);	// 刷新Tab页面
	}
}
</script>
