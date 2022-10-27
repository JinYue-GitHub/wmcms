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
			<a href="index.php?a=yes&c=operate.weixin.fans&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $type.$cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
		</div>
		<div class="list-tool">
			<form id="pagerForm" name="<?php echo $cFun.$type;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
                                           搜索分类：<select data-toggle="selectpicker" name="st" data-width="100">
                	<option value="1" <?php echo str::CheckElse( $st , 1 , 'selected=""' );?>>公众号ID</option>
                	<option value="2" <?php echo str::CheckElse( $st , 2 , 'selected=""' );?>>粉丝名字</option>
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
				<th width="5%" data-order-field="fans_id">ID</th>
				<th width="12%" data-order-field="account_name">公众号名字</th>
				<th width="10%" data-order-field="fans_headimgurl">粉丝头像</th>
				<th width="15%" data-order-field="fans_nickname">粉丝名字</th>
				<th width="6%" data-order-field="fans_subscribe">关注状态</th>
				<th width="5%" data-order-field="fans_sex">性别</th>
				<th width="10%">地区</th>
	            <th width="12%" data-order-field="fans_subscribe_time">关注时间</th>
	            <th width="12%" data-order-field="fans_unsubtime">取消关注时间</th>
	            <th width="12%">操作</th>
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
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['fans_id'].'"></td>
							<td style="text-align: center;">'.$v['fans_id'].'</td>
							<td style="text-align: center;">'.$v['account_name'].'</td>
							<td style="text-align: center;"><img src="'.$v['fans_headimgurl'].'" width="50"/></td>
							<td style="text-align: center;">'.$v['fans_nickname'].'</td>
							<td style="text-align: center;">'.$fansMod->subscribe[$v['fans_subscribe']].'</td>
							<td style="text-align: center;">'.$fansMod->sex[$v["fans_sex"]].'</td>
							<td style="text-align: center;">'.$v['fans_country'].'-'.$v['fans_province'].'-'.$v['fans_city'].'</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i:s' , $v['fans_subscribe_time']).'</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i:s' , $v['fans_unsubtime']).'</td>
							<td style="text-align: center;" data-noedit="true">
								<a class="btn btn-secondary radius size-MINI" href="index.php?c=operate.weixin.fans.detail&id='.$v['fans_id'].'"  data-mask="true" data-toggle="dialog" data-title="查看粉丝详细信息" data-width="600" data-height="480" >详情</a>
								<a class="btn btn-danger radius" onclick="'.$type.$cFun.'Ajax(\'del\','.$v['fans_id'].',0)">删除</a>
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
			msg = '确定要删除所选的粉丝吗？';
			break;
	}
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['loadingmask'] = true;
	ajaxOptions['url'] = "index.php?a=yes&c=operate.weixin.fans&t="+type;
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