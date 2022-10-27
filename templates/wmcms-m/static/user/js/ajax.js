(function() {
//ajax get请求
    $(document).on('click','[ajax-get]',function(){
        var target;
        var that = this;
        get_ajax=function(){
            if ( (target = $(that).attr('href')) || (target = $(that).attr('url'))) {
                var index = layer.open({type: 2,content: '加载中'});
                $.get(target).success(function(data){
                    layer.close(index);
                    if (data.code == 1) {
                        layer.open({content: data.msg,skin: 'msg',time: 2,end:function(){
                            var callback = $(that).attr('callback');
                            if(callback){
                                eval(callback);
                            }else{
                               location.href = data.url; 
                            }
                        }});
                    } else {
                        layer.open({content: data.msg,time: 2});
                    }
                });
            }
        }
        if ( $(this).hasClass('confirm') ) {
            layer.open({content: '您确认要执行该操作吗?', 
              btn: ['确定','取消'],
              yes: function(index){
                get_ajax();
            }});
        }else{
            get_ajax();
        }
        return false;
    });
    $(document).on('click','[ajax-post]',function(){
        var target, query, form;
        var target_form = $(this).attr('ajax-post');
        var that = this;
        if (($(this).attr('type') == 'submit') || (target = $(this).attr('href')) || (target = $(this).attr('url'))) {
            form = $('.' + target_form);
            if (form.get(0).nodeName == 'FORM') {
                if(!target){
                   target = form.get(0).action; 
                }
                query = form.serialize();
            } else {
                query = form.find('input,select,textarea').serialize();
            }
            var index = layer.open({type: 2,content: '加载中'});
            $(that).attr('autocomplete', 'off').prop('disabled', true);
            $.post(target, query).success(function(data) {
								data = JSON.parse(data);
                layer.close(index);
                $(that).prop('disabled', false);
                if (data.code == 200) {
                    layer.open({content: data.msg,skin: 'msg',time: 2,end:function(){
                        var callback = $(that).attr('callback');
                        if( !callback && data.data.url){
                            location.href = data.url;
                        }else if( callback ){
                            callback = callback+"('"+JSON.stringify(data)+"')";
                            eval(callback);
                        }
                    }});
                } else {
                    layer.open({content: data.msg,skin: 'msg',time: 2,end:function(){
                        if(data.url){
                            location.href = data.url;
                        }else{
                            var errorback = $(that).attr('errorback');
                            if(errorback){
                                eval(errorback);
                            }
                        }
                    }});
                }
            });
        }
        return false;
    });
		
	$(document).on('click','[ajax-reg]',function(){
	    var target, query, form;
	    var target_form = $(this).attr('ajax-reg');
	    var that = this;
	    if (($(this).attr('type') == 'submit') || (target = $(this).attr('href')) || (target = $(this).attr('url'))) {
	        form = $('.' + target_form);
	        if (form.get(0).nodeName == 'FORM') {
	            if(!target){
	               target = form.get(0).action; 
	            }
	            query = form.serialize();
	        } else {
	            query = form.find('input,select,textarea').serialize();
	        }
	        var index = layer.open({type: 2,content: '加载中'});
	        $(that).attr('autocomplete', 'off').prop('disabled', true);
	        $.post(target, query).success(function(data) {
							data = JSON.parse(data);
	            layer.close(index);
	            $(that).prop('disabled', false);
	            if (data.code != 200) {
	                layer.open({content: data.msg,skin: 'msg',time: 2,end:function(){
	                    var callback = $(that).attr('callback');
	                    
	                }});
	            } else {
	                layer.open({content: data.msg.info,skin: 'msg',time: 2,end:function(){
	                    if(data.msg.gourl){
	                        location.href = data.msg.gourl;
	                    }else{
	                        var errorback = $(that).attr('errorback');
	                        if(errorback){
	                            eval(errorback);
	                        }
	                    }
	                }});
	            }
	        });
	    }
	    return false;
	});
	
})();