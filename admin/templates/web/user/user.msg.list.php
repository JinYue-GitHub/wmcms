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
			<a href="index.php?a=yes&c=user.msg&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
			<a href="index.php?a=yes&c=user.msg&t=clear" data-toggle="doajax" data-confirm-msg="此操作不可撤回确定要清空吗？" class="btn btn-danger size-MINI radius"><i class="fa fa-trash-o"></i> 清空消息</a>
		</div>
		<div class="list-tool">
			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
                
                
				<select data-toggle="selectpicker" name="st" data-width="100">
                	<option value="f" <?php echo str::CheckElse( $st , 'f' , 'selected=""' );?>>发送用户</option>
                	<option value="t" <?php echo str::CheckElse( $st , 't' , 'selected=""' );?>>接收用户</option>
                </select>
                <input type="text" value="<?php echo $name;?>" placeholder="请输入用户id" name="name" size="15">
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
				<th width="5%" data-order-field="msg_id">ID</th>
				<th width="10%" data-order-field="f.user_nickname">发送用户</th>
				<th width="10%" data-order-field="t.user_nickname">接受用户</th>
				<th width="8%" data-order-field="msg_status">消息状态</th>
				<th>消息内容</th>
	            <th width="15%" data-order-field="msg_time">发送时间</th>
	            <th width="10%">操作</th>
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
					$status = str::CheckElse( $v['msg_status'] , 0 , '<span style="color:green">未查看</span>' , '<span style="color:#AFAAAA;">已查看</span>');
					$v['fnickname'] = str::CheckElse( $v['fnickname'] , 0 , '<span style="color:red">系统</span>' , $v['fnickname']);
					$content = mb_substr( str::DelHtml($v['msg_content']) , 0 , 55 ,'utf-8');
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['msg_id'].'"></td>
							<td style="text-align: center;">'.$v['msg_id'].'</td>
							<td style="text-align: center;">'.$v['fnickname'].'(ID:'.$v['msg_fuid'].')</td>
							<td style="text-align: center;">'.$v['tnickname'].'(ID:'.$v['msg_tuid'].')</td>
							<td style="text-align: center;">'.$status.'</td>
							<td style="text-align: center;">'.$content.'</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i:s' , $v['msg_time']).'</td>
							<td style="text-align: center;" data-noedit="true">
								<a class="btn btn-secondary radius size-MINI" href="index.php?d=yes&c=user.msg.detail&id='.$v['msg_id'].'" data-mask="true" data-toggle="dialog" data-title="查看内信消息详情" data-width="600" data-height="550" >详情</a>
								<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['msg_id'].')">删除</a>
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
//删除消息
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=user.msg&t=del";
	ajaxOptions['confirmMsg'] = "确定要删除所选的消息吗？";
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