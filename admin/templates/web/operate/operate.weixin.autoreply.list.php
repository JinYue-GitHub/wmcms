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
			<a href="index.php?c=operate.weixin.autoreply.edit&t=add&aid=<?php echo $aid;?>" data-toggle="navtab" data-id="operate-weixin-autoreply-add" data-title="接入新自动回复" class="btn btn-primary radius size-MINI"><i class="fa fa-plus"></i> 创建新的自动回复</a>
			<a href="index.php?a=yes&c=operate.weixin.autoreply&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $type.$cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
		</div>
		<div class="list-tool">
			<form id="pagerForm" name="<?php echo $cFun.$type;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
                <select data-toggle="selectpicker" name="st" data-width="100">
                	<option value="1" <?php echo str::CheckElse( $st , 1 , 'selected=""' );?>>自动回复ID</option>
                	<option value="2" <?php echo str::CheckElse( $st , 2 , 'selected=""' );?>>自动回复名字</option>
                	<option value="3" <?php echo str::CheckElse( $st , 3 , 'selected=""' );?>>自动回复名字</option>
                </select>
                <input type="text" placeholder="<?php echo $name;?>" name="name" size="15">
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
				<th width="5%" data-order-field="autoreply_id">ID</th>
				<th width="11%" data-order-field="account_name">公众号名字</th>
				<th width="5%" data-order-field="autoreply_status">使用状态</th>
				<th width="11%" data-order-field="autoreply_name">自动回复名字</th>
				<th width="8%" data-order-field="autoreply_key">匹配关键字</th>
				<th width="6%" data-order-field="autoreply_match">匹配类型</th>
				<th width="6%" data-order-field="autoreply_type">回复类型</th>
				<th width="5%" data-order-field="autoreply_default">默认</th>
	            <th width="12%" data-order-field="autoreply_addtime">添加时间</th>
	            <th width="16%">操作</th>
	            </tr>
			</thead>
			<tbody id="<?php echo $type.$cFun?>List">
			<?php
			if( $dataArr )
			{
				$i = 1;
				foreach ($dataArr as $k=>$v)
				{
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					$status = str::CheckElse( $v['autoreply_status'] , 0 , '<a href="javascript:;" onClick="'.$cFun.'Ajax(\'status\','.$v['autoreply_id'].',1)"><span style="color:red">未审核</span></a>' , '<a href="javascript:;" onClick="'.$cFun.'Ajax(\'status\','.$v['autoreply_id'].',0)"><span style="color:green">已审核</span></a>');

					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['autoreply_id'].'"></td>
							<td style="text-align: center;">'.$v['autoreply_id'].'</td>
							<td style="text-align: center;">'.$v['account_name'].'</td>
							<td style="text-align: center;">'.$status.'</td>
							<td style="text-align: center;">'.$v['autoreply_name'].'</td>
							<td style="text-align: center;">'.$v['autoreply_key'].'</td>
							<td style="text-align: center;">'.$replyMod->match[$v['autoreply_match']].'</td>
							<td style="text-align: center;">'.$replyMod->type[$v['autoreply_type']].'</td>
							<td style="text-align: center;">'.$replyMod->default[$v['autoreply_default']].'</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i:s' , $v['autoreply_addtime']).'</td>
							<td style="text-align: center;" data-noedit="true">
	            				<a class="btn btn-secondary radius size-MINI" data-toggle="navtab" data-id="operate-weixin-autoreply-edit" data-title="编辑自动回复" href="index.php?d=yes&c=operate.weixin.autoreply.edit&t=edit&id='.$v['autoreply_id'].'">编辑</a> 
								<a class="btn btn-warning radius size-MINI" data-toggle="dialog" data-mask="true" data-height="170" data-id="operate-weixin-autoreply-order" data-title="复制自动回复" href="index.php?d=yes&c=operate.weixin.autoreply.copy&rid='.$v['autoreply_id'].'">复制</a></li>
								<a class="btn btn-danger radius" onclick="'.$type.$cFun.'Ajax(\'del\','.$v['autoreply_id'].',0)">删除</a>
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
function <?php echo $type.$cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $type.$cFun;?>Form]").serializeArray();
	return op;
}
//ajax请求
function <?php echo $type.$cFun;?>Ajax(type,id,val)
{
	var msg;
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $type.$cFun;?>GetOp();

	switch(type){
		case "del":
			msg = '确定要删除所选的自动回复吗？';
			break;
		case "status":
		    ajaxData.status = val;
			msg = '确定要审核操作当前项吗？';
			break;
	}
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['loadingmask'] = true;
	ajaxOptions['url'] = "index.php?a=yes&c=operate.weixin.autoreply&t="+type;
	ajaxOptions['confirmMsg'] = msg;
	ajaxOptions['callback'] = "<?php echo $type.$cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//页面唯一回调函数
function <?php echo $type.$cFun;?>ajaxCallBack(json){
	$(this).bjuiajax("ajaxDone",json);//显示处理结果
	$(this).dialog("closeCurrent");	//关闭当前dialog
	$(this).navtab("reload",<?php echo $type.$cFun;?>GetOp());	//刷新当前Tab页面 
}


$(document).ready(function(){
	$('#<?php echo $type.$cFun;?>refresh').click(function() {
	   $(this).navtab("reload",<?php echo $type.$cFun;?>GetOp());// 刷新当前Tab页面
	});
});
</script>