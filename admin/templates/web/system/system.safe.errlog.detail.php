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
      <td valign="top"><?php echo $data['errlog_id']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>访问IP：</b></td>
      <td valign="top"><?php echo $data['errlog_ip']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>错误地址：</b></td>
      <td valign="top"><?php echo str::Escape($data['errlog_url'])?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>SQL代码：</b></td>
      <td valign="top"><?php echo $data['errlog_state']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>错误代码：</b></td>
      <td valign="top"><?php echo $data['errlog_code']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>详细信息：</b></td>
      <td valign="top"><?php echo str::Escape($data['errlog_msg'])?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>错误的sql：</b></td>
      <td valign="top"><?php echo str::Escape($data['errlog_sql'])?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>错误时间：</b></td>
      <td valign="top"><?php echo date("Y-m-d H:i:s" , $data['errlog_time'])?></td>
	</tr>
</table>
</div>


<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">关闭</button></li>
    </ul>
</div>