<iframe id="<?php echo $cFun;?>Shop_index" src="<?php echo $homeUrl;?>" width="100%" style="border:none;display:none" height="100%"></iframe>
<div id="<?php echo $cFun;?>Shop_tips" style="text-align: center;line-height: 50;">加载中，请稍候....</div>
<script>
//是否允许安装
var installStatus = true;
//已安装的应用
var apps = [<?php if($appArr){foreach($appArr as $k=>$v){echo "'".$k."',";}}?>];
//检测是否安装
function <?php echo $cFun;?>checkInstall(id){
	//已经安装
	if( apps.indexOf(id) != '-1'){
		return true;
	//没有安装
	}else{
		return false;
	}
}
//安装操作
function <?php echo $cFun;?>Install(id){
	if( installStatus == true ){
		//安装中不允许安装
		installStatus = false;
	  	var ajaxOptions=new Array();
	    ajaxOptions['type'] = 'GET';
		ajaxOptions['url'] = "index.php?a=yes&c=cloud.apps&t=install&id="+id;
		ajaxOptions['callback'] = "<?php echo $cFun;?>installCallBack";
		$(this).bjuiajax('doAjax', ajaxOptions);
	}
}
//安装成功回调
function <?php echo $cFun;?>installCallBack(json){
	//修改为允许安装
	installStatus = true;
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	if (json.statusCode == 200){
		//app追加元素。
		apps.push(json.data.id);
		//发送刷新消息
		<?php echo $cFun;?>sendToSon('refresh',{'refresh':true});
	}
}
//向子网页发送消息
function <?php echo $cFun;?>sendToSon(type,data) {
	data['type'] = type;
    window.frames[0].postMessage(data, '<?php echo WMSHOP;?>'); // 触发跨域子页面的messag事件
}

//接受子页面发来的消息
window.addEventListener('message', function(Event) {
	//检测安装
	if( Event.data.type == 'checkInstall' ){
		//发送完成检测消息
		<?php echo $cFun;?>sendToSon('checkInstall',{'return':<?php echo $cFun;?>checkInstall(Event.data.id)});
	//安装
	}else if( Event.data.type == 'install' ){
		<?php echo $cFun;?>Install(Event.data.id);
	}
}, false);

//框架加载完就隐藏提示显示框架，兼容ie
var iframe = document.getElementById("<?php echo $cFun;?>Shop_index");
if (iframe.attachEvent) {
    iframe.attachEvent("onload", function() {
        $('#<?php echo $cFun;?>Shop_index').show();
        $('#<?php echo $cFun;?>Shop_index').css('border','none');
        $('#<?php echo $cFun;?>Shop_tips').hide();
    });
} else {
    iframe.onload = function() {
        $('#<?php echo $cFun;?>Shop_index').show();
        $('#<?php echo $cFun;?>Shop_index').css('border','none');
        $('#<?php echo $cFun;?>Shop_tips').hide();
    };
}
</script>
