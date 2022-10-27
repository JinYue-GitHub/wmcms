<style>
.<?php echo $cFun?>Table td{text-align:center}
</style>
<div class="bjui-pageHeader">
    <table class="table table-bordered table-hover table-striped table-top">
    		<thead>
    			<tr>
    			<td align="right"><label class="label-control">选项所属配置：</label></td>
                <td>
                	<select data-toggle="selectpicker" data-nextselect="#<?php echo $cFun?>_config_select" data-refurl="index.php?a=yes&c=system.config.config&t=getconfig&id={value}" <?php if($type=='edit' && !DEVELOPER){echo 'readonly disabled';}?>>
                		<option value="">--所属配置--</option>
                		<?php
                		foreach ($groupArr as $k=>$v)
                		{
							$checked = str::CheckElse( $v['group_id'] , C('config_group',null,'data') , 'selected=""' );
                			echo '<option value="'.$v['group_id'].'" '.$checked.'>'.$v['group_remark'].'</option>';
                		}
                		?>
                    </select>
                    <select data-rule="required" id="<?php echo $cFun;?>_config_select" data-toggle="selectpicker" <?php if($type=='edit' && !DEVELOPER){echo 'readonly disabled';}?> data-width="150">
                    <option>请先选择左边</option>
                    </select>
                 </td>
                 <td style="color:red">选择了左边才能添加</td>
                </tr>
             </thead>
	</table>
		
	<div class="bjui-searchBar">
    	<div class="alert alert-info search-inline"><i class="fa fa-info-circle"></i> 双击行可编辑</div>&nbsp;
        <button type="button" <?php if($type=='add'){echo 'readonly disabled';}?> class="btn-green <?php echo $cFun;?>addrow" id="<?php echo $cFun;?>addrow_toggle" data-toggle="tableditadd" data-target="#<?php echo $cFun?>tableedit" data-num="1" data-icon="plus">添加一项</button>&nbsp;
        <button type="button" <?php if($type=='add'){echo 'readonly disabled';}?> class="btn-green <?php echo $cFun;?>addrow" onclick="$(this).tabledit('add', $('#<?php echo $cFun?>tableedit'), 2)">添加两项</button> &nbsp;
		<div class="alert alert-danger search-inline"><i class="fa fa-info-circle"></i> 对于未知的选项请不要修改。</div>
    </div>
</div>
<div class="bjui-pageContent tableContent ">
    <form data-toggle="validate" method="post">
        <table id="<?php echo $cFun?>tableedit" class="table table-bordered table-hover table-striped table-top <?php echo $cFun?>Table" data-action="index.php?a=yes&c=system.config.option&t=<?php echo $type?>" <?php if($type=='edit'){echo 'data-toggle="tabledit"';}?> data-initnum="0" data-single-noindex="true">
            <thead <?php if($type=='add'){echo 'style="display: none"';}?>>
                <tr data-idname="id[option_id]">
                	<input type="hidden" class="<?php echo $cFun;?>config_id" name="data[config_id]" value="<?php echo $id;?>">
                    <th title="选项标题"><input type="text" name="data[option_title]" data-rule="required" size="15"></th>
                    <th title="选项值"><input type="text" name="data[option_value]" data-rule="required" size="5"></th>
                    <th title="显示顺序"><input type="text" name="data[option_order]" data-rule="digits" value="5" size="5"></th>
                    <th title="操作" width="100">
                        <a href="javascript:<?php echo $cFun;?>SetVal();" class="btn btn-green" data-toggle="dosave">保存</a>
                        <a href="javascript:<?php echo $cFun;?>DelTd();" class="btn btn-red row-del" data-confirm-msg="确定要删除该行信息吗？">删除</a>
                    </th>
                </tr>
            </thead>
            <tbody>
            <?php
			if( $data )
			{
				$i = 1;
				foreach ($data as $k=>$v)
				{
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					echo '<tr class="'.$cur.'" data-id="'.$v['option_id'].'">
							<td>'.$v['option_title'].'</td>
							<td width="20%">'.$v['option_value'].'</td>
							<td width="20%">'.$v['option_order'].'</td>
							<td style="text-align: center;" width="25%" data-noedit="true">
        						<button type="button" class="btn-green" data-toggle="doedit">编辑</button>
                        		<a class="btn btn-red row-del" href="index.php?a=yes&c=system.config.option&t=del&id='.$v['option_id'].'" data-toggle="doAjax" data-callback="<?php echo $cFun;?>DelTd" data-confirm-msg="确定要删除该行信息吗？">删除</a> 
                			</td>
						</tr>';
					$i++;
				}
			}
			?>
            </tbody>
        </table>
	</form>
</div>

<script>
//删除项
function <?php echo $cFun;?>DelTd(){
	$(this).tabledit('del');
}
function <?php echo $cFun;?>SetVal(){
    $('#<?php echo $cFun;?>_config_select').val($(this).val());//加入当前选择的配置
}




$(document).ready(function(){
	$('#<?php echo $cFun;?>_config_select').change(function() {
		$('#<?php echo $cFun?>tableedit thead').css('display','');
		$('.<?php echo $cFun;?>addrow').attr('disabled',false);
		$('.<?php echo $cFun;?>addrow').attr('readonly',false);
	    $("input[name='data\[config_id\]']").val($(this).val());//加入当前选择的配置
		$("#<?php echo $cFun?>tableedit").tabledit();
	});
});
</script>