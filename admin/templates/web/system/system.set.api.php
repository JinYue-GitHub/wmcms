<div class="bjui-pageContent">
    <form action="index.php?a=yes&c=system.set.api&t=api" id="j_form_form" class="pageForm" data-toggle="validate">
            <fieldset>
                <legend>API接口设置</legend>
                <ul class="nav nav-tabs" role="tablist">
                	<?php
                	$i = true;
                	foreach ($apiArr as $k=>$v)
                	{
                		$v = $v[0];
                		$active = '';
                		if ( $i )
                		{
                			$active = 'active';
                			$i = false;
                		}
                		echo '<li class="'.$active.'"><a href="#'.$v['type_name'].'" role="tab" data-toggle="tab">'.$v['type_title'].'</a></li>';
                	}
                	?>
                </ul>
                
                <div class="tab-content">
                	<?php
                	$i = true;
                	//先输出每个分类的div
                	foreach ($apiArr as $k=>$v)
                	{
                		$active = '';
                		if ( $i )
                		{
                			$active = 'active in';
                			$i = false;
                		}
                		echo '<div class="tab-pane fade '.$active.'" id="'.$v[0]['type_name'].'">';

                		//输出每个分类下面的接口
                		foreach ($v as $key=>$val)
                		{
                			$openCheck1 = $openCheck2 = '';
                			$disabled = '';
                			if ( $val['api_name'] == 'system' )
                			{
                				$openCheck1 = 'checked';
                				$disabled = 'disabled';
                			}
                			if( $val['api_open'] == '1')
                			{
                				$openCheck1 = 'checked';
                			}
                			else
                			{
                				$openCheck2 = 'checked';
                			}
                			$optionStr = $option = '';
                			if( $val['api_option'] != '' )
                			{
                				$option = unserialize($val['api_option']);
                				foreach ($option as $opKey=>$opVal)
                				{
                					$optionStr .= '<tr>
                						<td width="15%">'.$opVal['title'].'：</td>
                						<td><input type="text" '.$disabled.' value="'.$opVal['value'].'" name="'.$val['api_id'].'[api_option]['.$opKey.']" size="25" class="input-text"  /> '.$opVal['info'].'</td>
                						</tr>';
                				}
                			}
                			$base = unserialize($val['api_base']);
                			$idTitle = 'apiid';
                			$keyTitle = 'apikey';
                			$scTitle = 'secretkey';
                			$idCss = $keyCss = $scCss = '';
                			if($base['api_appid']['mast']=='1'&&!empty($base['api_appid']['name'])){
                				$idTitle =$base['api_appid']['name'];
                			}else if($base['api_appid']['mast']=='0'){
                				$idCss = 'display:none';
                			}
                			if($base['api_apikey']['mast']=='1'&&!empty($base['api_apikey']['name'])){
                				$keyTitle =$base['api_apikey']['name'];
                			}else if($base['api_apikey']['mast']=='0'){
                				$keyCss = 'display:none';
                			}
                			if($base['api_secretkey']['mast']=='1'&&!empty($base['api_secretkey']['name'])){
                				$scTitle =$base['api_secretkey']['name'];
                			}else if($base['api_secretkey']['mast']=='0'){
                				$scCss = 'display:none';
                			}
                			echo '<table class="table table-border table-bordered table-hover table-bg table-sort">
								<thead><tr><th colspan="2" style="padding-left:10px;color:red"><b>'.$val['api_title'].'</b></th></tr></thead>
								<tr style="'.$idCss.'">
                					<td width="15%">'.$idTitle.'：</td><td><input type="text" '.$disabled.' value="'.$val['api_appid'].'" name="'.$val['api_id'].'[api_appid]" placeholder="'.GetKey($base,'api_appid,remark').'" size="30" class="input-text"  /></td>
                				</tr>
								<tr style="'.$keyCss.'">
                					<td width="15%">'.$keyTitle.'：</td><td><input type="text" '.$disabled.' value="'.$val['api_apikey'].'" name="'.$val['api_id'].'[api_apikey]" placeholder="'.GetKey($base,'api_apikey,remark').'" size="30" class="input-text"  /></td>
                				</tr>
								<tr style="'.$scCss.'">
                					<td width="15%">'.$scTitle.'：</td><td><input type="text" '.$disabled.' value="'.$val['api_secretkey'].'" name="'.$val['api_id'].'[api_secretkey]" placeholder="'.GetKey($base,'api_secretkey,remark').'" size="30" class="input-text"  /></td>
                				</tr>
								<tr>
                					<td width="15%">接口开关：</td>
                					<td>
	                					<input type="radio" '.$disabled.' '.$openCheck1.' name="'.$val['api_id'].'[api_open]" value="1" data-toggle="icheck" data-label="开启接口">
	                					<input type="radio" '.$disabled.' '.$openCheck2.' name="'.$val['api_id'].'[api_open]" value="0" data-toggle="icheck" data-label="关闭接口">
									</td>
								</tr>
                				'.$optionStr.'
								<tr><td width="15%">接口说明：</td><td>'.$val['api_info'].'</td></tr>
							    </table><br/>';
                		}
                		
                		echo '</div>';
                	}
                	?>
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