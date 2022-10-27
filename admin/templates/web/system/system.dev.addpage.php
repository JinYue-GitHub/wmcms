<style>
.<?php echo $cFun?>Table,.<?php echo $cFun?>SeoTable,.<?php echo $cFun?>UrlTable{margin-bottom:20px}
.create,.urlTr,.seoTr{display:none}
</style>

<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=system.dev.addpage&t=add" method="post" data-toggle="validate">
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>新增页面</b></th></tr></thead>
      <tr>
        <td width="200">文件名</td>
        <td width="350">
        	<input name="filename" type="text" value=""  />.php
	    </td>
        <td>文件名字，无需加.php后缀</td>
      </tr>
      <tr>
        <td>所属模块</td>
        <td width="350">
        	<select data-toggle="selectpicker" name="module">
	        	<option value="all">首页控制器</option>
	        	<?php 
	        	foreach ($moduleList as $k=>$v)
	        	{
	        		echo '<option value="'.$v['file'].'">'.$moduleMod->GetModuleName($v['file']).'控制器</option>';
	        	}
	        	?>
        	</select>
	    </td>
	    <td>文件所属模块</td>
      </tr>
      
      <tr class="create">
        <td>页面标识</td>
        <td width="350">
        	<input name="page" type="text"/>
	    </td>
        <td>只能填写英文，无需加模块前缀;如 novel_，系统会自动添加</td>
      </tr>
      <tr class="create">
        <td>页面名字</td>
        <td width="350">
        	<input name="name" type="text"/>
	    </td>
        <td>页面的中文名</td>
      </tr>
    </table>
      
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>UrlTable">
      <thead><tr><th colspan="4" style="text-align:left;"><b>
      		<input name="urlOpen" type="radio" data-toggle="icheck" data-label="不创建URL" value="0" checked="1"/>
      		<input name="urlOpen" type="radio" data-toggle="icheck" data-label="创建URL" value="1"/>
      </b></th></tr></thead>
      <tr class="urlTr">
        <td width="200">动态url地址</td>
        <td width="450">
        	<input name="url[url1]" type="text" size="45"/>
	    </td>
        <td>动态的url地址，格式：/index.php</td>
      </tr>
      <tr class="urlTr">
        <td>传统伪静态url地址</td>
        <td width="350">
        	<input name="url[url2]" type="text" size="45"/>
	    </td>
        <td>伪静态后的url地址，格式：/index.html。可以和动态地址一样</td>
      </tr>
      <tr class="urlTr">
        <td>普通模式url地址</td>
        <td width="350">
        	<input name="url[url3]" type="text" size="45"/>
	    </td>
        <td>普通模式url地址，格式：/?module=novel&file=info&nid={nid}</td>
      </tr>
      <tr class="urlTr">
        <td>兼容模式url地址</td>
        <td width="350">
        	<input name="url[url4]" type="text" size="45"/>
	    </td>
        <td>兼容模式url地址，格式：/?path=/novel/info/nid/{nid}</td>
      </tr>
      <tr class="urlTr">
        <td>PATHINFO模式url地址</td>
        <td width="350">
        	<input name="url[url5]" type="text" size="45"/>
	    </td>
        <td>PATHINFO模式url地址，格式：/index.php/novel/info/nid/{nid}</td>
      </tr>
      <tr class="urlTr">
        <td>REWRITE模式url地址</td>
        <td width="350">
        	<input name="url[url6]" type="text" size="45"/>
	    </td>
        <td>REWRITE模式url地址，格式：/novel/info/nid/{nid}</td>
      </tr>
    </table>
      
      
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>SeoTable">
      <thead><tr><th colspan="4" style="text-align:left;"><b>
      		<input name="seoOpen" type="radio" data-toggle="icheck" data-label="不创建SEO" value="0" checked="1"/>
      		<input name="seoOpen" type="radio" data-toggle="icheck" data-label="创建SEO" value="1"/>
      </b></th></tr></thead>
      <tr class="seoTr">
        <td width="200">标题</td>
        <td width="450">
        	<input name="seo[title]" type="text" size="45"/>
	    </td>
        <td>网页的title信息</td>
      </tr>
      <tr class="seoTr">
        <td>关键词</td>
        <td width="350">
        	<input name="seo[key]" type="text" size="45"/>
	    </td>
        <td>网页的keywords信息</td>
      </tr>
      <tr class="seoTr">
        <td>描述</td>
        <td width="350">
        	<input name="seo[desc]" type="text" size="45"/>
	    </td>
        <td>网页的description的信息</td>
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
$(document).ready(function(){
	var urlOpen=false;
	var seoOpen=false;
	//url创建开关
	$('input[name="urlOpen"]').on('ifChanged', function(e) {
		var checked = $(this).is(':checked'), val = $(this).val()
		if (checked && val=='1'){
			 $(".<?php echo $cFun?>UrlTable .urlTr").show();
			 $(".create").show();
			 urlOpen=true;
		}else{
			 urlOpen=false;
			 $(".<?php echo $cFun?>UrlTable .urlTr").hide();
			 if( urlOpen==false && seoOpen==false ){
				 $(".create").hide();
			 }
		}
	})
	
	//seo创建开关
	$('input[name="seoOpen"]').on('ifChanged', function(e) {
		var checked = $(this).is(':checked'), val = $(this).val()
		if (checked && val=='1'){
			 $(".<?php echo $cFun?>SeoTable .seoTr").show();
			 $(".create").show();
			 seoOpen=true;
		}else{
			 seoOpen=false;
			 $(".<?php echo $cFun?>SeoTable .seoTr").hide();
			 if( urlOpen==false && seoOpen==false ){
				 $(".create").hide();
			 }
		}
	})
});
</script>