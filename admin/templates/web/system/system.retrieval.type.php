<div class="tableContent">
	<div>
	  <form name="<?php echo $cFun;?>Form" action="index.php?a=yes&c=system.retrieval&t=<?php echo $module;?>_type" data-reload="false" data-toggle="validate" method="post" data-callback="<?php echo $cFun;?>ajaxCallBack">
	  <table class="table table-border table-bordered table-bg table-sort">
	    <tr>
	      <td colspan="6"><strong><?php echo GetModuleName($module);?>检索分类设置</strong></td>
	    </tr>
	    <tr>
	      <td style="text-align: center;">分类ID</td>
	      <td style="text-align: center;width:200px">分类名字</td>
	      <td style="text-align: center;">分类状态</td>
	      <td style="text-align: center;">检索类型</td>
	      <td style="text-align: center;">参数名字</td>
	      <td style="text-align: center;">排序</td>
	    </tr>
	    
	    <?php 
	    if( $typeArr )
	    {
		    foreach ($typeArr as $k=>$v)
	  	 	{	
		    	$status = str::CheckElse( $v['type_status'] , 0 , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(1,'.$v['type_id'].')"><span style="color:red">'.$reMod->GetStatus('0').'</span></a>' , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(0,'.$v['type_id'].')"><span style="color:green">'.$reMod->GetStatus('1').'</span></a>');	
		    	echo '<tr>
	    		 	<td style="text-align: center;">'.$v['type_id'].'</td>
			      <td style="text-align: center;"><input type="hidden" name="type['.$v['type_id'].'][id][type_id]" value="'.$v['type_id'].'">
					<input name="type['.$v['type_id'].'][data][type_name]" value="'.$v['type_name'].'" type="text"/></td>
			      <td style="text-align: center;">'.$status.'</td>
			      <td style="text-align: center;">'.$reMod->GetTypeType($v['type_type']).'</td>
			      <td style="text-align: center;">'.$v['type_par'].'</td>
			      <td style="text-align: center;"><input name="type['.$v['type_id'].'][data][type_order]"  value="'.$v['type_order'].'"  type="text"/></td>
			    </tr>';
		    }
		}
		else
		{
			echo '<tr><td colspan="6" style="text-align:center">暂无数据!</td></tr>';
		}
	    ?>
	    </table>
	  
		<div class="bjui-pageFooter">
		    <ul>
		        <li><button type="button" class="btn-close btn btn-danger size-MINI radius"><i class="fa fa-times"></i> 关闭</button></li>
		        <li><button type="submit" class="btn btn-success size-MINI radius"><i class="fa fa-floppy-o"></i> 提交更改</button></li>
		    </ul>
		</div>
	</form>
	</div>
</div>



<script type="text/javascript">
function <?php echo $cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $cFun;?>Form]").serializeArray();
	return op;
}
//禁用使用条件
function <?php echo $cFun;?>StatusAjax(status,id)
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
	ajaxOptions['url'] = "index.php?a=yes&c=system.retrieval&t=<?php echo $module;?>_typestatus";
	ajaxOptions['confirmMsg'] = "确定要"+type+"分类吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//页面唯一回调函数
function <?php echo $cFun;?>ajaxCallBack(json){
	$(this).bjuiajax("ajaxDone",json);//显示处理结果
	$(this).dialog("closeCurrent");	//关闭当前dialog
	$(this).navtab("reload",<?php echo $cFun;?>GetOp());	//刷新当前Tab页面 
}
</script>