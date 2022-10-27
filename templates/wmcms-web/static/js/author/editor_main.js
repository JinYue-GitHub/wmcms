//定时器
var timer = null;
//定时器暂停
var timerStop = true;
//编辑时长
var time = 0;
//初始字数
var word = 0;
//当前选中的大小属性
var currentSize = 2;
//上一个样式属性
var lastSize = 2;
//放大缩小数组
var sizeList = [
	{"class":"fz-xs","text":"50%"},
	{"class":"fz-s","text":"75%"},
	{"class":"fz-m","text":"100%"},
	{"class":"fz-l","text":"125%"},
	{"class":"fz-lg","text":"150%"},
];
//是否全屏
var full = false;
/**
 * 改变编辑器大小
 * 参数1，必须，大小索引
 */
function ChangeSize(size){
    setCookie('set_size',size);
	var lastClass = sizeList[lastSize];
	var currentClass = sizeList[size];
	$(".ne-font-scale").removeClass(lastClass.class);
	$(".ne-font-scale").addClass(currentClass.class);
	$(".ne-fs-current").html(currentClass.text);
	$("body").removeClass(lastClass.class);
	$("body").addClass(currentClass.class);
}
/**
 * 更换皮肤
 * 参数1，必须，皮肤id
 */
function ChangeSkin(skin){
    setCookie('set_skin',skin);
	//改变皮肤列表
	$(".skin-color").attr('data-select','false');
	$('.skin-list').find('[data-color="'+skin+'"]').attr('data-select','true');
	//更改body皮肤样式
	$("body").attr('data-theme',skin);
	$("body").removeClass('skin-light');
	$("body").removeClass('skin-dark');
	if( skin == 'dark' || skin == 'light' ){
		$("body").addClass('skin-'+skin);
	}
}
/**
 * 自定义弹窗提示
 * 参数1，必须，标题
 * 参数2，必须，内容
 */
function DialogAlert(title,content,confirm){
	$('#alert_panel_icon').attr('class','');
	$('#alert_panel .ui-button-primary').addClass('dn');
	$('#alert_panel .ui-button-primary').attr('onClick','');
	//确认图标
	if( confirm ){
		$('#alert_panel_icon').addClass('ui-dialog-warning');
		$('#alert_panel_icon').addClass('ui-dialog-confirm');
		$('#alert_panel .ui-button-primary').removeClass('dn');
		$('#alert_panel .ui-button-primary').attr('onClick','DeleteChapter()');
	}else{
		$('#alert_panel_icon').addClass('ui-dialog-remind');
		$('#alert_panel_icon').addClass('ui-dialog-alert');
	}
	$('#alert_panel_title').html(title);
	$('#alert_panel_content').html(content);
	$('#alert_panel').css('display','block');
}
/**
 * 自定义贴片提示
 * 参数1，必须，提示内容
 * 参数2，必须，提示图标
 */
function DialogTip(content,type){
	$('#tip_panel').removeClass('ui-lightip-success');
	$('#tip_panel').removeClass('ui-lightip-error');
	if(type==2){
		$('#tip_panel').addClass('ui-lightip-error');
	}else{
		$('#tip_panel').addClass('ui-lightip-success');
	}
	$('#tip_panel .ui-lightip-text').html(content);
	$('#tip_panel').show();
	setTimeout(function(){ $('#tip_panel').hide(); },"2000");
}

/** 加载进度 **/
function OnloadProgress(){
	nowLoadProgress++;
	//加载进度
	$("#loading_progress").html(parseInt(nowLoadProgress/progressCount*100)+'%');
	if( nowLoadProgress >= progressCount ){
		//加载完成
		$(".page-loading").addClass('dn');
		$("#root").removeClass('dn');
		//加载完成
		//$('.write-view-body').removeClass('dn');
		//$('.empty-page').addClass('dn');
	}
}
/** 加载章节列表 **/
function OnLoadChapterList(type,cid){
	$.ajax({
		type:"POST",url:getChapterListUrl,dataType:"json",data:{nid:novelId},
		success:function(data){
			if(data.code==201){
			}else if(data.code==200){
				var chapterCount = 0;
				for(i in data.data){
					var chapterHtml = '';
					var chapterCount = 0;
					for(j in data.data[i]){
						chapterCount++;
						var chapterId = data.data[i][j].chapter_id;
						var chapterName = data.data[i][j].chapter_name;
						var chapterNumber = data.data[i][j].chapter_number;
						var chapterTime = data.data[i][j].chapter_time;
						var chapterDate = FormatTime(chapterTime,'Y-m-d H:i');
						chapterHtml += '<li data-id="'+chapterId+'" data-type="chapter" data-word="'+chapterNumber+'" data-time="'+chapterTime+'" class=""><a ><div class="tab-coll-title">'+
							'<span class="chapter-title" title="'+chapterName+'">'+chapterName+'</span></div>'+
							'<div class="tab-coll-txt"><span class="chapter-date">'+chapterDate+'</span><span class="word_number">'+chapterNumber+'</span>字</div>'+
							'</a></li>';
					}
                    $('#volume_chapter_list_'+i).html(chapterHtml);
                    $('#volume_chapter_count_'+i).html(data.data[i].length);
                    $('#manager_volume_chapter_count_'+i).html(data.data[i].length);
				}
				$('#chapter_count').html(chapterCount);
				if( type==='create_chapter'){
					DraftChapterClick('create_chapter',cid);
				}
			}else{
				DialogAlert('错误提示',data.msg);
			}
			OnloadProgress();
		}
	});
}
/**
 * 加载草稿列表
 * 参数1，选填，定位类型，默认新建草稿定位
 * 参数1，选填，定位的草稿id
 */
function OnLoadDraftList(type,vid){
	$.ajax({
		type:"POST",url:getDraftListUrl,dataType:"json",data:{cid:novelId,module:module},
		success:function(data){
			if(data.code==201){
			}else if(data.code==200){
				var draftHtml = '';
				for(i in data.data){
					var draftName = data.data[i].draft_title;
					var draftId = data.data[i].draft_id;
					var draftNumber = data.data[i].draft_number;
					var draftTime = data.data[i].draft_createtime;
					draftHtml += '<li data-id="'+draftId+'" data-type="draft" data-word="'+draftNumber+'" data-time="'+draftTime+'"><a ><span class="tab-coll-title" title="'+draftName+'">'+draftName+'</span><span class="tab-coll-txt"><span class="chapter-date"></span><span class="word_number">'+draftNumber+'</span>字</span></a></li>';
				}
				$('.tab-coll-draft').html(draftHtml);
				$('#draft_count').html(data.data.length);
				if( type==='create_draft'){
					DraftChapterClick('create_draft',vid);
				}
			}else{
				DialogAlert('错误提示',data.msg);
			}
			OnloadProgress();
		}
	});
}
/** 加载书籍信息 **/
function OnLoadNovel(){
	$.ajax({
		type:"POST",url:getNovelUrl,dataType:"json",data:{nid:novelId},
		success:function(data){
			if(data.code==200){
				$('.novel_name').html(data.data.novel_name);
				$('.novel_newcname').html(data.data.novel_newcname);
				$('.ne-header-left .novel_cover').css('background-image','url('+data.data.novel_cover+')');
				if( data.data.novel_sell > 0){
					formData.pay = 1;
					$('#top_chapter_panel a').removeClass('dn');
					$('#top_chapter span').html('收费章节');
					$('#sure_chapter span').attr('收费章节');
					$('#sure_chapter span').html('收费章节');
				}
			}else{
				DialogAlert('错误提示',data.msg);
			}
			OnloadProgress();
		}
	});
}
/** 加载大纲 **/
function OnLoadOutLine(){
	$.ajax({
		type:"POST",url:getOutlineUrl,dataType:"json",data:{nid:novelId},
		success:function(data){
			if(data.code==201){
			}else if(data.code==200){
				$('#outline').val(data.data.outline_content);
				$("#top_outline_panel").find('.current-count').html($('#outline').val().length);
			}else{
				DialogAlert('错误提示',data.msg);
			}
			OnloadProgress();
		}
	});
}
/** 加载作者说 **/
function OnLoadSaid(cid){
	var cid = cid?cid:formData.cid;
	$.ajax({
		type:"POST",url:getSaidUrl,dataType:"json",data:{nid:novelId,cid:cid},
		success:function(data){
			if(data.code==201){
				ShowSaid('');
			}else if(data.code==200){
				ShowSaid(data.data.said_content);
			}else{
				DialogAlert('错误提示',data.msg);
			}
		}
	});
}
/** 加载分卷列表 **/
function OnLoadVolumeList(){
	$.ajax({
		type:"POST",url:getVolumeListUrl,dataType:"json",data:{nid:novelId},
		success:function(data){
			if(data.code==200){
				var lastVolumeName = '正文';
				var lastVolumeId = 1;
				//顶部选择分卷列表
				var topVolumePanelHtml = '';
				//左侧分卷列表菜单
				var pushVolumeHtml = '';
				//分卷管理html
				var managerVolumeHtml = '';
				for(i in data.data){
					lastVolumeName = data.data[i].volume_name;
					lastVolumeId = data.data[i].volume_id;
					topVolumePanelHtml = '<a href="javascript:" class="ui-droplist-li" data-vid="'+lastVolumeId+'">'+lastVolumeName+'</a>'+topVolumePanelHtml;
					managerVolumeHtml += '<li data-vid="'+lastVolumeId+'" data-json=\''+JSON.stringify(data.data[i])+'\'><a href="javascript:"><span class="tab-coll-title">'
						+lastVolumeName+'</span><span class="tab-coll-txt">本卷共<span id="manager_volume_chapter_count_'+lastVolumeId+'">0</span>章</span></a></li>';
					pushVolumeHtml += '<li class="volume-item" id="volume_title_'+lastVolumeId+'" data-vid="'+lastVolumeId+'">' +
						'<i class="send-volume-i">' +
						'<svg class="send-drop-icon icon icon_arrow_drop_down"><use xlink:href="#icon_arrow_drop_down"></use></svg>' +
						'</i>' +
						'<h5 class="volume-item-txt">' +
						'<p class="volume-item-num">(共<span id="volume_chapter_count_'+lastVolumeId+'">0</span>章)</p>' +
						'<p class="volume-item-txt-info" title="'+lastVolumeName+'">'+lastVolumeName+'</p>' +
						'</h5></li>' +
						'<ol class="volume-chapter-item dn" id="volume_chapter_list_'+lastVolumeId+'" data-vid="'+lastVolumeId+'"></ol>';
				}
				formData.vid = lastVolumeId;
				$('#volume_count').html(data.data.length);
				$('#top_volume_panel').html(topVolumePanelHtml);
				$('#publish-list').html(pushVolumeHtml);
				$('#top_volume_manager_panel .volume-nav ol').html(managerVolumeHtml);
				$('#top_volume span').html(lastVolumeName);
				$('#sure_volume span').attr(lastVolumeName);
				$('#sure_volume span').html(lastVolumeName);
				OnLoadChapterList();
			}else{
				DialogAlert('错误提示',data.msg);
			}
			OnloadProgress();
		}
	});
}
/**
 * 自定义弹窗提示
 * 参数1，必须，标题
 * 参数2，必须，内容
 */
function OnLoadTool(type){
	if( type=='name' || type=='menpai' ){
		if( type=='name' ){
			var country = $('.tool_cur').data('val');
			var xing = $('#xing').val();
			var ming = $('#ming').val();
			var xingLen = $('[name="xing_len"]:checked').val();
			var mingLen = $('[name="ming_len"]:checked').val();
			var sex = $('[name="sex"]:checked').val();
			var url = toolUrl+'&type=name';
			var data = {'country':country,'xing':xing,'ming':ming,'xing_len':xingLen,'ming_len':mingLen,'sex':sex};
			var ULObj= '#tool_name_ul';
		}else{
			var country = 'cn';
			var prefix = $('#prefix').val();
			var suffix = $('#suffix').val();
			var url = toolUrl+'&type=menpai';
			var data = {'country':country,'prefix':prefix,'suffix':suffix};
			var ULObj= '#tool_menpai_ul';
		}
		$.ajax({
			type:"POST",url:url,dataType:"json",data:data,
			success:function(data){
				if(data.code==200){
					var listHtml = '';
					for(var i=0;i<data.data.length;i++){
						var name = data.data[i];
						listHtml += '<li class="name-example-li" data-val="'+name+'"><a class="name-example-item" href="javascript:"><p class="cei-txt">'+name+'</p></a></li>';
					}
					$(ULObj).html(listHtml);
				}else{
					DialogAlert('错误提示',data.msg);
				}
			}
		});
	}
}
/**
 * 草稿和章节点击
 * 参数1，必须，点击类型
 * 参数2，必须，内容id
 */
function DraftChapterClick(type,id){
	$(".tab-coll-draft li,.volume-chapter-item li").removeClass('listselected');
	$('#guideHeaderBtns button').addClass('dn');
	//如果编辑器部分不可见，就使其可见。
	ShowEditor();
	if( type == 'search_draft' || type == 'draft' || type == 'create_draft' ){
		//选中样式
		$('.tab-coll-draft').find('[data-id="'+id+'"]').addClass('listselected');
		//显示草稿按钮
		$('#guideHeaderBtns .draftBtn').removeClass('dn');
		formData.cid=0;
		formData.did=id;
		//定位点击结果
		if( type == 'search_draft' || type == 'create_draft' ){
			//展开草稿箱
			$('.draftBox').addClass('active');
			//定位点击结果
			$('.tab-coll-draft').scrollTop($('.tab-coll-draft').find('[data-id="'+id+'"]')[0].offsetTop);
		}
		//搜索点击就关闭结果和加载内容
		if( type == 'search_draft' || type == 'draft' ){
			$(".capter-search-clear").attr('data-show','false');
			$(".search-list-content").attr('data-show','false');
			OnLoadDraft(id);
		}
	}else{
		//关闭草稿箱
		$('.draftBox').removeClass('active');

		var obj = $('.tab-coll-send').find('[data-id="'+id+'"]');
		//选中样式
		obj.addClass('listselected');
		//显示章节按钮
		$('#guideHeaderBtns .chapterBtn').removeClass('dn');
		formData.cid=id;
		formData.did=0;
		OnLoadSaid();
		//定位点击结果
		if( type == 'search_chapter' || type == 'create_chapter' ){
			//展开结果所在分卷
			$('.chapterBox').addClass('active');
			var vid = obj.parent().data('vid');
			$('#volume_title_'+vid).addClass('active');
			$('#volume_chapter_list_'+vid).removeClass('dn');
			//定位点击结果
			$('.tab-coll-send').scrollTop($('.tab-coll-send').find('[data-id="'+id+'"]')[0].offsetTop);
		}
		//搜索点击就关闭结果和加载内容
		if( type == 'search_chapter' || type == 'chapter' ){
			$(".capter-search-clear").attr('data-show','false');
			$(".search-list-content").attr('data-show','false');
			OnLoadChapter(id);
		}
	}
	//重置定时器
	RestTimer($('.ne-sc-details .listselected').data('word'),$('.ne-sc-details .listselected').data('time'));
}
/** 加载草稿详情 **/
function OnLoadDraft(did){
	ShowLoging(true);
	$.ajax({
		type:"POST",url:getDraftUrl,dataType:"json",data:{module:module,cid:novelId,did:did},
		success:function(data){
			ShowLoging();
			if(data.code==201){
				DialogAlert('错误提示','草稿不存在');
			}else if(data.code==200){
				$('#title').val(data.data.draft_title);
				editor.txt.html(data.data.draft_content);
				ShowSaid(data.data.draft_option.said);
				formData.did = did;
				formData.cid = 0;
			}else{
				DialogAlert('错误提示',data.msg);
			}
		}
	});
}
/** 加载章节详情 **/
function OnLoadChapter(cid){
	ShowLoging(true);
	$.ajax({
		type:"POST",url:getChapterUrl,dataType:"json",data:{module:module,nid:novelId,cid:cid},
		success:function(data){
			ShowLoging();
			if(data.code==201){
				DialogAlert('错误提示','章节不存在');
			}else if(data.code==200){
				$('#title').val(data.data.chapter_name);
				editor.txt.html(data.data.content);
				formData.did = 0;
				formData.cid = cid;
				formData.vid = data.data.chapter_vid;
				formData.pay = data.data.chapter_ispay;
				OnLoadSaid();
			}else{
				DialogAlert('错误提示',data.msg);
			}
		}
	});
}
/**
 * 展示/隐藏加载状态
 * 参数1，选填，定位类型，默认新建草稿定位
 * 参数1，选填，定位的草稿id
 */
function ShowLoging(show){
	//展示加载状态
	if( show === true ){
		$('.write-body .page-loading').removeClass('dn');
		$('#write-body').addClass('dn');
	}else{
		$('.write-body .page-loading').addClass('dn');
		$('#write-body').removeClass('dn');
	}
}
//显示/隐藏编辑器区域
function ShowEditor(show){
	if( show===false ){
		$('.write-view-body').addClass('dn');
		$('.empty-page').removeClass('dn');
	}else{
		if( $('.write-view-body').hasClass('dn') ){
			$('.write-view-body').removeClass('dn');
			$('.empty-page').addClass('dn');
			$('.w-e-text-container').css("height",$('.chapter-content-placeholder').height());
		}
	}
}
/**
 * 显示作者的话
 * 参数1，必须，作者的话内容
 */
function ShowSaid(content){
	if( content ){
		$('.ne-author-say .author-say').addClass('all-txt');
		$('.ne-author-say .author-say-txt').html(content);
		$('#said').val(content);
		$("#center_talk_panel").find('.current-count').html($('#said').val().length);
	}else{
		$('.ne-author-say .author-say').removeClass('all-txt');
		$('.ne-author-say .author-say-txt').html('');
		$('#said').val('');
		$("#center_talk_panel").find('.current-count').html(0);
	}
}
/**
 * 重置定时器
 * 参数1，选填，章节字数
 * 参数2，选填，创建时间
 */
function RestTimer(wordNumber,createTime){
	if( !wordNumber ){
		wordNumber = 0;
	}
	if( !createTime ){
		createTime = FormatTime();
	}else{
		createTime = FormatTime(createTime);
	}
	//设置当前字数
	$('.wordnumber').html(wordNumber);
	//设置创建时间
	$('.createtime').html(createTime);
	//清除编辑时长
	$('.time').html('00:00:00');
	//清空码字速度
	$('.speed').html(0);
	//清除定时器
	clearInterval(timer);
	time=0;
	word = wordNumber;
	timer = window.setInterval(function(){
	    if( timerStop == true ){
	        return false;
	    }
		time++;
		//统计字数
		var nowWord = wordsNumber(editor.txt.text());
		$('.wordnumber').html(nowWord);
		//统计码字速度
		speedWord = nowWord-word;
		$('.speed').html(speedWord<=0?0:parseInt(speedWord/time*60));
		//更新编辑时间
		$('.time').html(FormatSeconds(time));
	},1000);
}
//删除章节
function DeleteChapter(){
	if( $('#alert_panel .ui-button-primary').hasClass('loading') ){
		return false;
	}
	$('#alert_panel .alert_close').hide();
	$('#alert_panel .ui-button-primary').addClass('loading');
	$.ajax({
		type:"POST",url:delChapterUrl,dataType:"json",
		data:{cid:formData.cid},
		success:function(data){
			$('#alert_panel .alert_close').show();
			$('#alert_panel .ui-button-primary').removeClass('loading');
			$('#alert_panel .alert_close').click();
			if( data.code==200){
				DialogTip(data.msg);
				OnLoadChapterList();
				ShowEditor(false);
			}else{
				return DialogTip(data.msg,2);
			}
		},
		async:true,
	});
}
//清理空行
function Clearp(html){
    html = html.replace(/<p>　　<\/p>/g, '');
    html = html.replace(/<p><\/p>/g, '');
    html = html.replace(/<span.*?>/gim, '');
    html = html.replace(/<\/span>/gim, '');
	return html;
}
//内容格式化
function FormatContent(html){
    var newHtml = '';
    html = html.match(/<p>([\s\S]*?)<\/p>/g); 
    for(o in html){
        var line = html[o];
        line = line.replace(/<span.*?>/gim, '');
        line = line.replace(/<\/span>/gim, '');
        line = line.replace(/<p>　　/g, '<p>');
        line = line.replace(/<p>&nbsp;.*?&nbsp;/g,"<p>");
        line = line.replace(/<p>/g,'').replace(/<\/p>/g,'');
        newHtml += '<p>'+editor.customConfig.enterPush+$.trim(line)+'</p>';
    }
    return newHtml;
}
//监听窗口变化
$(window).resize(function(){
	$('.w-e-text-container').css("height",$('.chapter-content-placeholder').height());
});
//弹出窗口关闭
$(document).on('mouseup',function (e) {//只有在mouseup是展示的效果是正确的，down不行
	$('.ui-droplist-x').hide();
})
$(document).ready(function(){
	/** Start 初始化数据 **/
	//初始化默认皮肤和大小
	var autoFormatSpace = getCookie('auto_format_space')!='0'?true:false;
	var autoFormatContent = getCookie('auto_format_content')!='0'?true:false;
	var setSkin = getCookie('set_skin')?getCookie('set_skin'):'light';
	var setSize = getCookie('set_size')?getCookie('set_size'):'2';
	ChangeSkin(setSkin);
	ChangeSize(setSize);
	//不自动去除空格
	if( autoFormatSpace==false ){
	    $('#auto_format_space').removeAttr("checked");
	}
	//不自动格式化
	if( autoFormatContent==false ){
	    $('#auto_format_content').removeAttr("checked");
	}
	//书籍信息
	OnLoadNovel();
	//分卷列表 - 章节列表
	OnLoadVolumeList();
	//草稿列表
	OnLoadDraftList();
	//大纲
	OnLoadOutLine();
	/** End 初始化数据 **/
	
	//编辑器配置
	editor.customConfig.menus = false;
	editor.customConfig.focus = false;
	editor.customConfig.autoNewLine = false;
	editor.customConfig.upload = false;
	editor.customConfig.enterPush = '　　';
	//内容变化追加缩进
	editor.customConfig.onchange = function (newHtml) {
		var $selectionElem = editor.selection.getSelectionContainerElem();
		if( $selectionElem && $selectionElem[0].innerHTML == '<br>' ){
			var $p = $('<p>'+editor.customConfig.enterPush +'</p>');
			$p.insertBefore($selectionElem);
			editor.selection.createRangeByElem($p, true);
			$selectionElem.remove();
		}
	};
	// 配置粘贴文本的内容处理
	editor.customConfig.pasteTextHandle = function (pasteStr) {
		//当前行的内容
		var $selectionElem = editor.selection.getSelectionContainerElem();
		var selectionHtml = $selectionElem[0].innerHTML;
		//替换所有p结尾标签为br
        pasteStr = pasteStr.replace(/<html[\s\S]*?<body>/gim,"");
        pasteStr = pasteStr.replace(/<html[\s\S]*?<\/head>/gim,"");
		pasteStr = pasteStr.replace(/<p.*?>/gim, '<br/>');
		pasteStr = pasteStr.replace(/<br>/gim, '<br/>');
		pasteStr = pasteStr.replace(/<o:p>&nbsp;<\/o:p>/g,"");
        pasteStr = pasteStr.replace(/o:/gim,"");
        pasteStr = pasteStr.replace(/<font.*?>/gim,"");
        pasteStr = pasteStr.replace(/<span.*?>/gim,"");
        pasteStr = pasteStr.replace(/<\/p>/gim,"");
        pasteStr = pasteStr.replace(/<\/font>/gim,"");
        pasteStr = pasteStr.replace(/<\/span>/gim,"");

		//保留br标签，去除其他所有标签
		var reg = /<(?!\/?br\/?.+?>)[^<>]*>/gi;
		pasteStr = pasteStr.replace(reg, '');
		if( !pasteStr ){ return pasteStr; }
		if( pasteStr.trim()=='' ){ return editor.customConfig.enterPush; }
		//按br换行
		var contentArr = pasteStr.split("<br/>");
		var newPasteStr = '';
		if( contentArr && contentArr.length > 0 ){
			//去除第一行
			if(contentArr[0]==''){
				contentArr.shift();
			}
			for(var i=0;i<contentArr.length;i++){
				lineContent = contentArr[i].replace(/&nbsp;/g, ' ');
				lineContent = lineContent.replace(/\t/g,"&nbsp;");
				lineContent = lineContent.replace(/\r\n/g,"");
				lineContent = lineContent.replace(/<[^>]+>/g,"");
				lineContent = lineContent.trim();
				if( selectionHtml != editor.customConfig.enterPush ){
					//只有一行数据
					if( contentArr.length==1 && i==0 ){
						newPasteStr += lineContent;
					}else{
						newPasteStr += '<p>'+editor.customConfig.enterPush+lineContent+'</p>';
					}
				}else{
					newPasteStr += '<p>'+editor.customConfig.enterPush+lineContent+'</p>';
				}
			}
			if( editor.txt.text()==editor.customConfig.enterPush ){
				editor.txt.html('<p></p>');
			}
		}
		//自动去除空格
		if( autoFormatSpace == true ){
		    newPasteStr = Clearp(newPasteStr);
		}
		//自动格式化内容
		if( autoFormatContent == true ){
		    newPasteStr = FormatContent(newPasteStr);
		}
		return newPasteStr;
	}
	//创建编辑器
	editor.create();
	//初始化
	$('.w-e-toolbar').remove();
	$('.w-e-text-container').css("border","none");
	$('.w-e-text-container').css("height",$('.chapter-content-placeholder').height());
	//编辑器获得焦点
	$(".w-e-text").focus(function(){
	    timerStop = false;
		if(editor.txt.text()=='在此输入正文'){
			editor.txt.html('<p>　　</p>')
		}
		$('.chapter-content-placeholder').css("color","inherit");
	});
	//编辑器失去焦点
	$(".w-e-text").blur(function(){
	    timerStop = true;
		if(editor.txt.text()==''||editor.txt.text()=='　　'){
			editor.txt.html('<p>在此输入正文</p>')
		}
		$('.chapter-content-placeholder').css("color","#bfbfbf");
	});
	
	//通用面板关闭
	$(".panel_close,.nu_dialog_mask,.nu_dialog_close,.j_drawer_close,.btn-send-cancel").click(function(){
		$(".panel").removeClass('_open');
	});
	
	/** Start 顶部事件 **/
	//顶部左侧分卷
	$("#top_volume").click(function(){
		$('#top_volume_panel').css('top',46);
		$('#top_volume_panel').css('left',152);
		$("#top_volume_panel").show();
	});
	//顶部左侧分卷点击
	$("#top_volume_panel").on("click","a", function() {
		formData.vid = parseInt($(this).data('vid'));
		$('#top_volume span').html($(this).html());
		$('#sure_volume span').attr($(this).html());
		$('#sure_volume span').html($(this).html());
	});
	//顶部左侧章节类型
	$("#top_chapter").click(function(){
		$('#top_chapter_panel').css('top',46);
		$('#top_chapter_panel').css('left',247);
		$("#top_chapter_panel").show();
	});
	//顶部左侧章节类型点击
	$("#top_chapter_panel").on("click","a", function() {
		formData.pay = parseInt($(this).data('pay'));
		$('#top_chapter span').html($(this).html());
		$('#sure_chapter span').attr($(this).html());
		$('#sure_chapter span').html($(this).html());
	});
	//顶部右侧作品大纲
	$("#top_outline").click(function(){
		$("#top_outline_panel").addClass('_open');
	});
	//顶部右侧作品大纲和中部作者的话
	$("#outline,#said").bind("input propertychange",function(){
		var val = $(this).val();
		var countObj = $(this).parent().parent().parent().find('.current-count');
		var max = parseInt($(this).parent().parent().parent().find('.current-max').html());
		if(val.length>max){
			$(this).val(val.substring(0,max))
		}
		if(val==''){
			ShowSaid('');
		}else{
			ShowSaid(val);
		}
		countObj.html($(this).val().length);
	});
	//顶部右侧分卷管理
	$("#top_volume_manager").click(function(){
		$("#top_volume_manager_panel").addClass('_open');
	});
	//顶部右侧分卷管理-分卷点击
	$("#top_volume_manager_panel").on("click",".volume-nav ol li", function() {
		$("#top_volume_manager_panel ol li").removeClass('listselected');
		$(this).addClass('listselected');
		var data = $(this).data('json');
		formData.manager_vid = data.volume_id;
		$('#volume_name').val(data.volume_name);
		$('#volume_desc').val(data.volume_desc);
		$('#volume_order').val(data.volume_order);
		$('#volume_delete').show();
	});
	//顶部右侧分卷管理-新建分卷
	$("#create_volume_btn").click(function(){
		$("#top_volume_manager_panel ol li").removeClass('listselected');
		formData.manager_vid = 0;
		$('#volume_name').val('');
		$('#volume_desc').val('');
		$('#volume_order').val('');
		$('#volume_delete').hide();
	});
	//顶部右侧分卷管理的分卷名称和分卷管理的分卷简介
	$("#volume_name,#volume_desc").bind("input propertychange",function(){
		var val = $(this).val();
		var countObj = $(this).parent().parent().parent().find('.current-count');
		var max = parseInt($(this).parent().parent().parent().find('.current-max').html());
		if(val.length>max){
			$(this).val(val.substring(0,max))
		}
		countObj.html($(this).val().length);
	});
	//顶部右侧分卷管理-分卷顺序输入监听
	$("#volume_order").bind("input propertychange",function(){
		if(isNaN($(this).val())){
			$(this).val('');
		}
	});
	//顶部右侧分卷管理-分卷删除
	$("#volume_delete").click(function(){
		if( $(this).hasClass('loading') ){
			return false;
		}
		$('#top_volume_manager_panel .modal-edit-btn button').addClass('loading');
		$.ajax({
			type:"POST",url:delVolumeUrl,dataType:"json",
			data:{nid:novelId,vid:formData.manager_vid},
			success:function(data){
				$('#top_volume_manager_panel .modal-edit-btn button').removeClass('loading');
				if( data.code==200){
					$("#create_volume_btn").click();
					OnLoadVolumeList();
				}else{
					return DialogTip(data.msg,2);
				}
			},
			async:true,
		});
	});
	//顶部右侧分卷管理-分卷保存
	$("#volume_save").click(function(){
		//保存后重新加载
		if( $(this).hasClass('loading') ){
			return false;
		}
		var name = $('#volume_name').val();
		var order = $('#volume_order').val();
		var desc = $('#volume_desc').val();
		if( name=='' || order=='' || desc=='' ){
			return DialogTip('对不起，所有项不能为空',2);
		}else{
			$('#top_volume_manager_panel .modal-edit-btn button').addClass('loading');
			$.ajax({
				type:"POST",url:saveVolumeUrl,dataType:"json",
				data:{nid:novelId,vid:formData.manager_vid,name:name,order:order,desc:desc},
				success:function(data){
					$('#top_volume_manager_panel .modal-edit-btn button').removeClass('loading');
					if( data.code==200){
						DialogTip(data.msg);
						$("#create_volume_btn").click();
						OnLoadVolumeList();
					}else{
						return DialogTip(data.msg,2);
					}
				},
				async:true,
			});
		}
	});
	//顶部右侧保存按钮
	$(".save_btn").click(function(){
		if( $(this).hasClass('loading') ){
			return false;
		}
		var did = formData.did;
		var vid = formData.vid;
		var pay = formData.pay==1?1:0;
		var title = $("#title").val();
		var said = $("#said").val();
		var content = editor.txt.html();
		if( !isPositiveNum(did) ){
			return DialogTip('对不起，草稿id错误！',2);
		}else if( !isPositiveNum(vid) || vid==0 ){
			return DialogTip('对不起，请选择小说分卷！',2);
		}else if( title == '' || content == '' ){
			return DialogTip('对不起，请输入标题和内容！',2);
		}else{
			$('#guideHeaderBtns button').addClass('loading');
			$('.send-confirm-btn button').addClass('loading');
			$.ajax({
				type:"POST",url:saveDraftUrl,
				data:{did:did,module:module,contentid:novelId,vid:vid,pay:pay,title:title,content:content,said:said},
				dataType:"json",
				success:function(data){
					$('#guideHeaderBtns button').removeClass('loading');
					$('.send-confirm-btn button').removeClass('loading');
					if( data.code==200){
						DialogTip(data.msg);
						if( did==0){
							formData.did = data.data.draft_id;
							OnLoadDraftList('create_draft',formData.did);
						}else{
							$('.draftBox .listselected').find('.word_number').html(wordsNumber(content))
						}
					}else{
						return DialogTip(data.msg,2);
					}
				},
				async:true,
			});
		}
	});
	//顶部右侧发布按钮
	$(".release_btn").click(function(){
		if( $("#title").val() == '' || editor.txt.html()=='' ){
			return DialogTip('对不起，标题和内容不能为空！',2);
		}
		$(".publish_title").html($("#title").val());
		$(".publish_number").html(wordsNumber(editor.txt.text())+'字');
		$(".publish-form-dialog").addClass('_open');
	});
	//顶部右侧删除章节按钮
	$(".delete_btn").click(function(){
		DialogAlert('提示','删除后不可恢复，是否确认删除？','DeleteChapter');
	});
	//顶部右侧修改章节按钮
	$(".edit_save_btn").click(function(){
		if( $(this).hasClass('loading') ){
			return false;
		}
		var vid = formData.vid;
		var cid = formData.cid;
		var pay = formData.pay==1?1:0;
		var title = $("#title").val();
		var said = $("#said").val();
		var content = editor.txt.html();
		if( !isPositiveNum(cid) || cid<1){
			return DialogTip('对不起，章节id错误！',2);
		}else if( !isPositiveNum(vid) || vid==0 ){
			return DialogTip('对不起，请选择小说分卷！',2);
		}else if( title == '' || content == '' ){
			return DialogTip('对不起，请输入标题和内容！',2);
		}else{
			$('#guideHeaderBtns button').addClass('loading');
			$('.send-confirm-btn button').addClass('loading');
			$.ajax({
				type:"POST",url:saveChapterUrl,
				data:{did:0,module:module,contentid:novelId,cid:cid,vid:vid,pay:pay,title:title,content:content,said:said},
				dataType:"json",
				success:function(data){
					$('#guideHeaderBtns button').removeClass('loading');
					$('.send-confirm-btn button').removeClass('loading');
					if( data.code==200){
						DialogTip(data.msg);
						$('.chapterBox .listselected').find('.word_number').html(data.data.word_number)
					}else{
						return DialogTip(data.msg,2);
					}
				},
				async:true,
			});
		}
	});
	//顶部右侧更多
	$("#top_more").click(function(){
		$("#top_more_panel").show();
	});
	//顶部右侧更多-功能说明
	$("#top_explain").click(function(){
		$("#top_explain_panel").addClass('_open');
	});
	/** End 顶部事件 **/
	

	/** Start 中部事件 **/
	//中部左侧折叠
	$(".side-coll-icon").click(function(){
		$("#root").addClass('sidebar-coll-on');
	});
	$(".side-coll-txt").click(function(){
		$("#root").removeClass('sidebar-coll-on');
	});
	//中部左侧搜索
	$(".capter-search-input").bind("input propertychange",function(){
		var searchKey = $(this).val();
		if( searchKey == '' ){
			$(".capter-search-clear").attr('data-show','false');
			$(".search-list-content").attr('data-show','false');
			$('.search-chapter').hide();
			$('.search-draft').hide();
			$('.search-chapter-list').html('');
			$('.search-draft-list').html('');
		}else{
			//搜索章节
			var searchChapterHtml = '';
			$('.volume-chapter-item li').each(function(index){
				var draftTitle = $(this).find('.chapter-title').html();
				var result = Find(draftTitle,searchKey);
				var id = $(this).data('id');
				var type = $(this).data('type');
				if( result.count>0 ){
					searchChapterHtml += '<li title="'+draftTitle+'" data-id="'+id+'" data-type="search_'+type+'" class="search-res-item">'+result.content+'</li>';
				}
			});
			if( searchChapterHtml != '' ){
				$('.search-chapter').show();
				$('.search-chapter-list').show();
				$('.search-chapter-list').html(searchChapterHtml);
			}
			//搜索草稿箱
			var searchDraftHtml = '';
			$('.tab-coll-draft li').each(function(index){
				var draftTitle = $(this).find('.tab-coll-title').html();
				var result = Find(draftTitle,searchKey);
				var id = $(this).data('id');
				var type = $(this).data('type');
				if( result.count>0 ){
					searchDraftHtml += '<li title="'+draftTitle+'" data-id="'+id+'" data-type="search_'+type+'" class="search-res-item">'+result.content+'</li>';
				}
			});
			if( searchDraftHtml != '' ){
				$('.search-draft').show();
				$('.search-draft-list').show();
				$('.search-draft-list').html(searchDraftHtml);
			}
			//搜索结果均不为空
			if( searchChapterHtml!='' || searchDraftHtml!='' ){
				$(".capter-search-clear").attr('data-show','true');
				$(".search-list-content").attr('data-show','true');
			}else{
				$(".capter-search-clear").attr('data-show','false');
				$(".search-list-content").attr('data-show','false');
				$('.search-chapter').hide();
				$('.search-draft').hide();
				$('.search-chapter-list').html('');
				$('.search-draft-list').html('');
			}
		}
	});
	//中部左侧搜索结果点击
	$(".search-list-content").on("click",".search-res-item", function() {
		DraftChapterClick($(this).data('type'),$(this).data('id'));
	});
	//中部左侧清空搜索
	$(".capter-search-clear").click(function(){
		$('.capter-search-input').val('');
		$(".capter-search-clear").attr('data-show','false');
		$(".search-list-content").attr('data-show','false');
		$('.search-chapter').hide();
		$('.search-draft').hide();
		$('.search-chapter-list').html('');
		$('.search-draft-list').html('');
	});
	//中部左侧新建章节
	$("#create_draft").click(function(){
		//如果编辑器部分不可见，就使其可见。
		ShowEditor();
		//显示草稿按钮
		$('#guideHeaderBtns button').addClass('dn');
		$('#guideHeaderBtns .draftBtn').removeClass('dn');
		formData.cid=0;
		formData.did=0;
		$('#title').val('');
		editor.txt.html('<p>在此输入正文</p>');
		RestTimer(0,0);
		ShowSaid();
	});
	//中部左侧草稿正文菜单
	$(".ne-sc-summary").click(function(){
		if( $(this).parent().hasClass('active') ){
			$(this).parent().removeClass('active');
		}else{
			$(this).parent().addClass('active');
		}
	});
	//中部左侧分卷菜单
	$("#publish-list").on("click",".volume-item", function() {
		var thisIndex = parseInt($(this).index()/2);
		//如果分卷是打开状态就关闭
		if( $(this).hasClass('active') ){
			$(this).removeClass('active');
			//分卷下面的章节如果是隐藏就显示
			if( $('.volume-chapter-item').eq(thisIndex).hasClass('dn') ){
				$('.volume-chapter-item').eq(thisIndex).removeClass('dn');
			}else{
				$('.volume-chapter-item').eq(thisIndex).addClass('dn');
			}
		}else{
			$(this).addClass('active');
			$('.volume-chapter-item').eq(thisIndex).removeClass('dn');
		}
	});
	//中部左侧点击草稿文章或者正文文档
	$(".write-tabs").on("click",".tab-coll-draft li,.volume-chapter-item li", function() {
		DraftChapterClick($(this).data('type'),$(this).data('id'));
	});
	//中部撤销操作
	$("#center_undo").click(function(){
		editor.cmd.do('undo');
	});
	//中部前进操作
	$("#center_redo").click(function(){
		editor.cmd.do('redo');
	});
	//中部查找替换
	$("#center_find").click(function(){
		$("#center_find_panel").show();
		$("#center_content_tool_panel").hide();
	});
	//中部内容工具箱
	$("#center_content_tool").click(function(){
		$("#center_find_panel").hide();
		$("#center_content_tool_panel").show();
	});
	//中部查找替换关闭
	$(".tool-fr-close").click(function(){
		$("#center_find_panel").hide();
		$("#center_content_tool_panel").hide();
	});
	//查找替换输入查找字符后
	$("#txtFind").bind("input propertychange",function(){
		//当前选中索引
		var curIndex = 1;
		//查找关键字
		var result = Find(editor.txt.html(),$(this).val(),curIndex);
		editor.txt.html(result.content);
		//找到关键字才进入
		if( result.count > 0 ){
			$('#txtFindCur').html(curIndex);
			$('#txtFindCount').html(result.count);
			//给第一个加上选中样式
			$('#content .keywordBg').eq(curIndex-1).addClass('keywordSelect');
			//移动滚动条显示
			$('#content .w-e-text').scrollTop($('#content .keywordSelect')[0].offsetTop);
			//大于寻找下一个才可用
			if( result.count > 1 ){
				$('#txtFindDown').removeClass('disabled');
			}
		}else{
			$('#txtFindCur').html(0);
			$('#txtFindCount').html(0);
			$('#textReplaceBtn').addClass('disabled');
			$('#textReplaceAllBtn').addClass('disabled');
			$('#txtFindUp').addClass('disabled');
			$('#txtFindDown').addClass('disabled');
		}
	});
	//替换字符输入框
	$('#textReplace').keyup(function(){
		if( $('#txtFindCount').html() > '0' && $(this).val() ){
			$('#textReplaceBtn').removeClass('disabled');
			$('#textReplaceAllBtn').removeClass('disabled');
			return;
		}
		$('#textReplaceBtn').addClass('disabled');
		$('#textReplaceAllBtn').addClass('disabled');
	});
	//寻找上一个
	$('#txtFindUp').click(function(){
		var cur = parseInt($('#txtFindCur').html());
		var count = parseInt($('#txtFindCount').html());
		cur--;
		//选中关键字并且移动滚动条
		$('#content .keywordSelect').removeClass('keywordSelect');
		$('#content .keywordBg').eq(cur-1).addClass('keywordSelect');
		$('#content .w-e-text').scrollTop($('#content .keywordSelect')[0].offsetTop);
		if( cur <= 1 ){
			$('#txtFindCur').html(1)
			$('#txtFindDown').removeClass('disabled');
			$('#txtFindUp').addClass('disabled');
			return;
		}else{
			$('#txtFindDown').removeClass('disabled');
			$('#txtFindCur').html(cur)
		}
	});
	//寻找下一个
	$('#txtFindDown').click(function(){
		var cur = parseInt($('#txtFindCur').html());
		var count = parseInt($('#txtFindCount').html());
		cur++;
		//选中关键字并且移动滚动条
		$('#content .keywordSelect').removeClass('keywordSelect');
		$('#content .keywordBg').eq(cur-1).addClass('keywordSelect');
		if( $('#content .keywordSelect')[0].offsetTop - $('#content .w-e-text').scrollTop() > 450 ){
			$('#content .w-e-text').scrollTop($('#content .keywordSelect')[0].offsetTop);
		}
		if( cur >= count ){
			$('#txtFindCur').html(count)
			$('#txtFindUp').removeClass('disabled');
			$('#txtFindDown').addClass('disabled');
			return;
		}else{
			$('#txtFindUp').removeClass('disabled');
			$('#txtFindCur').html(cur)
		}
	});
	//替换一次
	$('#textReplaceBtn').click(function(){
		var curIndex = parseInt($('#txtFindCur').html());
		var result = Find(editor.txt.html(),$('#txtFind').val(),curIndex,$('#textReplace').val());
		editor.txt.html(result.content);
		if(result.count==0){
			$('#txtFindCur').html(0);
			$('#txtFindCount').html(0);
			$('#txtFindUp').addClass('disabled');
			$('#txtFindDown').addClass('disabled');
			$('#textReplaceBtn').addClass('disabled');
			$('#textReplaceAllBtn').addClass('disabled');
		}else{
			$('#txtFindCur').html(curIndex<result.count?curIndex:result.count);
			$('#txtFindCount').html(result.count);
			$('#content .keywordBg').eq(curIndex-1).addClass('keywordSelect');
			if( $('#content .keywordSelect')[0].offsetTop - $('#content .w-e-text').scrollTop() > 450 ){
				$('#content .w-e-text').scrollTop($('#content .keywordSelect')[0].offsetTop);
			}
		}
	});
	//替换全部
	$('#textReplaceAllBtn').click(function(){
		var result = Find(editor.txt.html(),$('#txtFind').val(),0,$('#textReplace').val());
		editor.txt.html(result.content);
		if(result.count==0){
			$('#txtFindCur').html(0);
			$('#txtFindCount').html(0);
			$('#txtFindUp').addClass('disabled');
			$('#txtFindDown').addClass('disabled');
			$('#textReplaceBtn').addClass('disabled');
			$('#textReplaceAllBtn').addClass('disabled');
		}
	});
	
	//中部错别字检查
	$("#center_word_check").click(function(){
		var result = CheckWords(editor.txt.html());
		if( result !== false ){
			DialogAlert('提示','检测到错别字：凑合');
		}else{
			DialogTip('暂未找到词库常用错别字');
		}
	});
	//中部清理空行
	$("#center_symbol_clearp").click(function(){
	    var html = editor.txt.html();
	    html = html.replace(/<p>　　<\/p>/g, '');
	    html = html.replace(/<p><\/p>/g, '');
		editor.txt.html(Clearp(editor.txt.html()));
	});
	//中部内容格式化
	$("#center_format").click(function(){
		editor.txt.html(FormatContent(editor.txt.html()));
	});
	//中部自动去除空格设置
	$('#auto_format_space').click(function(){
	    if( $(this).is(':checked')==true ){
	        setCookie('auto_format_space','1');
	    }else{
	        setCookie('auto_format_space','0');
	    }
	})
	//中部自动格式化内容设置
	$('#auto_format_content').click(function(){
	    if( $(this).is(':checked')==true ){
	        setCookie('auto_format_content','1');
	    }else{
	        setCookie('auto_format_content','0');
	    }
	})
	//中部符号替换
	$("#center_symbol_replace").click(function(){
		editor.txt.html(SymbolReplace(editor.txt.html()));
	});
	//中部作者工具
	$("#center_tool").click(function(){
		$("#center_tool_panel").addClass('_open');
		OnLoadTool($('#center_tool_panel .listselected').data('cell'));
	});
	//作者工具类型点击
	$("#center_tool_panel .volume-nav li").click(function(){
		$("#center_tool_panel .volume-nav li").removeClass('listselected');
		$(this).addClass('listselected');
		$("#center_tool_panel .cell .chinese-name").addClass('dn');
		$('.'+$(this).data('cell')).removeClass('dn');
		OnLoadTool($('#center_tool_panel .listselected').data('cell'));
	});
	//作者姓名工具 - 切换国家
	$("#tool_name_country .ui-button").click(function(){
		$("#tool_name_country .ui-button").removeClass('tool_cur');
		$(this).addClass('tool_cur');
		OnLoadTool('name');
	});
	//作者姓名工具 - 内容变化
	$("#xing,#ming").bind("input propertychange",function(){
		var val = $(this).val();
		var countObj = $(this).parent().find('.current-count');
		var max = parseInt($(this).parent().find('.current-max').html());
		if( val.length>max ){
			$(this).val(val.substring(0,max))
		}
		countObj.html($(this).val().length);
		OnLoadTool('name');
	});
	//作者姓名工具 - 选择条件
	$("[name=xing_len],[name=ming_len],[name=sex]").click(function(){
		OnLoadTool('name');
	});
	//作者姓名和门派工具点击复制
	$(".name-example-ul").on("click",".name-example-li", function() {
		$('#tool_copy_content').val($(this).data('val'));
		$('#tool_copy_content').select();
		document.execCommand("Copy"); // 执行浏览器复制命令
		DialogTip('"'+$(this).data('val')+'"复制成功');
	});
	//作者门派工具输入监听
	$("#prefix,#suffix").bind("input propertychange",function(){
		var val = $(this).val();
		var countObj = $(this).parent().find('.current-count');
		var max = parseInt($(this).parent().find('.current-max').html());
		if( val.length>max ){
			$(this).val(val.substring(0,max))
		}
		countObj.html($(this).val().length);
	});
	//中部作者的话
	$("#center_talk,.author-say").click(function(){
		$("#center_talk_panel").addClass('_open');
	});
	/** End 中部事件 **/
	

	/** Start 底部事件 **/
	//底部换肤工具栏
	$(".skin-color").click(function(){
		ChangeSkin($(this).data('color'));
	});
	//底部缩小工具栏
	$(".ne-font-down").click(function(){
		lastSize = currentSize;
		if( currentSize > 0 ){
			currentSize = currentSize-1;
		}
		ChangeSize(currentSize);
	});
	//底部放大工具栏
	$(".ne-font-add").click(function(){
		lastSize = currentSize;
		if( currentSize < 4 ){
			currentSize = currentSize+1;
		}
		ChangeSize(currentSize);
	});
	//底部放大工具栏
	$(".fullScreen").click(function(){
		if( full == false ){
			full = true;
			FullScreen();
		}else{
			full = false;
			ExitFullscreen();
		}
	});
	/** End 底部事件 **/
	
	/** Start 确认发布章节 **/
	//发布时间选择
	$("#sendNow").click(function(){
		$(".choose-date-time").hide();
	});
	$("#sendTimimg").click(function(){
		$(".choose-date-time").show();
	});
	//最后确认分卷
	$("#sure_volume").click(function(){
		$('#top_volume_panel').css('top',$(this).offset().top+20);
		$('#top_volume_panel').css('left',$(this).offset().left-20);
		$("#top_volume_panel").show();
	});
	//最后确认章节类型
	$("#sure_chapter").click(function(){
		$('#top_chapter_panel').css('top',$(this).offset().top+25);
		$('#top_chapter_panel').css('left',$(this).offset().left-10);
		$("#top_chapter_panel").show();
	});
	//确认发布内容
	$(".btn-send-sure").click(function(){
		if( $(this).hasClass('loading') ){
			return false;
		}
		var did = formData.did;
		var vid = formData.vid;
		var pay = formData.pay==1?1:0;
		var title = $("#title").val();
		var said = $("#said").val();
		var content = editor.txt.html();
		if( !isPositiveNum(pay)){
			return DialogTip('对不起，章节类型错误！',2);
		}else if( !isPositiveNum(vid) || vid==0 ){
			return DialogTip('对不起，请选择分卷！',2);
		}else if( !isPositiveNum(did) ){
			return DialogTip('对不起，草稿id错误！',2);
		}else if( title == '' || content == '' ){
			return DialogTip('对不起，所有标题内容不能为空！',2);
		}else{
			$('#guideHeaderBtns button').addClass('loading');
			$('.send-confirm-btn button').addClass('loading');
			$.ajax({
				type:"POST",
				url:saveChaptertUrl,
				data:{did:did,module:module,contentid:novelId,vid:vid,pay:pay,title:title,content:content,said:said},
				dataType:"json",
				success:function(data){
					if( data.code == 200){
						//移除当前草稿节点
						$('.draftBox .listselected').remove();
						//重新加载章节列表
						OnLoadChapterList('create_chapter',data.data.chapter_id);
						DialogTip(data.msg);
					}else{
						DialogTip(data.msg,2);
					}
					$('#guideHeaderBtns button').removeClass('loading');
					$('.send-confirm-btn button').removeClass('loading');
					$('.nu_dialog_close').click();
				},
				async:true,
			});
		}
	});
	/** Start 确认发布章节 **/

	
	//弹窗关闭
	$(".alert_close").click(function(){
		$("#alert_panel").hide();
	});
	//键盘事件
    document.addEventListener('keydown', function(e){
	    //编辑器存在焦点，并且是保存快捷键
        if ( e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey) ){
            e.preventDefault();
            if( $('.w-e-text').is(':focus')==true && $('.save_btn').hasClass('dn')==false ){
                $('.save_btn').click();
            }
        }
    });
});