<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
</style>

<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=system.dev.config&t=config" method="post" data-toggle="validate">
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>开发者配置</b></th></tr></thead>
      <tr>
        <td width="200">运行日志记录</td>
        <td width="350">
        	<input name="runlog" type="radio" data-toggle="icheck" data-label="关闭LOG记录" value="0" <?php if(!RUNLOG){echo 'checked="1"';}?> />
      		<input name="runlog" type="radio" data-toggle="icheck" data-label="开启LOG记录" value="1" <?php if(RUNLOG){echo 'checked="1"';}?>/>
	    </td>
        <td>请谨慎开启，访问量大会占用大量硬盘空间。可以对访问请求，sql日志进行记录，并且记录到缓存文件夹。</td>
      </tr>
      <tr>
        <td width="200">DEBUG模式</td>
        <td width="350">
        	<input name="debug" type="radio" data-toggle="icheck" data-label="关闭DEBUG模式" value="0" <?php if(!DEBUG){echo 'checked="1"';}?> />
      		<input name="debug" type="radio" data-toggle="icheck" data-label="开启DEBUG模式" value="1" <?php if(DEBUG){echo 'checked="1"';}?>/>
	    </td>
        <td>DEBUG模式开启可以显示详细的错误、警告等信息</td>
      </tr>
      <tr>
        <td>开发者模式</td>
        <td width="350">
        	<input name="developer" type="radio" data-toggle="icheck" data-label="关闭开发者模式" value="0" <?php if(!DEVELOPER){echo 'checked="1"';}?> />
      		<input name="developer" type="radio" data-toggle="icheck" data-label="开启开发者模式" value="1" <?php if(DEVELOPER){echo 'checked="1"';}?>/>
	    </td>
	    <td>开发者模式可以看到当前网页引用的文件和执行的sql语句。</td>
      </tr>
      <tr>
        <td style="color: red"><b>错误致命模式<b></td>
        <td width="350">
        	<input name="err" type="radio" data-toggle="icheck" data-label="关闭错误致命模式" value="0" <?php if(!ERR){echo 'checked="1"';}?> />
      		<input name="err" type="radio" data-toggle="icheck" data-label="开启错误致命模式" value="1" <?php if(ERR){echo 'checked="1"';}?>/>
	    </td>
        <td>默认为关闭，开启后一旦出现非致命错误程序将停止执行<span style="color: red">包括后台，请谨慎开启</span>。</td>
      </tr>
    </table>
    
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>其他开发设置</b></th></tr></thead>
      <tr>
        <td>404错误统计</td>
        <td width="350">
        	<input name="notfound" type="radio" data-toggle="icheck" data-label="关闭404错误统计" value="0" <?php if(!NOTFOUND){echo 'checked="1"';}?> />
      		<input name="notfound" type="radio" data-toggle="icheck" data-label="开启404错误统计" value="1" <?php if(NOTFOUND){echo 'checked="1"';}?>/>
	    </td>
        <td>开启后可以统计所有出现404错误的页面。需要将默认的404页面指向为根目录的404.php</td>
      </tr>
      <tr>
        <td>500错误统计</td>
        <td width="350">
        	<input name="serverer" type="radio" data-toggle="icheck" data-label="关闭500错误统计" value="0" <?php if(!SERVERER){echo 'checked="1"';}?> />
      		<input name="serverer" type="radio" data-toggle="icheck" data-label="开启500错误统计" value="1" <?php if(SERVERER){echo 'checked="1"';}?>/>
	    </td>
	    <td>开启后可以统计所有出现500错误的页面。需要将默认的500页面指向为根目录的500.php</td>
      </tr>
      <tr>
        <td width="200">蜘蛛爬行统计</td>
        <td width="350">
        	<input name="spider" type="radio" data-toggle="icheck" data-label="关闭蜘蛛统计" value="0" <?php if(!SPIDER){echo 'checked="1"';}?> />
      		<input name="spider" type="radio" data-toggle="icheck" data-label="开启蜘蛛统计" value="1" <?php if(SPIDER){echo 'checked="1"';}?>/>
	    </td>
	    <td>开启后可以统计所有蜘蛛爬行的记录，静态页面无法统计。</td>
      </tr>
      <tr>
        <td colspan="4">开启后请到网站SEO菜单查看详细数据</td>
      </tr>
    </table>
    
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table warning_set">
      <thead><tr><th colspan="4" style="text-align:left;"><b>预警通知设置</b></th></tr></thead>
      <tr>
        <td colspan="4"><b style="color: red">开启预警通知请确保对应的通道已经开启并且能正常接收消息</b></td>
      </tr>
      <tr id="warning_open_box">
        <td width="200">预警通知</td>
        <td width="350">
            <?php echo $manager->GetForm('dev','warning_open');?>
	    </td>
        <td>开启后可以推送后台登录、代码错误消息通知</td>
      </tr>
      <tr id="warning_channel_box" class="warning_open hide">
        <td>预警通道</td>
        <td width="350">
            <?php echo $manager->GetForm('dev','warning_channel');?>
	    </td>
	    <td>邮件和手机短信通道必须设置收件人</td>
      </tr>
      <tr id="warning_hook_channel_box" class="warning_open hide">
        <td>WebHook通道</td>
        <td width="350">
            <?php echo $manager->GetForm('dev','warning_hook_channel');?>
	    </td>
	    <td>请选择接受消息的WebHook</td>
      </tr>
      <tr id="warning_receive_box" class="warning_open hide">
        <td>预警通道收件人</td>
        <td width="350">
            <?php echo $manager->GetForm('dev','warning_receive');?>
	    </td>
	    <td>收件人的邮件地址或手机号</td>
      </tr>
      <tr id="warning_type_box" class="warning_open hide">
        <td>通知类型</td>
        <td width="350">
            <?php echo $manager->GetForm('dev','warning_type');?>
	    </td>
	    <td>勾选后出现对应事件会发送对应的消息通知。</td>
      </tr>
    </table>
  </form>
</div>


<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-green" data-icon="save">保存</button></li>
    </ul>
</div>

<script>
function SetOpen(open){
    if( open == '1'){
        $('#warning_channel_box').removeClass('hide');
    }else{
        $('.warning_open').addClass('hide');
    }
}
function SetChannel(channel){
    if( channel == 'webhook'){
        $('#warning_hook_channel_box').removeClass('hide');
        $('#warning_receive_box').addClass('hide');
    }else{
        $('#warning_hook_channel_box').addClass('hide');
        $('#warning_receive_box').removeClass('hide');
    }
    $('#warning_type_box').removeClass('hide');
}
$(document).ready(function(){
    SetOpen('<?php echo $warningOpen;?>');
    <?php
        if( $warningOpen=='1'){
            echo 'SetChannel("'.$warningChannel.'")';   
        }
    ?>
    
	$('#warning_open').change(function(){
	    SetOpen($(this).val());
	});
	$('#warning_channel').change(function(){
	    SetChannel($(this).val());
	});
});
</script>