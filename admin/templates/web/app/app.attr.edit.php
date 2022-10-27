<div class="bjui-pageContent">
<form action="index.php?a=yes&c=app.attr&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
<input type="hidden" name="id[attr_id]" value="<?php echo $id;?>">
<table class="table table-border table-bordered table-bg table-sort">
    <tr>
      <td valign="top" width="100"><b>资料类型：</b></td>
      <td valign="top">
      	<select data-toggle="selectpicker" name="attr[attr_type]" data-width="100">
                	<?php
                	foreach ($attrArr as $k=>$v)
                	{
                		$checked = str::CheckElse( $k , C('attr_type',null,'data') , 'selected=""' );
                		echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
                	}
                	?>
        </select>
     </td>
	</tr>
    <tr>
      <td valign="top"><b>资料名称：</b></td>
      <td valign="top"><input name="attr[attr_name]" value="<?php echo C('attr_name',null,'data')?>" type="text" class="input-text" data-rules="required" size="10"></td>
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