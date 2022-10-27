<div class="bjui-pageContent">
    <form action="index.php?a=yes&c=app.firms&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="id[firms_id]" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>厂商信息编辑</legend>
            	<div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>base">
		        	<table class="table table-condensed table-hover" width="100%">
		            <tbody>
		                <tr>
		                    <td colspan="2">
		                        <b>厂商类型：</b>
		                        <select data-toggle="selectpicker" name="firms[firms_type]">
			                        <?php 
								    foreach ($typeArr as $k=>$v)
								    {
								    	$checked = str::CheckElse( $k, C('firms_type',null,'data') , 'true');
								    	echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
								    }
								    ?>
                                </select>
		                    </td>
		                </tr>
		                <tr>
		                    <td width="50%">
		                        <b>厂商名字：</b>
		                        <input type="text" name="firms[firms_name]" value="<?php echo C('firms_name',null,'data');?>" data-rule="required">
		                    </td>
		                    <td>
		                        <b>厂商简称：</b>
		                        <input type="text" name="firms[firms_cname]" value="<?php echo C('firms_cname',null,'data');?>">
		                    </td>
		                </tr>  
		                <tr>
		                   <td>
		                        <b>官方网站：</b>
		                        <input type="text" name="firms[firms_url]"  value="<?php echo C('firms_url',null,'data');?>">
		                    </td>
		                   <td>
		                        <b>联系地址：</b>
		                        <input type="text" name="firms[firms_adress]" value="<?php echo C('firms_adress',null,'data');?>">
		                    </td>
		                </tr>
		                <tr>
		                   <td>
		                        <b>联系电话：</b>
		                        <input type="text"  name="firms[firms_phone]" value="<?php echo C('firms_phone',null,'data');?>">
		                    </td>
		                   <td>
		                        <b>联系邮箱：</b>
		                        <input type="text"  name="firms[firms_email]" value="<?php echo C('firms_email',null,'data');?>">
		                    </td>
		                </tr>
		                <tr>
		                   <td colspan="2">
		                        <b>厂商介绍：</b>
		                        <div style="display:inline-block; vertical-align: middle;">
									<?php echo Ueditor('width: 90%;height:300px' , 'firms[firms_content]' , C('firms_content',null,'data') , 'editor.app_firms');?>
		                        </div>
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
	var op = appFirmsListGetOp();
	var tabid = 'app-attr-list';
	op['id'] = tabid;
	
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).navtab("reload",op);	// 刷新Tab页面
    $(this).navtab("switchTab",tabid);	// 切换Tab页面
}
</script>