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
      <td valign="top"><?php echo $data['together_id']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>需求状态：</b></td>
      <td valign="top"><?php echo $data['together_status_text']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>提交站点：</b></td>
      <td valign="top"><a href="<?php echo $data['together_domain']?>" target="_blank"><?php echo $data['together_domain']?></a></td>
	</tr>
	<?php 
	if( $data['together_status'] == 6 || $data['together_status'] == 7)
	{
	?>
		<tr>
	      <td valign="top" width="150"><b>失败原因：</b></td>
	      <td valign="top"><?php echo $data['together_remark']?></td>
		</tr>
	<?php
	}
	else
	{
	?>
	    <tr>
	      <td valign="top" width="150"><b>需求站点信息：</b></td>
	      <td valign="top">总共需要站点响应：<?php echo $data['together_website']?>，目前<?php echo $data['together_need']?>个站点需求，<?php echo $data['together_noneed']?>个站点不需求</td>
		</tr>
	<?php
	}
	?>
	<tr>
      <td valign="top" width="150"><b>流程追踪：</b></td>
      <td valign="top" style="line-height: 25px;">
      	1.站点提交需求：<?php echo $data['together_addtime'];?><br/>
      	<?php
      	//官方评估需求
      	if( $data['together_status'] >= '1')
      	{
      		echo '2.官评需求开始：'.$data['together_status1_start_time'].'<br/>';
      	}
      	//众评中/官评失败
      	if( $data['together_status'] >= '2')
      	{
      		echo '3.官评需求结束：'.$data['together_status1_end_time'].'<br/>';
      		//官评通过
      		if( $data['together_status'] != '6' )
      		{
      			echo '4.众评需求开始：'.$data['together_status2_start_time'].'<br/>';
      		}
      	}
      	//开发中
      	if( $data['together_status'] >= '3' && $data['together_status'] != '6')
      	{
      		echo '5.众评需求结束：'.$data['together_status2_end_time'].'<br/>';
      		//众评通过
      		if( $data['together_status'] < '7' )
      		{
      			echo '6.官方开发开始：'.$data['together_status3_start_time'].'<br/>';
      		}
      	}
      	//测试中
      	if( $data['together_status'] >= '4' && $data['together_status'] < '6')
      	{
      		echo '7.官方开发结束：'.$data['together_status3_end_time'].'<br/>';
      		echo '8.内部测试开始：'.$data['together_status4_start_time'].'<br/>';
      	}
      	//已上线
      	if( $data['together_status'] == '5' )
      	{
      		echo '9.内部测试结束：'.$data['together_status4_end_time'].'<br/>';
      		echo '10.需求完成上线：'.$data['together_status5_time'].'<br/>';
      	}
		?>
      </td>
	</tr>
	
    <tr>
      <td valign="top" width="150"><b>需求标题：</b></td>
      <td valign="top"><?php echo $data['together_title']?></td>
	</tr>
    <tr>
      <td valign="top" width="150"><b>需求详情：</b></td>
      <td valign="top"><?php echo str_replace("\r", '<br/>', $data['together_desc']);?></td>
	</tr>
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