(function() {
    function isMoblie() {
        if(!navigator.userAgent.match(/(iPhone|iPod|Android|ios)/i)){
            $("body").addClass("app_NoMoblie");
        }else{
            $("body").removeClass("app_NoMoblie");
        }
    }

    $('body').on('click','.app_meskSection',function () {
        $('.app_header_second').hide();
        $('.app_meskSection').hide();
        $('#header').removeClass('app_headerFixed2');
        $('.moreIco').removeClass('closeIco');
    });

    $('.app_header_back').click(function () {
        window.history.go(-1);
    });

    /** 发送验证码开始 **/
    var timer = null;
	var sendBtn = true;
	var time = 60;
	var num = time;
	var thisObj = null;
    $('body').on('click','#get_smscode,.code_send_btn',function () {
	    var id = $(this).data('id');
	    var type = $(this).data('type');
        var email = '';
        var tel = '';
        if( sendBtn==false ){
            return false;
        }else{
            thisObj = $(this);
            if( id == 'user_getpsw' ){
                var receive = $('#receive').val();
                if( isEmail(receive) ){
                    type = 'email';
                    email = receive;
                }else if( isPhone(receive) ){
                    type = 'tel';
                    tel = receive;
                }else{
				    layer.open({content:'对不起，邮箱或手机号格式错误！',time:1});
                }
            }else{
                email = $('[name=email]').val();
                tel = $('[name=tel]').val();
            }
            
            if( type=='email' && !isEmail(email) ){
				layer.open({content:'对不起，邮箱格式错误！',time:1});
            }else if( type=='tel' && !isPhone(tel) ){
				layer.open({content:'对不起，手机号格式错误！',time:1});
            }else{
                //发送验证码类型id
                sendBtn = false;
                thisObj.html(time+'S');
                thisObj.css('background-color','#979696');
                timer = setInterval(timing, 1000); //一秒执行一次
                $.ajax({
            		type:"POST",url:"/wmcms/ajax/index.php?action=sys.sendcode",dataType:"json",
            		data:{"id":id,"email":email,"tel":tel},
            		success:function(data){
            		    if( data.code == '200' ){
							layer.open({content:'发送成功！',time:1});
            		    }else{
							layer.open({content: data.msg,time: 3});
            		    }
            		},
            		async:true,
            	});
    	    }
        }
	});
    function timing(){
        num--;
        if(num > 0){
            thisObj.html(num+'S');
        }else{
            clearInterval(timer); //清除js定时器
            thisObj.html('发送验证码');
            thisObj.css('background-color','#ea4136');
        	timer = null;
        	sendBtn = true;
            num = time; //重置时间
        }
    }
    /** 发送验证码结束 **/
    
    $('.moreIco').click(function () {
        if($(this).hasClass('closeIco')){
            $('#header').removeClass('app_headerFixed2');
            $(this).removeClass('closeIco');
            $('.app_header_second').hide();
            $('.app_meskSection').hide();
        }else{
            $('#header').addClass('app_headerFixed2');
            $(this).addClass('closeIco');
            $('.app_header_second').show();
            $('.app_meskSection').show();
        }
    });

    /** 点击完成按钮 */
    $('#success').click(function () {
        $('#administration').show();
        $(this).hide();
        $('#list .rightJustTime').show();
        $('#list .deleteBlock').hide();
    });

    /** 点击管理 */
    $('#administration').click(function () {
        $(this).hide();
        $('#success').show();
        $('#list .rightJustTime').hide();
        $('#list .deleteBlock').show();

    });
		
		/*上传头像按妞*/
		$('#hide_button').on('change',function(){
		    $.ajaxFileUpload({
		        url: '/wmcms/action/index.php?action=upload.userhead', //用于文件上传的服务器端请求地址
						type: 'post',
		        secureuri: false, //是否需要安全协议，一般设置为false
		        fileElementId: 'hide_button', //文件上传域的ID
		        data:{ajax:'yes',module: 'user'},
		        dataType: 'json', //返回值类型 一般设置为json
		        success: function (data){ //服务器成功响应处理函数
		        	if( data.code == 500 ){
		        		alert(data.msg);
		        	}else{
			            $("#head").attr("src", data.data.file);
		        	}
		        },
		        error: function(e) {
		        	alert(e);
		        } 
		     })
		});

    $(window).resize(function () {
        isMoblie();
    });

    newcode=function(){
        var verifyimg = $("#imgCode").attr("src");
        if (verifyimg.indexOf('?') > 0) {
            $("#imgCode").attr("src", verifyimg + '&random=' + Math.random());
        } else {
            $("#imgCode").attr("src", verifyimg.replace(/\?.*$/, '') + '?' + Math.random());
        }
        $("#code").val('');
    }

    $("#imgCode").click(function() {
        newcode();
    });

})();