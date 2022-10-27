<style>
#<?php echo $type.$cFun?>replay li{width:90px;cursor: pointer;}
#<?php echo $type.$cFun?>editor li{width:90px;cursor: pointer;}
</style>
<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=operate.face&t=install" method="post" data-toggle="validate">
	<fieldset>
		<legend>表情安装使用</legend>
		<ul class="nav nav-tabs" role="tablist">
           <li class="active" id="tab_install"><a href="#<?php echo $type.$cFun?>replay" role="tab" data-toggle="tab">评论表情</a></li>
           <li id="tab_list"><a href="#<?php echo $type.$cFun?>editor" role="tab" data-toggle="tab">编辑器表情</a></li>
		</ul>
		
             
		<div class="design_page">
		    <div class="tab-content">
		    	<div class="tab-pane fade active in" id="<?php echo $type.$cFun?>replay">  
			    	<ul>
		    		<?php
		    		if( $faceArr )
		    		{
			    		foreach ($faceArr as $k=>$v)
			    		{
			    			if( in_array('replay', explode(',', $v['apply'])) )
			    			{
			    				$checked = '';
			    				if( in_array($v['path'], $replayInstall ) )
			    				{
			    					$checked = 'checked="1"';
			    				}
				        		echo '<label><li><div class="img"><img src="'.str_replace('{i}', '1', $v['floder'].'/'.$v['path'].'/'.$v['url']).'" width="70" height="70" class="hoverZoomLink"></div>
							        <div class="ft" style="text-align: center;width: 100%;">
			    					<input name="replay[]" type="checkbox" value="'.$v['path'].'" '.$checked.' data-toggle="icheck"/>
						        </div></label>
			    				<div class="ft" style="text-align: center;width: 100%;"><span class="label label-danger radius">'.$v['name'].'</span></div></li>';
			    			}
			    		}
		    		}
		    		else
		    		{
		    			echo '您暂时没有表情';
		    		}
					?>
		    		</ul>
		    		<div style="clear:both"></div>
				</div>
				
				<div class="tab-pane fade" id="<?php echo $type.$cFun?>editor">  
			    	<ul>
		    		<?php
		    		if( $faceArr )
		    		{
			    		foreach ($faceArr as $k=>$v)
			    		{
			    			if( in_array('editor', explode(',', $v['apply'])) )
			    			{
			    				$checked = '';
			    				if( in_array($v['path'], $editorInstall ) )
			    				{
			    					$checked = 'checked="1"';
			    				}
				        		echo '<label><li><div class="img"><img src="'.str_replace('{i}', '1', $v['floder'].'/'.$v['path'].'/'.$v['url']).'" width="70" height="70" class="hoverZoomLink"></div>
							        <div class="ft" style="text-align: center;width: 100%;">
			    					<input name="editor[]" type="checkbox" value="'.$v['path'].'" '.$checked.' data-toggle="icheck"/>
						        </div></label>
			    				<div class="ft" style="text-align: center;width: 100%;"><span class="label label-danger radius">'.$v['name'].'</span></div></li>';
			    			}
			    		}
		    		}
		    		else
		    		{
		    			echo '您暂时没有表情';
		    		}
					?>
		    		</ul>
				</div>
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
$(document).ready(function(){
});
</script>