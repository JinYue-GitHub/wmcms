<?php
if( !is_array($data) )
{
	echo '<script type="text/javascript">$(document).ready(function(){$(this).alertmsg("info", "对不起，该数据已经被处理或者数据不存在，无法进行查看！");$(this).dialog("closeCurrent");});</script>';
	exit;
}
?>
<div class="bjui-pageContent">
<table class="table table-border table-bordered table-bg table-sort">
    <tr>
      <td valign="top" width="10%"><b>字段名：</b></td>
      <td valign="top" width="45%">旧的数据</td>
      <td valign="top" width="45%">新的数据</td>
	</tr>
	<?php 
	foreach ($data as $k=>$v)
	{
		if( $k =='content' )
		{
			?>
		    <tr>
		      <td valign="top"><b>差异化统计</b></td>
		      <td valign="top" colspan="2" id="content_total">
                <p style="background-color: #FD8;"><strong>0</strong>处修改</p>
	            <p style="background-color: #9E9;"><strong>0</strong>处新增</p>
	            <p style="background-color: #E99"><strong>0</strong>处删除</p>
            	</td>
			</tr>
		    <tr>
		      <td valign="top" style="vertical-align: unset;"><b><?php echo GetKey($v,'title').'</b><br/>['.$k.']';?></td>
		      <td valign="top" style="vertical-align: unset;"><pre id="old_<?php echo $k;?>"><code class="">差异对比中....</code></pre></td>
		      <td valign="top" style="vertical-align: unset;"><pre id="new_<?php echo $k;?>"><code class="">差异对比中....</code></pre></td>
			</tr>
			
		    <tr style="display:none">
		    	<td style="display:none">
		    	<div id="old_content_html"><?php echo $v['old']?></div>
		    	<div id="new_content_html"><?php echo str::ToHtml($v['new'])?></div>
				</td>
			</tr>
			<?php
		}
		else
		{
			?>
		    <tr>
		      <td valign="top"><b><?php echo GetKey($v,'title').'</b><br/>['.$k.']';?></td>
		      <td valign="top" id="old_<?php echo $k;?>"><?php echo $v['old']?></td>
		      <td valign="top" id="new_<?php echo $k;?>"><?php
		      if( $v['old'] != $v['new'])
		      {
		      	echo '【<span style="color:red">有修改</span>】';
		      }
		      echo $v['new'];
		      ?></td>
			</tr>
			<?php
		}
	}
	?>
</table>
</div>


<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">关闭</button></li>
    </ul>
</div>
<style>
code{
white-space: unset;
color: unset;
}
pre{background-color: #f5f2f2;}
.hljs{line-height: 20px;background-color: #f5f2f2;}
</style>
<link href="/files/js/diff/diff.css" rel="stylesheet">
<style>
.hljs{line-height: 20px;background-color: #f5f2f2;}
.modified{line-height: 20px;}
.deleted{line-height: 20px;}
.inserted{line-height: 20px;}
.padding{line-height: 20px;}
</style>
<script src="/files/js/diff/LineDiff.js"></script>
<script src="/files/js/diff/EditSet.js"></script>
<script src="/files/js/diff/LineUtils.js"></script>
<script src="/files/js/diff/Diff.js"></script>
<script src="/files/js/diff/DiffFormatter.js"></script>
<script src="/files/js/diff/LineFormatter.js"></script>
<script src="/files/js/diff/AnchorIterator.js"></script>
<script src="/files/js/diff/highlight.pack.js"></script>
<script>
	setTimeout(function () { doDiff(); }, 500);
	
    function doDiff() {
        var text1 = $('#old_content_html').html().replace(/<br>/g,"\r\n");
        var text2 = $('#new_content_html').html().replace(/<br>/g,"\r\n");

        var chag = 0, add = 0, del = 0;
        var diff = new SourceDiff.Diff(true);
        var formatter = new SourceDiff.DiffFormatter(diff);
		
        var results = formatter.formattedDiff(text1, text2);
        var adds = results[3].added.all();
        var dels = results[3].deleted.all();
        var cha = arrayIntersection(adds, dels);
        //记录变更内容
        $('#content_total strong:eq(0)').html(cha.length);
        $('#content_total strong:eq(1)').html(adds.length - cha.length);
        $('#content_total strong:eq(2)').html(dels.length - cha.length);
        $(".hljs-line-numbers").remove();
        $('#old_content code').html(results[0]);
        $('#new_content code').html(results[1]);
		
        var pre = $('pre code');
        for (var i = 0; i < pre.length; i++) {
            hljs.highlightBlock(pre[i]);
        }
        var _line = 0;
        $('pre code').each(function () {

            console.log(Math.round($(this).height()/20));
            var lines = $(this).html().split('<br>').length - 1;
            var lines = Math.round($(this).height()/20);
            _line = lines;
            $(this).before('<code class="hljs hljs-line-numbers" style="float: left;"></code>');
            var html = $(this).prev('.hljs-line-numbers');
            for (i = 1; i <= lines; i++) {
                if (i == lines)
                    html.html(html.html() + (i + '.'));
                else
                    html.html(html.html() + (i + '.<br>'));
            }
        });

    }

    function arrayIntersection(a, b) {
        var ai = 0, bi = 0;
        var result = new Array();
        while (ai < a.length && bi < b.length) {
            if (a[ai] < b[bi]) { ai++; }
            else if (a[ai] > b[bi]) { bi++; }
            else /* they're equal */
            {
                result.push(a[ai]);
                ai++;
                bi++;
            }
        }
        return result;
    }
    function arrayIntersect(a, b) {
        return jQuery.merge(jQuery.grep(a, function (i) {
            return jQuery.inArray(i, b) == -1;
        }), jQuery.grep(b, function (i) {
            return jQuery.inArray(i, a) == -1;
        })
        );
    };
</script>