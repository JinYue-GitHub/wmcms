/*
用户上传头像js
此为默认上传头像js，使用前必须使用默认的上传表单{上传头像表单}，此js调用方法为{上传头像js}
*/

/*点击wmcms标签的img标签更换head元素的头像*/
$("wmcms img").click(function(){
	$("#head").attr("src",$(this).attr("src"));
	var id = $(this).attr("data-id");
	if( id ){
		$.ajax({
			type:"POST",
			url:"/wmcms/action/index.php?action=user.savehead",
			data:{'id':id,'ajax':'yes'},
			dataType:"json",
			success:function(data){
				alert(data.msg);
			},
			//服务器响应失败处理函数
			error:function (e){
                alert(e.responseText);
            },
			async:true,
		});
	}
});

/*保存头像按钮*/
$("#save_button").click(function(){
    alert('手动保存头像已关闭');
    return false;
});

/*上传头像按妞*/
$('#hide_button').live('change',function(){
	//禁用按钮
	$('#save_button').attr('disabled',"true");
	$('#upload_button').val('上传中');
    $.ajaxFileUpload
    ({
        url: '/wmcms/action/index.php?action=upload.userhead', //用于文件上传的服务器端请求地址
		type: 'post',
        secureuri: false, //是否需要安全协议，一般设置为false
        fileElementId: 'hide_button', //文件上传域的ID
        data:{ajax:'yes',module: 'user'},
        dataType: 'json', //返回值类型 一般设置为json
        success: function (data){ //服务器成功响应处理函数
			//还原按钮
			$('#save_button').removeAttr('disabled');
			$('#hide_button').clone().val('');
			$('#upload_button').val('上传');
        	if( data.code == 500 ){
        		alert(data.msg);
        	}else{
				//设置头像url
	        	$("#head").attr("src", data.data.file);
        	}
        },
        error: function(e) {
        	alert(e);
        } 
     })
});