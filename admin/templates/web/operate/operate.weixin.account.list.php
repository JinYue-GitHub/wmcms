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
			<a href="index.php?c=operate.weixin.account.edit&t=add" data-toggle="navtab" data-id="operate-weixin-account-add" data-title="接入新公众号" class="btn btn-primary radius size-MINI"><i class="fa fa-plus"></i> 接入新公众号</a>
			<a href="index.php?a=yes&c=operate.weixin.account&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $type.$cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
		</div>
		<div class="list-tool">
			<form id="pagerForm" name="<?php echo $cFun.$type;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
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
				<th width="5%" data-order-field="account_id">ID</th>
				<th width="10%" data-order-field="account_name">公众号名字</th>
				<th width="5%" data-order-field="account_status">是否审核</th>
				<th width="5%" data-order-field="account_type">账号类型</th>
				<th width="5%" data-order-field="account_auth">认证状态</th>
				<th width="5%" data-order-field="account_access">接入状态</th>
				<th width="6%" data-order-field="account_main">主号</th>
				<th width="5%" data-order-field="account_follow">强制关注</th>
	            <th width="14%" data-order-field="account_time">添加时间</th>
	            <th width="25%">操作</th>
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
					$status = str::CheckElse( $v['account_status'] , 0 , '<a href="javascript:;" onClick="'.$type.$cFun.'Ajax(\'status\','.$v['account_id'].',1)"><span style="color:red">已隐藏</span></a>' , '<a href="javascript:;" onClick="'.$type.$cFun.'Ajax(\'status\','.$v['account_id'].',0)"><span style="color:green">显示中</span></a>');
					$auth = str::CheckElse( $v['account_auth'] , 0 , '<span style="color:red">未认证</span>' , '<span style="color:green">已认证</span>');
					$access = str::CheckElse( $v['account_access'] , 0 , '<span style="color:red">未接入</span>' , '<span style="color:green">已接入</span>');
					$accountType = str::CheckElse( $v['account_type'] , 1 , '订阅号' , '服务号');
					$main = str::CheckElse( $v['account_main'] , 0 , '<a href="javascript:;" onClick="'.$type.$cFun.'Ajax(\'main\','.$v['account_id'].',1)">设为主号</a>' , '<a href="javascript:;" onClick="'.$type.$cFun.'Ajax(\'main\','.$v['account_id'].',0)"><span style="color:green">取消主号</span></a>');
					$follow = str::CheckElse( $v['account_follow'] , 1 , '强制关注' , '不限制');
						
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['account_id'].'"></td>
							<td style="text-align: center;">'.$v['account_id'].'</td>
							<td style="text-align: center;">'.$v['account_name'].'</td>
							<td style="text-align: center;">'.$status.'</td>
							<td style="text-align: center;">'.$accountType.'</td>
							<td style="text-align: center;">'.$auth.'</td>
							<td style="text-align: center;">'.$access.'</td>
							<td style="text-align: center;">'.$main.'</td>
							<td style="text-align: center;">'.$follow.'</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i:s' , $v['account_time']).'</td>
							<td style="text-align: center;" data-noedit="true">
								<button type="button" class="btn btn-warning radius" data-toggle="dropdown">更多<span class="caret"></span></button>
								<ul class="dropdown-menu" style="width:100px">
								  <li><a data-toggle="navtab" data-id="operate-weixin-menu-list" data-title="'.$v['account_name'].'自定义菜单" href="index.php?d=yes&c=operate.weixin.menu.list&st=1&name='.$v['account_id'].'&aid='.$v['account_id'].'">自定义菜单</a></li>
								  <li class="divider"></li>
						 		  <li><a data-toggle="navtab" data-id="operate-weixin-autoreply-list" data-title="'.$v['account_name'].'自动回复列表" href="index.php?d=yes&c=operate.weixin.autoreply.list&st=1&name='.$v['account_id'].'&aid='.$v['account_id'].'">自动回复列表</a></li>
						 		  <li><a data-toggle="navtab" data-id="operate-weixin-autoreply-edit" data-title="添加'.$v['account_name'].'自动回复" href="index.php?d=yes&c=operate.weixin.autoreply.edit&aid='.$v['account_id'].'">添加自动回复</a></li>
								  <li class="divider"></li>
						 		  <li><a data-toggle="navtab" data-id="operate-weixin-media-list" data-title="'.$v['account_name'].'素材列表" href="index.php?d=yes&c=operate.weixin.media.list&st=1&name='.$v['account_id'].'&aid='.$v['account_id'].'">素材列表</a></li>
								  <li class="divider"></li>
						 		  <li><a data-toggle="navtab" data-id="operate-weixin-fans-list" data-title="'.$v['account_name'].'粉丝列表" href="index.php?d=yes&c=operate.weixin.fans.list&st=1&name='.$v['account_id'].'&aid='.$v['account_id'].'">粉丝列表</a></li>
						 		</ul>
	            				<a class="btn btn-secondary radius size-MINI" data-toggle="navtab" data-id="operate-weixin-account-edit" data-title="编辑公众号" href="index.php?d=yes&c=operate.weixin.account.edit&t=edit&id='.$v['account_id'].'">编辑</a> 
								<button type="button" class="btn btn-success radius size-MINI"  onClick="'.$type.$cFun.'Ajax(\'check\','.$v['account_id'].',0)">接入检测</button>
								<a class="btn btn-danger radius" onclick="'.$type.$cFun.'Ajax(\'del\','.$v['account_id'].',0)">删除</a>
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
			msg = '删除所选的公众号同时也会删除自定义菜单和自动回复，确定要删除吗？';
			break;
		case "main":
			ajaxData.main = val;
			if(val==1){
				msg = "确定要设置为主公众号吗？";
			}else{
				msg = "确定要取消为主公众号吗？";
			}
			break;
		case "check":
			msg = '确定要对所选的公众号接入检测吗？';
			break;
		case "status":
			ajaxData.status = val;
			if(val==1){
				msg = "确定要通过审核吗？";
			}else{
				msg = "确定要取消审核吗？";
			}
			break;
	}
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['loadingmask'] = true;
	ajaxOptions['url'] = "index.php?a=yes&c=operate.weixin.account&t="+type;
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