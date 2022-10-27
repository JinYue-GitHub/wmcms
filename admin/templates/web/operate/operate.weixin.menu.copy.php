<div class="bjui-pageContent">
    <form action="index.php?a=yes&c=operate.weixin.menu&t=copy" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
		<input name="mid" type="hidden" class="input-text" value="<?php echo $mid;?>">
		<fieldset>
			<legend>复制自定义菜单</legend>
            <div class="tab-pane fade active in">
				<table class="table table-border table-bordered table-bg table-sort">
					<tr>
					    <td valign="top" width="30%">
					    	<b>复制到：</b>
	                        <select data-toggle="selectpicker" name="aid">
							<?php 
	                        if( $data )
	                        {
		                        foreach ($data as $k=>$v)
		                        {
		                        	echo '<option value="'.$v['account_id'].'">'.$v['account_name'].'</option>';
		                        }
	                        }
	                        ?>
	                        </select>
                        </td>
					</tr>
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
function <?php echo $cFun;?>(json){
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).dialog("closeCurrent");//关闭
    $(this).navtab("reload",operateWeixinMenuListGetOp());	// 刷新当前Tab页面
    
}
</script>