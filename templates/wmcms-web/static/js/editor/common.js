//显示遮罩
function ShowDialogMask(){
    $('.ui-dialog-container').css('display','block');
    $('.ui-dialog').hide();
    $('.ui-dialog-title').html('');
    $('.ui-dialog-body').html('');
    $('.ui-dialog-footer').hide();
}
//显示弹窗内容
function ShowDialog(title,subTitle,body,width,btn){
    if(!width){
        width='auto';
    }else if(width!=isNaN(width)){
        width=width;
    }else{
        width=width+'px';
    }
    
    $('#pass').hide();
    $('#refuse').hide();
    $('#sure').hide();
    $('#close').hide();
    if( !btn ){
        $('#sure').show();
        $('#close').show();
    }else{
        for(i in btn){
            $('#'+btn[i]).show();
        }
    }
    $('.ui-dialog-container').css('display','block');
    $('.ui-dialog').css({'display':'inline-block','width':width});
    $('.ui-dialog-title').html(title+'<span class="sub-title">'+subTitle+'</span>');
    $('.ui-dialog-body').html(body);
    $('.ui-dialog-footer').show();
}
//关闭弹窗
function HideDialog(){
    ShowDialogMask();
    $('.ui-dialog-container').css('display','none');
}
//弹窗加载中显示
function DialogLoadingShow(){
    $('.dialog-loading').css('height',$(document).height()+'px');
    $('.dialog-loading').show();
    $('.ui-dialog-container').css('overflow-y','hidden');
}
//弹窗加载中以藏
function DialogLoadingHide(){
    $('.dialog-loading').hide();
    $('.ui-dialog-container').css('overflow-y','unset');
}

$(document).ready(function(){
	$("#close,#sure,.icon-close").click(function(){
	    HideDialog();
	});
});