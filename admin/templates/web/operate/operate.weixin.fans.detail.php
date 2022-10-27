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
      <td valign="top" width="190"><b>记录ID：</b></td>
      <td valign="top"><?php echo $data['fans_id'];?></td>
	</tr>
    <tr>
      <td valign="top"><b>所属公众号：</b></td>
      <td valign="top"><?php echo $data['account_name'];?></td>
	</tr>
    <tr>
      <td valign="top"><b>用户Openid：</b></td>
      <td valign="top"><?php echo $data['fans_openid'];?></td>
	</tr>
    <tr>
      <td valign="top"><b>用户Unionid：</b></td>
      <td valign="top"><?php echo $data['fans_unionid'];?></td>
	</tr>
    <tr>
      <td valign="top"><b>关注状态：</b></td>
      <td valign="top"><?php echo $fansMod->subscribe[$data['fans_subscribe']];?></td>
	</tr>
    <tr>
      <td valign="top"><b>粉丝头像：</b></td>
      <td valign="top"><img src="<?php echo $data['fans_headimgurl'];?>"/></td>
	</tr>
    <tr>
      <td valign="top"><b>粉丝名字：</b></td>
      <td valign="top"><?php echo $data['fans_nickname'];?></td>
	</tr>
    <tr>
      <td valign="top"><b>性别：</b></td>
      <td valign="top"><?php echo $fansMod->sex[$data['fans_sex']];?></td>
	</tr>
    <tr>
      <td valign="top"><b>粉丝地区：</b></td>
      <td valign="top"><?php echo $data['fans_country'].' - '.$data['fans_province'].' - '.$data['fans_city'];?></td>
	</tr>
    <tr>
      <td valign="top"><b>备注信息：</b></td>
      <td valign="top"><?php echo $data['fans_remark'];?></td>
	</tr>
    <tr>
      <td valign="top"><b>其他信息：</b></td>
      <td valign="top" style="word-wrap:break-word; word-break:break-all;"><?php echo $data['fans_json'];?></td>
	</tr>
    <tr>
      <td valign="top"><b>关注时间：</b></td>
      <td valign="top"><?php echo date("Y-m-d H:i:s" ,$data['fans_subscribe_time']);?></td>
	</tr>
    <tr>
      <td valign="top"><b>取消关注时间：</b></td>
      <td valign="top"><?php echo date("Y-m-d H:i:s" ,$data['fans_unsubtime']);?></td>
	</tr>
    <tr>
      <td valign="top"><b>入库时间：</b></td>
      <td valign="top"><?php echo date("Y-m-d H:i:s" ,$data['fans_time']);?></td>
	</tr>
</table>
</div>


<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">关闭</button></li>
    </ul>
</div>