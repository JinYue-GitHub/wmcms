$(function(){

	var Cookie = {
        set:function(name, value, expires, path, domain){
            if(expires){
                expires = new Date(+new Date() + expires);
            }
            var tempcookie = name + '=' + escape(value) +
                ((expires) ? '; expires=' + expires.toGMTString() : '') +
                ((path) ? '; path=' + path : '') +
                ((domain) ? '; domain=' + domain : '');
            if(tempcookie.length < 4096) {
                document.cookie = tempcookie;
            }
        }
    };

	function resi() {
		var fontSize = Math.min(window.innerWidth, screen.width, document.body.offsetWidth) / 750 * 100 + "px";
		Cookie.set('fontsize',fontSize, 86400000 * 365, '/');
		document.documentElement.style.fontSize = fontSize;
	}
	window.addEventListener("DOMContentLoaded", function() {
		resi();
	});
	window.addEventListener("resize", resi);
	
	if($('#swiper').length>0){
		TouchSlide({ 
			slideCell:"#swiper",
			titCell:".van-swipe__indicators", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
			mainCell:".swiper-wrapper",
			effect:"leftLoop", 
			autoPlay:true,//自动播放
			autoPage:true, //自动分页
			switchLoad:"_src" //切换加载，真实图片路径为"_src" 
		});
	}
	digg=function(id,digg,type){
		var DingCaiurl = '';
		if(digg=='ding'){
			DingCaiurl = dingUrl
		}else{
			DingCaiurl = caiUrl
		}
		$.get(DingCaiurl+'&ajax=yes',{},function(data){
			data = JSON.parse(data)
			layer.open({content: data.msg,skin: 'msg',time: 2});
		});
	};
	
	tuijian=function(url){
		$.get(url+'&ajax=yes',{},function(data){
			data = JSON.parse(data)
			layer.open({content: data.msg,skin: 'msg',time: 2});
		});
	};
	
	addshujia=function(url){
		$.get(url+'&ajax=yes',{},function(data){
			data = JSON.parse(data)
			layer.open({content: data.msg,skin: 'msg',time: 2});
		});
	};
	$('.x-library__category--more').click(function(event) {
		if($('.x-category__box:gt(2)').is(':hidden')){
			$('.x-category__box:gt(2)').slideDown();
			$(this).text('隐藏更多筛选项');
		}else{
			$('.x-category__box:gt(2)').slideUp();
			$(this).text('展开更多筛选项');
		}
	});

	if($('.chapter-list').length>0){
		$('.chapter-list').css({right: -$(window).innerWidth()+"px"});
		$('.x-page__content .van-pull-refresh__track').height($(window).innerHeight()-$('.x-page__header').outerHeight(true));
	}

	var chapter_scrolltop;
	$('.x-catalog__more').click(function(event) {
		document.body.scrollTop=document.documentElement.scrollTop=0;
		chapter_scrolltop=$(document).scrollTop()
		$('.chapter-list').css({top:chapter_scrolltop+"px"});
		$('.chapter-list').show();
		$('.chapter-list').animate({right:"0"},function(){
			// $('#app').hide();
			$('.chapter-list').css({top:0});
		});
		$('.van-pull-refresh__track').css('height','100%');
	});

	$('#chapter_back').click(function(event) {
		$('#app').show();
		$(document).scrollTop(chapter_scrolltop);
		$('.chapter-list').css({top:chapter_scrolltop+"px"});
		$('.chapter-list').animate({right:-$(window).innerWidth()+"px"},function(){
			$('.chapter-list').hide();
		});
	});

	if($('.x-rank__list').length>0){
		$('.x-rank__list').css({height:"calc("+$(window).innerHeight()+"px - 1.2rem)"});
	}

	$('#souya').click(function(event) {
		$('.x-city__search').submit();
	});

	$('.search-img').click(function(event) {
		$('.search-box form').submit();
	});
});