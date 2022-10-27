<?php
if( !is_array($data) )
{
	echo '<script type="text/javascript">$(document).ready(function(){$(this).alertmsg("info", "对不起，没有此条数据!");$(this).dialog("closeCurrent");});</script>';
	exit;
}
?>
<div class="bjui-pageContent">
<table class="table table-border table-bordered table-bg table-sort">
    <tr>
      <td valign="top" width="150"><b>ID：</b></td>
      <td valign="top"><?php echo $data['message_id']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>反馈类型：</b></td>
      <td valign="top"><?php echo $data['type_name']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>反馈用户：</b></td>
      <td valign="top"><a href="<?php echo $data['message_domain']?>" target="_blank"><?php echo $data['message_domain']?></a></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>反馈内容：</b></td>
      <td valign="top" style="word-break : break-all;"><?php echo str_replace("\r", '<br/>',$data['message_content']);?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>提交时间：</b></td>
      <td valign="top"><?php echo $data['message_addtime']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>处理状态：</b></td>
      <td valign="top"><?php echo $data['message_status_text']?></td>
	</tr>
	<?php 
	if( $data['message_status'] == 1)
	{
	?>
    <tr>
      <td valign="top" width="150"><b>官方回复：</b></td>
      <td valign="top"><?php echo str_replace("\r", '<br/>',$data['message_replay']);?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>回复时间：</b></td>
      <td valign="top"><?php echo $data['message_uptime']?></td>
	</tr>
	<?php
	}
	?>
</table>
</div>


<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">关闭</button></li>
    </ul>
</div>
<script type="text/javascript">
function <?php echo $cFun;?>(json){
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).dialog("closeCurrent");//关闭
    $(this).navtab("reload",systemConfigListGetOp());	// 刷新当前Tab页面
}
</script>