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
			<a href="index.php?a=yes&c=novel.novel&t=status&status=1" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要审核选中项吗？" data-callback="<?php echo $cFun;?>ajaxCallBack" class="btn btn-warning radius"> 批量审核</a>
			<a href="javascript:;" onClick="<?php echo $cFun?>MoveDiv()" class="btn btn-warning  radius"> 批量移动</a>
			<a href="index.php?a=yes&c=novel.novel&t=del" data-toggle="doajaxchecked" data-mask="true" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>

			<a href="index.php?a=yes&c=novel.rec&t=rec&val=1&rec=icr" data-toggle="doajaxchecked" data-mask="true" data-idname="ids" data-group="ids" data-confirm-msg="确定要为选中项设置首页封面吗？" class="btn btn-success size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack">批量首封</a>
			<a href="index.php?a=yes&c=novel.rec&t=rec&val=1&rec=ibr" data-toggle="doajaxchecked" data-mask="true" data-idname="ids" data-group="ids" data-confirm-msg="确定要为选中项设置首页精品吗？" class="btn btn-success size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack">批量首精</a>
			<a href="index.php?a=yes&c=novel.rec&t=rec&val=1&rec=ir" data-toggle="doajaxchecked" data-mask="true" data-idname="ids" data-group="ids" data-confirm-msg="确定要为选中项设置首页推荐吗？" class="btn btn-success size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack">批量首推</a>
			<a href="index.php?a=yes&c=novel.rec&t=rec&val=1&rec=ccr" data-toggle="doajaxchecked" data-mask="true" data-idname="ids" data-group="ids" data-confirm-msg="确定要为选中项设置分类封面吗？" class="btn btn-success size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack">批量分封</a>
			<a href="index.php?a=yes&c=novel.rec&t=rec&val=1&rec=cbr" data-toggle="doajaxchecked" data-mask="true" data-idname="ids" data-group="ids" data-confirm-msg="确定要为选中项设置分类精品吗？" class="btn btn-success size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack">批量分精</a>
			<a href="index.php?a=yes&c=novel.rec&t=rec&val=1&rec=cr" data-toggle="doajaxchecked" data-mask="true" data-idname="ids" data-group="ids" data-confirm-msg="确定要为选中项设置分类推荐吗？" class="btn btn-success size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack">批量分推</a>
		</div>
		<div class="list-tool">
			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
                
				<input name="tid" type="hidden" value="<?php echo $tid;?>">
		      	<input name="tname" type="text" data-toggle="selectztree" data-tree="#<?php echo $cFun;?>_ztree_select" readonly value="<?php echo $tname;?>">
	             <ul id="<?php echo $cFun;?>_ztree_select" class="ztree hide" data-toggle="ztree" data-expand-all="true" data-check-enable="true" data-chk-style="radio" data-radio-type="all" data-on-check="<?php echo $cFun;?>S_NodeCheck" data-on-click="<?php echo $cFun;?>S_NodeClick" style="width:120px">
	             <li data-id="">全部分类</li>
	             <?php 
	             if( $typeArr )
	             {
				    foreach ($typeArr as $k=>$v)
				    {
				    	$checked = str::CheckElse( $v['type_id'], C('type_topid',null,'data') , 'true');
				    	echo '<li data-checked="'.$checked.'" data-id="'.$v['type_id'].'" data-pid="'.$v['type_topid'].'">'.$v['type_name'].'</li>';
				    }
	             }
				 ?>
	             </ul>
				<select data-toggle="selectpicker" name="st" data-width="100">
                	<option value="1" <?php echo str::CheckElse( $st , 1 , 'selected=""' );?>>搜书名</option>
                	<option value="2" <?php echo str::CheckElse( $st , 2 , 'selected=""' );?>>搜作者</option>
                	<option value="3" <?php echo str::CheckElse( $st , 3 , 'selected=""' );?>>搜书号</option>
                </select>
                <input type="text" value="<?php echo $name;?>" placeholder="请输入搜索关键字" name="name" size="15">
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
				<th width="5%" data-order-field="novel_id">ID</th>
				<th width="7%" data-order-field="n.type_id">小说分类</th>
				<th width="5%" data-order-field="novel_status">是否审核</th>
				<th data-order-field="novel_name">小说名字</th>
	            <th width="8%" data-order-field="novel_author">小说作者</th>
				<th width="4%" data-order-field="rec_icr">首封</th>
				<th width="4%" data-order-field="rec_ibr">首精</th>
				<th width="4%" data-order-field="rec_ir">首推</th>
				<th width="4%" data-order-field="rec_ccr">分封</th>
				<th width="4%" data-order-field="rec_cbr">分精</th>
				<th width="4%" data-order-field="rec_cr">分推</th>
	            <th width="14%" data-order-field="novel_createtime">创建时间</th>
	            <th width="13%">操作</th>
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
					$status = str::CheckElse( $v['novel_status'] , 0 , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(1,'.$v['novel_id'].')"><span style="color:red">未审核</span></a>' , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(0,'.$v['novel_id'].')"><span style="color:green">已审核</span></a>');
					$icr = str::CheckElse( $v['rec_icr'] , 1 , '<a href="javascript:;" onClick="'.$cFun.'RecAjax(\'icr\',0,'.$v['novel_id'].')"><span style="color:green">是</span></a>' , '<a href="javascript:;" onClick="'.$cFun.'RecAjax(\'icr\',1,'.$v['novel_id'].')">否</a>');
					$ibr = str::CheckElse( $v['rec_ibr'] , 1 , '<a href="javascript:;" onClick="'.$cFun.'RecAjax(\'ibr\',0,'.$v['novel_id'].')"><span style="color:green">是</span></a>' , '<a href="javascript:;" onClick="'.$cFun.'RecAjax(\'ibr\',1,'.$v['novel_id'].')">否</a>');
					$ir = str::CheckElse( $v['rec_ir'] , 1 , '<a href="javascript:;" onClick="'.$cFun.'RecAjax(\'ir\',0,'.$v['novel_id'].')"><span style="color:green">是</span></a>' , '<a href="javascript:;" onClick="'.$cFun.'RecAjax(\'ir\',1,'.$v['novel_id'].')">否</a>');
					$ccr = str::CheckElse( $v['rec_ccr'] , 1 , '<a href="javascript:;" onClick="'.$cFun.'RecAjax(\'ccr\',0,'.$v['novel_id'].')"><span style="color:green">是</span></a>' , '<a href="javascript:;" onClick="'.$cFun.'RecAjax(\'ccr\',1,'.$v['novel_id'].')">否</a>');
					$cbr = str::CheckElse( $v['rec_cbr'] , 1 , '<a href="javascript:;" onClick="'.$cFun.'RecAjax(\'cbr\',0,'.$v['novel_id'].')"><span style="color:green">是</span></a>' , '<a href="javascript:;" onClick="'.$cFun.'RecAjax(\'cbr\',1,'.$v['novel_id'].')">否</a>');
					$cr = str::CheckElse( $v['rec_cr'] , 1  , '<a href="javascript:;" onClick="'.$cFun.'RecAjax(\'cr\',0,'.$v['novel_id'].')"><span style="color:green">是</span></a>', '<a href="javascript:;" onClick="'.$cFun.'RecAjax(\'cr\',1,'.$v['novel_id'].')">否</a>');
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['novel_id'].'"></td>
							<td style="text-align: center;">'.$v['novel_id'].'</td>
							<td style="text-align: center;">'.$v['type_name'].'</td>
							<td style="text-align: center;">'.$status.'</td>
							<td><a data-toggle="tooltip" data-placement="bottom" data-html="true" title="<img src=\''.$v['novel_cover'].'\' width=210 height=280/>">'.$v['novel_name'].'</a></td>
							<td style="text-align: center;">'.$v['novel_author'].'</td>
							<td style="text-align: center;">'.$icr.'</td>
							<td style="text-align: center;">'.$ibr.'</td>
							<td style="text-align: center;">'.$ir.'</td>
							<td style="text-align: center;">'.$ccr.'</td>
							<td style="text-align: center;">'.$cbr.'</td>
							<td style="text-align: center;">'.$cr.'</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i:s' , $v['novel_createtime']).'</td>
							<td style="text-align: center;" data-noedit="true">
				            	<button type="button" class="btn btn-warning radius" data-toggle="dropdown">操作<span class="caret"></span></button>
								<ul class="dropdown-menu" style="width:80px">
							      <li><a data-toggle="navtab" data-id="novel-novel-edit" data-title="编辑小说内容" href="index.php?d=yes&c=novel.novel.edit&t=edit&id='.$v['novel_id'].'">编辑书籍</a></li>
							      <li class="divider"></li>
							      <li><a data-toggle="dialog" data-mask="true" data-id="novel-timelimit-edit" data-width="420" data-height="400" data-title="《'.$v['novel_name'].'》限时免费" href="index.php?d=yes&c=novel.timelimit.edit&t=add&nid='.$v['novel_id'].'">限时免费</a></li>
							      <li class="divider"></li>
								  <li><a data-toggle="navtab" data-id="novel-chapter-list" data-title="《'.$v['novel_name'].'》章节列表" href="index.php?c=novel.chapter.list&nid='.$v['novel_id'].'">章节管理</a></li>
								  <li><a data-toggle="navtab" data-id="novel-volume-list" data-title="《'.$v['novel_name'].'》分卷列表" href="index.php?c=novel.volume.list&nid='.$v['novel_id'].'">分卷管理</a></li>
								  <li class="divider"></li>
								  <li><a data-toggle="dialog" data-mask="true" data-id="novel-sign-add" data-width="500" data-height="330" data-title="《'.$v['novel_name'].'》在线签约" href="index.php?d=yes&c=novel.sign.add&nid='.$v['novel_id'].'">在线签约</a></li>
								  <li><a data-toggle="dialog" data-mask="true" data-id="novel-sell-add" data-width="450" data-height="450" data-title="《'.$v['novel_name'].'》上架销售" href="index.php?d=yes&c=novel.sell.add&nid='.$v['novel_id'].'">上架销售</a></li>
								  <li><a data-toggle="navtab" data-id="novel-sell-welfare" data-title="《'.$v['novel_name'].'》福利设置" href="index.php?d=yes&c=novel.sell.welfare&nid='.$v['novel_id'].'">福利设置</a></li>
								  <li class="divider"></li>
								  <li><a data-toggle="navtab" data-id="novel-sell-settlement" data-title="《'.$v['novel_name'].'》的结算统计" href="index.php?d=yes&c=novel.sell.settlement&nid='.$v['novel_id'].'">结算统计</a></li>
								  <li><a data-toggle="navtab" data-id="novel-sell-log" data-title="《'.$v['novel_name'].'》的销售统计" href="index.php?d=yes&c=novel.sell.log&nid='.$v['novel_id'].'">销售统计</a></li>
								  <li><a data-toggle="navtab" data-id="finance-report-list" data-title="《'.$v['novel_name'].'》的销售报表" href="index.php?d=yes&c=finance.report.list&cid='.$v['novel_id'].'">结算报表</a></li>
								</ul>
								<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['novel_id'].')">删除</a>
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


<!-- 批量移动操作层 -->
<div id="<?php echo $cFun;?>MoveDiv" data-noinit="true" class="hide" align="center">
	<input name="move_tid" id="<?php echo $cFun;?>move_tid" type="hidden">
	批量移动到：
	<input type="text" data-toggle="selectztree" data-tree="#<?php echo $cFun;?>_move_ztree_select" readonly value="<?php echo $tname;?>">
	<ul id="<?php echo $cFun;?>_move_ztree_select" class="ztree hide" data-toggle="ztree" data-expand-all="true" data-check-enable="true" data-chk-style="radio" data-radio-type="all" data-on-check="<?php echo $cFun;?>S_NodeCheck" data-on-click="<?php echo $cFun;?>S_NodeClick" style="width:120px">
	    <?php 
	    foreach ($typeArr as $k=>$v)
	    {
	    	echo '<li data-id="'.$v['type_id'].'" data-pid="'.$v['type_topid'].'">'.$v['type_name'].'</li>';
	    }
	    ?>
    </ul>
    <div class="pt-10" style="margin-left: 80px">
		<button onClick="<?php echo $cFun;?>MoveAjax()" type="button" class="btn-green" data-icon="location-arrow">移动</button>
		<button type="button" class="btn-close" data-icon="close">关闭</button>
	</div>
</div>


<script type="text/javascript">
var <?php echo $cFun;?>ZtreeInputName = 'tid';
//页面唯一op获取函数
function <?php echo $cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['loadingmask'] = true;
	op['data'] = $("[name=<?php echo $cFun;?>Form]").serializeArray();
	return op;
}
//获得选中项
function getChecked() {
	var records = new Array();
	$('#<?php echo $cFun?>List').each(function() {
		if($(this).find('td:eq(0)>input:checked').length == 1){
			records[records.length] = gridObj.getRowRecord($(this));
		}
	});
	return records;
}
//批量移动窗口打开
function <?php echo $cFun;?>MoveDiv()
{
	<?php echo $cFun;?>ZtreeInputName = 'move_tid';
	var ajaxOptions=new Array();
	
	ajaxOptions['target'] = "#<?php echo $cFun;?>MoveDiv";
	ajaxOptions['title'] = "批量移动";
	ajaxOptions['width'] = "300";
	ajaxOptions['height'] = "100";
	ajaxOptions['loadingmask'] = true;
	ajaxOptions['mask'] = "true";
	$(this).dialog(ajaxOptions);
}
//批量移动操作请求
function <?php echo $cFun;?>MoveAjax()
{
	var tid = $("#<?php echo $cFun;?>move_tid").val();
	if( tid == ''){
		$(this).alertmsg('error', '对不起请先选择要移动到分类!')
	}else{
		var ajaxOptions=new Array();
		ajaxOptions['url'] = "index.php?a=yes&c=novel.novel&t=move&tid="+tid;
		ajaxOptions['idName'] = "ids";
		ajaxOptions['group'] = "ids";
		ajaxOptions['isNavtab'] = true;
		ajaxOptions['confirmMsg'] = "确定要批量移动选中项吗？";
		ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
		$(this).bjuiajax('doAjaxChecked', ajaxOptions);
	}
}
//删除小说
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=novel.novel&t=del";
	ajaxOptions['loadingmask'] = true;
	ajaxOptions['confirmMsg'] = "确定要删除所选的小说吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//设置小说推荐属性
function <?php echo $cFun;?>RecAjax(rec,val,id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	var msg;
	var type;
	ajaxOptions = <?php echo $cFun;?>GetOp();

	//小说操作属性类型
	switch(rec)
	{
		case "icr":
			msg = "首页封面推荐";
	  		break;
	  		
		case "ibr":
			msg = "首页精品推荐";
	  		break;
	  		
		case "ir":
			msg = "首页推荐";
	  		break;
	  		
		case "ccr":
			msg = "分类封面推荐";
	  		break;
	  		
		case "cbr":
			msg = "分类精品推荐";
	  		break;
	  		
		case "cr":
			msg = "分类推荐";
	  		break;
	}
	//操作类型设置
	switch(val)
	{
		case 0:
			type = "取消";
	  		break;
	  		
		default:
			type = "设置";
	  		break;
	}
	
	ajaxData.id = id;
	ajaxData.rec = rec;
	ajaxData.val = val;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=novel.rec&t=rec";
	ajaxOptions['confirmMsg'] = "确定要"+type+"小说的"+msg+"属性吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//审核小说
function <?php echo $cFun;?>StatusAjax(status,id)
{
	var type;
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();

	//小说操作类型
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
	ajaxOptions['url'] = "index.php?a=yes&c=novel.novel&t=status";
	ajaxOptions['confirmMsg'] = "确定要"+type+"小说吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//页面唯一回调函数
function <?php echo $cFun;?>ajaxCallBack(json){
	$(this).bjuiajax("ajaxDone",json);//显示处理结果
	$(this).dialog("closeCurrent");	//关闭当前dialog
	$(this).navtab("reload",<?php echo $cFun;?>GetOp());	//刷新当前Tab页面 
}
//选择事件
function <?php echo $cFun;?>S_NodeCheck(e, treeId, treeNode) {
    var zTree = $.fn.zTree.getZTreeObj(treeId),
        nodes = zTree.getCheckedNodes(true)
    var ids = '', names = ''
    
    for (var i = 0; i < nodes.length; i++) {
        ids   += ','+ nodes[i].id
        names += ','+ nodes[i].name
    }
    if (ids.length > 0) {
        ids = ids.substr(1), names = names.substr(1)
    }
    
    var $from = $('#'+ treeId).data('fromObj')

    $('[name="'+<?php echo $cFun;?>ZtreeInputName+'"]').val(ids);
    <?php echo $cFun;?>ZtreeInputName = 'tid';
    if ($from && $from.length) $from.val(names)
}
//单击事件
function <?php echo $cFun;?>S_NodeClick(event, treeId, treeNode) {
    var zTree = $.fn.zTree.getZTreeObj(treeId)
    
    zTree.checkNode(treeNode, !treeNode.checked, true, true)
    
    event.preventDefault()
}


$(document).ready(function(){
	$('#<?php echo $cFun;?>refresh').click(function() {
	   $(this).navtab("reload",<?php echo $cFun;?>GetOp());// 刷新当前Tab页面
	});
});
</script>