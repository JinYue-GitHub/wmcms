document.write('<script src="/files/js/wangeditor/scripts/wangeditor.min.js"></script>');
/**
 * 插入附件到编辑框
 * id，附件的id
 * url，文件的路径
 * alt，文件的描述
 */
function attachment_insert(id){
	var imgUrl = $('#attachment_'+id).data('url');
	return editor.uploadImg.insertLinkImg(imgUrl);
}
//删除附件，id为附件id
function attachment_delete(id){
	$.ajax({
		url: "/wmcms/action/index.php?ajax=yes&action=upload.del&module=bbs&id="+id,    //请求的url地址
		dataType: "json",   //返回格式为json
		type: "GET",   //请求方式
		success: function(data){
			console.log(data);
			if(data.code == 200){
				$('#attachment_'+id).hide();
			}else{
				alert(data.msg);
			}
		},
		error: function(data) {
			alert(data.responseJSON.msg);
		}
	});
}

