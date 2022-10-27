<style>
.table tr {
    height: 35px;
}
thead th {
	text-align: center;
}
#<?php echo $cFun;?>List td{text-align: center;}
</style>
<div class="bjui-pageHeader">
	<div class="row cl pt-10 pb-10 pl-10">
		<div class="f-l">
			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="index.php?c=system.site.site" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
                <input type="text" placeholder="<?php echo $name;?>" name="name" size="15">
				<button type="submit" class="btn btn-warning radius" data-icon="search">查询</button>
				<a id="<?php echo $cFun;?>refresh" class="btn size-MINI btn-primary radius"><i class="fa fa-refresh fa-spin"></i> 刷新</a> &nbsp;
			</form>
		</div>
	</div>
	<div class="row cl pt-10 pb-10 pl-10">
		<div class="f-l">
            <span class="" style="float:left;margin:5px 0 0 15px;"> </span>
			<a href="index.php?a=yes&c=system.site.site&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
			<a href="index.php?a=yes&c=system.site.site&t=clear" data-toggle="doajax" data-confirm-msg="确定清空记录要吗？" class="btn btn-danger size-MINI radius"><i class="fa fa-trash-o"></i> 清空记录</a>
			<div class="alert alert-danger search-inline"><i class="fa fa-info-circle"></i> 此处添加站内站点，添加后需要保存一下站群配置才会生效。</div>
            <a class="btn btn-secondary radius size-MINI" data-toggle="dialog" data-mask="true" data-title="添加站点" href="index.php?d=yes&c=system.site.site.edit&t=add" data-width="450" data-height="420" ><i class="fa fa-plus"></i> 添加站点</a> &nbsp;	
		</div>
	</div>
</div>

<div class="bjui-pageContent" id="<?php echo $cFun;?>List">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
				<tr>
				<th style="text-align: center;" width="2%;"><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
				<th width="5%" data-order-field="site_id">ID</th>
				<th width="7%" data-order-field="site_status">站点状态</th>
				<th width="10%">站点名字</th>
				<th width="15%">站点域名</th>
				<th width="8%">域名类型</th>
				<th width="8%">站点类型</th>
	            <th width="12%">模版文件</th>
	            <th width="7%">显示顺序</th>
	            <th width="14%">添加时间</th>
	            <th width="10%">操作</th>
	            </tr>
			</thead>
			
			<tbody>
			<?php
			if( $data )
			{
				$i = 1;
				foreach ($data as $k=>$v)
				{
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					$status = str::CheckElse( $v['site_status'] , 0 , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(1,'.$v['site_id'].')"><span style="color:red">'.$siteSer->GetStatus(0).'</span></a>' , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(0,'.$v['site_id'].')"><span style="color:green">'.$siteSer->GetStatus(1).'</span></a>');
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['site_id'].'"></td>
							<td>'.$v['site_id'].'</td>
							<td>'.$status.'</td>
							<td>'.$v['site_title'].'</td>
							<td>'.$v['site_domain'].'</td>
							<td>'.$siteSer->GetType($v['site_type']).'</td>
							<td>'.$siteSer->domainType[$v['site_domain_type']].'</td>
							<td>'.$v['site_template'].'</td>
							<td>'.$v['site_order'].'</td>
							<td style="text-align: center;">'.date("Y-m-d H:i:s" , $v['site_time']).'</td>
							<td style="text-align: center;">
				            	<a class="btn btn-secondary radius size-MINI" data-mask="true" href="index.php?d=yes&c=system.site.site.edit&t=edit&id='.$v['site_id'].'"  data-toggle="dialog" data-title="修改站点信息" data-width="450" data-height="420" >编辑</a> 
				            	<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['site_id'].')">删除</a>
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
    <div id="asd" class="pagination-box" data-toggle="pagination" data-total="<?php echo $total;?>" data-page-size="<?php echo $pageSize;?>" data-pageCurrent="<?php echo $pageCurrent?>">
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
//删除数据
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=system.site.site&t=del";
	ajaxOptions['confirmMsg'] = "确定要删除所选的数据吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//禁用使用站点
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
	ajaxOptions['url'] = "index.php?a=yes&c=system.site.site&t=status";
	ajaxOptions['confirmMsg'] = "确定要"+type+"站点吗？";
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
	$('#<?php echo $cFun;?>module').change(function() {
		$("[name=<?php echo $cFun;?>Form]").submit();
	});
	$('#<?php echo $cFun;?>refresh').click(function() {
	   $(this).navtab("reload",<?php echo $cFun;?>GetOp());// 刷新当前Tab页面
	});
});
</script>