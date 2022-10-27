<div class="bjui-pageHeader">
	<div class="bjui-searchBar">
   		<button type="button" class="btn-green" data-toggle="dialog" data-id="<?php echo $cFun;?>add" data-mask="true" data-url="index.php?c=author.level.sign.edit&t=add" data-width="465" data-height="350" data-title="添加签约等级" data-icon="plus">添加签约等级</button>&nbsp;
    	<span style="color: red">注意：签约只是获得作品版权，所有收入必须是上架后才能体现。</span>
    </div>
</div>

<div class="bjui-pageContent">
	<ul class="nav nav-tabs" role="tablist" id="<?php echo $cFun?>tabs">
		<li class="active"><a href="#<?php echo $cFun;?>novel" data-id="0" role="tab" data-toggle="tab">小说签约等级</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane fade active in">
			<form name="<?php echo $cFun;?>EditForm" action="index.php?a=yes&c=author.sign&t=upall" data-reload="false" data-toggle="validate" method="post" data-callback="<?php echo $cFun;?>ajaxCallBack">
	  			<table class="table table-border table-bordered table-bg table-sort">
			    <tr>
			      <td>签约等级</td>
			      <td width="240">收入分成比例【如：7(网站):3(作者)】</td>
			      <td width="240">用户阅读：每千字/<?php echo $config['gold1_name']?></td>
			      <td width="200">用户阅读：每千字/<?php echo $config['gold2_name']?></td>
			      <td width="150">排序</td>
				  <td width="150">操作</td>
			    </tr>
			    <?php 
			    if( isset($lvArr['novel']) )
			    {
				    foreach ($lvArr['novel'] as $k=>$v)
				    {
				    	echo '<tr>
					      <td><input type="hidden" name="level['.$v['sign_id'].'][id][sign_id]" value="'.$v['sign_id'].'">
							<input name="level['.$v['sign_id'].'][data][sign_name]" value="'.$v['sign_name'].'" type="text" size="20"/></td>
					      <td><input name="level['.$v['sign_id'].'][data][sign_divide]" value="'.$v['sign_divide'].'" type="text" size="20"/></td>
					      <td><input name="level['.$v['sign_id'].'][data][sign_gold1]" value="'.$v['sign_gold1'].'"  type="text" size="10"/></td>
					      <td><input name="level['.$v['sign_id'].'][data][sign_gold2]" value="'.$v['sign_gold2'].'"  type="text" size="10"/></td>
					      <td><input name="level['.$v['sign_id'].'][data][sign_order]"  value="'.$v['sign_order'].'"  type="text" size="10"/></td>
					     <td>
	  						<a class="btn btn-secondary radius size-MINI '.$cFun.'updateAjax">修改</a>
	  						<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['sign_id'].')">删除</a></td>
					    </tr>';
				    }
				}
				else
				{
					echo '<tr><td colspan="5" style="text-align:center">暂无数据!</td></tr>';
				}
			    ?>
			    </table>
			</form>
		</div>
	</div>
</div>
			  
<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close btn btn-danger size-MINI radius"><i class="fa fa-times"></i> 关闭</button></li>
        <li><button type="submit" class="btn btn-success size-MINI radius"><i class="fa fa-floppy-o"></i> 全部更改</button></li>
    </ul>
</div>



<script type="text/javascript">
var module = 'novel';
$(document).ready(function(){

	//修改等级
	$(".<?php echo $cFun.'updateAjax';?>").click(function(){
		var ajaxOptions=new Array();
		var ajaxData=new Object();
		var obj = $(this).parent().parent();
		var id = obj.find('input').eq(0).val();
		
		ajaxData.name = obj.find('input').eq(1).val();
		ajaxData.divide = obj.find('input').eq(2).val();
		ajaxData.gold1 = obj.find('input').eq(3).val();
		ajaxData.gold2 = obj.find('input').eq(4).val();
		ajaxData.order = obj.find('input').eq(5).val();
		ajaxOptions['data'] = ajaxData;
		ajaxOptions['type'] = 'POST';
		ajaxOptions['url'] = "index.php?a=yes&c=author.sign&t=update&id="+id;
		ajaxOptions['confirmMsg'] = "确定要修改当前作者等级吗？";
		$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
	});
});
//删除等级
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	ajaxOptions['url'] = "index.php?a=yes&c=author.sign&t=del&id="+id;
	ajaxOptions['confirmMsg'] = "确定要删除当前签约等级吗？";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
</script>