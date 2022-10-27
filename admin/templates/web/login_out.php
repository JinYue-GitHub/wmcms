<div class="bjui-pageContent">
    <form action="?a=yes&c=login" data-toggle="validate" method="post">
        <hr>
        <div class="form-group">
            <label for="j_pwschange_oldpassword" class="control-label x85">用户名：</label>
            <input type="text" data-rule="required" name="name" value="" placeholder="用户名" size="20">
        </div>
        <div class="form-group">
            <label for="j_pwschange_oldpassword" class="control-label x85">密码：</label>
            <input type="password" data-rule="required" name="psw" value="" placeholder="密码" size="20">
        </div>
         <?php
         if( C('config.web.admin_login_code') == ' 1' )
         {
             echo '<div class="form-group">
            			<label for="j_pwschange_oldpassword" class="control-label x85">验证码：</label>
			            <img src="../../wmcms/inc/imgcode.php" id="refresh" onClick="document.getElementById(\'refresh\').src=\'../../wmcms/inc/imgcode.php?t=\'+Math.random()" width="54" height="22" class="imgode" />
			            <input type="text" data-rule="required" name="code" value="" placeholder="密码" size="14">
			        </div>';
          }
		?>
    </form>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close">取消</button></li>
        <li><button type="submit" class="btn-default">提交</button></li>
    </ul>
</div>