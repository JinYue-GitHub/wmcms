<div class="bjui-pageContent">
	<fieldset>
		<legend>发信服务设置</legend>
       		<ul class="nav nav-tabs" role="tablist" id="<?php echo $cFun.$type;?>emailLis">
            	<li <?php if(Request('tab')!='temp'){echo 'class="active"';}?>><a href="#<?php echo $cFun.$type;?>account" role="tab" data-toggle="tab">邮件服务设置</a></li>
            	<li <?php if(Request('tab')=='temp'){echo 'class="active"';}?>><a href="#<?php echo $cFun.$type;?>temp" role="tab" data-toggle="tab">邮件模版设置</a></li>
            </ul>
            
            <div class="tab-content"> 
            	<div class="tab-pane fade <?php if(Request('tab')!='temp'){echo 'active in';}?>" id="<?php echo $cFun.$type;?>account">
					<a class="btn btn-secondary radius size-MINI" style="margin-bottom:10px;" data-toggle="dialog" data-mask="true" data-title="添加服务" href="index.php?d=yes&c=system.email.email.edit&t=add" data-width="450" data-height="420" ><i class="fa fa-plus"></i> 添加服务</a> &nbsp;&nbsp;&nbsp;&nbsp;多个smtp服务器会随机选择一个发送。
					<table class="table table-border table-bordered table-hover table-bg table-sort">
						<thead>
							<tr>
							<th width="5%">ID</th>
							<th width="5%">状态</th>
							<th width="10%">发送方式</th>
							<th width="12%">邮件服务器</th>
							<th width="5%">端口</th>
							<th width="10%">登录账号</th>
							<th width="10%">发信账户</th>
							<th width="12%">操作</th>
							</tr>
						</thead>
						
						<tbody>
						<?php
						if( $emailList )
						{
							$i = 1;
							foreach ($emailList as $k=>$v)
							{
								$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
								$status = str::CheckElse( $v['email_status'] , 0 , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(1,'.$v['email_id'].',1)"><span style="color:red">'.$emailMod->status[0].'</span></a>' , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(0,'.$v['email_id'].',1)"><span style="color:green">'.$emailMod->status[1].'</span></a>');
								echo '<tr class="'.$cur.'">
										<td>'.$v['email_id'].'</td>
										<td>'.$status.'</td>
										<td>'.$emailMod->sendType[$v['email_type']].'</td>
										<td>'.$v['email_smtp'].'</td>
										<td>'.$v['email_port'].'</td>
										<td>'.$v['email_name'].'</td>
										<td>'.$v['email_send'].'</td>
										<td style="text-align: center;">
											<a class="btn btn-success radius" onclick="'.$cFun.'TestAjax('.$v['email_id'].')">测试</a>
											<a class="btn btn-secondary radius size-MINI" data-mask="true" href="index.php?d=yes&c=system.email.email.edit&t=edit&id='.$v['email_id'].'"  data-toggle="dialog" data-title="修改邮箱信息" data-width="450" data-height="420" >编辑</a> 
											<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['email_id'].',1)">删除</a>
										</td>
									</tr>';
								$i++;
							}
						}
						else
						{
							echo '<tr><td colspan="8">暂时没有配置邮件服务!</td></tr>';
						}
						?>
						</tbody>
					</table>
		    	</div>
		    	
		    	<div class="tab-pane fade <?php if(Request('tab')=='temp'){echo 'active in';}?>"" id="<?php echo $cFun.$type;?>temp">
					<a class="btn btn-secondary radius size-MINI" style="margin-bottom:10px;" data-toggle="dialog" data-mask="true" data-title="添加模版" href="index.php?d=yes&c=system.email.temp.edit&t=add" data-width="850" data-height="670" ><i class="fa fa-plus"></i> 添加模版</a>
					<table class="table table-border table-bordered table-hover table-bg table-sort">
						<thead>
							<tr>
							<th width="15%">ID</th>
							<th width="10%">状态</th>
							<th width="20%">模版名字</th>
							<th width="30%">模版描述</th>
							<th width="10%">操作</th>
							</tr>
						</thead>
						
						<tbody>
						<?php
						if( $tempList )
						{
							$i = 1;
							foreach ($tempList as $k=>$v)
							{
								$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
								$status = str::CheckElse( $v['temp_status'] , 0 , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(1,\''.$v['temp_id'].'\',2)"><span style="color:red">'.$emailMod->status[0].'</span></a>' , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(0,\''.$v['temp_id'].'\',2)"><span style="color:green">'.$emailMod->status[1].'</span></a>');
								echo '<tr class="'.$cur.'">
										<td>'.$v['temp_id'].'</td>
										<td>'.$status.'</td>
										<td>'.$v['temp_name'].'</td>
										<td>'.$v['temp_desc'].'</td>
										<td style="text-align: center;">
											<a class="btn btn-secondary radius size-MINI" data-mask="true" href="index.php?d=yes&c=system.email.temp.edit&t=edit&id='.$v['temp_id'].'"  data-toggle="dialog" data-title="修改模版信息" data-width="850" data-height="670" >编辑</a> 
											<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax(\''.$v['temp_id'].'\',2)">删除</a>
										</td>
									</tr>';
								$i++;
							}
						}
						else
						{
							echo '<tr><td colspan="5">暂时没有模版!</td></tr>';
						}
						?>
						</tbody>
					</table>
		    	</div>
	    	</div>
	</fieldset>
</div>

<script type="text/javascript">
var tab;
//页面唯一op获取函数
function <?php echo $cFun;?>GetOp(tab){
	var op=new Array();
	var ajaxData=new Object();
	ajaxData.tab = tab;
	op['type'] = 'POST';
	op['data'] = ajaxData;
	return op;
}
//测试邮件
function <?php echo $cFun;?>TestAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['loadingmask'] = true;
	ajaxOptions['url'] = "index.php?a=yes&c=system.email.email&t=test";
	ajaxOptions['confirmMsg'] = "确定配置正确开始测试吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//删除数据
function <?php echo $cFun;?>delAjax(id,type)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	if(type==1){
		tab='email';
		ajaxOptions['url'] = "index.php?a=yes&c=system.email.email&t=del";
	}else{
		tab='temp';
		ajaxOptions['url'] = "index.php?a=yes&c=system.email.temp&t=del";
	}
	ajaxOptions['confirmMsg'] = "确定要删除所选的数据吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//禁用使用站点
function <?php echo $cFun;?>StatusAjax(status,id,stt)
{
	var type;
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	switch(status)
	{
		case 0:
			type = "禁用";
	  		break;
	  		
		default:
			type = "使用";
	  		break;
	}
	
	ajaxData.id = id;
	ajaxData.status = status;
	ajaxOptions['data'] = ajaxData;
	if(stt==1){
		ajaxOptions['url'] = "index.php?a=yes&c=system.email.email&t=status";
	}else{
		ajaxOptions['url'] = "index.php?a=yes&c=system.email.temp&t=status";
	}
	ajaxOptions['confirmMsg'] = "确定要"+type+"吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//页面唯一回调函数
function <?php echo $cFun;?>ajaxCallBack(json){
	$(this).bjuiajax("ajaxDone",json);//显示处理结果
	$(this).dialog("closeCurrent");	//关闭当前dialog
	if(json.statusCode==200){
		$(this).navtab("reload",<?php echo $cFun;?>GetOp(tab));	//刷新当前Tab页面 
	}
}
</script>