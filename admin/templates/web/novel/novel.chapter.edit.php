<div class="bjui-pageContent">
    <form action="index.php?a=yes&c=novel.chapter&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="chapter_id" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>小说章节内容编辑</legend>
            <ul class="nav nav-tabs" role="tablist">
            	<li class="active"><a href="#<?php echo $cFun.$type;?>base" role="tab" data-toggle="tab">基本信息</a></li>
            	<li><a href="#<?php echo $cFun.$type;?>senior" id="<?php echo $cFun;?>senior_tab" role="tab" data-toggle="tab">高级信息</a></li>
            </ul>
            <div class="tab-content">
            	<div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>base">
		        	<table class="table table-condensed table-hover" width="100%">
		            <tbody>
		                <tr>
		                    <td width="50%">
		                        <b>小说名字：</b>
		                        <input type="text" id="<?php echo $cFun;?>Name" data-callback="<?php echo $cFun;?>GetVolume" data-url="index.php?a=yes&c=novel.novel&t=search&st=name&rt=key" data-toggle="autocomplete" name="novel_name" value="<?php echo C('novel_name',null,'data');?>" data-rule="小说名:required" size="30">
		                    </td>
		                </tr>
		                <tr>
		                    <td width="50%">
		                        <b>小说作者：</b>
		                        <input type="text" data-url="index.php?a=yes&c=novel.novel&t=search&st=author&rt=key" data-toggle="autocomplete" name="novel_author" value="<?php echo C('novel_author',null,'data');?>">
		                        (存在同名小说必填)
		                    </td>
		                </tr>
		                <tr>
		                    <td>
		                        <b>章节名字：</b>
		                        <input type="text" name="chapter[chapter_name]" value="<?php echo C('chapter_name',null,'data');?>" data-rule="章节名:required" size="30">
		                    </td>
		                </tr>
		                <tr>
		                    <td>
		                        <b>所属分卷：</b>
		                        <select data-toggle="selectpicker" id="<?php echo $cFun;?>chapter_status" name="chapter[chapter_vid]">
		                        <?php 
		                        if( $vidArr )
		                        {
			                        foreach ($vidArr as $k=>$v)
			                        {
			                        	$selected = str::CheckElse( $v['volume_id'], C('chapter_vid',null,'data') , 'selected=""');
			                        	echo '<option value="'.$v['volume_id'].'" '.$selected.'>'.$v['volume_name'].'</option>';
			                        }
								}
		                        ?>
		                        </select>
		                    </td>
		                </tr>
		                </tr>
		                    <td>
		                        <b>章节状态：</b>
		                        <select data-toggle="selectpicker" name="chapter[chapter_status]">
                                    <option value="0" <?php if(C('chapter_status',null,'data') == '0'){ echo 'selected=""';}?>>待审核</option>
                                    <option value="1" <?php if(C('chapter_status',null,'data') == '1'){ echo 'selected=""';}?>>已通过</option>
                                    <option value="2" <?php if(C('chapter_status',null,'data') == '2'){ echo 'selected=""';}?>>审核失败</option>
                                </select>
		                    </td>
		                </tr>
		                <tr>
		                   <td colspan="2">
		                        <b>章节内容：</b>
		                        <textarea cols="90" rows="16" name="content" data-rule="小说内容:required"><?php echo C('content',null,'data');?></textarea>
		                    </td>
		                </tr>
		            </tbody>
		        </table>
		       </div>
		       
		       <div class="tab-pane fade" id="<?php echo $cFun.$type;?>senior">
		       		<table class="table table-condensed table-hover" width="100%">
		            <tbody>
		            	<tr>
						  <td><b>是否需要会员阅读 ：</b>
		                      <select data-toggle="selectpicker" name="chapter[chapter_islogin]">
                              	<option value="0" <?php if(C('chapter_islogin',null,'data') == '0'){ echo 'selected=""';}?>>无需登录</option>
                                <option value="1" <?php if(C('chapter_islogin',null,'data') == '1'){ echo 'selected=""';}?>>需要登录</option>
                              </select>
					     </td>
						  <td><b>是否需要会员阅读 ：</b>
		                      <select data-toggle="selectpicker" name="chapter[chapter_isvip]">
                                <option value="0" <?php if(C('chapter_isvip',null,'data') == '0'){ echo 'selected=""';}?>>无需VIP阅读</option>
                              	<option value="1" <?php if(C('chapter_isvip',null,'data') == '1'){ echo 'selected=""';}?>>需要VIP阅读</option>
                              </select>
					     </td>
						  <td><b>是否要付费阅读：</b>
		                      <select data-toggle="selectpicker" name="chapter[chapter_ispay]">
                              	<option value="0" <?php if(C('chapter_ispay',null,'data') == '0'){ echo 'selected=""';}?>>无需付费</option>
                                <option value="1" <?php if(C('chapter_ispay',null,'data') == '1'){ echo 'selected=""';}?>>需要付费</option>
                              </select>
                          </td>
						</tr>
		            	<tr>
						  <td colspan="3"><b>章节创建时间：</b>
							<input type="text" name="chapter[chapter_time]" value="<?php echo date('Y-m-d H:i:s',C('chapter_time',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
		            	  </td>
						</tr>
		            	<tr>
						  <td colspan="3"><b>章节顺序 ：</b>
		                      <input type="text" name="chapter[chapter_order]" value="<?php echo C('chapter_order',null,'data');?>" size="7">
					     </td>
						</tr>
		            </tbody>
		        </table>
		       </div>
			</div>
		</fieldset>
	</form>
</div>

<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-green" data-icon="save">保存</button></li>
    </ul>
</div>


<script>
//页面唯一回调函数
function <?php echo $cFun;?>(json){
	var op = novelChapterListGetOp();
	var tabid = 'novel-chapter-list';
	op['id'] = tabid;
	if( json.statusCode == 300 ){
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	}else{
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    	$(this).navtab("reload",op);	// 刷新Tab页面
    	$(this).navtab("switchTab",tabid);	// 切换Tab页面
	}
}
function <?php echo $cFun;?>GetVolume(data){
	var nid = data.key;
	$.post("index.php?a=yes&c=novel.volume&t=getvolume",{volume_nid:nid},function(result){
		var data = $.parseJSON(result).data;
		var optionhtml='';
		for(var i=0;i<data.length;i++){
		   optionhtml += '<option value="'+data[i].volume_id+'">'+data[i].volume_name+'</option>';
		}
		optionhtml = '<option value="1">正文</option>'+optionhtml;
		$('#<?php echo $cFun;?>chapter_status').html(optionhtml);
		$('#<?php echo $cFun;?>chapter_status').selectpicker('refresh');
	});
}


$(document).ready(function(){
	$("#<?php echo $cFun;?>senior_tab").click(function(){
		$('.bjui-lookup').css("line-height",'22px');
		$('.bjui-lookup').css("height",'22px');
	});
});
</script>