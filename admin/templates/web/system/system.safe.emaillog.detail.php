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
      <td valign="top"><?php echo $data['log_id']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>发送状态：</b></td>
      <td valign="top"><?php echo $emailMod->logStatus[$data['log_status']]?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>发信账户：</b></td>
      <td valign="top"><?php echo $data['log_sendmail']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>收信账户：</b></td>
      <td valign="top"><?php echo $data['log_getmail']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>邮件主题：</b></td>
      <td valign="top"><?php echo str::Escape($data['log_title'])?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>邮件正文：</b></td>
      <td valign="top"><?php echo str::Escape($data['log_content'])?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>备注：</b></td>
      <td valign="top"><?php echo $data['log_remark']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>任务创建时间：</b></td>
      <td valign="top"><?php echo date("Y-m-d H:i:s" , $data['log_time'])?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>邮件发送时间：</b></td>
      <td valign="top"><?php echo date("Y-m-d H:i:s" , $data['log_sendtime'])?></td>
	</tr>
</table>
</div>


<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">关闭</button></li>
    </ul>
</div>