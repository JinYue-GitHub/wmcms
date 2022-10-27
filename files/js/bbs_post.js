/*
论坛发帖上传附件js
此为默认论坛发帖上传附件js，使用前必须使用默认的上传表单{上传附件表单}，此js调用方法为{上传附件js}
*/
/*上传附件按妞*/
$('#hide_button').live('change',function(){
	//禁用按钮
	$('#upload_button').val('上传中');
    $.ajaxFileUpload
    (
        {
            url: '/wmcms/action/index.php?action=upload.bbspost', //用于文件上传的服务器端请求地址
			type: 'post',
            secureuri: false, //是否需要安全协议，一般设置为false
            fileElementId: 'hide_button', //文件上传域的ID
            data:{ajax:'yes'},
            dataType: 'json', //返回值类型 一般设置为json
            success: function (data)  //服务器成功响应处理函数
            {
				//加入到文本内容中间去
            	if ( data.code == 200  )
            	{
            		var fid = data.data.file_id;
            		var fname = data.data.name;
            		$('#content').val($('#content').val()+"\r\n"+'[file:'+fid+'][/file]');

            		//暂时取消高级的上传附件设置
            		//$('#upfile').append('<li><a href="javascript:append('+fid+')">'+fname+'</a> 描述:<input type="text" class="file_alt" id="file_'+fid+'_alt" value="'+fname+'"> 售价:<input type="text" class="file_price" id="file_'+fid+'_price" value="0"><li>');
            	}
            	else
            	{
            		alert(data.msg);
            	}
				//还原按钮
				$('#hide_button').clone().val('');
				$('#upload_button').val('上传附件');
            },
            error: function(e) {
            	alert(e);
            } 
        }
    )
});


//暂时取消高级的上传附件设置
//点击文件插入到内容
/*function append( id )
{
	var alt = $("#file_"+id+"_alt").val();
	var p = $("#file_"+id+"_price").val();
	if ( !checkprice(p) )
	{
        alert("售价只能为正整数！");
	};
	$('#content').append('{file:id='+id+';alt='+alt+';p='+p+'}');
}

//价格文本框是否为数字
function checkprice( price )
{
	if ( price == '')
	{
		price = 0;
	}
	
    if ( parseInt(price) != price )
    {
    	return false;
    }
    else
    {
    	return true;
    }
}*/