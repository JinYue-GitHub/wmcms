$(document).ready(function(){
	//作者其他作品切换
	$(".bx-next").click(function(){
		slider($(".bx-default-pager .active").parent().index(),'next');
	});
	$(".bx-prev").click(function(){
		slider($(".bx-default-pager .active").parent().index(),'prev');
	});

	
	//反馈提交
	$("#message_sub").click(function(){
		var type = $("[name=type]:checked").val();
		var novel = $("#message_novel").html();
		var content = $("#content").val();
		if(  content== '' ){
			$("#content").addClass("ac-tip-border");
			$("#content_tip").addClass("ac-tip-error");
		}else{
			content = "【"+novel+"】"+type+content;
			$.ajax({
				type:"POST",
				url:"/wmcms/action/index.php?action=message.add&ajax=yes",
				data:{'content':content},
				dataType:"json",
				success:function(data)
				{
					var msg =  data.msg;
					if( data.code == 200 ){
						msg = '恭喜您，反馈成功，请等待管理员查看并处理！';
					}
					easyDialog.open({container : {content :msg},autoClose : 2000});
				},
				error:function (e)//服务器响应失败处理函数
	            {
					console.log(e);
	                alert(e);
	            },
				async:true,
			});
		}
	});
	
	//内容框点击的时候
	$("#content").click(function(){
		$("#content").removeClass("ac-tip-border");
		$("#content_tip").removeClass("ac-tip-error");
	});
	

	//收藏
	$("#div_collection_btn .coll").click(function(){
		$.ajax({
			type:"POST",
			url:$(this).data('url')+'&ajax=yes',
			dataType:"json",
			success:function(data)
			{
				easyDialog.open({container : {content :data.msg},autoClose : 2000});
			},
			error:function (e)//服务器响应失败处理函数
	        {
				console.log(e);
	            alert(e);
	        },
			async:true,
		});
	});
});


function openlogin(){
	easydialog_url('user.login','登录/注册','',320,420);
}

//作者的其他作品
function slider(index,type){
	//多少个图片
	var slider = $(".bx-default-pager a").length-1;
	$(".works-slider-list li").css("display","none");
	$(".bx-pager-item a").removeClass("active");
	
	if( type=='next' ){
		if(index>=slider){
			index=0;
		}else{
			index=index+1;
		}
	}else{
		if(index<=0){
			index=slider;
		}else{
			index=index-1;
		}
	}
	$(".works-slider-list li").eq(index).css("display","block");
	$(".bx-default-pager a").eq(index).addClass("active");
}


//顶踩
function wmcms_dingcai(type , module , cid){
	if( type=='ding' ){
		type='ding';
	}else{
		type='cai';
	}
	$.ajax({
		type:"POST",
		url:"/wmcms/action/index.php?action=dingcai."+type+"&ajax=yes",
		data:{'module':module,'cid':cid},
		dataType:"json",
		success:function(data)
		{
			easyDialog.open({container : {content :data.msg},autoClose : 2000});
		},
		error:function (e)//服务器响应失败处理函数
        {
			console.log(e);
            alert(e);
        },
		async:true,
	});
}


function wmcms_operate(type){
	if( typeof(type) == 'undefined' || typeof(type) == '' ){
		type='operate';
	}
	var data=[];
	data.nid = nid;
	data.tab = type;
	easydialog_url('novel.operate','互动操作','',813,407,data);
}