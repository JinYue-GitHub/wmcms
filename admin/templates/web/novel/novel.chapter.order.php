<div class="bjui-pageContent">
    <form action="index.php?a=yes&c=novel.chapter&t=order" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="nid" type="hidden" class="input-text" value="<?php echo $nid;?>">
	<input name="cid" type="hidden" class="input-text" value="<?php echo $cid;?>">
		<fieldset>
			<legend>小说推荐属性编辑</legend>
            <div class="tab-pane fade active in">
				<table class="table table-border table-bordered table-bg table-sort">
					<tr>
					    <td valign="top" width="30%">
					    	<b>移动到：</b>
	                        <select style="border: 1px solid #c3ced5;height: 25px;border-radius: 4px;width: 250px;" name="order">
							<?php 
	                        if( $data )
	                        {
		                        foreach ($data as $k=>$v)
		                        {
		                        	echo '<option value="'.$v['chapter_order'].'">'.$v['chapter_name'].'</option>';
		                        }
	                        }
	                        ?>
	                        </select>
							<select data-toggle="selectpicker" name="localtion">
								<option value="0">前面</option>
								<option value="1">后面</option>
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
    $(this).navtab("reload",novelChapterListGetOp());	// 刷新当前Tab页面
}
</script>
