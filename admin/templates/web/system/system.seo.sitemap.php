<div class="bjui-pageContent">
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;">
      <b style="color:red;font-size:14px;">提示:保存路径使用的是sitemap的伪静态路径！</b>
      </th></tr></thead>

      <thead>
	      <tr>
	      	<td width="150">HTML地图生成</td>
	        <td><a class="btn btn-success radius size-MINI" onClick="createMap('html')">生成HTML地图</a></td>
	      </tr>
	      <tr>
	      	<td width="150">XML地图生成</td>
	        <td><a class="btn btn-success radius size-MINI" onClick="createMap('xml')">生成XML地图</a></td>
	      </tr>
	      <tr>
	      	<td width="150">XML结构化数据生成</td>
	        <td><a class="btn btn-success radius size-MINI" onClick="createMap('site')">生成XML结构化数据</a></td>
	      </tr>
      <thead>
	 </table>
	</div>
</div>

<script>
//生成首页
function createMap(page){
	var ajaxOptions=new Array();
	ajaxOptions['url'] = "index.php?a=yes&c=system.seo.sitemap&t=sitemap&page="+page;
	ajaxOptions['loadingmask'] = true;
	$(this).bjuiajax('doAjax', ajaxOptions);
}
</script>