<div class="bjui-pageContent">
<form action="index.php?a=yes&c=data.file&t=movefile" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
<input type="hidden" name="oldpath" value="<?php echo $path;?>">
<input type="hidden" name="file" value="<?php echo $file;?>">
<table class="table table-border table-bordered table-bg table-sort">
    <tr>
      <td valign="top" colspan="2"><b>提示:新位置前面不加'/'表示相对于当前位置，加'/'表示相对于根目录。</td>
	</tr>
    <tr>
      <td valign="top" width="100"><b>移动的文件：</b></td>
      <td valign="top"><?php echo $file;?></td>
	</tr>
    <tr>
      <td valign="top" width="100"><b>移动到：</b></td>
      <td valign="top"><input name="newpath" type="text" size="30" class="input-text"></td>
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