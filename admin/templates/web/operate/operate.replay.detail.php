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
      <td valign="top"><?php echo $data['replay_id']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>评论模块数据：</b></td>
      <td valign="top"><?php echo GetModuleName($data['replay_module'])?> - 《<?php echo $tableSer->GetContentName($data['replay_module'],$data['replay_cid'])?>》</td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>评论状态：</b></td>
      <td valign="top"><?php echo $data['replay_status']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>用户昵称(id)：</b></td>
      <td valign="top"><?php echo $data['replay_nickname']?>(id:<?php echo $data['replay_uid']?>)</td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>评论内容：</b></td>
      <td valign="top"><?php echo $data['replay_content']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>评论IP：</b></td>
      <td valign="top"><?php echo $data['replay_ip']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>评论时间：</b></td>
      <td valign="top"><?php echo date("Y-m-d H:i:s" , $data['replay_time'])?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>顶踩数据：</b></td>
      <td valign="top">顶：<?php echo $data['replay_ding']?> 踩：<?php echo $data['replay_cai']?></td>
	</tr>
</table>
</div>


<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">关闭</button></li>
    </ul>
</div>