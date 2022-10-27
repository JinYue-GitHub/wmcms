<?php if(!defined('WMINC'))die();?>
<div class="step">
	<ul>
	<li><em>1</em>检测环境</li>
	<li class="current"><em>2</em>创建数据</li>
	<li><em>3</em>完成安装</li>
	</ul>
</div>

<div class="server">
	<form id="install" action="index.php" method="post">
	<input type="hidden" name="action" value="step4">
	<table width="100%">
	<tr>
		<td class="td1" width="100">模块选择</td>
		<td class="td1" width="200">&nbsp;</td>
		<td class="td1">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3">
			<?php 
			$i = 1;
			foreach ($module as $k=>$v)
			{
				$width="40";
				if( $i== '1' || $i == '5' || $i == '9' || $i == '13' ){
					$width="50";
				}
				echo '<label style="margin-left: '.$width.'px;cursor: pointer;"><input type="checkbox" name="module[]" '.@$v['disabled'].' value="'.$k.'" '.@$v['checked'].'> '.$v['name'].'</label>';
				if($i == '4' || $i == '8' || $i == '12'){
					echo '<br/>';
				}
				$i++;
			}
			?>
		</td>
	</tr>
	</table>
	<table width="100%">
	<tr>
		<td class="td1" width="100">数据库信息</td>
		<td class="td1" width="200">&nbsp;</td>
		<td class="td1">&nbsp;</td>
	</tr>
	<tr>
		<td class="tar">数据库服务器：</td>
		<td><input type="text" name="dbhost" value="localhost" class="input"></td>
		<td><span class="gray">数据库服务器地址，一般为localhost</span></td>
	</tr>
	<tr>
		<td class="tar">数据库端口：</td>
		<td><input type="text" name="dbport" value="3306" class="input"></td>
		<td><span class="gray">默认为3306</span></td>
	</tr>
	<tr>
	  <td class="tar">数据库类型：</td>
	  <td><input type="radio" value="mysql" CHECKED />&nbsp;<label for="mysql">MySQL</label>
	  </td>
	  <td></td>
	</tr>
	<tr>
		<td class="tar">数据库用户名：</td>
		<td><input type="text" name="dbuser" value="root" class="input"></td>
		<td><span class="gray">数据库登录用户名</span></td>
		<td></td>
	</tr>
	<tr>
		<td class="tar">数据库密码：</td>
		<td><input type="text" name="dbpw" value="" class="input"></td>
		<td><span class="gray">数据库登录密码</span></td>
		<td></td>
	</tr>
	<tr>
		<td class="tar">数据库名：</td>
		<td><input type="text" name="dbname" value="" class="input"></td>
		<td><span class="gray">如果没有新建权限请使用默认数据库</span></td>
	</tr>
	<tr>
		<td class="tar">表前缀：</td>
		<td><input type="text" name="pre" value="wm_" class="input"></td>
		<td><span class="gray">默认为wm_</span></td>
	</tr>
	</table>
	<table width="100%">
	<tr>
		<td class="td1" width="100">管理员信息</td>
		<td class="td1" width="200">&nbsp;</td>
		<td class="td1">&nbsp;</td>
	</tr>
	<tr>
		<td class="tar">管理员邮箱：</td>
		<td><input type="text" name="adminemail" value="" class="input"></td>
		<td><span class="gray">后台管理员登录的账号</span></td>
	</tr>
	<tr>
		<td class="tar">用户名：</td>
		<td><input type="text" name="adminuser" value="admin" class="input"></td>
		<td><span class="gray">后台管理员登录的账号</span></td>
	</tr>
	<tr>
		<td class="tar">密码：</td>
		<td><input type="text" name="adminpsw" class="input"></td>
		<td><span class="gray">输入后台管理员登录密码</span></td>
	</tr>
	<tr>
		<td class="tar">重复密码：</td>
		<td><input type="text" name="admincpsw" class="input"></td>
		<td><span class="gray">重复一次管理员密码</span></td>
		<td></td>
	</tr>
	</table>
	</form>
</div>


<div class="bottom tac">
	<a href="javascript:;" onclick='window.location.href="javascript:history.go(-1)";return false;' class="btn">上一步</a><a href="javascript:;" id="next" onclick="postcheck();return false;" class="btn">下一步</a>
</div>

<script type="text/javascript">
var isSub = false;
function postcheck(){
	if( isSub == true ){
		return false;
	}
	var obj = document.getElementById('install');
	if (!obj.dbhost.value) {
		alert('数据库服务器不能为空');
		obj.dbhost.focus();
		return false;
	} else if (!obj.dbport.value) {
		alert('数据库端口不能为空');
		obj.dbport.focus();
		return false;
	} else if (!obj.dbuser.value) {
		alert('数据库用户名不能为空');
		obj.dbuser.focus();
		return false;
	} else if (!obj.dbname.value) {
		alert('数据库名不能为空');
		obj.dbname.focus();
		return false;
	} else if (!obj.adminemail.value) {
		alert('管理员邮箱不能为空');
		obj.adminemail.focus();
		return false;
	} else if (!obj.adminuser.value) {
		alert('管理员用户名不能为空');
		obj.adminuser.focus();
		return false;
	} else if (!obj.adminpsw.value) {
		alert('管理员密码不能为空');
		obj.adminpsw.focus();
		return false;
	} else if (obj.adminpsw.value != obj.admincpsw.value) {
		alert('两次输入密码不同');
		obj.admincpsw.focus();
		return false;
	} else if (!obj.dbpw.value && !confirm('你填的数据库密码为空，是否使用空的数据库密码')) {
		return false;
	}
	else
	{
		isSub = true;
		document.getElementById('next').disabled = 'disabled';
		document.getElementById('next').setAttribute("class", "btn_old");
		document.getElementById('next').innerHTML = '安装中...';
		obj.submit();
	}
}
</script>