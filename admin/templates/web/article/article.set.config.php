<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
</style>

<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=article.config&t=edit" method="post" data-toggle="validate">
	
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>模块基本设置</b></th></tr></thead>
      <tr>
        <td>前台调用缺省缩略图：</td>
        <td colspan="3"><?php echo $manager->GetForm('article' , 'default_simg');?></td>
      </tr>
      <tr>
        <td width="200">后台发布文章默认状态：</td>
        <td colspan="3"><?php echo $manager->GetForm('article' , 'admin_add');?></td>
      </tr>
      <tr>
        <td>删除数据方式：</td>
        <td colspan="3"><?php echo $manager->GetForm('article' , 'admin_del');?></td>
      </tr>
      <tr>
        <td>文章页检查参数：</td>
        <td colspan="3"><?php echo $manager->GetForm('article' , 'par_article');?></td>
      </tr>
      <tr>
        <td>评论页检查参数：</td>
        <td colspan="3"><?php echo $manager->GetForm('article' , 'par_replay');?></td>
      </tr>
      <tr>
        <td>自动生成文章HTML：</td>
        <td colspan="3"><?php echo $manager->GetForm('article' , 'auto_create_html');?></td>
      </tr>
      <tr>
        <td>文章内容保存方式：</td>
        <td colspan="3"><?php echo $manager->GetForm('article' , 'data_save_type');?></td>
      </tr>
      <tr>
        <td>文件内容保存路径：</td>
        <td colspan="3"><?php echo $manager->GetForm('article' , 'data_save_path');?>（保存方式为文件的时候生效）</td>
      </tr>
    </table>
    

    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="6" style="text-align:left;"><b>模块互动设置</b></th></tr></thead>
       <tr>
        <td width="200">文章顶踩功能：</td>
        <td><?php echo $manager->GetForm('article' , 'dingcai_open');?></td>
        <td width="200">顶踩是需要否登录：</td>
        <td><?php echo $manager->GetForm('article' , 'dingcai_login');?></td>
        <td width="200">每日每篇文章顶踩次数：</td>
        <td><?php echo $manager->GetForm('article' , 'dingcai_count');?></td>
      </tr>
      <tr>
        <td>文章评分功能：</td>
        <td><?php echo $manager->GetForm('article' , 'score_open');?></td>
        <td>评分是否需要登录：</td>
        <td><?php echo $manager->GetForm('article' , 'score_login');?></td>
        <td>每日每篇文章评分次数：</td>
        <td><?php echo $manager->GetForm('article' , 'score_count');?></td>
      </tr>
      <tr>
        <td>文章评论功能：</td>
        <td><?php echo $manager->GetForm('article' , 'replay_open');?></td>
        <td>评论是否需要登录：</td>
        <td colspan="3"><?php echo $manager->GetForm('article' , 'replay_login');?></td>
      </tr>
    </table>
    
    
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>模块搜索设置</b></th></tr></thead>
      <tr>
        <td width="200">文章搜索功能：</td>
        <td><?php echo $manager->GetForm('article' , 'search_open');?></td>
        <td>每次搜索的间隔时间(单位：秒)：</td>
        <td><?php echo $manager->GetForm('article' , 'search_time');?></td>
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
	$('#data_save_path').css("width",'350px');
	$('#default_simg').css("width",'300px');
	$('#dingcai_count').css("width",'50px');
	$('#score_count').css("width",'50px');
});
</script>
