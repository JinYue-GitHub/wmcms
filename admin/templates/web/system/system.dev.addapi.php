<style>
.<?php echo $cFun?>Table,.<?php echo $cFun?>SeoTable,.<?php echo $cFun?>UrlTable{margin-bottom:20px}
.create,.urlTr,.seoTr{display:none}
</style>

<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=system.dev.addapi&t=add" method="post" data-toggle="validate">
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>接口基本信息</b></th></tr></thead>
      <tr>
        <td>接口类型</td>
        <td width="350">
        	<select data-toggle="selectpicker" name="data[type_id]">
	        	<?php 
	        	foreach ($typeArr as $k=>$v)
	        	{
	        		echo '<option value="'.$v['type_id'].'">'.$v['type_title'].'</option>';
	        	}
	        	?>
        	</select>
	    </td>
	    <td>API所属的类型</td>
      </tr>
      <tr>
        <td width="200">接口开关</td>
        <td width="350">
        	<input name="data[api_open]" type="radio" data-toggle="icheck" data-label="默认开启" checked value="1"  />
        	<input name="data[api_open]" type="radio" data-toggle="icheck" data-label="默认关闭" value="0"  />
	    </td>
        <td>接口的默认开启关闭状态</td>
      </tr>
      <tr>
        <td>接口标识</td>
        <td width="350">
        	<input name="data[api_name]" type="text" placeholder="英文字母，必填！"/>
	    </td>
        <td>只能填写英文字母，如：wxpay</td>
      </tr>
      <tr>
        <td>接口名字</td>
        <td width="350">
        	<input name="data[api_title]" type="text" placeholder="接口的名字！"/>
	    </td>
        <td>接口的中文名，如：微信扫码支付</td>
      </tr>
      <tr>
        <td>接口简称</td>
        <td width="350">
        	<input name="data[api_ctitle]" type="text" placeholder="接口的简称！"/>
	    </td>
        <td>接口的中文名，如：微信</td>
      </tr>
      <tr>
        <td>接口简介</td>
        <td width="350">
        	<input name="data[api_info]" type="text" placeholder="接口的简介！"/>
	    </td>
        <td>接口的简要描述，如：微信扫码支付接口，需要去微信商户平台申请。</td>
      </tr>
      <tr>
        <td>接口显示顺序</td>
        <td width="350">
        	<input name="data[api_order]" type="text" placeholder="接口顺序！"/>
	    </td>
        <td>纯数字，接口的显示顺序，越小越靠前。</td>
      </tr>
    </table>
    
    
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="1" style="text-align:left;"><b>接口参数设置</b></th></tr></thead>
      <tr>
        <td>
        	<select data-toggle="selectpicker" name="base[api_appid][mast]">
	        	<option value="1">使用</option>
	        	<option value="0">不使用</option>
        	</select>
        	<input name="base[api_appid][name]" type="text" value="api_appid"/>
        	&nbsp;&nbsp;<input name="base[api_appid][remark]" type="text" size="40" value="应用appid"/>
	    </td>
      </tr>
      <tr>
        <td>
        	<select data-toggle="selectpicker" name="base[api_apikey][mast]">
	        	<option value="1">使用</option>
	        	<option value="0">不使用</option>
        	</select>
        	<input name="base[api_apikey][name]" type="text" value="api_apikey"/>
        	&nbsp;&nbsp;<input name="base[api_apikey][remark]" type="text" size="40" value="应用apikey"/>
	    </td>
      </tr>
      <tr>
        <td>
        	<select data-toggle="selectpicker" name="base[api_secretkey][mast]">
	        	<option value="1">使用</option>
	        	<option value="0">不使用</option>
        	</select>
        	<input name="base[api_secretkey][name]" type="text" value="api_secretkey"/>
        	&nbsp;&nbsp;<input name="base[api_secretkey][remark]" type="text" size="40" value="应用secretkey"/>
	    </td>
      </tr>
    </table>
    <?php ?>
    
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table" id="<?php echo $cFun?>Option">
      <thead><tr><th colspan="1" style="text-align:left;"><b>接口附加参数</b></th></tr></thead>
      <tr>
        <td>
        	<input name="option[name][]" size="15" type="text" placeholder="英文字母，参数键名"/>
        	<input name="option[title][]" size="15" type="text" placeholder="中文，参数标题"/>
        	<input name="option[value][]" size="15" type="text" placeholder="参数默认值"/>
        	<input name="option[info][]" type="text" size="40" placeholder="参数的基本描述，如：应用appid！"/>
        	&nbsp;&nbsp;<a href="javascript:<?php echo $cFun;?>add()"><i class="fa fa-plus-square"></i></a>
	    </td>
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
function <?php echo $cFun;?>add(){
	var html = '<tr><td>'+
		'<input name="option[name][]" class="form-control" style="width:150px" type="text" placeholder="英文字母，参数键名"/>'+
		' <input name="option[title][]" class="form-control" style="width:150px" type="text" placeholder="中文，参数标题"/>'+
		' <input name="option[value][]" class="form-control" style="width:150px" type="text" placeholder="参数默认值"/>'+
		' <input class="form-control" style="width:400px" name="option[info][]" type="text" placeholder="参数的基本描述，如：应用appid！"/>'+
		'&nbsp;&nbsp;<a href="javascript:<?php echo $cFun;?>add()"><i class="fa fa-plus-square"></i></a>'+
		'&nbsp;&nbsp;<a href="javascript:void(0)" onClick="<?php echo $cFun;?>unadd(this)"><i class="fa fa-minus-square"></i></a></li>'+
		'</td></tr>';
	$("#<?php echo $cFun?>Option").append(html);
	
}
function <?php echo $cFun;?>unadd(obj){
	$(obj).parent().parent().remove();
}
	        	
</script>