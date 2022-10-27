<style>
.barline{float:left;margin-right:5px;width:200px;border:1px solid #4DBF7D;text-align:center;height:16px;font-size:8px;border-radius:8px;}
.barline_text_box{line-height:16px;background:#4DBF7D;border-radius:8px;width:0px;color: #3045E6;}
.barline_perc{line-height:16px;}
.barline_msg{margin-top: 11px;}
.<?php echo $cFun?>from{color: #222;background: #f9f9f9;border-bottom-color: #c3ced5;}
</style>

<div class="bjui-pageHeader">
	<form action="index.php?a=yes&c=system.seo.html_plan&t=add" data-reload="true" data-toggle="validate" method="post">
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>from">
    	<tr>
	     	<td width="100">页面标题：</td>
		    <td width="400"><input type="text" class="form-control" name="data[plan_name]" size="15" placeholder="计划的名字" data-rule="required"></td>
		    <td width="100">保存路径：</td>
		    <td width="350"><input type="text" class="form-control" name="data[plan_path]" size="20" placeholder="如：/html/idnex.html" data-rule="required"></td>
		    <td width="100" rowspan="2"><button type="submit" class="btn-green" data-icon="save">添加</button></td>
		</tr>
		<tr>
	     	<td>目标URL：</td>
		    <td><input type="text" class="form-control" name="data[plan_url]" size="45" placeholder="如：http://www.weimengcms.com" data-rule="required"></td>
		    <td>POST参数：</td>
		    <td><input type="text" class="form-control" name="data[plan_data]" size="35" placeholder='{"name":"user_name","psw":"user_psw"}'></td>
		</tr>
    </table>
    </form>
</div>
 

<div class="bjui-pageContent">
	<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
	<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
	<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
	<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
	<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
	</form>
	<table class="table table-border table-bordered table-hover table-bg table-sort">
		<thead>
			<tr>
			<th style="text-align: center;" width="2%;"><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
			<th width="5%" data-order-field="plan_id">ID</th>
			<th width="12%">任务名字</th>
            <th width="15%">保存路径</th>
			<th>URL地址</th>
            <th width="14%" data-order-field="plan_addtime">添加时间</th>
            <th width="14%" data-order-field="plan_lasttime">最后执行</th>
            <th width="11%">操作</th>
            </tr>
		</thead>
		<tbody>
		<?php
		if( $list )
		{
			$i = 1;
			foreach ($list as $k=>$v)
			{
				$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
				echo '<tr class="'.$cur.'">
						<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['plan_id'].'"></td>
						<td style="text-align: center;">'.$v['plan_id'].'</td>
						<td>'.$v['plan_name'].'</td>
						<td>'.$v['plan_path'].'</td>
						<td>'.$v['plan_url'].'</td>
						<td>'.date('Y-m-d H:i:s',$v['plan_addtime']).'</td>
						<td>'.date('Y-m-d H:i:s',$v['plan_lasttime']).'</td>
						<td style="text-align: center;">
    						<a class="btn btn-success radius" onclick="'.$cFun.'runAjax('.$v['plan_id'].')">执行</a> 
		    				&nbsp;&nbsp;<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['plan_id'].')">删除</a> 
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
//页面唯一回调函数
function <?php echo $cFun;?>ajaxCallBack(json){
	$(this).bjuiajax("ajaxDone",json);//显示处理结果
	$(this).dialog("closeCurrent");	//关闭当前dialog
	$(this).navtab("reload",<?php echo $cFun;?>GetOp());	//刷新当前Tab页面 
}
//删除静态计划
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=system.seo.html_plan&t=del";
	ajaxOptions['loadingmask'] = "true";
	ajaxOptions['confirmMsg'] = "确定要删除所选的静态计划吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//运行静态计划
function <?php echo $cFun;?>runAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=system.seo.html_plan&t=run";
	ajaxOptions['loadingmask'] = "true";
	ajaxOptions['confirmMsg'] = "确定要运行所选的静态计划吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
</script>