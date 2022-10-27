//by 未梦
//评论js系统
//使用方法：
//id="wmcms_replay" 容器
//设置初始属性cid内容id，和module
//引用js文件 /files/js/replay.js
var replayconfig;
var rid=0;
var nowPage = 1;
var isOpenFace = false;
var faceTitle = faceTitleCur = faceListHtml = faceList = fileName = file = '' ;
if(typeof(subsetId) == "undefined" || subsetId == null){
	var subsetId=0;
}
if(typeof(segmentId) == "undefined" || segmentId == null){
	var segmentId=0;
}
if(typeof(replaysum) == "undefined" || replaysum == null){
	var replaysum=0;
}
var codeHtml = '';
$(document).ready(function(){
	$("<script>").attr({type: "text/javascript",src: "/files/js/insertContent.js"}).appendTo("head");
	//加载评论框的css
	$("<link>").attr({ rel: "stylesheet",type: "text/css",href: "/files/js/replay/default/default.css"}).appendTo("head");

	//加载表情
	$.getJSON('/files/face/replay.json',null,function(faceObj){
		for(var i in faceObj){
			faceTitleCur = '';
			if(i==0){
				faceTitleCur = 'wmcms_replay_face_title_cur';
			}
			faceTitle += '<li data-id="'+faceObj[i]['floder']+'" style="margin: auto;" class="'+faceTitleCur+'">'+faceObj[i]['title']+'</li>';
			faceList = '';
			for(var j=1;j<=faceObj[i]['number'];j++){
				if(j>40){break;}
				fileName = faceObj[i]['url'].replace('{i}',j);
				file = fileName.split("."); //字符分割 
				faceList += '<li data-face="{face:'+faceObj[i]['floder']+'|'+file[0]+'}" style="margin: auto;"><img src="/files/face/'+faceObj[i]['floder']+'/'+fileName+'"/></li>';
			}
			faceListBox = '';
			if(i>0){
				faceListBox= 'display:none';
			}
			faceListHtml +='<ul id="face_'+faceObj[i]['floder']+'_list" class="wmcms_replay_face_list" style="'+faceListBox+'">'+faceList+'</ul>';
		}
		$(".wmcms_replay_face_box").html('<ul class="wmcms_replay_face_title">'+faceTitle+'</ul><div style="clear:both"></div>'+faceListHtml);
	});
	//标题。
	if(codeOpen == '1'){
		codeHtml = '<div class="wmcms_replay_codebox">验证码：'+$("#wmcms_replay_code_form").html()+'<input type="text" id="wmcms_replay_code"></div>';
	}
	var titlebox='<div class="wmcms_replay_titlebox"><span id="wmcms_repaly_title">最新评论</span>(<i id="wmcms_replay_sum">0</i>人参与 , <i id="wmcms_replay_num">0</i>条评论)</div>';
	var frombox='<div class="wmcms_replay_frombox"><div class="wmcms_replay_header"><img src="/files/images/nohead.gif" width="80" height="80" /><div class="wmcms_repaly_nickname" id="wmcms_repaly_nickname">游客</div></div><div class="wmcms_replay_from"><textarea  class="wmcms_replay_textarea" id="wmcms_replay_content" placeholder=""></textarea><div class="wmcms_replay_tool"><div class="wmcms_replay_buttonbox"><input class="wmcms_replay_button" id="submit" type="button" value="发 布" /></div>'+codeHtml+'<div class="wmcms_replay_face"></div></div><div class="wmcms_replay_face_box"></div></div></div><div class="wmcms_replay_listbox"><div class="wmcms_replay_nodata" id="wmcms_replay_loding"><img src="/files/js/replay/default/waiting.gif" style="vertical-align:middle"/> &nbsp;&nbsp;加载评论中...</div><div class="wmcms_replay_nodata" style="display:none" id="wmcms_replay_nodata"></div><div class="wmcms_replay_listbox" id="wmcms_replay_listhotbox" style="display:none"><div class="wmcms_replay_listtitle">热门评论</div><div class="wmcms_replay_hotlist"><ul></ul></div></div><div class="wmcms_replay_listbox" id="wmcms_replay_listbox" style="display:none" ><div class="wmcms_replay_listtitle">最新评论</div><div class="wmcms_replay_list"><ul></ul></div></div></div><div class="clear"></div>';
	
	var fromReplaybox='<div class="wmcms_replay_fromreplaybox" style="display:none"><textarea  class="wmcms_replay_textarea" id="wmcms_replay_replay_content" placeholder=""></textarea><div class="wmcms_replay_tool"><div class="wmcms_replay_buttonbox"><input class="wmcms_replay_button" id="replay_submit" type="button" value="发 布" /></div>'+codeHtml+'<div class="wmcms_replay_face"></div></div><div class="wmcms_replay_face_box"></div></div><div class="clear"></div>';
	//插入到网页相关ID中
	$("#wmcms_replay").html(titlebox+frombox+fromReplaybox);

	//开启表情操作
	$("body").on("click",".wmcms_replay_face",function(){
		isOpenFace = true;
		$(this).addClass('wmcms_replay_facehover');
		$(this).parent().parent().find('.wmcms_replay_face_box').show();
	});
	//发票评论按钮
	$(".wmcms_replay_button").hover(function(){
		$(this).removeClass("wmcms_replay_button").addClass("wmcms_replay_buttonhover");
	},function(){
		$(this).removeClass("wmcms_replay_buttonhover").addClass("wmcms_replay_button");
	});
	//表情分类点击
	$("#wmcms_replay").on("click",".wmcms_replay_face_title li",function(){//修改成这样的写法
		$(this).parent().find('li').removeClass('wmcms_replay_face_title_cur');
		$(this).addClass('wmcms_replay_face_title_cur');
		$(this).parent().parent().find(".wmcms_replay_face_list").hide();
		$(this).parent().parent().find('#face_'+$(this).data('id')+'_list').show();
	});
	//表情点击
	$("#wmcms_replay").on("click",".wmcms_replay_face_list li",function(){//修改成这样的写法
		$(this).parent().parent().parent().find('textarea').insertContent($(this).data('face'));
	});
	
	//读取默认配置
	replayconfig = load_config();
	//读取第一页评论
	var pageconfig=get_replaylist(1,'id','list');
	//修改网页默认配置
	$("#wmcms_repaly_title").html(replayconfig.boxname);
	$("#wmcms_repaly_nickname").html(replayconfig.nickname);
	$("#wmcms_replay_content").attr('placeholder',replayconfig.input);
	$(".wmcms_replay_header img").attr('src',replayconfig.head);
	$("#wmcms_replay_sum").html(pageconfig.sum);
	$("#wmcms_replay_num").html(pageconfig.datacount);
	//读取分页
	if(parseInt(pageconfig.datacount)>parseInt(replayconfig.list_limit)){
		$(".wmcms_replay_list").append('<div class="wmcms_replay_pagelist">'+replay_pagelist(pageconfig.page,pageconfig.sumpage,pageconfig.datacount,replayconfig.page_count)+'</div>');
	}
	//读取热门评论
	if(parseInt(pageconfig.datacount)>parseInt(replayconfig.hot_display)){
		get_replaylist(1,'hot','hot');
	}
	
	//数字翻页的样式
	$(".wmcms_replay_pagelist li").hover(function(){
		if($(this).attr("class")!="wmcms_replay_list_hover"){
			$(this).attr("style","border:#4d82a2 1px solid");
		}
	},function(){
		$(this).attr("style","");
	});
	
	
	//监听关闭表情事件
	$(document).on('mousedown',function(e){
        if( !$(e.target).is($('.wmcms_replay_facehover') ) && !$(e.target).is($('.wmcms_replay_face_box') ) && $(e.target).parents('.wmcms_replay_face_box').length === 0){
			$('.wmcms_replay_face_box').hide();
			$('.wmcms_replay_facehover').removeClass('wmcms_replay_facehover').addClass('wmcms_replay_face');
        }
    });
	
	//提交数据
	$("#submit").click(function(){
		var content=$("#wmcms_replay_content").val();
		var code=$("#wmcms_replay_code").val();
		post(content,code,0);
	});
	$("body").on("click","#replay_submit",function(){
		var content=$("#wmcms_replay_replay_content").val();
		var code=$(this).parent().parent().find("#wmcms_replay_code").val();
		post(content,code,rid);
	});
	
	//请求更多回复
	$("body").on("click",".more_replay_box a",function(){
		var page=$(this).data('page');
		var pid=$(this).data('pid');
		get_replay_replaylist($(this),page,pid);
	});
});

//提交数据
var ispost='0';//防止重复提交设置
function post(content,code,replayId){
	if(ispost=='0'){
		if(content==''){
			alert("对不起，请输入评论内容!");
		}else if(codeOpen == '1' && code==''){
			alert("对不起，请输入验证码!");
		}else{
			var subName = '#submit';
			if( replayId > 0 ){
				subName = '#replay_submit';
			}
			ispost='1';
			$(subName).addClass("wmcms_replay_buttonclick");
			$(subName).val("发表中");
			$.ajax({
				type:"POST",
				url:"/wmcms/action/index.php?action=replay.add",
				data:{
					'module':module,
					'cid':cid,
					'rid':replayId,
					'sid':subsetId,
					'segid':segmentId,
					'ajax':'yes',
					'content':content,
					'form_token':token,
					'code':code,
				},
				dataType:"json",
				success:function(data){
					if(data.code == "200"){
						token = data.data.token;
						$("#wmcms_replay_sum").html(parseInt($("#wmcms_replay_sum").html())+1);
						$("#wmcms_replay_num").html(parseInt($("#wmcms_replay_num").html())+1);
						
						$("#wmcms_replay_code").val('');
						$("#wmcms_replay_content").val('');
						$("#wmcms_replay_replay_content").val('');
						
						get_replaylist(nowPage,'id','list');
					}else{
						alert(data.msg);
					}
					$(subName).removeClass("wmcms_replay_buttonclick");
					$(subName).val("发 布");
					ispost='0';
				},
			});
		}
	}
}

//顶踩的悬浮样式
function dingcaihover(obj,t){
	$(obj).addClass("wmcms_replay_replaytext");
	$(obj).children("i").addClass("wmcms_replay_replay"+t+"hover");
}
function dingcaiout(obj,t){
	$(obj).removeClass("wmcms_replay_replaytext");
	$(obj).children("i").removeClass("wmcms_replay_replay"+t+"hover");
}
//处理顶踩操作
function dingcaiclick(obj,t,id){
	if(t=='d'){
		type='ding';
	}else{
		type='cai';
	}
	$.ajax({
		type:"POST",
		url:"/wmcms/action/index.php?action=dingcai."+type,
		data:{
			'cid':id,
			'module':'replay',
			'ajax':'yes',
		},
		
		dataType:"json",
		success:function(data){
			if(data.code == "200"){
				$(obj).children("div").html(parseInt($(obj).children("div").html())+1);
			}else{
				alert(data.msg);
			}
		},
	});
}

//读取小说评论配置。
function load_config(){
	var result;
	$.ajax({
		type:"POST",
		url:"/wmcms/action/index.php?action=replay.config",
		data:{
			'ajax':'yes',
			'module':module,
		},
		dataType:"json",
		success:function(data){
			if(data.code == "200"){
				result = data.data;
			}else{
				alert(data.msg);
			}
		},
		async:false,
	});
	return result;
}
//读取评论列表内容
function get_replaylist(page,order,lt){
	var result;
	nowPage = page;
	$('#wmcms_replay_listbox').append($('.wmcms_replay_fromreplaybox').prop("outerHTML"));
	$('.wmcms_replay_fromreplaybox').hide();
	$.ajax({
		type:"POST",
		url:"/wmcms/action/index.php?action=replay.list",
		data:{
			'ajax':'yes',
			'module':module,
			'cid':cid,
			'sid':subsetId,
			'segid':segmentId,
			'page':page,
			'order':order,
		},
		dataType:"json",
		success:function(data){
			token = data.data.token;
			if(data.data.datacount=='0'){
				$("#wmcms_replay_loding").hide();
				if( order != 'hot' )
				{
					$("#wmcms_replay_nodata").html(replayconfig.no_data);
					$("#wmcms_replay_nodata").show();
					$("#wmcms_replay_listbox").hide();
					$(".wmcms_replay_list ul").html('');
				}
			}else{
				$("#wmcms_replay_loding").hide();
				$("#wmcms_replay_listbox").show();
				$("#wmcms_replay_sum").html(data.data.sum);
				$("#wmcms_replay_num").html(data.data.datacount);
				var lists=data.data.data;

				var listhtml='';
				var childhtml='';
				var mooreHtml = '';
				for(var o in lists){
					if( lists[o].user_head == '' ){
						lists[o].user_head = replayconfig.no_head;
					}
					childhtml='';
					//如果存在回复，就显示
					if ( lists[o].hasOwnProperty('child') && lists[o].child.length > 0 ){
						mooreHtml = '';
						for(var j in lists[o].child){
							if( lists[o].child[j].user_head == '' ){
								lists[o].child[j].user_head = replayconfig.no_head;
							}
							childhtml += '<div style="margin-bottom: 5px;clear:both" class="wmcms_replay_replaybox" id="wmcms_replay_replaybox_'+lists[o].child[j].replay_id+'">'+
								'<div style="float:left;" class="wmcms_replay_replay_header"><img src="'+lists[o].child[j].user_head+'" width="40" height="40" style="border: #84a8c2 solid 2px;border-radius: 5px;margin-right: 5px;"></div>'+
								'<div class="wmcms_replay_replay_body">'+
									'<div class="wmcms_replay_replay_user">'+
										'<a style="color: #3c6986;" href="'+lists[o].child[j].fhome+'" id="wmcms_replay_replay_'+lists[o].child[j].replay_id+'">'+lists[o].child[j].replay_nickname+'</a>  '+
										'<span style="color: #aaa;">回复</span> '+
										'<a style="color: #3c6986;" href="'+lists[o].child[j].rfhome+'">'+lists[o].child[j].replay_rnickname+'</a>：'+
										'<span style="color: #666;">'+lists[o].child[j].replay_content+'</span>'+
									'</div>'+
									'<div>'+
										'<div style="color: #aaa;float:left;margin-right:15px">'+getTime(lists[o].child[j].replay_time)  +'</div>'+
										'<div style="float:left;margin-right:15px;"><a style="color: #3c6986;" onclick="replay(this,2,'+lists[o].child[j].replay_id+')">回复</a></div>'+
										'<div class="wmcms_replay_replaydc"><div class="curd" onmouseover="dingcaihover(this,\'d\')" onmouseout="dingcaiout(this,\'d\')" onclick="dingcaiclick(this,\'d\','+lists[o].child[j].replay_id+')"><i class="wmcms_replay_replayd" style="float:left;margin-top: 6px;"></i><div class="left">'+lists[o].child[j].replay_ding+'</div></div><i style=" width:10px;" class="left">&nbsp;</i><div class="curc" onmouseover="dingcaihover(this,\'c\')" onmouseout="dingcaiout(this,\'c\')" onclick="dingcaiclick(this,\'c\','+lists[o].child[j].replay_id+')"><i class="wmcms_replay_replayc" style="float:left;margin-top: 6px;"></i><div class="left">'+lists[o].child[j].replay_cai+'</div></div></div>'+
									'</div>'+
								'</div>'+
							'</div>';
						}
						if( parseInt(replayconfig.replay_replay_number) < parseInt(lists[o].replay_count) ){
							mooreHtml = '<div class="more_replay_box" id="more_replay_'+lists[o].replay_id+'" style="margin-left: 50px;clear: both;"><a href="javascript:void(0);" style="color: #3c6986;font-size: 14px;" data-page="1" data-pid="'+lists[o].replay_id+'">共有'+lists[o].replay_count+'条回复，点击查看更多</a></div>';
						}
						childhtml = '<div class="wmcms_replay_replaycontent" style="background-color: #ededed;border-radius: 5px;margin-bottom: 15px;padding-bottom: 10px;">'+childhtml+mooreHtml+'</div>';
					}
					listhtml+='<li><div class="wmcms_replay_replayheader"><img src="'+lists[o].user_head+'" width="80" height="80" /></div><div class="wmcms_replay_replay" id="wmcms_replay_replaybox_'+lists[o].replay_id+'"><div><div class="wmcms_replay_replaytime">'+getTime(lists[o].replay_time)+'</div><div class="wmcms_replay_replaynickname"><a href="'+lists[o].fhome+'" id="wmcms_replay_replay_'+lists[o].replay_id+'">'+lists[o].replay_nickname+'</a> [中国用户]</div></div><div><div class="wmcms_replay_replaycontent">'+lists[o].replay_content+'</div><div class="wmcms_replay_replaydc"><div class="curc" onmouseover="dingcaihover(this,\'c\')" onmouseout="dingcaiout(this,\'c\')" onclick="dingcaiclick(this,\'c\','+lists[o].replay_id+')"><div class="right">'+lists[o].replay_cai+'</div><i class="wmcms_replay_replayc"></i></div><i style=" width:10px;" class="right">&nbsp;</i><div class="curd" onmouseover="dingcaihover(this,\'d\')" onmouseout="dingcaiout(this,\'d\')" onclick="dingcaiclick(this,\'d\','+lists[o].replay_id+')"><div class="right">'+lists[o].replay_ding+'</div><i class="wmcms_replay_replayd"></i></div><div class="right" style="margin-right: 10px;"><a style="color: #3c6986;" onclick="replay(this,1,'+lists[o].replay_id+')">回复</a></div></div></div>'+childhtml+'</div></li>'
				}

				//数据向最后插入
				if(lt=='list'){
					$(".wmcms_replay_list ul").html(listhtml);
				}else{
					$("#wmcms_replay_listhotbox").show();
					$(".wmcms_replay_hotlist ul").html(listhtml);
					$(".wmcms_replay_hotlist .curd").addClass("wmcms_replay_replaytext");
					$(".wmcms_replay_hotlist .wmcms_replay_replayd").addClass("wmcms_replay_replaydhover");
				}
			}
			result = data.data;
		},
		async:false,
	});
	return result;
}
//读取评论回复列表
function get_replay_replaylist(obj,page,pid){
	$.ajax({
		type:"POST",
		url:"/wmcms/action/index.php?action=replay.list",
		data:{
			'ajax':'yes',
			'module':module,
			'cid':cid,
			'pid':pid,
			'sid':subsetId,
			'segid':segmentId,
			'page':page,
		},
		dataType:"json",
		success:function(data){
			token = data.data.token;
			if(data.data.datacount=='0' ){
				obj.parent().remove();
			}else{
				var lists=data.data.data;
				var listhtml='';
				for(var o in lists){
					if( $('#wmcms_replay_replaybox_'+lists[o].replay_id).length == 0 ){
						if( lists[o].user_head == '' ){
							lists[o].user_head = replayconfig.no_head;
						}
						listhtml += '<div style="margin-bottom: 5px;clear:both" id="wmcms_replay_replaybox_'+lists[o].replay_id+'">'+
							'<div style="float:left;"><img src="'+lists[o].user_head+'" width="40" height="40" style="border: #84a8c2 solid 2px;border-radius: 5px;margin-right: 5px;"></div>'+
							'<div style="padding-left: 50px;">'+
								'<div style="padding-top: 8px;">'+
									'<a style="color: #3c6986;" href="'+lists[o].fhome+'" id="wmcms_replay_replay_'+lists[o].replay_id+'">'+lists[o].replay_nickname+'</a>  '+
									'<span style="color: #aaa;">回复</span> '+
									'<a style="color: #3c6986;" href="'+lists[o].rfhome+'">'+lists[o].replay_rnickname+'</a>：'+
									'<span style="color: #666;">'+lists[o].replay_content+'</span>'+
								'</div>'+
								'<div>'+
									'<div style="color: #aaa;float:left;margin-right:15px">'+getTime(lists[o].replay_time)  +'</div>'+
									'<div style="float:left;margin-right:15px;"><a style="color: #3c6986;" onclick="replay(this,2,'+lists[o].replay_id+')">回复</a></div>'+
									'<div class="wmcms_replay_replaydc"><div class="curd" onmouseover="dingcaihover(this,\'d\')" onmouseout="dingcaiout(this,\'d\')" onclick="dingcaiclick(this,\'d\','+lists[o].replay_id+')"><i class="wmcms_replay_replayd" style="float:left;margin-top: 6px;"></i><div class="left">'+lists[o].replay_ding+'</div></div><i style=" width:10px;" class="left">&nbsp;</i><div class="curc" onmouseover="dingcaihover(this,\'c\')" onmouseout="dingcaiout(this,\'c\')" onclick="dingcaiclick(this,\'c\','+lists[o].replay_id+')"><i class="wmcms_replay_replayc" style="float:left;margin-top: 6px;"></i><div class="left">'+lists[o].replay_cai+'</div></div></div>'+
								'</div>'+
							'</div>'+
						'</div>';
					}
				}
				obj.data('page',page+1);
				obj.parent().before(listhtml);
				if( page >= data.data.sumpage ){
					obj.parent().remove();
				}
			}
		},
		async:true,
	});
}

//格式化时间
function getTime(/** timestamp=0 **/) {
	var ts = arguments[0] || 0;
	var t,y,m,d,h,i,s;
	t = ts ? new Date(ts*1000) : new Date();
	y = t.getFullYear();
	m = t.getMonth()+1;
	d = t.getDate();
	h = t.getHours();
	i = t.getMinutes();
	s = t.getSeconds();
	// 可根据需要在这里定义时间格式
	return y+'年'+(m<10?'0'+m:m)+'月'+(d<10?'0'+d:d)+'日 '+(h<10?'0'+h:h)+':'+(i<10?'0'+i:i)+':'+(s<10?'0'+s:s);
}

//更换数字
function jumppage(page){
	var pageconfig=get_replaylist(page,'id','list');
	$(".wmcms_replay_pagelist").html(replay_pagelist(page,pageconfig.sumpage,pageconfig.datacount,replayconfig.page_count));
}

/**
 * 跳页函数
 * 参数1，当前页数
 * 参数2，总页数
 * 参数3，总数据量
 * 参数4，每页显示的条数
 */
function replay_pagelist(page,sumpage,datacount,replaypagenum){
	page=parseInt(page);
	sumpage=parseInt(sumpage);
	datacount=parseInt(datacount);
	replaypagenum=parseInt(replaypagenum);
	if(datacount>replaypagenum){
		var indexpage='',lastpage='',pagelist='',startnum=1,endnum=1;
		if(sumpage>replaypagenum){
			if(page-replaypagenum<=1){
				startnum=1;
				endnum=replaypagenum*2+1;
			}else if(page-replaypagenum>1){
				if(replaypagenum*2+page>=sumpage){
					startnum=page-replaypagenum;
					endnum=startnum+replaypagenum*2;
					if(endnum>sumpage){
						endnum=sumpage;
					}
				}else{
					startnum=page-replaypagenum;
					endnum=replaypagenum*2+page-replaypagenum;
				}
			}
			
			if(page>replaypagenum && startnum!=1){
				indexpage='<li onclick="jumppage(1)">1</li><li>...</li>';
			}
			if(sumpage>page && endnum!=sumpage){
				lastpage='<li>...</li><li onclick="jumppage('+sumpage+')">'+sumpage+'</li>';
			}
		}else{
			startnum=1;
			endnum=sumpage;
		}
		for(i=startnum;i<=endnum;i++){
			if( i <= sumpage ){
				if(page==i){
					pagelist+='<li class="wmcms_replay_list_hover">'+i+'</li>';
				}else{
					pagelist+='<li onclick="jumppage('+i+')">'+i+'</li>';
				}
			}
		}
	}
	return indexpage+pagelist+lastpage;
}


/**
 * 评论回复
 * @param 需要回复的评论id
 */
function replay(obj,type,id){
	if($('.wmcms_replay_fromreplaybox').is(':hidden')){
		var nicknameId = '#wmcms_replay_replay_'+id;
		var formBoxId = '#wmcms_replay_replaybox_'+id;
		rid = id;
		var fromReplayHtml = $('.wmcms_replay_fromreplaybox').prop("outerHTML");
		$('.wmcms_replay_fromreplaybox').remove();
		$(obj).parent().parent().parent().append(fromReplayHtml);
		$('#wmcms_replay_replay_content').attr('placeholder','回复 @'+$(nicknameId).text());
		$('.wmcms_replay_fromreplaybox').show();
	}else{
		$('.wmcms_replay_fromreplaybox').hide()
	}
}