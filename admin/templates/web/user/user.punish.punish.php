<div class="bjui-pageContent">
<form action="index.php?a=yes&c=user.punish&t=punish" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
<table class="table table-border table-bordered table-bg table-sort">
	<tr>
      <td valign="top" width="120"><b>处罚用户id：</b></td>
      <td valign="top">
			<input type="text" name="punish[punish_uid]" value="<?php echo $uid?>" data-rule="required">
		</td>
	</tr>
    <tr>
      <td valign="top" width="120"><b>处罚类型：</b></td>
      <td valign="top">
      	<select data-toggle="selectpicker" name="punish[punish_type]" id="<?php echo $cFun;?>settype">
		<?php
		foreach ($typeArr as $k=>$v)
		{
			$select = str::CheckElse( $k, $st , 'selected=""');
			echo '<option value="'.$k.'" '.$select.'>'.$v.'</option>';
		}
		?>
      	</select>
      </td>
	</tr>
    <tr>
      <td valign="top" width="120"><b>处罚原因：</b></td>
      <td valign="top" colspan="3"><input name="punish[punish_remark]" type="text" class="input-text" value="系统处罚"></td>
	</tr>
    <tr>
		<td valign="top" width="120"><b>处罚结束时间：</b></td>
		<td valign="top">
		<input type="text" name="punish[punish_endtime]" value="<?php echo $endTime;?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
	  </td>
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
//页面唯一回调函数
function <?php echo $cFun;?>(json){
	var op = userUserListGetOp();
	var tabid = 'user-list';
	op['id'] = tabid;
	if( json.statusCode == '300' ){
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	}else{
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	    $(this).navtab("reload",op);	// 刷新Tab页面
	    $(this).navtab("switchTab",tabid);	// 切换Tab页面
	}
}
</script>