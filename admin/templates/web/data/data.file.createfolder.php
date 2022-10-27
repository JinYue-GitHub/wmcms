<div class="bjui-pageContent">
<form action="index.php?a=yes&c=data.file&t=createfolder" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
<input type="hidden" name="path" value="<?php echo $path;?>">
<table class="table table-border table-bordered table-bg table-sort">
    <tr>
      <td valign="top"><b>新的文件夹名：</b></td>
      <td valign="top"><input name="newname" type="text" class="input-text" data-rules="required"></td>
	</tr>
</table>
</form>
</div>

<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close btn btn-danger"><i class="fa fa-times"></i> 关闭</button></li>
        <li><button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> 确定</button></li>
    </ul>
</div>

<script type="text/javascript">
function <?php echo $cFun;?>(json){
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).dialog("closeCurrent");//关闭
    $(this).navtab("reload");	// 刷新当前Tab页面
}
</script>