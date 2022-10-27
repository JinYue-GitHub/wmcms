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
      <td valign="top"><?php echo $data['operation_id']?></td>
	</tr>
    <tr>
      <td valign="top"><b>管理员：</b></td>
      <td valign="top"><?php echo $data['manager_name']?></td>
	</tr>
    <tr>
      <td valign="top"><b>操作的模块：</b></td>
      <td valign="top"><?php echo $data['operation_module']?></td>
	</tr>
    <tr>
      <td valign="top"><b>操作的类型：</b></td>
      <td valign="top"><?php echo $data['operation_type']?></td>
	</tr>
    <tr>
      <td valign="top"><b>操作的备注：</b></td>
      <td valign="top"><?php echo $data['operation_remark']?></td>
	</tr>
    <tr>
      <td valign="top"><b>操作的时间：</b></td>
      <td valign="top"><?php echo date("Y-m-d H:i:s" ,$data['operation_time'])?></td>
	</tr>
    <tr>
      <td valign="top"><b>操作的表：</b></td>
      <td valign="top"><?php echo $data['operation_table']?></td>
	</tr>
    <tr>
      <td valign="top"><b>操作的数据：</b></td>
      <td valign="top">
      <?php
      if( $operation )
      {
      	foreach ( $operation as $key=>$val)
      	{
      		echo '修改【'.$val[0][0].'】等于【'.$val[0][1].'】的数据：<br/>';
      		if( is_array(GetKey($val,'1')) )
      		{
	      		foreach ( $val[1] as $k=>$v)
	      		{
	      			echo '将字段【'.$v[0].'】的值修改为【'.$v[1].'】<br/>';
	      		}
	      		echo '<br/>';
      		}
      	}
      }
      ?>
      </td>
	</tr>
    <tr>
      <td valign="top"><b>数据的镜像：</b></td>
      <td valign="top"><?php echo $data['operation_backdata']?></td>
	</tr>
    <tr>
      <td valign="top"><b>请求时间：</b></td>
      <td valign="top"><?php echo date("Y-m-d H:i:s" , $data['operation_time'])?></td>
	</tr>
    <tr></tr>
</table>
</div>


<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">关闭</button></li>
        <li><button type="submit" class="btn-green" data-icon="save" disabled>回滚数据</button></li>
    </ul>
</div>