<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
</style>
<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=system.cache&t=config" method="post" data-toggle="validate">
	<fieldset>
		<legend>缓存配置</legend>
        <ul class="nav nav-tabs" role="tablist">
        	<li class="active"><a href="#<?php echo $cFun.$type;?>default" role="tab" data-toggle="tab">通用设置</a></li>
            <li><a href="#<?php echo $cFun.$type;?>module" id="<?php echo $cFun;?>senior_tab" role="tab" data-toggle="tab">页面区块专用缓存</a></li>
        </ul>
		<div class="tab-content" id="<?php echo $cFun.$type;?>box">
            <div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>default">
            	<table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
			      <thead><tr><th colspan="8" style="text-align:left;"><b>缓存机制设置</b></th></tr></thead>
			      <tr>
			        <td width="150">普通缓存开关：</td>
			        <td><?php echo $manager->GetForm('cache' , 'cache_open');?></td>
			        <td>缓存机制：</td>
			        <td><?php echo $manager->GetForm('cache' , 'cache_mechanism');?></td>
			        <td>缓存类型：</td>
			        <td><?php echo $manager->GetForm('cache' , 'cache_type');?></td>
			        <td>缓存时间：</td>
			        <td><?php echo $manager->GetForm('cache' , 'cache_time');?><br/>
			        	<span style="color: red">如果功能专用缓存时间为0则使用通用保存时间</span>
			        </td>
			      </tr>
			      <tr>
			        <td>SQL缓存开关：</td>
			        <td colspan="3"><?php echo $manager->GetForm('cache' , 'cache_sql');?></td>
			        <td>缓存类型：</td>
			        <td><?php echo $manager->GetForm('cache' , 'cache_sqltype');?></td>
			        <td>缓存时间：</td>
			        <td><?php echo $manager->GetForm('cache' , 'cache_sqltime');?></td>
			      </tr>
			      <tr>
			        <td>队列缓存开关：</td>
			        <td colspan="3"><?php echo $manager->GetForm('cache' , 'cache_queue');?></td>
			        <td>缓存类型：</td>
			        <td><?php echo $manager->GetForm('cache' , 'cache_queuetype');?></td>
			        <td>缓存时间：</td>
			        <td><?php echo $manager->GetForm('cache' , 'cache_queuetime');?></td>
			      </tr>
			    </table>
			
			    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
			      <thead><tr><th colspan="4" style="text-align:left;"><b>缓存保存路径设置<span style="color: red">【路径不能以"/"开头和结尾】</span></b></th></tr></thead>
			      <tr>
			        <td>缓存保存主路径：</td>
			        <td colspan="3"><?php echo $manager->GetForm('cache' , 'cache_path');?></td>
			      </tr>
			      <tr>
			        <td width="200">普通缓存路径：</td>
			        <td><?php echo $manager->GetForm('cache' , 'file_folder');?></td>
			        <td>普通缓存后缀：</td>
			        <td><?php echo $manager->GetForm('cache' , 'file_ext');?></td>
			      </tr>
			      <tr>
			        <td>SQL缓存路径：</td>
			        <td><?php echo $manager->GetForm('cache' , 'sql_folder');?></td>
			        <td>SQL缓存后缀：</td>
			        <td><?php echo $manager->GetForm('cache' , 'sql_ext');?></td>
			      </tr>
			      <tr>
			        <td>队列缓存路径：</td>
			        <td><?php echo $manager->GetForm('cache' , 'queue_folder');?></td>
			        <td>队列缓存后缀：</td>
			        <td><?php echo $manager->GetForm('cache' , 'queue_ext');?></td>
			      </tr>
			    </table>
			    
			    
			    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
			      <thead><tr><th colspan="4" style="text-align:left;"><b>缓存服务器设置</b></th></tr></thead>
			      <tr>
			        <td width="200">redis服务器：</td>
			        <td><?php echo $manager->GetForm('cache' , 'redis_host');?></td>
			        <td>redis端口：</td>
			        <td><?php echo $manager->GetForm('cache' , 'redis_port');?></td>
			      </tr>
			      <tr>
			        <td width="200">memcached服务器：</td>
			        <td><?php echo $manager->GetForm('cache' , 'memcached_host');?></td>
			        <td>memcached端口：</td>
			        <td><?php echo $manager->GetForm('cache' , 'memcached_port');?></td>
			      </tr>
			    </table>
			</div>
			
		    <div class="tab-pane fade" id="<?php echo $cFun.$type;?>module">
		    	<table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
			      <thead><tr><th colspan="6" style="text-align:left;"><b>全站通用(单位：秒)</b></th></tr></thead>
			      <tr>
			        <td width="200">全站首页：</td>
			        <td colspan="5"><?php echo $manager->GetForm('cache' , 'cache_index');?></td>
			      </tr>
			    </table>
			    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
			      <thead><tr><th colspan="6" style="text-align:left;"><b>模块类首页(单位：秒)</b></th></tr></thead>
			      <tr>
			        <td>模块首页：</td>
			        <td><?php echo $manager->GetForm('cache' , 'cache_module_index');?></td>
			        <td>模块分类首页：</td>
			        <td><?php echo $manager->GetForm('cache' , 'cache_module_tindex');?></td>
			        <td>模块排行首页：</td>
			        <td><?php echo $manager->GetForm('cache' , 'cache_module_topindex');?></td>
			      </tr>
			    </table>
			    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
			      <thead><tr><th colspan="6" style="text-align:left;"><b>模块类列表页(单位：秒)</b></th></tr></thead>
			      <tr>
			        <td>模块列表页：</td>
			        <td><?php echo $manager->GetForm('cache' , 'cache_module_type');?></td>
			        <td>模块评论列表页：</td>
			        <td><?php echo $manager->GetForm('cache' , 'cache_module_replay');?></td>
			        <td>模块搜索列表页：</td>
			        <td><?php echo $manager->GetForm('cache' , 'cache_module_search');?></td>
			      </tr>
			      <tr>
			        <td>模块排行列表页：</td>
			        <td colspan="5"><?php echo $manager->GetForm('cache' , 'cache_module_toplist');?></td>
			      </tr>
			    </table>
			    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
			      <thead><tr><th colspan="6" style="text-align:left;"><b>模块类详情页(单位：秒)</b></th></tr></thead>
			      <tr>
			        <td>模块内容(文章内容、小说阅读)页：</td>
			        <td><?php echo $manager->GetForm('cache' , 'cache_module_content');?></td>
			        <td>模块目录页：</td>
			        <td><?php echo $manager->GetForm('cache' , 'cache_module_menu');?></td>
			        <td>模块详情(小说详情)页：</td>
			        <td><?php echo $manager->GetForm('cache' , 'cache_module_info');?></td>
			      </tr>
			    </table>
			</div>
		</div>
  </form>
</div>

<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-green" data-icon="save">保存</button></li>
    </ul>
</div>
<script>
$(document).ready(function(){
	$('#<?php echo $cFun.$type;?>box input').css("width",'80px');
	$('#memcached_host').css("width",'120px');
	$('#redis_host').css("width",'120px');
	
	
	$('#cache_mechanism').change(function(){
		if($(this).val() == 'page' && $('#cache_type').val() != 'file'){
			$('#cache_type').val("file");
			$('#cache_type').selectpicker("refresh");
			$(this).alertmsg('warn', '页面缓存只能使用文件缓存方式!');
			return false;
		}
	});
	$('#cache_type').change(function(){
		if($("#cache_mechanism").val() == 'page' && $(this).val() != 'file'){
			$(this).val("file");
			$(this).selectpicker("refresh");
			$(this).alertmsg('warn', '页面缓存只能使用文件缓存方式!');
			return false;
		}
	});
});
</script>
