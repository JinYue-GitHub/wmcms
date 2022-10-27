<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=system.set.domain&t=domain" method="post" data-toggle="validate">
	<fieldset>
		<legend>域名模版绑定设置</legend>
       		<ul class="nav nav-tabs" role="tablist">
            	<li class="active"><a href="#templates" role="tab" data-toggle="tab">域名设置</a></li>
            	<li><a href="#domain" role="tab" data-toggle="tab">域名绑定</a></li>
            </ul>
            
            <div class="tab-content"> 
            	<div class="tab-pane fade active in" id="templates">   
			    	<table class="table table-border table-bordered table-bg table-sort">
				      	<tr>
			        		<td width="20%"><b>301跳转设置</b><br />
							<span class="STYLE2">域名请不要添加http://，不设置请留空，否则造成网站无法访问</span></td>
			                <td width="30%">
			                	<input name="bdomain" value="<?php echo C('config.web.bdomain');?>" type="text" size="30" placeholder="选填：例如将<?php echo WMDOMAIN?>"/>
			                </td>
	                		<td>跳转到</td>
			                <td>
			                	<input name="ndomain" value="<?php echo C('config.web.ndomain');?>" type="text" size="30" placeholder="选填：跳转到www.<?php echo WMDOMAIN?>"/>
			                </td>
						</tr>
						
				      	<tr>
			        		<td width="20%"><b>是否显示版本标识</b></td>
			                <td colspan="3">
			                	<select data-toggle="selectpicker" name="pt_rep">
			                        <option value="1" <?php if( C('config.web.pt_rep')=='1'){echo 'selected';}?>>显示版本标识</option>
			                        <option value="0" <?php if( C('config.web.pt_rep')=='0'){echo 'selected';}?>>不显示版本标识</option>
			                    </select>
			                </td>
						</tr>
						
						<?php
						$tpArr = array('简洁','3G','触屏','电脑');
						for($i=1 ; $i <= 4 ; $i++)
						{
							echo '<tr>
				        		<td width="20%"><b>'.$tpArr[$i-1].'版绑定域名</b><br />
								<span class="STYLE2">直接访问'.$tpArr[$i-1].'版的域名(无需加http://)</span></td>
				                <td width="30%">
				                	<input name="domain'.$i.'" value="'.C('config.web.domain'.$i).'" placeholder="不绑定请留空" type="text" size="30" />
				                </td>
		                		<td>版本标识</td>
				                <td>
				                	<input name="tpmark'.$i.'" value="'.C('config.web.tpmark'.$i).'" type="text"/>
				                </td>
							</tr>';
						}
						?>
			    	</table>
		    	</div>
		    	
		    	<div class="tab-pane fade" id="domain">   
			    	<table class="table table-border table-bordered table-bg table-sort">
				      	<tr>
			        		<td width="15%"><b>后台域名</b><br />
							<span class="STYLE2">是否绑定直接访问后台的域名，不绑定请留空(域名无需加http://)</span></td>
			                <td width="35%">
			                	<input name="admin_domain" value="<?php echo C('config.web.admin_domain');?>" type="text" size="25"/>
			                </td>
	                		<td width="25%">开启域名认证<br/>
			                	开启后只能通过绑定的后台域名访问管理后台。
			                </td>
			                <td>
			                <select id="admin_domain_access" name="admin_domain_access" data-toggle="selectpicker">
			                	<?php echo $manager->GetConfigOption('domain[admin_domain_access]',$adminDomainAccess);?>
							</select>
			                </td>
						</tr>
						<tr>
			        		<td colspan="4"><span class="STYLE2"><b>以下模块域名不绑定请留空(域名无需加http://)</b></span></td>
						</tr>
						<?php
						foreach ($domainArr as $k=>$v)
						{
							echo '<tr>
				        		<td width="20%"><b>'.$v['domain_title'].'</b></td>
				                <td colspan="3">
				                	<input name="domain['.$v['domain_id'].']" value="'.$v['domain_domain'].'" placeholder="不绑定请留空" type="text" size="30"/>
				                </td>
							</tr>';
						}
						?>
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