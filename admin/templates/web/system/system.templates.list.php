<style>
.table tr {
    height: 35px;
}
thead th {
	text-align: center;
}
.list-tool{
	margin-bottom:5px;
}
</style>
<div class="bjui-pageHeader">
	<div class="row cl pt-10 pl-10">
		<div class="list-tool pl-15">
            <span >快捷操作：</span>
			<a href="index.php?a=yes&c=system.templates.templates&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $type.$cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
			<a href="index.php?a=yes&c=system.templates.templates&t=clear" data-toggle="doajax" data-confirm-msg="此操作不可撤回确定要清空吗？" class="btn btn-danger size-MINI radius"><i class="fa fa-trash-o"></i> 清空记录</a>
		</div>
		<div class="list-tool">
			<form id="pagerForm" name="<?php echo $cFun.$type;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
                
                <select data-toggle="selectpicker" id="module" name="module" data-width="100">
                	<option value="">全部模块</option>
                	<?php
                	foreach ($moduleArr as $k=>$v)
                	{
                		$checked = str::CheckElse( $k , $module , 'selected=""' );
                		echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
                	}
                	?>
                </select>
                
                <input type="text" placeholder="请输入关键字" value="<?php echo $name;?>" name="name" size="15">
                
				<button type="submit" class="btn btn-warning radius" data-icon="search">查询</button>
				<a id="<?php echo $type.$cFun;?>refresh" class="btn size-MINI btn-primary radius"><i class="fa fa-refresh fa-spin"></i> 刷新</a>
			</form>
		</div>
	</div>
</div>

<div class="bjui-pageContent">
	<table class="table table-border table-bordered table-hover table-bg table-sort">
		<thead>
			<tr>
			<th style="text-align: center;" width="2%;"><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
			<th width="5%" data-order-field="temp_id">ID</th>
			<th>模版名字</th>
			<th width="10%" data-order-field="temp_module">所属模块</th>
			<th width="10%" data-order-field="temp_type">所属页面</th>
			<th width="8%">简版</th>
			<th width="8%">3G</th>
			<th width="8%">触屏</th>
            <th width="8%">电脑</th>
            <th width="10%">操作</th>
            </tr>
		</thead>
		<tbody>
			<?php
			if( $tempArr )
			{
				$i = 1;
				foreach ($tempArr as $k=>$v)
				{
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					$temp1 = str::CheckElse( $v['temp_temp1'] , '' , '<span style="color:#B9B6B6">无模版</span>' , '<span style="color:green">有模版</span>');
					$temp2 = str::CheckElse( $v['temp_temp2'] , '' , '<span style="color:#B9B6B6">无模版</span>' , '<span style="color:green">有模版</span>');
					$temp3 = str::CheckElse( $v['temp_temp3'] , '' , '<span style="color:#B9B6B6">无模版</span>' , '<span style="color:green">有模版</span>');
					$temp4 = str::CheckElse( $v['temp_temp4'] , '' , '<span style="color:#B9B6B6">无模版</span>' , '<span style="color:green">有模版</span>');
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['temp_id'].'"></td>
							<td style="text-align: center;">'.$v['temp_id'].'</td>
							<td style="text-align: center;">'.$v['temp_name'].'</td>
							<td style="text-align: center;">'.GetModuleName($v['temp_module']).'</td>
							<td style="text-align: center;">'.$tempSer->GetTempType($v['temp_module'],$v['temp_type']).'</td>
							<td style="text-align: center;">'.$temp1.'</td>
							<td style="text-align: center;">'.$temp2.'</td>
							<td style="text-align: center;">'.$temp3.'</td>
							<td style="text-align: center;">'.$temp4.'</td>
							<td style="text-align: center;">
                				<button type="button" class="btn btn-warning radius" data-toggle="dropdown">更多<span class="caret"></span></button>
								<ul class="dropdown-menu" style="width:80px">
							      <li><a data-toggle="dialog" data-title="编辑模版" data-width="540" data-mask="true" data-height="450" href="index.php?d=yes&c=system.templates.edit&t=edit&id='.$v['temp_id'].'">编辑内容</a></li>
							      <li><a data-toggle="dialog" data-title="'.$v['temp_name'].'的模版资源管理" data-width="440" data-mask="true" data-height="260" href="index.php?d=yes&c=system.templates.static&id='.$v['temp_id'].'">资源管理</a></li>
								</ul>
								<a class="btn btn-danger radius" onclick="'.$type.$cFun.'delAjax('.$v['temp_id'].')">删除</a>
							</td>
				          </tr>';
					$i++;
				}
			}
			else
			{
				echo '<script type="text/javascript">$(document).ready(function(){$(this).alertmsg("info", "没有数据了!")});</script>';
			}
			?>
		</tbody>
	</table>
</div>

<div class="bjui-pageFooter">
    <div class="pages">
        <span>每页&nbsp;</span>
        <div class="selectPagesize">
            <select data-toggle="selectpicker" data-toggle-change="changepagesize">
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="60">60</option>
                <option value="120">120</option>
            </select>
        </div>
        <span>&nbsp;条，共 <?php echo $total;?> 条</span>
    </div>
    <div class="pagination-box" data-toggle="pagination" data-total="<?php echo $total;?>" data-page-size="<?php echo $pageSize;?>" data-pageCurrent="<?php echo $pageCurrent?>"></div>
</div>


<script type="text/javascript">
//页面唯一op获取函数
function <?php echo $type.$cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $type.$cFun;?>Form]").serializeArray();
	return op;
}
//删除数据
function <?php echo $type.$cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $type.$cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=system.templates.templates&t=del";
	ajaxOptions['confirmMsg'] = "确定要删除所选的评论吗？";
	ajaxOptions['callback'] = "<?php echo $type.$cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//页面唯一回调函数
function <?php echo $type.$cFun;?>ajaxCallBack(json){
	$(this).bjuiajax("ajaxDone",json);//显示处理结果
	$(this).dialog("closeCurrent");	//关闭当前dialog
	$(this).navtab("reload",<?php echo $type.$cFun;?>GetOp());	//刷新当前Tab页面 
}
</script>