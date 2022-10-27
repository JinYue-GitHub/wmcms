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
			<a href="index.php?a=yes&c=author.author&t=status&status=1" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要审核选中项吗？" data-callback="<?php echo $cFun;?>ajaxCallBack" class="btn btn-warning radius"> 批量审核</a>
			<a href="index.php?a=yes&c=author.author&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
		</div>
		<div class="list-tool">
			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
               
				<select data-toggle="selectpicker" name="type" data-width="100">
                	<?php
                	foreach ($typeArr as $k=>$v)
                	{
                		$checked = str::CheckElse( $k , $type , 'selected=""' );
                		echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
                	}
                	?>
                </select>
                <input type="text" placeholder="<?php echo $name;?>" name="name" size="15">
				<button type="submit" class="btn btn-warning radius" data-icon="search">查询</button>
				<a id="<?php echo $cFun;?>refresh" class="btn size-MINI btn-primary radius"><i class="fa fa-refresh fa-spin"></i> 刷新</a>
			</form>
		</div>
	</div>
</div>

<div class="bjui-pageContent">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
				<tr>
				<th style="text-align: center;" width="2%;"><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
				<th width="5%" data-order-field="author_id">作者ID</th>
				<th width="7%" data-order-field="user_id">用户id</th>
				<th width="5%" data-order-field="author_status">是否审核</th>
				<th data-order-field="author_nickname">作者笔名</th>
				<th width="10%" data-order-field="user_name">用户账号</th>
				<th width="10%" data-order-field="user_nickname">用户昵称</th>
				<th width="20%" data-order-field="author_info">作者简介</th>
				<th width="15%" data-order-field="author_time">申请时间</th>
	            <th width="12%">操作</th>
	            </tr>
			</thead>
			<tbody id="<?php echo $cFun?>List">
			<?php
			if( $dataArr )
			{
				$i = 1;
				foreach ($dataArr as $k=>$v)
				{
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					switch ($v['author_status'])
					{
						case '0':
							$status = '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(1,'.$v['author_id'].')"><span style="color:red">未审核</span></a>';
							break;
						case '1':
							$status = '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(0,'.$v['author_id'].')"><span style="color:green">已审核</span></a>';
							break;
						default:
							$status = '<span style="color:#A09E9E">已拒绝</span>';
							break;
					}
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['author_id'].'"></td>
							<td style="text-align: center;">'.$v['author_id'].'</td>
							<td style="text-align: center;">'.$v['user_id'].'</td>
							<td style="text-align: center;">'.$status.'</td>
							<td style="text-align: center;">'.$v['author_nickname'].'</td>
							<td style="text-align: center;">'.$v['user_name'].'</td>
							<td style="text-align: center;">'.$v['user_nickname'].'</td>
							<td style="text-align: center;">'.$v['author_info'].'</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i:s' , $v['author_time']).'</td>
							<td style="text-align: center;" data-noedit="true">
                				<button type="button" class="btn btn-warning radius" data-toggle="dropdown">操作<span class="caret"></span></button>
								<ul class="dropdown-menu" style="width:90px">
							      <li><a data-toggle="navtab" data-id="author-author-edit" data-title="编辑作者信息" href="index.php?d=yes&c=author.author.edit&t=edit&id='.$v['author_id'].'">编辑作者信息</a></li>
							      <li class="divider"></li>
								  <li><a href="javascript:;" onClick="'.$cFun.'StatusAjax(1,'.$v['author_id'].')">通过审核</a></li>
								  <li><a href="index.php?d=yes&c=system.apply.refuse&t=refuse_author_apply&module=author&type=apply&uid='.$v['user_id'].'&cid='.$v['author_id'].'" data-mask="true" data-toggle="dialog" data-title="拒绝作者审核" data-width="630" data-height="510">拒绝通过</a></li>
								</ul>
				            	<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['author_id'].')">删除</a>
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
    <div class="pagination-box" data-toggle="pagination" data-total="<?php echo $total;?>" data-page-size="<?php echo $pageSize;?>" data-pageCurrent="<?php echo $pageCurrent?>">
    </div>
</div>


<script type="text/javascript">
//页面唯一op获取函数
function <?php echo $cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $cFun;?>Form]").serializeArray();
	return op;
}
//删除作者
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=author.author&t=del";
	ajaxOptions['confirmMsg'] = "确定要删除所选的作者吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//审核作者
function <?php echo $cFun;?>StatusAjax(status,id)
{
	var type;
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();

	//作者操作类型
	switch(status)
	{
		case 0:
			type = "取消审核";
	  		break;
	  		
		default:
			type = "通过审核";
	  		break;
	}
	
	ajaxData.id = id;
	ajaxData.status = status;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=author.author&t=status";
	ajaxOptions['confirmMsg'] = "确定要"+type+"作者吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//页面唯一回调函数
function <?php echo $cFun;?>ajaxCallBack(json){
	$(this).bjuiajax("ajaxDone",json);//显示处理结果
	$(this).dialog("closeCurrent");	//关闭当前dialog
	$(this).navtab("reload",<?php echo $cFun;?>GetOp());	//刷新当前Tab页面 
}


$(document).ready(function(){
	$('#<?php echo $cFun;?>refresh').click(function() {
	   $(this).navtab("reload",<?php echo $cFun;?>GetOp());// 刷新当前Tab页面
	});
});
</script>