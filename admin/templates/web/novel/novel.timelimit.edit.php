<div class="bjui-pageContent">
    <form action="index.php?a=yes&c=novel.timelimit&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="timelimit_id" type="hidden" class="input-text" value="<?php echo $id;?>">
	<input name="timelimit[timelimit_nid]" type="hidden" class="input-text" value="<?php echo C('timelimit_nid',null,'data');?>">
		<fieldset>
			<legend>小说限时免费编辑</legend>
            <div class="tab-pane fade active in">
				<table class="table table-border table-bordered table-bg table-sort">
					<tr>
					    <td valign="top" width="30%"><b>小说分类：</b></td>
						<td valign="top"><?php echo C('type_name',null,'data');?></td>
					</tr>
					<tr>
					    <td valign="top"><b>所属小说：</b></td>
						<td valign="top"><?php echo C('novel_name',null,'data');?></td>
					</tr>
					<tr>
					    <td valign="top"><b>小说作者：</b></td>
						<td valign="top"><?php echo C('novel_author',null,'data');?></td>
					</tr>
				    <tr>
				      <td valign="top"><b>是否免费：</b></td>
				      <td valign="top">
		              	<select data-toggle="selectpicker" name="timelimit[timelimit_status]">
                        	<option value="1" <?php if(C('timelimit_status',null,'data') == '1'){ echo 'selected=""';}?>>限时免费</option>
                            <option value="0" <?php if(C('timelimit_status',null,'data') == '0'){ echo 'selected=""';}?>>暂停免费</option>
                     	</select>
                     </td>
					</tr>
	            	<tr>
					  <td valign="top"><b>免费开始时间：</b></td>
				      <td valign="top">
						<input type="text" name="timelimit[timelimit_starttime]" value="<?php echo date('Y-m-d H:i:s',C('timelimit_starttime',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
	            	  </td>
					</tr>
	            	<tr>
					  <td valign="top"><b>免费结束时间：</b></td>
				      <td valign="top">
						<input type="text" name="timelimit[timelimit_endtime]" value="<?php echo date('Y-m-d H:i:s',C('timelimit_endtime',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
	            	  </td>
					</tr>
					<tr>
					    <td valign="top"><b>显示顺序：</b></td>
						<td valign="top"><input size="10" name="timelimit[timelimit_order]" type="text" class="input-text" value="<?php echo C('timelimit_order',null,'data');?>"></td>
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
    $(this).navtab("reload",novelTimelimitListGetOp());	// 刷新当前Tab页面
}
</script>