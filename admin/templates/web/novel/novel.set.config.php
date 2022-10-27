<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
</style>

<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=novel.config&t=edit" method="post" data-toggle="validate">
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>模块基本设置</b></th></tr></thead>
      <tr>
        <td>小说默认封面：</td>
        <td colspan="3"><?php echo $manager->GetForm('novel' , 'cover');?></td>
      </tr>
      <tr>
        <td>小说页检查参数：</td>
        <td><?php echo $manager->GetForm('novel' , 'par_info');?></td>
        <td>评论页检查参数：</td>
        <td><?php echo $manager->GetForm('novel' , 'par_replay');?></td>
      </tr>
      <tr>
        <td>目录页检查参数：</td>
        <td><?php echo $manager->GetForm('novel' , 'par_menu');?></td>
        <td>阅读页检查参数：</td>
        <td><?php echo $manager->GetForm('novel' , 'par_read');?></td>
      </tr>
      <tr><td colspan="4" style="color:red"><b>请勿在中途更改保存路径，否则无法读取小说章节内容</b></td></tr>
      <tr>
        <td width="200">小说文件名自定义加密字符串：</td>
        <td colspan="3">
        	<?php echo $manager->GetForm('novel' , 'novel_en_str');?> 
        	<span style="color:red">默认请留空</span>
        </td>
      </tr>
      <tr>
        <td width="200">小说文件保存路径：</td>
        <td><?php echo $manager->GetForm('novel' , 'novel_save');?></td>
        <td width="200">章节文件保存路径：</td>
        <td><?php echo $manager->GetForm('novel' , 'chapter_save');?></td>
      </tr>
      <tr>
        <td width="200">小说听书音频保存路径：</td>
        <td><?php echo $manager->GetForm('novel' , 'novel_mp3_save');?></td>
        <td width="200">章节听书音频保存路径：</td>
        <td><?php echo $manager->GetForm('novel' , 'chapter_mp3_save');?></td>
      </tr>
      <tr>
        <td>后台发布小说的状态：</td>
        <td><?php echo $manager->GetForm('novel' , 'admin_novel_add');?></td>
        <td>发布章节默认状态：</td>
        <td><?php echo $manager->GetForm('novel' , 'admin_chapter_add');?></td>
      </tr>
      <tr>
        <td>小说发布默认类型：</td>
        <td><?php echo $manager->GetForm('novel' , 'type');?></td>
        <td>自动生成章节HTML：</td>
        <td><?php echo $manager->GetForm('novel' , 'auto_create_html');?></td>
      </tr>
      <tr>
      </tr>
    </table>
    
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table" style="margin-top: -40px">
      <thead><tr><th colspan="4" style="text-align:left;"><b>小说章节设置</b></th></tr></thead>
      <tr>
        <td width="200">相似章节对比模式：</td>
        <td><?php echo $manager->GetForm('novel' , 'chapter_compare');?></td>
        <td width="200">内容相似度(0-100数字)：</td>
        <td><?php echo $manager->GetForm('novel' , 'chapter_compare_number');?></td>
      </tr>
      <tr>
        <td width="200"><span style="color:red">数据入库模式(中途禁止修改)</span>：</td>
        <td><?php echo $manager->GetForm('novel' , 'data_type');?></td>
        <td width="200">章节默认是否需要登录阅读：</td>
        <td><?php echo $manager->GetForm('novel' , 'chapter_isvip');?></td>
      </tr>
      <tr>
        <td>低于多少字数不收费：</td>
        <td><?php echo $manager->GetForm('novel' , 'buy_wordnumber');?></td>
        <td>订阅章节使用金币种类：</td>
        <td><?php echo $manager->GetForm('novel' , 'buy_gold_type');?></td>
      </tr>
      <tr>
        <td>阅读页未订阅提示方式：</td>
        <td><?php echo $manager->GetForm('novel' , 'read_sub_prompt');?></td>
        <td>是否开启下载：</td>
        <td><?php echo $manager->GetForm('novel' , 'down_open');?></td>
      </tr>
      <tr>
        <td>新建小说默认章节名：</td>
        <td colspan="3"><?php echo $manager->GetForm('novel' , 'new_cname');?></td>
      </tr>
      <tr>
        <td>首次生成小说TXT的头部信息：<br/><span style="color:red">留空则表示不插入自定义文字</span></td>
        <td colspan="3"><?php echo $manager->GetForm('novel' , 'novel_head');?></td>
      </tr>
      <tr>
        <td>新建章节TXT开始模版：<br/><span style="color:red">留空则表示不插入自定义文字</span></td>
        <td colspan="3"><?php echo $manager->GetForm('novel' , 'chapter_start');?></td>
      </tr>
      <tr>
        <td>新建章节TXT结束模版：<br/><span style="color:red">留空则表示不插入自定义文字</span></td>
        <td colspan="3"><?php echo $manager->GetForm('novel' , 'chapter_end');?></td>
      </tr>
    </table>
    
    
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table" id="<?php echo $cFun.$type?>Opera">
      <thead><tr><th colspan="6" style="text-align:left;"><b>模块互动设置</b></th></tr></thead>
      <tr>
        <td width="200">顶踩功能：</td>
        <td><?php echo $manager->GetForm('novel' , 'dingcai_open');?></td>
        <td width="200">顶踩是否登录：</td>
        <td><?php echo $manager->GetForm('novel' , 'dingcai_login');?></td>
        <td width="200">每日每本小说顶踩次数：</td>
        <td><?php echo $manager->GetForm('novel' , 'dingcai_count');?></td>
      </tr>
      <tr>
        <td>评分功能：</td>
        <td><?php echo $manager->GetForm('novel' , 'score_open');?></td>
        <td>评分是否登录：</td>
        <td><?php echo $manager->GetForm('novel' , 'score_login');?></td>
        <td>每日每本小说评分次数：</td>
        <td><?php echo $manager->GetForm('novel' , 'score_count');?></td>
      </tr>
      <tr>
        <td>评论功能：</td>
        <td><?php echo $manager->GetForm('novel' , 'replay_open');?></td>
        <td>评论是否登录：</td>
        <td colspan="3"><?php echo $manager->GetForm('novel' , 'replay_login');?></td>
      </tr>
      <tr>
        <td>收藏功能：</td>
        <td><?php echo $manager->GetForm('novel' , 'coll_open');?></td>
        <td>书架功能：</td>
        <td><?php echo $manager->GetForm('novel' , 'shelf_open');?></td>
        <td>阅读记录：</td>
        <td><?php echo $manager->GetForm('novel' , 'read_open');?></td>
      </tr>
      <tr>
        <td>推荐功能：</td>
        <td><?php echo $manager->GetForm('novel' , 'rec_open');?></td>
        <td>赠送礼物功能：</td>
        <td><?php echo $manager->GetForm('novel' , 'give_open');?></td>
        <td>打赏金币功能：</td>
        <td><?php echo $manager->GetForm('novel' , 'reward_open');?></td>
      </tr>
      <tr>
        <td colspan="3">
        	消费 <?php echo $manager->GetForm('novel' , 'fans_exp_gold1');?> <?php echo $userConfig['gold1_name'];?>增加1点粉丝经验值
        </td>
        <td colspan="3">
        	消费 <?php echo $manager->GetForm('novel' , 'fans_exp_gold2');?> <?php echo $userConfig['gold2_name'];?>增加1点粉丝经验值
        </td>
      </tr>
      <tr>
        <td colspan="3">
        	消费 <?php echo $manager->GetForm('novel' , 'cons_gold1');?> <?php echo $userConfig['gold1_name'];?>
        	可以获得 <?php echo $manager->GetForm('novel' , 'cons_gold1_month');?> 张<?php echo $userConfig['ticket_month'];?>和
        	<?php echo $manager->GetForm('novel' , 'cons_gold1_rec');?> 张<?php echo $userConfig['ticket_rec'];?>
        </td>

        <td colspan="3">
        	消费 <?php echo $manager->GetForm('novel' , 'cons_gold2');?> <?php echo $userConfig['gold2_name'];?>
        	可以获得 <?php echo $manager->GetForm('novel' , 'cons_gold2_month');?> 张<?php echo $userConfig['ticket_month'];?>和
        	<?php echo $manager->GetForm('novel' , 'cons_gold2_rec');?> 张<?php echo $userConfig['ticket_rec'];?>
        </td>
      </tr>
    </table>

    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>模块搜索设置</b></th></tr></thead>
      <tr>
        <td width="200">搜索功能：</td>
        <td><?php echo $manager->GetForm('novel' , 'search_open');?></td>
        <td>每次搜索的间隔时间(单位：秒)：</td>
        <td><?php echo $manager->GetForm('novel' , 'search_time');?></td>
      </tr>
      <tr>
        <td width="200">只有一条结果的是否直接跳转：</td>
        <td colspan="3"><?php echo $manager->GetForm('novel' , 'search_jump');?></td>
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
	$('#novel_head').css("width",'500px');
	$('#novel_head').css("height",'100px');
	$('#chapter_start').css("width",'500px');
	$('#chapter_start').css("height",'100px');
	$('#chapter_end').css("width",'500px');
	$('#chapter_end').css("height",'100px');
	$('#buy_wordnumber').css("width",'50px');
	$('#novel_save').css("width",'300px');
	$('#chapter_save').css("width",'300px');
	$('#novel_mp3_save').css("width",'300px');
	$('#chapter_mp3_save').css("width",'300px');
	$('#cover').css("width",'300px');
	$('#novel_en_str').css("width",'300px');
	$('#<?php echo $cFun.$type?>Opera input').css("width",'50px');
});
</script>
