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
			<a href="index.php?a=yes&c=user.user&t=status&status=1" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要审核选中项吗？" data-callback="<?php echo $cFun;?>ajaxCallBack" class="btn btn-warning radius"> 批量审核</a>
			<a href="index.php?a=yes&c=user.user&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
		</div>
		<div class="list-tool">
			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
               
				<select data-toggle="selectpicker" name="user_type" data-width="100">
                	<option value="" <?php echo str::CheckElse( $userType , '' , 'selected=""' );?>>全部来源</option>
		            <?php 
						foreach ($userMod->GetUserType() as $k=>$v)
						{
							echo '<option value="'.$k.'" '.str::CheckElse( $userType , "{$k}" , 'selected=""' ).'>'.$v.'</option>';
						}
					?>
                </select>
				<select data-toggle="selectpicker" name="status" data-width="100">
                	<option value="" <?php echo str::CheckElse( $status , '' , 'selected=""' );?>>全部状态</option>
                	<option value="0" <?php echo str::CheckElse( $status , '0' , 'selected=""' );?>>待审核</option>
                	<option value="1" <?php echo str::CheckElse( $status , 1 , 'selected=""' );?>>已通过</option>
                </select>
                
                <input type="text" placeholder="<?php echo $name;?>" name="name" size="15">
                <input type="text" placeholder="<?php echo $tel;?>" name="tel" size="15">
                <input type="text" placeholder="<?php echo $email;?>" name="email" size="15">
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
				<th width="5%" data-order-field="user_id">ID</th>
				<th width="7%">注册来源</th>
				<th data-order-field="user_name">用户名</th>
				<th width="5%" data-order-field="user_status">是否审核</th>
				<th data-order-field="user_nickname">用户昵称</th>
				<th data-order-field="user_tel">手机号</th>
				<th data-order-field="user_email">邮箱</th>
				<th width="5%" data-order-field="user_sex">性别</th>
				<th width="8%" data-order-field="user_exp">等级</th>
				<th width="8%" data-order-field="user_gold1"><?php echo $configArr['gold1_name'];?></th>
				<th width="8%" data-order-field="user_gold2"><?php echo $configArr['gold2_name'];?></th>
	            <th width="12%" data-order-field="user_regtime">注册时间</th>
	            <th width="15%">操作</th>
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
					$status = str::CheckElse( $v['user_status'] , 0 , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(1,'.$v['user_id'].')"><span style="color:red">未审核</span></a>' , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(0,'.$v['user_id'].')"><span style="color:green">已审核</span></a>');
					$sex = str::CheckElse( $v['user_sex'] , 1 , '男' , '女');
					if( $punishMod->GetOne($v['user_id'],'login') )
					{
						$login = '<li><a href="index.php?a=yes&c=user.punish&t=unpunish&st=login&uid='.$v['user_id'].'" data-mask="true" data-toggle="doajax" data-confirm-msg="确定要解除封禁吗？">解除封禁</a></li>';
					}
					else
					{
						$login = '<li><a href="index.php?d=yes&c=user.punish.punish&st=login&uid='.$v['user_id'].'" data-mask="true" data-toggle="dialog" data-title="禁止登陆" data-width="420" data-height="260">禁止登陆</a></li>';
					}	      
					if( $punishMod->GetOne($v['user_id'],'talk') )
					{
						$talk = '<li><a href="index.php?a=yes&c=user.punish&t=unpunish&st=talk&uid='.$v['user_id'].'" data-mask="true" data-toggle="doajax" data-confirm-msg="确定要解除禁言吗？">解除禁言</a></li>';
					}
					else
					{
						$talk = '<li><a href="index.php?d=yes&c=user.punish.punish&st=talk&uid='.$v['user_id'].'" data-mask="true" data-toggle="dialog" data-title="禁止发言" data-width="420" data-height="260">禁止发言</a></li>';
					}
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['user_id'].'"></td>
							<td style="text-align: center;">'.$v['user_id'].'</td>
							<td style="text-align: center;">'.$userMod->GetUserType($v['user_type']).'</td>
							<td style="text-align: center;">'.$v['user_name'].'</td>
							<td style="text-align: center;">'.$status.'</td>
							<td>'.$v['user_nickname'].'</span></td>
							<td>'.$v['user_tel'].'</span></td>
							<td>'.$v['user_email'].'</span></td>
							<td style="text-align: center;">'.$sex.'</td>
							<td style="text-align: center;">'.$v['level_name'].'</td>
							<td style="text-align: center;">'.$v['user_gold1'].'</td>
							<td style="text-align: center;">'.$v['user_gold2'].'</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i' , $v['user_regtime']).'</td>
							<td style="text-align: center;" data-noedit="true">
								<button type="button" class="btn btn-warning radius" id="'.$cFun.'dropdownMenu_'.$v['user_id'].'" data-toggle="dropdown">更多<span class="caret"></span></button>
								<ul class="dropdown-menu" aria-labelledby="'.$cFun.'dropdownMenu_'.$v['user_id'].'" style="width:80px">
							      <li><a href="index.php?d=yes&c=user.user.reward&id='.$v['user_id'].'" data-mask="true" data-toggle="dialog" data-title="用户金币奖惩操作" data-width="550" data-height="180">金币奖惩</a></li>
							      <li><a href="index.php?d=yes&c=user.msg.send&id='.$v['user_id'].'" data-mask="true" data-toggle="dialog" data-title="发送内信" data-width="700" data-height="550">发送内信</a></li>
							      <li class="divider"></li>
							      '.$login.'
							      '.$talk.'
								</ul>
				            	<a class="btn btn-secondary radius size-MINI" data-toggle="navtab" data-id="user-user-edit" data-title="编辑用户内容" href="index.php?d=yes&c=user.user.edit&t=edit&id='.$v['user_id'].'">编辑</a> 
								<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['user_id'].')">删除</a>
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
//删除用户
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=user.user&t=del";
	ajaxOptions['confirmMsg'] = "确定要删除所选的用户吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//审核用户
function <?php echo $cFun;?>StatusAjax(status,id)
{
	var type;
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();

	//用户操作类型
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
	ajaxOptions['url'] = "index.php?a=yes&c=user.user&t=status";
	ajaxOptions['confirmMsg'] = "确定要"+type+"用户吗？";
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