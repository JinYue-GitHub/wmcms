<style>
.form-horizontal .row {display: table;width: 100%;}
.form .row {margin-top: 15px;}
.form-horizontal .form-label {margin-top: 8px;cursor: text;text-align: right;padding-right: 10px; font-size: 14px;font-weight: normal;}
</style>

<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=system.tags&t=<?php echo $module.'_'.$type;?>" name="<?php echo $cFun.$type;?>Form" class="form form-horizontal" data-reload="false" data-toggle="ajaxform" method="post" data-callback="<?php echo $cFun.$type;?>">
    	<input type="hidden" name="tags_id" value="<?php echo $id;?>"/>
	    <div class="row cl">
			<label class="form-label col-md-4">标签分类：</label>
	    	<div class="formControls col-md-8">
	        	<select data-toggle="selectpicker" name="data[tags_type_id]">
		      	    <?php
		      	    if( $typeData )
		      	    {
    		      	    foreach ($typeData as $k=>$v)
    		      	    {
    		      	        $selected = '';
    		      	        if( C('tags_type_id',null,'data')==$v['type_id'] ){
    		      	            $selected = 'selected=""';
    		      	        }
    		      	        echo '<option value="'.$v['type_id'].'" '.$selected.'>'.$v['type_name'].'</option>';
    		      	    }
		      	    }
		      	    ?>
				</select>
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">作者推荐：</label>
	    	<div class="formControls col-md-8">
	        	<select data-toggle="selectpicker" name="data[tags_author_rec]">
		      	    <?php
		      	    foreach ($authorRec as $k=>$v)
		      	    {
		      	        $selected = '';
		      	        if( C('tags_author_rec',null,'data')==$k ){
		      	            $selected = 'selected=""';
		      	        }
		      	        echo '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
		      	    }
		      	    ?>
				</select>
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">标签名字：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" placeholder="标签的名字，多个用,分割" name="data[tags_name]" size="24" value="<?php echo C('tags_name',null,'data');?>">
	    	</div>
	    </div>
	</div>
	</form>
	<div class="bjui-pageFooter">
	    <ul>
	        <li><button type="button" class="btn-close btn btn-danger size-MINI radius"><i class="fa fa-times"></i> 关闭</button></li>
	        <li><button type="submit" class="btn btn-success size-MINI radius"><i class="fa fa-floppy-o"></i> 保存</button></li>
	    </ul>
	</div>
</div>
<script type="text/javascript">
function <?php echo $cFun.$type;?>(json){
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	if(json.statusCode==200){
    	$(this).navtab("reload",systemTagsListGetOp());	// 刷新当前Tab页面
	}
}
</script>