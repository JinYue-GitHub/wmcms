<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=operate.ad.type&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="id[type_id]" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>广告分类编辑</legend>
        	<table class="table table-condensed table-hover" width="100%">
            <tbody>
                <tr>
                    <td>
                        <b>分类名字：</b>
                        <input type="text" name="type[type_name]" value="<?php echo C('type_name',null,'data');?>" data-rule="required">
                    </td>
                </tr>
	            <tr>
                    <td>
                        <b>分类简介：</b>
                        <input type="text" name="type[type_info]" value="<?php echo C('type_info',null,'data');?>" size="30">
                    </td>
                </tr>
            </tbody>
        </table>
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
//页面唯一回调函数
function <?php echo $cFun;?>(json){
	var op = operateAdTypeListGetOp();
	
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).dialog("closeCurrent");//关闭
    $(this).navtab("reload",op);	// 刷新当前Tab页面
}
</script>