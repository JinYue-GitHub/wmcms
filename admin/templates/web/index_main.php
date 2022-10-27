<link href="<?php echo $tempPath;?>/BJUI/css/main.css" rel="stylesheet">
<style>
	code{font-size: 14px;}
	.tongji_box{font-size:14px;padding-bottom: 25px;}
	.span{color:red;font-size:16px}
</style>
<div class="bjui-pageHeader" style="background:#FFF;z-index: 999;">
    <div style="padding: 0 15px;">
        <h4 style="margin-bottom:20px;">
            WMCMS(WeiMeng CMS) 内容管理系统　<small>轻松应用，专注您的梦想，从WMCMS开始！</small>
        </h4>
        <div style="float:left; width:157px;">
            <div class="alert alert-info" role="alert" style="margin:0 0 5px; padding:10px;text-align:center;">
                <img src="<?php echo $tempPath;?>/BJUI/images/ewm.png" width="135">
               	<div style="font-size:16px;color:red;font-weight: bold;margin-top: 10px">扫码捐赠</div>
            </div>
        </div>
        <div style="margin-left:170px; margin-top:22px; padding-left:6px;">
            <a target="_blank" href="javascript:void(0)"><img border="0" src="<?php echo $tempPath;?>/BJUI/images/group.png" alt="wmcms交流群-群1" title="wmcms交流群-群1"></a>
            <span class="label label-default">(1群已满)</span>　
            <a target="_blank" href="http://shang.qq.com/wpa/qunwpa?idkey=987a1c49151af08090fae2ab262caaea009981d5fc0b84bd2c7c0e78a80ec7f0"><img border="0" src="<?php echo $tempPath;?>/BJUI/images/group.png" alt="wmcms交流群-群2" title="wmcms交流群-群2"></a>
            <span style="padding-left:30px;">官方论坛：</span><a href="<?php echo WMBBS;?>/" target="_blank"><?php echo WMBBS;?></a>
        </div>
        
        
        <div class="row" style="margin-left:170px; margin-top:10px;">
            <div class="col-md-6" style="padding:5px;">
                <div class="alert alert-success" role="alert" style="margin:0 0 5px; padding:5px 15px;">
                    <strong>WMCMS团队欢迎你!</strong>
                    <br><span class="label label-default">开发：</span> <a href="#">@未梦（重庆）</a>
                    <br><span class="label label-default">模版：</span> <a href="#">@Kind小丑（深圳）</a> <a href="#">@君子（北京）</a>
                    <br><span class="label label-default">测试 & 试用：</span> <a href="#">@YawZhou（山西）</a> <a href="#">@ReaL（河南）</a>
                    <br><span class="label label-default">推广 & 维护：</span> <a href="#">@Idaho（北京）</a>
                </div>
            </div>
            <div class="col-md-6" style="padding:5px;">
                <div class="alert alert-info" role="alert" style="margin:0 0 5px; padding:5px 15px;">
                    <h5>项目官网：<a href="<?php echo WMURL;?>" target="_blank"><?php echo WMURL;?></a></h5>
                    <h5>域名、IP：<?php echo HTTP_TYPE;?>://<?php echo $_SERVER['SERVER_NAME'];?>(<?php echo HTTP_TYPE;?>://<?php echo gethostbyname($_SERVER["SERVER_NAME"])?>)</h5>
                    <h5>PHP版本：<?php echo phpversion()?></h5>
                    <h5>Zend版本：<?php echo Zend_Version()?></h5>
                    <h5>服务器信息：<?php echo $_SERVER['SERVER_SOFTWARE']?></h5>
                </div>                                  
            </div>
        </div>
    </div>
</div>
<div class="bjui-pageContent">
    <!--<div style="position:absolute;top:15px;right:0;width:300px;">
        <iframe width="100%" height="550" class="share_self" frameborder="0" scrolling="no" src="http://show.v.t.qq.com/index.php?c=show&a=index&n=oulEshiRfnet&h=550&fl=1&l=4&o=29&co=0"></iframe>
    </div>-->
    <div style="margin-top:5px; margin-right:450px; overflow:hidden;">
	    <div style="position:absolute;top:15px;right:0;width:450px;margin-right:10px">
	        <div class="col-md-13">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">每日数据</h3></div>
                    <div class="panel-body">
                    	<div class="tongji_box">今日新增留言 <span class="span"><?php echo $newMessage;?></span> 条， 共有  <span class="span"><?php echo $sumMessage;?></span> 条留言未读 </div>
                    	<div class="tongji_box">今日新增 <span class="span"><?php echo $newUser;?></span> 位用户， 共有 <span class="span"><?php echo $sumUser;?></span> 位用户</div>
                    	<div class="tongji_box">今日新增 <span class="span"><?php echo $newNovel;?></span> 本小说 , 共有 <span class="span"><?php echo $sumNovel;?></span> 本小说</div>
                    </div>
                </div>
            </div>
	    </div>
        <div class="row" style="padding: 0 8px;">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">当前版本：<code>V<?php echo WMVER?></code> 最新版本：<code id="version_new">V<?php echo WMVER?></code>　最近更新日志(<code id="version_time">......</code>)：</h3></div>
                    <div class="panel-body bjui-doc" style="padding:0;">
                        <ul>
                            <li id="version_remark" style="font-size: 15px">加载中...</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--<div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">标题</h3></div>
                    <div class="panel-body">
                        <iframe width="100%" height="240" class="share_self" frameborder="0" scrolling="no" src="*.html"></iframe>
                    </div>
                </div>
            </div>-->
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
	var op = new Array();
	op['type'] = 'GET';
	op['reload'] = 'false';
	op['url'] = "index.php?a=yes&c=cloud.version&t=getnew";
	op['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(this).bjuiajax("doAjax",op);// 显示处理结果
});
function <?php echo $cFun;?>ajaxCallBack(json){
	var data = json.data.data;
	if(data){
		if( '<?php echo WMVER?>' != data['version_number'] )
		{
			$(this).alertmsg("ok", "服务器有新版本可以更新!");
		}
		$("#version_new").html('V'+data['version_number']);
		$("#version_time").html(data['version_addtime']);
		$("#version_remark").html(data['version_remark']);
	}
}
</script>