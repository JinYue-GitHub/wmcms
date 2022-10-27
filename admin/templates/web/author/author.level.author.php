<div class="bjui-pageHeader">
	<div class="bjui-searchBar">
   		<button type="button" class="btn-green" data-toggle="dialog" data-id="<?php echo $cFun;?>add" data-mask="true" data-url="index.php?c=author.level.author.edit&t=add" data-width="480" data-height="320" data-title="添加作者等级" data-icon="plus">添加作者等级</button>&nbsp;
    </div>
</div>

<div class="bjui-pageContent">
	<ul class="nav nav-tabs" role="tablist" id="<?php echo $cFun?>tabs">
		<li class="active"><a href="#<?php echo $cFun;?>novel" data-id="0" role="tab" data-toggle="tab">小说作者等级</a></li>
		<li><a href="#<?php echo $cFun;?>article" data-id="0" role="tab" data-toggle="tab">文章作者等级</a></li>
	</ul>
	
	<form name="<?php echo $cFun;?>EditForm" action="index.php?a=yes&c=author.level&t=upall" data-reload="false" data-toggle="validate" method="post" data-callback="<?php echo $cFun;?>ajaxCallBack">	
		<div class="tab-content">
			<?php 
  			$i = 1;
  			foreach ($lvArr as $key=>$val)
  			{
  			?>
  			<div class="tab-pane fade <?php if($i==1){echo 'active in';}?>" id="<?php echo $cFun.$type.$key;?>">
            	<table class="table table-border table-bordered table-bg table-sort">
				    <tr>
				      <td>等级名字</td>
				      <td width="240">等级开始经验</td>
				      <td width="240">等级结束经验</td>
				      <td width="200">等级排序</td>
				      <td width="150">操作</td>
				    </tr>
				    <?php 
				    foreach ($val as $k=>$v)
				    {
				    	echo '<tr>
					      <td><input type="hidden" name="level['.$v['level_id'].'][id][level_id]" value="'.$v['level_id'].'">
							<input name="level['.$v['level_id'].'][data][level_name]" value="'.$v['level_name'].'" type="text" size="20"/></td>
					      <td><input name="level['.$v['level_id'].'][data][level_start]" value="'.$v['level_start'].'"  type="text" size="10"/></td>
					      <td><input name="level['.$v['level_id'].'][data][level_end]" value="'.$v['level_end'].'"  type="text" size="10"/></td>
					      <td><input name="level['.$v['level_id'].'][data][level_order]"  value="'.$v['level_order'].'"  type="text" size="10"/></td>
					     <td>
	  						<a class="btn btn-secondary radius size-MINI '.$cFun.'updateAjax">修改</a>
	  						<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['level_id'].')">删除</a></td>
					    </tr>';
				    }
				    ?>
			    </table>
			</div>
			<?php
			$i++;
			}
			?>
		</div>
	</form>
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
		ajaxData.start = obj.find('input').eq(2).val();
		ajaxData.end = obj.find('input').eq(3).val();
		ajaxData.order = obj.find('input').eq(4).val();
		ajaxOptions['data'] = ajaxData;
		ajaxOptions['type'] = 'POST';
		ajaxOptions['url'] = "index.php?a=yes&c=author.level&t=update&id="+id;
		ajaxOptions['confirmMsg'] = "确定要修改当前作者等级吗？";
		$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
	});
});
//删除等级
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	ajaxOptions['url'] = "index.php?a=yes&c=author.level&t=del&id="+id;
	ajaxOptions['confirmMsg'] = "确定要删除当前作者等级吗？";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
</script>