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
			<a href="index.php?a=yes&c=bbs.bbs&t=status&status=1" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要审核选中项吗？" data-callback="<?php echo $cFun;?>ajaxCallBack" class="btn btn-warning radius"> 批量审核</a>
			<a href="javascript:;" onClick="<?php echo $cFun?>MoveDiv()" class="btn btn-warning  radius"> 批量移动</a>
			<a href="index.php?a=yes&c=bbs.bbs&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
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
				<select data-toggle="selectpicker" name="attr" data-width="100">
                	<option value="">全部属性</option>
                	<?php
                	foreach ($attrArr as $k=>$v)
                	{
                		$checked = str::CheckElse( $k , $attr , 'selected=""' );
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
				<th width="5%" data-order-field="bbs_id">ID</th>
				<th width="7%" data-order-field="b.type_id">主题分类</th>
				<th width="5%" data-order-field="bbs_status">是否审核</th>
				<th>主题标题</th>
				<th width="5%" data-order-field="bbs_rec">推荐</th>
	            <th width="5%" data-order-field="bbs_es">精华</th>
	            <th width="6%" data-order-field="bbs_top">置顶</th>
	            <th width="6%" data-order-field="bbs_read">浏览量</th>
	            <th width="6%" data-order-field="bbs_replay">回帖量</th>
				<th width="10%">发帖用户</th>
	            <th width="14%" data-order-field="bbs_time">发帖时间</th>
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
					$url = tpl::url( 'bbs_bbs' , array('page'=>1,'bid'=>$v['bbs_id'],'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']) );
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					$status = str::CheckElse( $v['bbs_status'] , 0 , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(1,'.$v['bbs_id'].')"><span style="color:red">未审核</span></a>' , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(0,'.$v['bbs_id'].')"><span style="color:green">已审核</span></a>');
					$rec = str::CheckElse( $v['bbs_rec'] , 0 , '<a href="javascript:;" onClick="'.$cFun.'AttrAjax(\'rec\',1,'.$v['bbs_id'].')">否</a>' , '<a href="javascript:;" onClick="'.$cFun.'AttrAjax(\'rec\',0,'.$v['bbs_id'].')"><span style="color:green">是</span></a>');
					$es = str::CheckElse( $v['bbs_es'] , 0 , '<a href="javascript:;" onClick="'.$cFun.'AttrAjax(\'es\',1,'.$v['bbs_id'].')">否</a>' , '<a href="javascript:;" onClick="'.$cFun.'AttrAjax(\'es\',0,'.$v['bbs_id'].')"><span style="color:green">是</span></a>');
					$top = str::CheckElse( $v['bbs_top'] , 0 , '<a href="javascript:;" onClick="'.$cFun.'AttrAjax(\'top\',1,'.$v['bbs_id'].')">否</a>' , '<a href="javascript:;" onClick="'.$cFun.'AttrAjax(\'top\',0,'.$v['bbs_id'].')"><span style="color:green">是</span></a>');
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['bbs_id'].'"></td>
							<td style="text-align: center;">'.$v['bbs_id'].'</td>
							<td style="text-align: center;">'.$v['type_name'].'</td>
							<td style="text-align: center;">'.$status.'</td>
							<td><a href="'.$url.'" target="_blank">'.$v['bbs_title'].'</a></td>
							<td style="text-align: center;">'.$rec.'</td>
							<td style="text-align: center;">'.$es.'</td>
							<td style="text-align: center;">'.$top.'</td>
							<td style="text-align: center;">'.$v['bbs_read'].'</td>
							<td style="text-align: center;">'.$v['bbs_replay'].'</td>
							<td style="text-align: center;">'.$v['user_nickname'].'</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i:s' , $v['bbs_time']).'</td>
							<td style="text-align: center;" data-noedit="true">
				            	<button type="button" class="btn btn-warning radius" data-toggle="dropdown">更多<span class="caret"></span></button>
								<ul class="dropdown-menu" style="width:80px">
							      <li><a href="index.php?c=bbs.replay.list&id='.$v['bbs_id'].'" data-toggle="navtab" data-title="《'.$v['bbs_title'].'》回帖列表" data-id="bbs-bbsreplay">查看回帖</a></li>
								</ul>
								<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['bbs_id'].')">删除</a>
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
	    if( $typeArr )
	    {
		    foreach ($typeArr as $k=>$v)
		    {
		    	echo '<li data-id="'.$v['type_id'].'" data-pid="'.$v['type_topid'].'">'.$v['type_name'].'</li>';
		    }
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
		ajaxOptions['url'] = "index.php?a=yes&c=bbs.bbs&t=move&tid="+tid;
		ajaxOptions['idName'] = "ids";
		ajaxOptions['group'] = "ids";
		ajaxOptions['isNavtab'] = true;
		ajaxOptions['confirmMsg'] = "确定要批量移动选中项吗？";
		ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
		$(this).bjuiajax('doAjaxChecked', ajaxOptions);
	}
}
//删除主题
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=bbs.bbs&t=del";
	ajaxOptions['confirmMsg'] = "确定要删除所选的主题吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//设置主题属性
function <?php echo $cFun;?>AttrAjax(attr,val,id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	var msg;
	var type;
	ajaxOptions = <?php echo $cFun;?>GetOp();

	//主题操作属性类型
	switch(attr)
	{
		case "rec":
			msg = "推荐";
	  		break;
	  		
		case "es":
			msg = "精华";
	  		break;
	  		
		case "top":
			msg = "头条";
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
	ajaxData.attr = attr;
	ajaxData.val = val;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=bbs.bbs&t=attr";
	ajaxOptions['confirmMsg'] = "确定要"+type+"主题的"+msg+"属性吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//审核主题
function <?php echo $cFun;?>StatusAjax(status,id)
{
	var type;
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();

	//主题操作类型
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
	ajaxOptions['url'] = "index.php?a=yes&c=bbs.bbs&t=status";
	ajaxOptions['confirmMsg'] = "确定要"+type+"主题吗？";
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