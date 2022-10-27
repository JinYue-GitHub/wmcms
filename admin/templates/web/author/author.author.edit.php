<div class="bjui-pageContent">               
    <form action="index.php?a=yes&c=author.author&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="id[author_id]" id="<?php echo $cFun.$type;?>_id" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>作者基本信息编辑</legend>
			<ul class="nav nav-tabs" role="tablist">
            	<li class="active"><a href="#<?php echo $cFun.$type;?>base" role="tab" data-toggle="tab">基本信息</a></li>
            </ul>
            <div class="tab-content">
            	<div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>base">
					<table class="table table-border table-bordered table-bg table-sort">
		            <tbody>
		                <tr>
		                    <td width="150"><b>用户 i d：</b></td>
		                    <td colspan="3">
		                        <input type="text" name="author[user_id]" value="<?php echo C('user_id',null,'data');?>" data-rule="required;digits" size="10">
		                    </td>
		                </tr>
		                <tr>
		                    <td><b>作者状态：</b></td>
		                    <td colspan="3">
		                    	<select data-toggle="selectpicker" name="author[author_status]">
	                            	<option value="1" <?php if(C('author_status',null,'data') == '1'){ echo 'selected=""';}?>>审核通过</option>
	                                <option value="0" <?php if(C('author_status',null,'data') == '0'){ echo 'selected=""';}?>>待审核</option>
	                            	<option value="2" <?php if(C('author_status',null,'data') == '2'){ echo 'selected=""';}?>>拒绝审核</option>
	                            </select>
		                    </td>
		                </tr>
		                <tr>
		                    <td><b>作者笔名：</b></td>
		                    <td colspan="3">
		                    	<input type="text" name="author[author_nickname]" value="<?php echo C('author_nickname',null,'data');?>" data-rule="required">
		                    </td>
		                </tr>
		                <tr>
		                    <td><b>作者简介：</b></td>
		                    <td colspan="3">
		                    	<textarea cols="25" rows="3" name="author[author_info]"><?php echo C('author_info',null,'data');?></textarea>
		                    </td>
		                </tr>
		                <tr>
		                    <td><b>作者小说经验：</b></td>
		                    <td>
		                    	<input type="text" name="exp[novel][exp_number]" value="<?php echo C('novel.exp_number',null,'exp');?>" data-rule="required;digits" size="10">
		                    </td>
		                    <td><b>写作小说等级：</b></td>
		                    <td>
		                    	<input type="text" value="<?php echo C('novel.level_name',null,'exp');?>" disabled> 
		                    </td>
		                </tr>
		                <tr>
		                    <td><b>作者文章经验：</b></td>
		                    <td>
		                    	<input type="text" name="exp[article][exp_number]" value="<?php echo C('article.exp_number',null,'exp');?>" data-rule="required;digits" size="10">
		                    </td>
		                    <td><b>写作文章等级：</b></td>
		                    <td>
		                    	<input type="text" value="<?php echo C('article.level_name',null,'exp');?>" disabled> 
		                    </td>
		                </tr>
		                <tr>
		                    <td><b>作者公告：</b></td>
		                    <td colspan="3">
		                    	<textarea cols="25" rows="3" name="author[author_notice]"><?php echo C('author_notice',null,'data');?></textarea>
		                    </td>
		                </tr>
		            	<tr>
						  <td><b>申请时间：</b></td>
		                  <td colspan="3">
		                  	<input type="text" name="author[author_time]" value="<?php echo date('Y-m-d H:i:s',C('author_time',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
					      </td>
						</tr>
		            </tbody>
		        </table>
			</div>
		</fieldset>
	</form>
</div>

<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-green" data-icon="save">保存</button></li>
    </ul>
</div>


<script>
//页面唯一op获取函数
function <?php echo $cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $cFun;?>Form]").serializeArray();
	return op;
}
//页面唯一回调函数
function <?php echo $cFun;?>(json){
	var op = authorAuthorListGetOp();
	var tabid = 'author-author-list';
	op['id'] = tabid;
	
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).navtab("reload",op);	// 刷新Tab页面
    $(this).navtab("switchTab",tabid);	// 切换Tab页面
}
</script>