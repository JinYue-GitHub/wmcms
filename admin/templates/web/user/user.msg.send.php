<div class="bjui-pageContent">	                       
    <form action="index.php?a=yes&c=user.msg&t=send" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
		<fieldset>
			<legend>发送内信</legend>
    		<table class="table table-border table-bordered table-bg table-sort">
	            <tbody>
	                <tr>
	                    <td colspan="5">
	                        <b>用户id：</b>
	                        <input type="text" name="uid" value="<?php echo $uid?>" data-rule="required">
	                        (0为发送给全部用户，多个用户用,分开)
	                    </td>
	                </tr>
	                <tr>
	                   <td>
	                        <b>消息内容：</b>
							<?php echo Ueditor('height:200px' , 'content', '','editor.user_msg');?>
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