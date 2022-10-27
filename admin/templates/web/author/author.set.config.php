<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
</style>

<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=author.config&t=edit" method="post" data-toggle="validate">
	
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="6" style="text-align:left;"><b>模块基本设置</b></th></tr></thead>
      <tr>
        <td>开启前台自助申请作者：</td>
        <td><?php echo $manager->GetForm('author' , 'apply_author_open');?></td>
        <td>作者申请默认状态：</td>
        <td colspan="3"><?php echo $manager->GetForm('author' , 'apply_author_status');?></td>
      </tr>
      <tr>
        <td>作者申请默认简介：</td>
        <td><?php echo $manager->GetForm('author' , 'author_default_info');?></td>
        <td>作者申请默认公告：</td>
        <td colspan="3"><?php echo $manager->GetForm('author' , 'author_default_notice');?></td>
      </tr>
      <tr>
        <td>审核通过的消息：</td>
        <td><?php echo $manager->GetForm('author' , 'author_apply_1');?></td>
        <td>拒绝通过的消息：</td>
        <td><?php echo $manager->GetForm('author' , 'author_apply_2');?></td>
        <td>变为未审核的消息：</td>
        <td><?php echo $manager->GetForm('author' , 'author_apply_0');?></td>
      </tr>
    </table>
    
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table" id="<?php echo $cFun.$type?>Opera">
      <thead><tr><th colspan="6" style="text-align:left;"><b>作者道具打赏订阅收入模式</b></th></tr></thead>
      <tr>
        <td width="180">作者道具打赏订阅收入模式</td>
        <td>
        	<?php echo $manager->GetForm('author' , 'author_income_type');?>
        </td>
      </tr>
      <tr>
        <td width="160">收入直接转入作者账户</td>
        <td>该模式对于道具打赏、金币打赏和订阅收入等<b style="color:red">直接转入作者的对应金币账户</b></td>
      </tr>
      <tr>
        <td width="160">人工结算模式</td>
        <td>该模式对于道具打赏、金币打赏和订阅收入等会先进入财务模块的待结算报表，可以在后台人工对部分收入确认转账后进行批量结算后。<b style="color:red">结算后资金不会进入作者账户</b>。
        </td>
      </tr>
      <thead><tr><th colspan="6" style="text-align:left;"><b>销售统计财务申请模式</b></th></tr></thead>
      <tr>
        <td width="180">销售统计财务申请模式</td>
        <td>
        	<?php echo $manager->GetForm('author' , 'author_finance_apply_type');?>
        </td>
      </tr>
      <tr>
        <td width="160">财务申请处理后资金转入作者账户</td>
        <td>在小说结算统计页面发起财务申请并且处理通过后资金会<b style="color:red">直接转入作者的对应金币账户</b></td>
      </tr>
      <tr>
        <td width="160">人工结算模式</td>
        <td>在小说结算统计页面发起财务申请并且处理通过后<b style="color:red">资金不会进入作者账户</b>。只会对相关作者发送财务申请通过的消息。
        </td>
      </tr>
    </table>
    
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table" id="<?php echo $cFun.$type?>Opera">
      <thead><tr><th colspan="6" style="text-align:left;"><b>模块互动设置</b></th></tr></thead>
      <tr>
        <td width="160">每日登录赠送 </td>
        <td>
        	<?php echo $manager->GetForm('author' , 'login_exp');?> 点作者经验值
        </td>
        <td width="160">一张<?php echo $userConfig['ticket_rec'];?>增加 </td>
        <td>
        	<?php echo $manager->GetForm('author' , 'income_rec');?> 点作者经验值
        </td>
        <td width="160">一张<?php echo $userConfig['ticket_month'];?>增加 </td>
        <td>
        	<?php echo $manager->GetForm('author' , 'income_month');?> 点作者经验值
        </td>
      </tr>
      <tr>
        <td colspan="3">
        	1点作者经验值需要收入 <?php echo $manager->GetForm('author' , 'income_gold1');?> <?php echo $userConfig['gold1_name'];?>
        </td>
        <td colspan="3">
        	1点作者经验值需要收入 <?php echo $manager->GetForm('author' , 'income_gold2');?> <?php echo $userConfig['gold2_name'];?>
        </td>
      </tr>
    </table>
    
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="6" style="text-align:left;"><b>原创小说设置</b></th></tr></thead>
      <tr>
        <td>新小说审核方式：</td>
        <td><?php echo $manager->GetForm('author' , 'author_novel_createnovel');?></td>
        <td>编辑小说是否需要审核：</td>
        <td colspan="3"><?php echo $manager->GetForm('author' , 'author_novel_editnovel');?></td>
      </tr>
      <tr>
        <td>新章节审核方式：</td>
        <td><?php echo $manager->GetForm('author' , 'author_novel_createchapter');?></td>
        <td>编辑章节是否需要审核：</td>
        <td colspan="3"><?php echo $manager->GetForm('author' , 'author_novel_editchapter');?></td>
      </tr>
      <tr>
        <td>封面上传审核方式：</td>
        <td><?php echo $manager->GetForm('author' , 'author_novel_uploadcover');?></td>
        <td>小说封面宽高：</td>
        <td colspan="3">
        	<?php echo $manager->GetForm('author' , 'author_novel_coverwidth');?> *
        	<?php echo $manager->GetForm('author' , 'author_novel_coverheight');?> 像素 , 前台上传的封面宽和高不能超过设置的数值
        </td>
      </tr>
      <tr>
        <td>封面审核通过的消息：</td>
        <td><?php echo $manager->GetForm('author' , 'author_novel_cover_1');?></td>
        <td>封面拒绝审核的消息：</td>
        <td><?php echo $manager->GetForm('author' , 'author_novel_cover_2');?></td>
        <td>封面变为未审核的消息：</td>
        <td><?php echo $manager->GetForm('author' , 'author_novel_cover_0');?></td>
      </tr>
      <tr>
        <td>编辑小说通过：</td>
        <td><?php echo $manager->GetForm('author' , 'author_novel_editnovel_1');?></td>
        <td>编辑小说拒绝审核：</td>
        <td><?php echo $manager->GetForm('author' , 'author_novel_editnovel_2');?></td>
        <td>编辑小说未审核：</td>
        <td><?php echo $manager->GetForm('author' , 'author_novel_editnovel_0');?></td>
      </tr>
      <tr>
        <td>章节审核通过的消息：</td>
        <td><?php echo $manager->GetForm('author' , 'author_novel_editchapter_1');?></td>
        <td>章节拒绝审核的消息：</td>
        <td><?php echo $manager->GetForm('author' , 'author_novel_editchapter_2');?></td>
        <td>章节变为未审核的消息：</td>
        <td><?php echo $manager->GetForm('author' , 'author_novel_editchapter_0');?></td>
      </tr>
    </table>
    
    
    
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="6" style="text-align:left;"><b>文章投稿设置</b></th></tr></thead>
      <tr>
        <td>投稿功能状态：</td>
        <td colspan="5"><?php echo $manager->GetForm('author' , 'author_article_open');?></td>
      </tr>
      <tr>
        <td>创建文章审核方式：</td>
        <td><?php echo $manager->GetForm('author' , 'author_article_createarticle');?></td>
        <td>编辑文章是否需要审核：</td>
        <td colspan="3"><?php echo $manager->GetForm('author' , 'author_article_editarticle');?></td>
      </tr>
      <tr>
        <td>编辑文章拒绝审核：</td>
        <td><?php echo $manager->GetForm('author' , 'author_article_editarticle_1');?></td>
        <td>编辑文章未审核：</td>
        <td><?php echo $manager->GetForm('author' , 'author_article_editarticle_2');?></td>
        <td>编辑文章通过：</td>
        <td><?php echo $manager->GetForm('author' , 'author_article_editarticle_0');?></td>
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
$(document).ready(function(){
	$('#author_novel_coverwidth').css("width",'80px');
	$('#author_novel_coverheight').css("width",'80px');
	
	$('#author_apply_0').attr("style",'width:220px;height:80px');
	$('#author_apply_1').attr("style",'width:220px;height:80px');
	$('#author_apply_2').attr("style",'width:220px;height:80px');
	
	$('#author_novel_cover_0').attr("style",'width:220px;height:80px');
	$('#author_novel_cover_1').attr("style",'width:220px;height:80px');
	$('#author_novel_cover_2').attr("style",'width:220px;height:80px');

	$('#author_novel_editnovel_0').attr("style",'width:220px;height:80px');
	$('#author_novel_editnovel_1').attr("style",'width:220px;height:80px');
	$('#author_novel_editnovel_2').attr("style",'width:220px;height:80px');

	$('#author_novel_editchapter_0').attr("style",'width:220px;height:80px');
	$('#author_novel_editchapter_1').attr("style",'width:220px;height:80px');
	$('#author_novel_editchapter_2').attr("style",'width:220px;height:80px');

	$('#author_article_edit_0').attr("style",'width:220px;height:80px');
	$('#author_article_edit_1').attr("style",'width:220px;height:80px');
	$('#author_article_edit_2').attr("style",'width:220px;height:80px');

	$('#<?php echo $cFun.$type?>Opera input').css("width",'50px');
});
</script>
