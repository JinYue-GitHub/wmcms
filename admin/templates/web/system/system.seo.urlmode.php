<style>
.barline{float:left;margin-right:5px;width:200px;border:1px solid #4DBF7D;text-align:center;height:16px;font-size:8px;border-radius:8px;}
.barline_text_box{line-height:16px;background:#4DBF7D;border-radius:8px;width:0px;color: #3045E6;}
.barline_perc{line-height:16px;}
.barline_msg{margin-top: 11px;}
.<?php echo $cFun;?>sptype_info{display:none}
.urlmode3,.urlmode4,.urlmode5,.urlmode6{display:none}
</style>
<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=system.seo.urlmode&t=config" method="post" data-toggle="validate">
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;">
      <b style="color:red;font-size:14px;">提示:URL可以在“伪静态”里面修改！</b>
      </th></tr></thead>

      <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
		<thead>
		      <tr>
		        <td width="150">是否开启HTML</td>
		        <td width="400">
		        	<?php echo $manager->GetForm( $configArr['ishtml'] );?>
		         	<span class="<?php echo $cFun;?>sptype_info" id="<?php echo $cFun;?>sptype_info_baidu">提示：每次最多只能提交2000条链接，每天可以提交5000000条</span>
				</td>
		        <td>HTML需要手动生成</td>
		      </tr>
		      <tr>
		        <td width="150">URL类型</td>
		        <td>
		        	<?php echo $manager->GetForm( $configArr['url_type'] );?>
				</td>
		        <td id="<?php echo $cFun;?>urldesc"></td>
		      </tr>
		      <tr>
		        <td width="150">URL示例</td>
		        <td id="<?php echo $cFun;?>url" colspan="2"></td>
		      </tr>
		      <tr class="urlmode3 urlmode4 urlmode5 urlmode6">
		        <td width="150">URL参数分割字符</td>
		        <td><?php echo $manager->GetForm( $configArr['urlmode_exp'] );?></td>
		        <td>普通模式、兼容模式、PATHINFO模式、REWRITE模式  使用【默认为/】</td>
		      </tr>
		      <tr class="urlmode4 urlmode5 urlmode6">
		        <td width="150">URL文件后缀[可以为空]</td>
		        <td><?php echo $manager->GetForm( $configArr['urlmode_suffix'] );?></td>
		        <td>兼容模式、PATHINFO模式、REWRITE模式  使用【如：.html】</td>
		      </tr>
		      <tr class="urlmode3">
		        <td width="150">普通模式参数名</td>
		        <td><?php echo $manager->GetForm( $configArr['urlmode_par_odnr1'] );?>&nbsp;&nbsp;<?php echo $manager->GetForm( $configArr['urlmode_par_odnr2'] );?></td>
		        <td>普通模式  使用【默认为module和file】</td>
		      </tr>
		      <tr class="urlmode4">
		        <td width="150">兼容模式参数名</td>
		        <td><?php echo $manager->GetForm( $configArr['urlmode_par_cptb'] );?></td>
		        <td>兼容模式  使用【默认为path】</td>
		      </tr>
		      <tr class="urlmode4 urlmode5 urlmode6">
		        <td width="150">URL路由</td>
		        <td><?php echo $manager->GetForm( $configArr['urlmode_route'] );?></td>
		        <td>兼容模式、PATHINFO模式、REWRITE模式  使用【默认为空】<br/>一行一对；格式为【url路由】=>【实际路由】，如：/info/nid/1=>/novel/info/nid/1</td>
		      </tr>
		</thead>
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
var descArr = new Array();
var urlArr = new Array();
var domain = '<?php echo $sysConfig['tcp_type']['config_value'];?>://'+'<?php echo $sysConfig['weburl']['config_value'];?>';
descArr[1] = 'SEO优化-中，伪静态 - 无';
descArr[2] = 'SEO优化-极高，伪静态 - 极高，需要懂伪静态规则';
descArr[3] = 'SEO优化-低，伪静态 - 无';
descArr[4] = 'SEO优化-低，伪静态 - 无';
descArr[5] = 'SEO优化-低，伪静态 - 无';
descArr[6] = 'SEO优化-高，伪静态 - 高，无需懂伪静态规则';
urlArr[1] = domain+'/module/novel/read.php';
urlArr[2] = domain+'/novel/read.html';
urlArr[3] = domain+'/?module=novel&file=read';
urlArr[4] = domain+'/?path=/novel/read';
urlArr[5] = domain+'/index.php/novel/read';
urlArr[6] = domain+'/novel/read';

$(document).ready(function(){
	$("#urlmode_par_odnr1").css('width','100px');
	$("#urlmode_par_odnr2").css('width','100px');
	$("#urlmode_route").css('width','400px');
	$("#urlmode_route").css('height','250px');
	$("#<?php echo $cFun;?>urldesc").html(descArr[<?php echo $configArr['url_type']['config_value'];?>]);
	$("#<?php echo $cFun;?>url").html(urlArr[<?php echo $configArr['url_type']['config_value'];?>]);
	$(".urlmode<?php echo $configArr['url_type']['config_value'];?>").show();

	//模块选择
	$("#url_type").change(function(){
		$(".urlmode3,.urlmode4,.urlmode5,.urlmode6").hide();
		$("#<?php echo $cFun;?>urldesc").html(descArr[$(this).val()]);
		$("#<?php echo $cFun;?>url").html(urlArr[$(this).val()]);
		$(".urlmode"+$(this).val()).show();
	});
});

function <?php echo $cFun;?>post(){
	var sptype = $("#<?php echo $cFun;?>sptype").val();
	var sptype = $("#<?php echo $cFun;?>sptype").val();
	
	var ajaxOptions=new Array();
	var data=new Object();
	data.sptype = sptype;
	data.module = module;
	data.contentWhere = contentWhere;
	data.startid = startid;
	data.endid = endid;
	data.starttime = starttime;
	data.endtime = endtime;
	data.urls = urls;
	ajaxOptions['data'] = data;
	ajaxOptions['loadingmask'] = true;
	ajaxOptions['reload'] = false;
	ajaxOptions['url'] = "index.php?a=yes&c=system.seo.urlmode&t=config";
	$(this).bjuiajax('doAjax', ajaxOptions);
}
</script>