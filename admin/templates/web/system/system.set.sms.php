<div class="bjui-pageContent">
	<fieldset>
		<legend>短信服务设置</legend>
       		<ul class="nav nav-tabs" role="tablist" id="<?php echo $cFun.$type;?>smsLis">
            	<li class="active"><a href="#<?php echo $cFun.$type;?>temp" role="tab" data-toggle="tab">短信模版设置</a></li>
            </ul>
            
            <div class="bjui-pageHeader">
            	<div class="row cl pt-10 pb-10 pl-10">
            		<div class="f-l">
            			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="index.php?c=system.set.sms" method="post">
            				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
            				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
            				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
            				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                            <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
            				<select data-toggle="selectpicker" id="api_name" name="api_name" data-width="100">
                            	<option value="">全部渠道</option>
                            	<?php
                            	foreach ($apiList as $k=>$v)
                            	{
                            		$checked = str::CheckElse( $v['api_name'] , $apiName , 'selected=""' );
                            		echo '<option value="'.$v['api_name'].'" '.$checked.'>'.$v['api_title'].'</option>';
                            	}
                            	?>
                            </select>
            				<button type="submit" class="btn btn-warning radius" data-icon="search">查询</button>
                        	<a class="btn btn-secondary radius size-MINI" data-toggle="dialog" data-mask="true" data-title="添加短信模版" href="index.php?d=yes&c=system.set.sms.edit&t=add" data-width="650" data-height="440"><i class="fa fa-plus"></i> 添加短信模版</a> &nbsp;同类型的多个模版同时启用会随机选择一个开放的短信接口进行发送，短信配置请前往API管理。
            			</form>
            		</div>
            	</div>
            </div>
            
            <div class="tab-content">
		    	<div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>temp">
					
					<table class="table table-border table-bordered table-hover table-bg table-sort">
						<thead>
							<tr>
							<th width="5%">ID</th>
							<th width="5%">状态</th>
							<th width="8%">短信接口</th>
							<th width="8%">模版类型</th>
							<th width="12%">第三方签名</th>
							<th width="12%">第三方模版代码</th>
							<th>替换参数</th>
							<th width="15%">创建时间</th>
							<th width="15%">操作</th>
							</tr>
						</thead>
						
						<tbody>
						<?php
						if( $smsList )
						{
							$i = 1;
							foreach ($smsList as $k=>$v)
							{
								$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
								$status = str::CheckElse( $v['sms_status'] , 0 , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(1,\''.$v['sms_id'].'\',2)"><span style="color:red">'.$smsMod->statusArr[0].'</span></a>' , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(0,\''.$v['sms_id'].'\',2)"><span style="color:green">'.$smsMod->statusArr[1].'</span></a>');
								echo '<tr class="'.$cur.'">
										<td>'.$v['sms_id'].'</td>
										<td>'.$status.'</td>
										<td>'.$v['sms_api_name'].'</td>
										<td>'.$v['sms_type'].'</td>
										<td>'.$v['sms_sign'].'</td>
										<td>'.$v['sms_tempcode'].'</td>
										<td>'.$v['sms_params'].'</td>
										<td>'.date('Y-m-d H:i:s',$v['sms_time']).'</td>
										<td style="text-align: center;">
											<a class="btn btn-secondary radius size-MINI" data-mask="true" href="index.php?d=yes&c=system.set.sms.edit&t=edit&id='.$v['sms_id'].'"  data-toggle="dialog" data-title="修改短信模版" data-width="650" data-height="440" >编辑</a> 
											<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax(\''.$v['sms_id'].'\')">删除</a>
										</td>
									</tr>';
								$i++;
							}
						}
						else
						{
							echo '<tr><td colspan="9">暂时没有短信模版!</td></tr>';
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
//删除数据
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=system.sms&t=del";
	ajaxOptions['confirmMsg'] = "确定要删除所选的数据吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//禁用使用
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
	ajaxOptions['url'] = "index.php?a=yes&c=system.sms&t=status";
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