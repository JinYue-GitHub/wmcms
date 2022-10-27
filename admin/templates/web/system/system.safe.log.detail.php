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
      <td valign="top" width="150"><b>记录ID：</b></td>
      <td valign="top"><?php echo $data['login_id']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>登录账号：</b></td>
      <td valign="top"><?php echo $data['manager_name']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>登录状态：</b></td>
      <td valign="top"><?php echo $data['login_status_text']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>备注信息：</b></td>
      <td valign="top"><?php echo $data['login_remark']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>登录IP：</b></td>
      <td valign="top"><?php echo $data['login_ip']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>登录时间：</b></td>
      <td valign="top"><?php echo date("Y-m-d H:i:s" , $data['login_time'])?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>浏览器类型：</b></td>
      <td valign="top"><?php echo $data['login_browser']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>浏览器UA：</b></td>
      <td valign="top"><?php echo $data['login_ua']?></td>
	</tr>
</table>
</div>


<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">关闭</button></li>
    </ul>
</div>