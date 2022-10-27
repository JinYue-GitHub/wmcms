//获得当前窗口高度
var clientHeight = GetClientHeight();
//内容区域距离顶部高度
var contentTop = $('.j_readContent').offset().top;
//内容区域高度
var contentHeight = $('.j_readContent').height();
//内容区域总行数
var contentLine = $('.j_readContent').find('p').length;
//每行高的像素
var contentLinePx = parseInt(contentHeight/contentLine);
//最多行数
var maxLine =  Math.ceil(contentHeight/contentLinePx);

// 当网页向下滑动 20px 出现"返回顶部" 按钮
function scrollFunction() {
	if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
		$('#j_goTop').show();
	} else {
		$('#j_goTop').hide();
	}
	if (document.body.scrollTop > 60 || document.documentElement.scrollTop > 60) {
		$('.section-comment-wrap').addClass('fixed');
		$('.wmcms_replay_list ul').css('height','calc(100vh - 381px + 125px)');
	} else {
		$('.section-comment-wrap').removeClass('fixed');
		$('.wmcms_replay_list ul').css('height','calc(100vh - 480px + 145px)');
	}
	
	//开启弹幕，并且当前行数小于总行数才进行弹幕更新
	if( danmu == true && danmuLine < maxLine && danmuListArr!==false){
		pushDanmuArr();
	}
}
window.onscroll = function() {scrollFunction()};

//登录操作
function login(){
	return easydialog_url('user.login','登录/注册','',340,420);
}
//检查用户是否登录
function checkLogin(){
	$.get(userInfoUrl,function(data){
		if(data.code == 200){
			isLogin = true;
			$('.sign-out').hide();
			$('.sign-in').show();
			$('.rechange').show();
			$('#nav-user-name').html(data.data.user_nickname);
		}
	},"json");
}
//收藏操作
function coll(url){
	$.ajax({type:"POST",dataType:"json",url:url+'&ajax=yes',success:function(data){},});
}
//打开互动操作
function operate(type){
	if( typeof(type) == 'undefined' || typeof(type) == '' ){
		type='operate';
	}
	var data=[];
	data.nid = novelId;
	data.tab = type;
	easydialog_url('novel.operate','互动操作','',813,407,data);
}
//分卷列表初始化
function volumeListInit(){
	$("#volume-"+volumeId).addClass("cur");
	$("#nav-chapter-"+cid).addClass("on");
	$("#volume-list-"+volumeId).show();
}
//推送弹幕数组
function pushDanmuArr(){
	//当前滚动条高度
	var scrollTop = document.body.scrollTop?document.body.scrollTop:document.documentElement.scrollTop;
	var newLine = Math.ceil(((clientHeight-contentTop-300)+scrollTop)/contentLinePx);
	if ( newLine>danmuLine ){
		for(danmuLine;danmuLine<=newLine;danmuLine++){
			if(danmuListArr[danmuLine] && danmuListArr[danmuLine].length>0 ){
				danmuArr = danmuArr.concat(danmuListArr[danmuLine]);
			}
		}
	}
}
//推送弹幕
function pushDanmu(){
	if( danmuArr.length ==0 || danmuIsInit == false ){
		return;
	}else if( danmuArr.length < 10 ){
		$('body').barrager({
			img:danmuArr[0].user_head,info:danmuArr[0].replay_content,close:true, //显示关闭按钮 
			speed:danmuSpeedNumberList[danmuSpeedIndex],
			color:danmuArr[0].color??danmuColor,size:danmuSize,opacity:danmuOpacity/100,
			position:danmuPosition,
			bodyHeight:clientHeight-40,
		});
		danmuArr.splice(0,1);
	}else{
		for (var i=1;i<=5;i++){
			$('body').barrager({
				img:danmuArr[i].user_head,info:danmuArr[i].replay_content,close:true, //显示关闭按钮 
				speed:danmuSpeedNumberList[danmuSpeedIndex],//速度
				color:danmuArr[i].color??danmuColor,//颜色
				size:danmuSize,//字体
				opacity:danmuOpacity/100,//透明度
				position:danmuPosition, //距离底部高度,单位px,默认随机 
				bodyHeight:clientHeight-40,//内容区域高度
			});
		}
		danmuArr.splice(0,10);
	}
}
//弹幕初始化
function danmuInit(){
	//开启了弹幕
	if( danmu == true && danmuIsInit == false){
		window.setInterval(pushDanmu,900);
		danmuIsInit = true;
		//获得弹幕
		$.ajax({
			type:"POST",dataType:"json",
			url:subsetListUrl,data:{module:module,sid:subsetId,cid:cid},
			success:function(result){
				if(parseInt(result.data.count)>0){
					$('body').barrager({
						info:"高能来袭，前方共有"+result.data.count+"位道友为您传音！", 
						speed:12,color:"#65FF04",size:danmuSize,opacity:danmuOpacity/100,
						bottom:clientHeight/2,bodyHeight:clientHeight-40
					});
					danmuListArr = result.data.list;
					pushDanmuArr();

				}else{
					$('body').barrager({
						info:"前方暂无道友为您传音！！！", 
						speed:12,color:"#90EE90",size:danmuSize,opacity:danmuOpacity/100,
						bottom:clientHeight/2,bodyHeight:clientHeight-40
					});
				}
			},
		});
	}
}
//切换主题
function switchTheme(val){
	if( !val ){ return false;}
	$('body').removeClass(theme);
	$('body').addClass(val);
	$('#j_themeList span').removeClass('act');
	$('#j_themeList').find('[data-val="'+val+'"]').addClass('act');
	theme = val;
	setCookie('read_theme',val);
}
//切换字体
function switchFont(val){
	if( !val ){ return false;}
	$('#j_readMainWrap').removeClass(contentFont);
	$('#j_readMainWrap').addClass(val);
	$('#j_fontFamily span').removeClass('act');
	$('#j_fontFamily').find('[data-val="'+val+'"]').addClass('act');
	contentFont = val;
	setCookie('read_font',val);
}
//切换字体大小
function switchFontSize(val){
	if( !val ){ return false;}
	$('#j_readMainWrap').css('font-size',val+'px');
	$('#j_fontSize .lang').html(val)
	contentFontSize = parseInt(val);
	setCookie('read_font_size',val);
}
//切换界面宽度
function switchWidth(val){
	if( val!==0 && !val ){ return false;}
	$('body').removeClass('w'+contentWidth);
	$('body').addClass('w'+contentWidthList[val]);
	$('#j_pageWidth .lang').html(contentWidthList[val]);
	contentWidthIndex = val;
	contentWidth = contentWidthList[val];
	setCookie('read_width',val);
}
//切换自动订阅
function switchAutoSub(val){
	if( val == 'null' || val==null ){ return false; }
	if(val==true || val == 'true'){
		$('#j_SubscribeAuto').removeClass('off');
		$('#j_SubscribeAuto').addClass('on');
		$('#j_autoSwitch').html('开启');
		$('#subscribeTip').html('当前已开启，新的收费章节会自动订阅');
	}else{
		$('#j_SubscribeAuto').removeClass('on');
		$('#j_SubscribeAuto').addClass('off');
		$('#j_autoSwitch').html('关闭');
		$('#subscribeTip').html('当前已关闭，每次订阅均需要确认扣费');
	}
	autoSub = val=='true'||val==true?true:false;
	setCookie('read_auto_sub',val);
}
//自动订阅
function autoSubRequest(){
	//请求订阅
	$.ajax({
		type:"POST",url:autoSubUrl,dataType:"json",
		data:{'nid':novelId,'module':module},
		success:function(data){
			//前端与后端同步
			if( data.code=='200' ){
				switchAutoSub(data.data.autosub==0?false:true);
			}
		}
	});
}
//切换开关本章说
function switchReplay(val){
	if( val == 'null' || val==null ){ return false; }
	if(val==true || val == 'true'){
		$('#j-sectionCommentBtn').removeClass('off');
		$('#j-sectionCommentBtn').addClass('on');
		$('#j-sectionCommentSwitch').html('开启');
		$('#j-sectionIsOpenTip').html('当前会正常显示所有本章说');
		//评论数量大于0的才显示
		$('.review-count').each(function(index,obj) {
			if( parseInt($(this).text()) > 0 ){
				$(obj).show();
			}
		});
	}else{
		$('#j-sectionCommentBtn').removeClass('on');
		$('#j-sectionCommentBtn').addClass('off');
		$('#j-sectionCommentSwitch').html('关闭');
		$('#j-sectionIsOpenTip').html('当前不会显示本章说');
		$('.review-count').hide();
	}
	replay = val=='true'||val==true?true:false;
	setCookie('read_replay',val);
}

//切换开关弹幕
function switchDanmu(val){
	if( val == 'null' || val==null){ return false; }
	if(val==true || val == 'true'){
		$('#danmuSwitch').removeClass('off');
		$('#danmuSwitch').addClass('on');
		$('#danmuSwitch a').html('开启');
		$('#danmuSwitchTip').html('当前会显示弹幕，但是无法点击本章说');
		$('.danmu-list-wrap').css('opacity',1);
		$('#j_danmuFastSwitch').show();
		danmu = true;
		if( danmuIsInit == true ){
			$(".barrage").show();
		}else{
			danmuInit();
		}
	}else{
		$('#danmuSwitch').removeClass('on');
		$('#danmuSwitch').addClass('off');
		$('#danmuSwitch a').html('关闭');
		$('#danmuSwitchTip').html('当前不会显示弹幕');
		$('.danmu-list-wrap').css('opacity',0.5);
		$('#j_danmuFastSwitch').hide();
		danmu = false;
		if( danmuIsInit == true ){
			$(".barrage").hide();
		}
	}
	setCookie('danmu',val);
}
//切换弹幕颜色
function switchDanmuColor(val){
	if( !val || danmu==false || val == 'null' ){ return false;}
	$('#j_danmuColorList span').removeClass('act');
	$('#j_danmuColorList').find('[data-val="'+val+'"]').addClass('act');
	danmuColor = val;
	setCookie('danmu_color',val);
}
//切换弹幕区域
function switchDanmuPosition(val){
	if( val == 'null' || val==null){ return false; }
	$('#j_danmuPositionList span').removeClass('act');
	$('#j_danmuPositionList').find('[data-val="'+val+'"]').addClass('act');
	danmuPosition = val;
	setCookie('danmu_position',val);
}
//切换弹幕大小
function switchDanmuSize(val){
	if( !val || danmu==false ){ return false;}
	$('#j_danmuSize .lang').html(val)
	danmuSize = parseInt(val);
	setCookie('danmu_size',val);
}
//切换弹幕速度
function switchDanmuSpeed(val){
	if( val!==0 && (!val || danmu==false) ){ return false;}
	$('#j_danmuSpeed .lang').html(danmuSpeedList[val]);
	danmuSpeedIndex = parseInt(val);
	danmuSpeed = danmuSpeedList[val];
	setCookie('danmu_speed',val);
}
//切换弹幕透明度
function switchDanmuOpacity(val){
	if( val!==0 && (!val || danmu==false) ){ return false;}
	$('#j_danmuOpacity .lang').html(danmuOpacityList[val]);
	danmuOpacityIndex = parseInt(val);
	danmuOpacity = danmuOpacityList[val];
	//切换弹幕透明度
	if( danmuIsInit == true ){
		$('.barrage').css("opacity",danmuOpacity/100);
	}
	setCookie('danmu_opacity',val);
}
//阅读模式设置
function switchReadType(val){
	if( !val || val==null ){ return false;}
	//听书
	if( val=='voice' ){
		//居中听书div
		var readTypeTop = (document.body.clientHeight-$('.voice-panel').height())/2;
		var readTypeLeft = ($(window).width() - $('.voice-panel').width())/2;
		$('.voice-panel').css({top:readTypeTop,left:readTypeLeft});
	    $('#readTypeVoice').hide();
	    $('#readTypeWord').show();
		$('body').css({"overflow-y":"hidden"});
	    $('.voice-panel').removeClass('hidden');
	    $('#voiceMask').removeClass('hidden');
	}else{ //文字阅读
	    resetPlayer();
	    $('#readTypeVoice').show();
	    $('#readTypeWord').hide();
		$('body').css({"overflow-y":"auto"});
	    $('.voice-panel').addClass('hidden');
	    $('#voiceMask').addClass('hidden');
	    $('.aplayer').addClass('hidden');
	}
	setCookie('read_type',val);
}
//切换语音合成渠道
function switchVoiceChannel(val){
	if( val ){
    	$('.voice-channel-box span').removeClass('act');
    	$('.voice-channel-box').find('[data-val="'+val+'"]').addClass('act');
    	voiceChannel = val;
    	setCookie('voice_channel',val);
	}
	var voicetList = voicetChannelList[$('.voice-channel-box .act').data('val')];
	var voicetHtml = '';
	for(o in voicetList){
	    voicetHtml += '<option value="'+voicetList[o].voicet_ids+'">'+voicetList[o].voicet_name+'</option>';
	}
	$('.voice-voicet-box select').html(voicetHtml);
}
//切换语速
function switchVoiceSpeed(val){
	if( !val && val!='0' ){ return false;}
	$('.voice-speed-box span').removeClass('act');
	$('.voice-speed-box').find('[data-val="'+val+'"]').addClass('act');
	voiceSpeed = val;
	setCookie('voice_speed',val);
}
//切换自动播放下一章
function switchVoiceAutoNext(val){
	if( val == 'null' || val==null ){ return false; }
	if(val==false || val == 'false'){
		$('#j-voiceAutoNextBtn').removeClass('on');
		$('#j-voiceAutoNextBtn').addClass('off');
		$('#j-voiceAutoNextSwitch').html('关闭');
	}else{
		$('#j-voiceAutoNextBtn').removeClass('off');
		$('#j-voiceAutoNextBtn').addClass('on');
		$('#j-voiceAutoNextSwitch').html('开启');
	}
	voiceAutoNext = val=='true'||val==true?true:false;
	setCookie('voice_auto_next',val);
}
//重置播放器
function resetPlayer(){
	$('.voice-playerBtn').removeClass('hidden');
	$('.voice-player').addClass('hidden');
	$('.voice-playerLoading').addClass('hidden');
	if( player ){
	    player.pause();
	    player.destroy();
	}
}
//播放完毕事件
function playerEnd(){
    if( voiceAutoNext == true ){
        onloadVoice(chapterNextId);
    }
}
//阅读设置初始化
switchTheme(getCookie('read_theme'));
switchFont(getCookie('read_font'));
switchFontSize(getCookie('read_font_size'));
switchWidth(getCookie('read_width'));
switchAutoSub(getCookie('read_auto_sub'));
switchReplay(getCookie('read_replay'));
switchVoiceSpeed(getCookie('voice_speed'));
switchVoiceAutoNext(getCookie('voice_auto_next'));
//弹幕设置初始化
switchDanmu(getCookie('danmu'));
switchDanmuColor(unescape(getCookie('danmu_color')));
switchDanmuPosition(getCookie('danmu_position'));
switchDanmuSize(getCookie('danmu_size'));
switchDanmuSpeed(getCookie('danmu_speed'));
switchDanmuOpacity(getCookie('danmu_opacity'));

//段落评论初始化
function subsetCount(){
	$.ajax({
		type:"POST",url:subsetCountUrl,dataType:"json",
		data:{'cid':novelId,'sid':chapterId,'module':module},
		success:function(data){
			if(data.code==200){
				for(i in data.data){
					//段落评论数量大于0的才显示
					if( data.data[i].count > 0 ){
						index = data.data[i].replay_segment_id;
						$('[data-segid="'+index+'"]').removeClass('hidden');
						$('[data-segid="'+index+'"]').html(data.data[i].count+'<i><cite></cite></i>');
					}
				}
			}
		}
	});
}
//加载评论
function onloadReplay(segId){
	segmentId = segId;
	if( replayOnload == false){
		replayOnload = true;
		$("<script>").attr({type: "text/javascript",src: "/files/js/replay/replay.js?ver=1"}).appendTo("head");
	}else{
		get_replaylist(1,'id','list');
	}
}
//初始化语音合成
function onloadApi(){
    $.ajax({
		type:"POST",url:ttsConfigUrl,dataType:"json",
		success:function(result){
			if(result.code==200){
			    var channelHtml = '';
			    for(o in result.data){
			        var act = '';
			        if( o == 0){
			            act = 'act';
			        }
			        channelHtml += '<span data-val="'+result.data[o].api_name+'" class="voice-channel '+act+'">'+result.data[o].api_ctitle+'</span>';
			        voicetChannelList[result.data[o].api_name] = result.data[o].list;
			    }
			    $('.voice-channel-box').append(channelHtml);
			    $('#j_readType').removeClass('hidden');
			    switchReadType(getCookie('read_type'));
			    switchVoiceChannel(getCookie('voice_channel'));
			}
		}
	});
}
//加载语音合成内容
function onloadVoice(cid){
    if( cid == 0 ){
        easyDialog.open({container : {content :'没有了'},autoClose : 2000}); 
    }else if( loading == false ){
        loading = true;
        $('.aplayer').prepend('<div class="playerMask"></div>');
        $.ajax({
    		type:"POST",dataType:"json",url:getChapterUrl,data:{'cid':cid,"format":1,"tts":1},
    		success:function(result){
    		    loading = false;
    		    $('.playerMask').remove();
    		    if(result.code=='200'){
    		        //书籍基本信息
    		        subsetId = result.data.chapter.chapter_id;
    		        chapterId = result.data.chapter.chapter_id;
    		        chapterName = result.data.chapter.chapter_name;
    		        //上下章节
    		        chapterPrveId = result.data.prev.chapter_id;
    		        chapterNextId = result.data.next.chapter_id;
    		        //价格
    		        allPrice = result.data.price.sell_all;
    		        monethPrice = result.data.price.sell_month;
    		        chapterPrice = result.data.price.sell_number;
    
    	            $('.voice-playerLoading').addClass('hidden');
    			    $('.voice-player').removeClass('hidden');
    			    var api = $('.voice-channel-box .act').data('val');
    			    var voicet = $('.voice-voicet-box').find('option:selected').val();
    			    var speed = $('.voice-speed-box .act').data('val');
    			    if( player ){
                	    player.destroy();
                	}
                    player = new APlayer({
                        element: document.getElementById('player'),
                        loop: false,autoplay: false,showlrc: false,
                        music: {
                            title: chapterName,author:novelAuthor,pic:novelCover,
                            url: ttsUrl+'&api='+api+'&voicet='+voicet+'&speed='+speed+'&tts='+result.data.tts
                        }
                    });
                    //播放完毕事件
                    player.on('ended', function () {
                        playerEnd();
                    });
                    player.play();
    		    }else{
    		       easyDialog.open({container : {content :result.msg},autoClose : 2000}); 
    		    }
    		},
    	});
    }
}
$(document).ready(function(){
	//start 初始化
	//选中当前章节
	volumeListInit();
	//执行滚动
	scrollFunction();
	//检查登录
	checkLogin();
	//段落评论数量
	subsetCount();
	//弹幕初始化
	danmuInit();
	//语音合成初始化
	onloadApi();
	//end 初始化

	//顶部导航
	$(".left-nav li,.sign-in").hover(function(){
		$(this).addClass("act");
	},function(){
		$(this).removeClass("act");
	});

	//自动订阅选择
	$(".vip-limit-wrap p").click(function(){
		if( $(this).find('span').hasClass('lbf-checkbox-checked')){
			autoSub = false;
			$(this).find('span').removeClass("lbf-checkbox-checked");
		}else{
			autoSub  = true;
			$(this).find('span').addClass("lbf-checkbox-checked");
		}
	});
	//订阅操作
	$(".subscribe-btn-wrap a").click(function(){
		easydialog_alert("确定要订阅吗？",'是否订阅',function(){
			$.ajax({
				type:"POST",url:subUrl,dataType:"json",
				data:{'nid':novelId,'cid':cid,'st':$(this).data('type'),'auto':autoSub?1:0},
				success:function(data){
					if(data.code==200){
						easydialog_autoclose(data.msg,'success',1000);
						setTimeout(function(){window.parent.location = '';},1000);
					}else{
						easydialog_autoclose(data.msg,'',2000);
					}
				}
			});
		});
	});


	//加入收藏
	$('#j_Coll').click(function(){
		if( isLogin == false ){
			return login();
		}else{
			coll(collUrl);
			if( $(this).hasClass('on') ){
				$(this).removeClass('on');
			}else{
				$(this).addClass('on');
			}
		}
	});
	//双击显示和关闭本章说
	$(".read-content p").dblclick(function(){
		//当前P为空也不进行操作
		var thisPContent = $(this).find('.content-wrap').html().replace(/&nbsp;+/g,"").trim();
		if( replay == false || thisPContent==''){
			return ;
		}
		$(".read-content p").removeClass("active");
		$(this).addClass("active");
		$('.section-comment-container').show();
		$('body').addClass("section-comment-open");
		onloadReplay($(this).find('.review-count').data('segid'));
	});
	//点击评论数量也显示
	$(".read-content p .review-count").click(function(){
		if( replay == false ){
			return ;
		}
		$(".read-content p").removeClass("active");
		$(this).parent().addClass("active");
		$('.section-comment-container').show();
		$('body').addClass("section-comment-open");
		onloadReplay($(this).data('segid'));
	});
	//关闭评论面板
	$(".section-comment-container .close-panel").click(function(){
		$('.section-comment-container').hide();
		$('body').removeClass("section-comment-open");
		$('.read-content p').removeClass("active");
	});
	//打赏的用户
	$(".j_userActive").hover(function(){
		$(this).find('.active-bubble').show();
	},function(){
		$('.j_userActive .active-bubble').hide();
	});


	//左侧操作菜单
	$("#j_navCatalogBtn,#j_navSettingBtn,#j_navDanmuBtn,#j_phoneRead").click(function(){
		$("#j_leftBarList dd").removeClass('act');
		$(this).addClass("act");
		$(".panel-wrap").hide();
		$('#'+$(this).data('panel')).show();
		//如果是分卷
		if( $(this).attr('id') == 'j_navCatalogBtn' ){
			$('#j_catalogListWrap').scrollTop($('.catalog-list-wrap .on').position().top);
		}
	});
	//关闭左侧操作菜单的开启面板
	$(".panel-wrap .close-panel").click(function(){
		$(".panel-wrap").hide();
		$('#'+$(this).parent().data("master")).removeClass('act');
	});
	//左侧目录面板内事件-开关分卷
	$('#j_catalogTabWrap h3').click(function(){
		var isClose = $(this).hasClass('cur')?true:false;
		//如果当前类是关闭状态就显示
		if( isClose == false ){
			$(this).addClass('cur');
			$(this).next().show();
		}else{
			$(this).removeClass('cur');
			$(this).next().hide();
		}
	});
	//左侧设置面板内事件-主题切换
	$('#j_themeList span').click(function(){
		switchTheme($(this).data('val'));
	});
	//左侧设置面板内事件-字体切换
	$('#j_fontFamily span').click(function(){
		switchFont($(this).data('val'));
	});
	//左侧设置面板内事件-字体大小切换
	$('#j_fontSize span').click(function(){
		var eventType = $(this).attr('class');
		if( eventType == 'prev' || eventType == 'next' )
		{
			if( eventType == 'prev' && contentFontSize<=contentFontSizeMin
				|| eventType == 'next' && contentFontSize>=contentFontSizeMax ){
				return;
			}
			if( eventType == 'prev' ){
				contentFontSize = contentFontSize-contentFontSizeSetp;
			}else{
				contentFontSize = contentFontSize+contentFontSizeSetp;
			}
			switchFontSize(contentFontSize);
		}
	});
	//左侧设置面板内事件-宽度变更
	$('#j_pageWidth span').click(function(){
		var eventType = $(this).attr('class');
		if( eventType == 'prev' || eventType == 'next' )
		{
			if( eventType == 'prev' && contentWidthIndex<=0
				|| eventType == 'next' && contentWidthIndex>=contentWidthList.length-1 ){
				return;
			}
			if( eventType == 'prev' ){
				contentWidthIndex--;
			}else{
				contentWidthIndex++;
			}
			switchWidth(contentWidthIndex);
		}
	});
	//左侧设置面板内事件-开关自动订阅
	$('#j_SubscribeAuto').click(function(){
		if( isLogin == false ){
			return login();
		}else{
			if($(this).attr('class')=='on'){
				switchAutoSub(false);
			}else{
				switchAutoSub(true);
			}
			autoSubRequest();
		}
	});
	//左侧设置面板内事件-开关本章说
	$('#j-sectionCommentBtn').click(function(){
		if($(this).attr('class')=='on'){
			switchReplay(false);
		}else{
			switchReplay(true);
		}
	});

	//左侧弹幕设置面板内事件-开关弹幕
	$('#danmuSwitch').click(function(){
		if($(this).attr('class')=='on'){
			switchDanmu(false);
		}else{
			switchDanmu(true);
		}
	});
	//左侧弹幕设置面板内事件-弹幕颜色切换
	$('#j_danmuColorList span').click(function(){
		switchDanmuColor($(this).data('val'));
	});
	//左侧弹幕设置面板内事件-弹幕区域切换
	$('#j_danmuPositionList span').click(function(){
		switchDanmuPosition($(this).data('val'));
	});
	//左侧弹幕设置面板内事件-弹幕字体大小切换
	$('#j_danmuSize span').click(function(){
		var eventType = $(this).attr('class');
		if( eventType == 'prev' || eventType == 'next' )
		{
			if( eventType == 'prev' && danmuSize<=danmuSizeMin
				|| eventType == 'next' && danmuSize>=danmuSizeMax ){
				return;
			}
			if( eventType == 'prev' ){
				danmuSize = danmuSize-danmuSizeSetp;
			}else{
				danmuSize = danmuSize+danmuSizeSetp;
			}
			switchDanmuSize(danmuSize);
		}
	});
	//左侧弹幕设置面板内事件-弹幕弹幕速度切换
	$('#j_danmuSpeed span').click(function(){
		var eventType = $(this).attr('class');
		if( eventType == 'prev' || eventType == 'next' )
		{
			if( eventType == 'prev' && danmuSpeedIndex<=0
				|| eventType == 'next' && danmuSpeedIndex>=danmuSpeedList.length-1 ){
				return;
			}
			if( eventType == 'prev' ){
				danmuSpeedIndex--;
			}else{
				danmuSpeedIndex++;
			}
			switchDanmuSpeed(danmuSpeedIndex);
		}
	});
	//左侧弹幕设置面板内事件-弹幕透明度切换
	$('#j_danmuOpacity span').click(function(){
		var eventType = $(this).attr('class');
		if( eventType == 'prev' || eventType == 'next' )
		{
			if( eventType == 'prev' && danmuOpacityIndex<=0
				|| eventType == 'next' && danmuOpacityIndex>=danmuOpacityList.length-1 ){
				return;
			}
			if( eventType == 'prev' ){
				danmuOpacityIndex--;
			}else{
				danmuOpacityIndex++;
			}
			switchDanmuOpacity(danmuOpacityIndex);
		}
	});

	//左侧菜单-加入书架
	$('#j_bookShelf').click(function(){
		if( isLogin == false ){
			return login();
		}else{
			//不存在书架才进行操作
			if( $(this).find('.in-shelf').length == 0 ){
				$(this).html('<a class="add-book in-shelf" href="javascript:">已在书架</a>');
				coll(shelfUrl);
			}
		}
	});
	
	//左侧菜单-阅读模式切换
	$('#j_readType a').click(function(){
	    switchReadType($(this).data('type'));
	});
	//左侧菜单-阅读模式-开始播放
	$('#j_playerInit').click(function(){
	    $('.aplayer').removeClass('hidden');
		$('.voice-playerBtn').addClass('hidden');
		$('.voice-playerLoading').removeClass('hidden');
		onloadVoice(chapterId);
	});
	//左侧菜单-阅读模式-上一章
	$('.vioce-prve').click(function(){
	    onloadVoice(chapterPrveId);
	});
	//左侧菜单-阅读模式-下一章
	$('.vioce-next').click(function(){
	    onloadVoice(chapterNextId);
	});
	//左侧菜单-阅读模式-语音渠道切换
    $('.voice-channel-box').on("click","span", function () {
		switchVoiceChannel($(this).data('val'));
		resetPlayer();
	});
	//左侧菜单-阅读模式-发音人切换
    $('.voice-voicet-box').on("change","select", function () {
		resetPlayer();
	});
	//左侧菜单-阅读模式-切换语速
	$('.voice-speed-box span').click(function(){
		switchVoiceSpeed($(this).data('val'));
		resetPlayer();
	});
	//左侧菜单-阅读模式-是否自动播放下一章
	$('#j-voiceAutoNextBtn').click(function(){
		if($(this).attr('class')=='on'){
			switchVoiceAutoNext(false);
		}else{
			switchVoiceAutoNext(true);
		}
	});

	//左侧工具-帮助指南
	$('#j_guideBtn').click(function(){
		$('#readHeader').css('z-index','99');
		$('.guide-box').css('display','block');
		//显示帮助面板
		$('#lbf_top').show();
		$('#lbf_bottom').show();
		$('.lbf-panel').show();
		//居中div
		var top = (document.body.clientHeight-$('.lbf-panel').height())/2+$(document).scrollTop()
		var left = ($(window).width() - $('.lbf-panel').width())/2+$(document).scrollLeft();
		$('.lbf-panel').offset({top:top,left:left});
		$('body').css({"overflow-y":"hidden"});
	});
	//关闭帮助面板
	$('#j_closeGuide,.lbf-icon-close').click(function(){
		$('#readHeader').css('z-index','104');
		$('.guide-box').hide();
		$('#lbf_top').hide();
		$('#lbf_bottom').hide();
		$('.lbf-panel').hide();
		$('body').css({"overflow-y":"auto"});
	});
	//关闭听书面板
	$('#j_closeVoice,.voice-panel-close').click(function(){
	    switchReadType('word');
	});


	//右侧工具-打赏
	$('#navReward').click(function(){
		if( isLogin == false ){
			return login();
		}else{
			return operate('reward');
		}
	});
	//右侧工具-投票推荐
	$('#navTicket').click(function(){
		if( isLogin == false ){
			return login();
		}else{
			return operate('rec');
		}
	});
	//右侧工具-反馈提交
	$("#message_sub").click(function(){
		var type = $("[name=msg_type]:checked").val();
		var content = $("#msg_content").val();
		if(  content== '' ) {
			$("#msg_content_tip").show();
		}else if( loading==false){
			$("#msg_content_tip").hide();
			loading = true;
			$.ajax({
				type:"POST",dataType:"json",url:messageUrl,
				data:{'content':"【"+novelName+"】"+type+content},
				success:function(data){
					loading = false;
					var msg =  data.msg;
					if( data.code == 200 ){
						msg = '恭喜您，反馈成功，请等待管理员查看并处理！';
					}
					easyDialog.open({container : {content :msg},autoClose : 2000});
				},
			});
		}
	});
	//右侧工具-快速隐藏显示弹幕
	$("#j_danmuFastSwitch").click(function(){
		if(  danmuShow == false ) {
			danmuShow = true;
			$(".barrage").show();
			$(this).css('opacity','1');
			$(this).find('span').html('隐藏弹幕');
		}else{
			danmuShow = false;
			$(".barrage").hide();
			$(this).css('opacity','0.5');
			$(this).find('span').html('显示弹幕');
		}
	});
	//右侧工具-返回顶部
	$('#j_goTop').click(function(){
		$("html,body").animate({ scrollTop:0},300);
	});

	//监听左右键
	$(document).keydown(function(event){
		if(event.keyCode == 37){
			window.location = prveUrl;
		}else if (event.keyCode == 39){
			window.location = nextUrl;
		}
	});
});