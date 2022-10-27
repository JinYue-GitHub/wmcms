AC = {
    namespace: function() {
        var a = arguments, o = null, i, j, d, rt;
        for (i = 0; i < a.length; ++i) {
            d = a[i].split(".");
            rt = d[0];
            eval('if (typeof ' + rt + ' == "undefined") {' + rt + ' = {};} o = ' + rt + ';');
            for (j = 1; j < d.length; ++j) {
                o[d[j]] = o[d[j]] || {};
                o = o[d[j]];
            }
        }
    }
};

AC.namespace("AC", "AC.Page", "AC.UI", "AC.UTILS");

AC.namespace("AC.UI.Tab");  
AC.UI.Tab = {
    /*标签切换*/
    change: function(tabNav, activeClass, tabCon) {
        $(tabCon).each(function(){
            $(this).children().eq(0).show();
        });

        $(tabNav).each(function(){
            $(this).children().eq(0).addClass(activeClass);
        });

        $(tabNav).children().click(function(){
            $(this).addClass(activeClass).siblings().removeClass(activeClass);

            var index = $(tabNav).children().index(this);

            $(tabCon).children().eq(index).show().siblings().hide();
        });
    }
};


AC.namespace("AC.Page");
AC.Page = {
    //加载图片
    loadImage: function(container, force) {
        if (undefined == container || container == '') {
            $('img.lazy').lazyload({threshold : 100, failurelimit : 10});
        } else {
            if (undefined != force && true == force) {
                $(container).find('img.lazy').each(function() {
                    var src = $(this).attr('src');
                    var original = $(this).attr('data-original');

                    if (src != "" && original != "" && original != src) {
                        $(this).attr('src', original);
                    }
                });
            } else {
                $(container).find('img.lazy').lazyload({threshold : 100, failurelimit : 10});
            }
        }
    },
    registerLoadImage:function(btn,container,eventName) {
        $(btn).bind(eventName,'',function(){
            AC.Page.loadImage(container,true);
        });
    },

    //绑定事件
    coreBind: function() {
        //顶部书架状态
        AC.UI.Tab.change("#mod-log-handle", "active", "#mod-log-main");
        AC.UI.Tab.change("#mod-log-his-handle", "active", "#mod-log-his-panel");

        //绑定hover事件
        AC.Page.Core.bindEvent();

        //绑定dropbox事件
        AC.Page.Core.setPanelPagination("",3);
    },
    //顶导个人中心数据的更新
    LoadUserInfo:function() {
        if (AC.Page.checkSkey() && AC.Page.getUserCookie()) {
            AC.Page.showUserImage();
            return;
        }
    },

    //展示用户头像等信息
    showUserImage: function() {
        var data = {};
        data.avatar = AC.Page.Core.avatar;
        data.hasLogin = AC.Page.Core.hasLogin;
        data.nick = AC.Page.Core.nick;
        data.uin = AC.Page.Core.uin;
        data.token = AC.Page.Core.token;

        //个人中心及书架图标
        // $('.mod-top-userlog').html(template.render('script-mod-top-userlog', data));
    },

    //登出
    logout: function() {
        pt_logout.logout();
        this.cleanCookie();
        AC.Page.cleanUserCookie();
        setTimeout(function(){location.reload();},500);
    },

    //检测支付skey
    checkSkey: function() {
        var c = AC.Page.cookie('skey');

        if (c != null && c != '') {
            return true;
        }

        return false;
    },

    //缓存的头像和昵称等信息
    getUserCookie: function() {
        var c = AC.Page.cookie('pc_userinfo_cookie');

        if (c != null && c != '') {
            var object = JSON.parse(c);

            if (typeof(object) != 'object') {
                return false;
            };

            AC.Page.Core.uin = object.uin;
            AC.Page.Core.uinCrypt = object.uinCrypt;
            AC.Page.Core.nick = object.nick;
            AC.Page.Core.avatar = object.avatar;
            AC.Page.Core.hasLogin = object.hasLogin;
            AC.Page.Core.token = object.token;
        };
        
        return AC.Page.Core.uin > 0;
    },

    //缓存用户头像、昵称等信息，缓存1小时
    setUserCookie: function() {
        var object = {'uin':AC.Page.Core.uin,'uinCrypt':AC.Page.Core.uinCrypt,'nick':AC.Page.Core.nick,'avatar':AC.Page.Core.avatar,'hasLogin':AC.Page.Core.hasLogin,'token':AC.Page.Core.token};
        var date = new Date();
        date.setTime(date.getTime() + 3600000); //1小时
        AC.Page.cookie('pc_userinfo_cookie', JSON.stringify(object), {path: '/', expires: date});
    },

    //清除缓存的用户昵称、头像等信息
    cleanUserCookie: function() {
        AC.Page.cookie('pc_userinfo_cookie', '', {path: '/', "expires":1});
    },

    cleanCookie: function() {
        var i = {};
        i.domain = "ac.qq.com";
        i.path = "/";
        this.cookie("p_skey", "", i);
        this.cookie("p_uin", "", i);
    },

    //获取和设置cookie
    cookie: function(name, value, options) {
        if (typeof value != 'undefined') {
            options = options || {};
            if (value === null) {
                value = '';
                options.expires = -1;
            }

            var expires = '';
            if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
                var date;
                if (typeof options.expires == 'number') { 
                    date = new Date();
                    date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
                } else { 
                    date = options.expires; 
                }

                expires = '; expires=' + date.toUTCString();
            }

            var path = options.path ? '; path=' + options.path : ''; 
            var domain = options.domain ? '; domain=' + options.domain : ''; 
            var secure = options.secure ? '; secure' : ''; 

            document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join(''); 
        } else {
            var cookieValue = null;
            if (document.cookie && document.cookie != '') {
                var cookies = document.cookie.split(';');
                for (var i = 0; i < cookies.length; i++) {
                    var cookie = jQuery.trim(cookies[i]);

                    if (cookie.substring(0, name.length + 1) == (name + '=')) {
                        cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                        break;
                    }
                }
            }

            return cookieValue;
        }
    },
    ajaxChangePage: function(page, url) {
        $.ajax({
            type: 'post',
            url: url,
            data: {
                'objid':$('#objid').val(),
                'page':page
            },
            beforeSend: function() {
                $('#commentList').html('<span class="loading">加载中，请稍后...</span>');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $('#commentList').html('加载失败，请<a href="javascript:void(0);" onclick="">重试</a>');
            },
            success: function(data) {
                if (data.substr(0, 1)+'' == '1') {
                    $('#commentList').html(data.substr(1));
                    bind();
                    pagination(url);
                } else if (data.substr(0, 1)+'' == '2') {
                    $('#commentList').html(data.substr(1));
                } else {
                    art.dialog({
                        content: data.substr(1),
                        time: 2000
                    });
                    $('#commentList').html('');
                }
            }
        });

        return false;
    }

};

//响应登录框关闭
function ptlogin2_onClose() {
    $.unblockUI();
}

AC.namespace("AC.Page.Core");
AC.Page.Core = {
    nick:'',
    avatar:'',
    uin:'',

    init: function() {
        AC.Page.Core.Timetstamp = new Date().getTime();

        AC.Page.loadImage();
         
        //排行榜
        this.bindForRank();

        // 返回顶部工具栏
        AC.Page.Core.sideTool();

        // 搜索相关事件&样式
        this.searchStyle();

        //用户信息相关
        AC.Page.LoadUserInfo();

        //核心绑定
        AC.Page.coreBind();

        // setTimeout(function() {
        //     //取个人消息数字
        //     AC.Page.Core.loadMessageNotify(false);

        //     //取收藏更新数
        //     AC.Page.Core.loadFavoritUpdate();
           
        // }, 1500);
        this.channel();
    },
    channel: function(){
        var channel = AC.Page.cookie("ac_channel");
        
        if (channel == null) {
            var url = window.location.search;
            if (url != "") {
                var index = url.indexOf("ADTAG");
                if (index != -1) {
                    AC.Page.cookie("ac_channel", $.trim(url.substring(index+14)));
                }
            }
        }
    },
    bindForRank: function() {
        //排行榜通用操作
        $('.mod_rank_wr').each(function(){
            $(this).find('.mod_rank_list').eq(0).css('display','block');
        })
        $('.mod_rank_tab li').click(function() {
            var css = 'current';
            if (!$(this).hasClass(css)) {
                var tmp = this.id.split('_');
                if (tmp.length > 0) {
                    var flag = tmp[0];
                    var id = tmp[1];
                    
                    for (var i = 1; i <= 4; i++) {
                        $('#' + flag + '_' + i).removeClass(css);
                        $('#d_' + flag + '_' + i).hide();
                    }
                    
                    $('#d_' + flag + '_' + id).show();
                    $(this).addClass(css);
                }
            }
        });
    },

    bindEvent: function() {
        //登入登出相关操作
         $('#a_login').live('click', function () {
             AC.Page.showLogin(location.pathname);
         });
         $('#bookshelf_login').live('click', function () {
             AC.Page.showLogin(location.pathname);
         });
         $('#a_logout').live('click', function () {
             $('#top-user-info').hide();
             AC.Page.logout();
         });
         
        $('#igonAllComic').live('click', function () {
            AC.Page.Core.igonAllComic();
         });
        
        //头部个人信息和消息
        $(".mod-top-user").hoverDelay({
            outDuring: 200,
            hoverEvent: function() {
                $obj = $(this);
                var timestamp = new Date().getTime();
                if ( AC.Page.Core.firstLoad != 1 || AC.Page.Core.Timetstamp + 10000 < timestamp ) {
                   AC.Page.Core.Timetstamp = timestamp;
                   $.get('/Ajax/getUserInfo',{stamp: Math.random()}, function(data){
                        AC.Page.Core.firstLoad = 1;
                        if (data.status == 2) {
                            $obj.attr('data', timestamp);
                            data = data.result;
                            $('#mod-dropdown-info').html(template.render('script-user-info-dropdown', data));
                            $('#mod-top-user').find('img').first().attr('src', data.avatar);
                            $('.mod-user-msg-wr').attr('style', 'display:block');

                            AC.Page.Core.avatar = data.avatar;
                            AC.Page.Core.hasLogin = "1";
                            AC.Page.Core.nick = data.shortNick;
                            AC.Page.Core.uin = data.uin;
                            AC.Page.Core.uinCrypt = data.uinCrypt;
                            AC.Page.Core.token = data.token;

                            AC.Page.setUserCookie();
                        }
                        else if(data.status == -99){
                            $('#mod-dropdown-info').html('');
                            $('#mod-top-user').find('img').first().attr('src', 'http://ac.gtimg.com/media/images/top_face_no_bg.jpg');
                            $('.mod-user-msg-wr').attr('style', 'display:none');

                            AC.Page.cleanUserCookie();
                        }
                        else if(data.status == -97){
                            $('#top-user-info').hide();
                            AC.Page.logout();
                        }
                    }, 'json');
                }
            },
            outEvent: function() {
            }
        }); 
     
        //读取书架的"收藏更新"和阅读历史数据
        $('.mod-top-log').hoverDelay({
            outDuring: 200,
            hoverEvent: function() {
                var timestamp = new Date().getTime();
                if (AC.Page.Core.Timetstamp + 10000 < timestamp)
                {
                    AC.Page.Core.loadFavoritUpdate();
                    AC.Page.Core.Timetstamp = timestamp;
                }
                
            },
            outEvent: function() {}
        });
        
        //给书架中的"阅读历史"绑定单击事件
        $("#mod-top-log-history").die("click").live("click",function(){
            var timestamp = new Date().getTime();
            if (AC.Page.Core.Timetstamp + 10000 < timestamp)
            {
                AC.Page.Core.loadReadHistory();
                AC.Page.Core.Timetstamp = timestamp;
            }
        });

        this.directH5btn();
    },
    directH5btn:function()
    {
         var reg = new RegExp("(^|&)" + "platform" + "=([^&]*)(&|$)");
         var r = window.location.search.substr(1).match(reg);
         if (r != null)  var platurl = unescape(r[2]); 
         else var platurl = '0';
         var platcookie = AC.Page.cookie('platform');
         if(platcookie == 1 || platurl == '1')
         {
            AC.Page.cookie('platform', 1,{"expires":0});
            var btn = $(".mod-side-touch");
            if(btn)
            {
                var locationurl = location.href;
                var newurl = locationurl.replace("ac.qq.com","m.ac.qq.com");
                btn.find("a").attr("href",newurl);
                btn.show();
            }
         }
    },
    igonAllComic: function(){
        $.post('/Ajax/ignoreUpdate', {'tokenKey' : AC.Page.Core.token}, function(data) {
            if (data.status == 1) {
                $("#mod-log-fav-mh").html('<div class="mod-userlog-tips"><h3>您收藏的漫画暂无更新</h3><p class="ui-mb40">sorry,作者正在努力赶稿中，明天再来看看吧！</p></div>');
                $("#mod-top-log").find(".mod-user-msg-num").text("0").hide();
            }
            else if(data.status == -98 || data.status == -99) {
                AC.Page.showLogin(location.pathname);
            }
        }, 'json');
    },

    doSearch: function(formId) {
        var form = $('#'+formId),
        input = form.find('.mod-search-input'),
        label = form.find('.mod-search-label'),
        submit = form.find('.mod-search-submit'),
        search = {
            init: function(){
                if (input.val() !== ''){
                    label.hide();
                } else {
                    label.show();
                }
            },
            bindEvent: function() {
                var self = this;
                input.focusin(function() {
                    var val = $(this).val();
                     $.trim(val) === '' && label.hide();
                }).focusout(function() {
                    var val = $(this).val();
                    self.isNull(val) && label.hide() || label.show();
                });
                
                // 提交事件
                submit.click(function(){
                    var val = input.val();
                    if (!self.isNull(val)){
                        return false;
                    } else {
                        location.replace('/Comic/searchList/search/' + val, false);
                    }
                });
            },
            isNull: function(val) {
                return $.trim(val) !== '' && $.trim(val) !== null;
            }
        };
        search.init();
        search.bindEvent();
    },

    searchStyle: function(){
        // 搜索列表样式切换
        $("#ui-search-list li").first().addClass("active");
        $("#ui-search-list li").last().addClass("last");
        $("#ui-search-list li").mouseover(function(){
           $(this).addClass("active").siblings().removeClass("active");
        });

        $("#top-search-input").live("focus",function(){
           if($(this).val()==""){
               $(this).parents(".mod-top-search").find(".ui-search-list-wrap").show();
           }
        });

        $(document).bind("click",function(e){
          var target  = $(e.target);
          if(target.closest(".mod-top-search-wr").length == 0){
               $(".ui-search-list-wrap").hide();
          }
        });

        $("#top-search-input").live("keyup",function(){
          if($(this).val()!=""){
              $(this).parents(".mod-top-search").find(".ui-search-list-wrap").hide();
          }
        });

        this.doSearch('top-search');
        $("#top-search-input").autocomplete("/Comic/search", {
            delay:100,
            minChars:1,
            width: 384,
            matchSubset:1,
            matchContains:1,
            autoFill:false,
            cacheLength:10,
            formatItem: function(item) {
                if (item[0] == "505430") {
                    return "<a href='javascript:void(0)'>" + item[1] + "（海贼王）" + "</a>";
                } else if (item[0] == "501661") {
                    return "<a href='javascript:void(0)'>" + item[1] + "（死神）" + "</a>";
                } else {
                    return "<a href='javascript:void(0)'>" + item[1] + "</a>";
                }
            },
            onItemSelect:function(li) {
                if (li.selectValue !== null && li.selectValue.length > 0){
                    pgvSendClick({hottag: 'NEW.AC_SEARCH.KEYWORD.LIANXIANG'});
                    location.replace('/Comic/ComicInfo/id/' + li.selectValue, false);
                }
            }
        });
    },

    loadReadHistory: function() {
        if (AC.Page.Core.hasLogin == '1') {
            $.get('/Ajax/getReadHistory', {stamp: Math.random()},function(data){
                if (data.status == 2) {
                    //漫画阅读历史
                    var comicHisTotal = data.comicResult.length;
                    if (comicHisTotal > 0){
                        for (var i = 0; i < comicHisTotal; i++) {
                            data.comicResult[i]["delType"] = 3;
                            data.comicResult[i]["comicName"] = decodeURIComponent(data.comicResult[i].comicName);
                            data.comicResult[i]["shortComicName"] = decodeURIComponent(data.comicResult[i].shortComicName).substring(0,15);
                            data.comicResult[i]["comicLink"] = "/Comic/comicInfo/id/" + data.comicResult[i].id;
                            data.comicResult[i]["finishStateCss"] = parseInt(data.comicResult[i].finishState) === 1 ? 'ui-icon-new' : 'ui-icon-done';
                            data.comicResult[i]["latedChapterName"] = data.comicResult[i].latedChapterName;
                            data.comicResult[i]["shortLatedChapterName"] = data.comicResult[i].latedChapterName.substring(0,9);
                            data.comicResult[i]["coverUrl"] = 'http://ugc.qpic.cn/manhua_cover/0/'+data.comicResult[i].coverUrl+'/100';
                            data.comicResult[i]["link"] = data.comicResult[i].cid != 0 ? '/ComicView/index/id/' + data.comicResult[i].id + '/seqno/' + data.comicResult[i].viewNo + '/ref/dbsc' : "javascript:void(0)";
                            data.comicResult[i]["nextComicLink"] = '/ComicView/index/id/' + data.comicResult[i].id + '/seqno/' + data.comicResult[i].nextSeqNo + '/ref/dbsc';
                            data.comicResult[i]["latedComicLink"] = '/ComicView/index/id/' + data.comicResult[i].id + '/seqno/' + data.comicResult[i].latedSeqNo + '/ref/dbsc';
                        }
                    }
                    
                    //动画阅读历史
                    var cartoonHisTotal = data.cartoonResult.length;
                    if (cartoonHisTotal > 0){
                        for (var i = 0; i < cartoonHisTotal; i++) {
                            data.cartoonResult[i]["delType"] = 4;
                            data.cartoonResult[i]["link"] = '/Cartoon/detail/id/' + data.cartoonResult[i].id + '/sid/' + data.cartoonResult[i].viewNo + '/ref/dbyd';
                            data.cartoonResult[i]["cartoonName"] = decodeURIComponent(data.cartoonResult[i].cartoonName);
                            data.cartoonResult[i]["shortCartoonName"] = decodeURIComponent(data.cartoonResult[i].shortCartoonName).substring(0,10);
                            data.cartoonResult[i]["nextCartoonLink"] = '/Cartoon/detail/id/' + data.cartoonResult[i].id + '/sid/' + data.cartoonResult[i].nextEpisodeId + '/ref/dbyd';
                            data.cartoonResult[i]["photoUrl"] =  (data.cartoonResult[i].photoUrl != null && data.cartoonResult[i].photoUrl != "") ? data.cartoonResult[i].photoUrl : "";
                        }
                    }
                    
                    $('#mod-log-his-mh').html(template.render('script-mod-log-his-mh', data));
                    $('#mod-log-his-dh').html(template.render('script-mod-log-his-dh', data));
                    AC.Page.Core.setPanelPagination("#mod-log-his-mh",3);
                }
            }, 'json');
        } else {
            var comicStr = AC.Page.cookie('readRecord');
            var comicJson = eval(comicStr)|| [];
            var comicSubJson = {items:[]};
            
            for (var i = 0; i < comicJson.length; i++){
                if (comicJson[i][0] !== null && comicJson[i][2] !== null){
                    var item = {"comicId":comicJson[i][0],"chapterId":comicJson[i][2]};
                    comicSubJson.items.push(item);
                }
            }
            
            if (comicSubJson.items.length > 0) {
                $.get('/Ajax/getChapterInfo',{ comicInfoList: JSON.stringify(comicSubJson.items),stamp: Math.random()},function(result){
                    var data = {"comicResult":[]};
                    if (result.status == 1) {
                        for (var i = 0; i < comicJson.length; i++) {
                            //取得cookie中的漫画的最新章节、下一章节、完结状态数据
                            if(!result[comicJson[i][0]]){
                                continue;
                            }
                            var nextComicLink =  '/ComicView/index/id/' + comicJson[i][0] + '/seqno/' + result[comicJson[i][0]].info.nextSeqNo + '/ref/dbsc';
                            var latedComicLink = '/ComicView/index/id/' + comicJson[i][0] + '/seqno/' + result[comicJson[i][0]].info.latedSeqNo + '/ref/dbsc';
                            var finishStateCss = parseInt(result[comicJson[i][0]].finishState) === 1 ? 'ui-icon-new' : 'ui-icon-done';

                            var comicName = comicJson[i][1];
                            item = {
                                "id":comicJson[i][0],
                                "cid":comicJson[i][2],
                                "delType":1,
                                "viewNo":comicJson[i][4],
                                "latedSeqNo":result[comicJson[i][0]].info.latedSeqNo,
                                "latedChapterId":result[comicJson[i][0]].info.latedChapterId,
                                "comicName":comicName,
                                "finishStateCss":finishStateCss,
                                "latedComicLink":latedComicLink,
                                "nextComicLink":nextComicLink,
                                "shortComicName":comicName.substring(0,10),
                                "latedChapterName":result[comicJson[i][0]].info.latedChapterName,
                                "nextChapterName":result[comicJson[i][0]].info.nextChapterName,
                                "coverUrl":'http://ugc.qpic.cn/manhua_cover/0/'+ result[comicJson[i][0]].coverUrl +'/0',
                                "link":'/ComicView/index/id/' + comicJson[i][0] + '/cid/' + comicJson[i][2] + '/ref/dbyd',
                                "comicLink" : "/Comic/comicInfo/id/" + comicJson[i][0]
                            };
                            data.comicResult.push(item);
                        }
                    }
                    $('#mod-log-his-mh').html(template.render('script-mod-log-his-mh', data));
                    AC.Page.Core.setPanelPagination("#mod-log-his-mh",3);
                }, 'json');
            } else {
                var data = {"comicResult":[]};
                $('#mod-log-his-mh').html(template.render('script-mod-log-his-mh', data));
            }
                    
               
             
                //动画
                var data = {"cartoonResult":[]};
                var cartoonStr = AC.Page.cookie('ac_cartoon_readhistory');
                var cartoonJson = eval(cartoonStr)|| [];
                for (var i = 0; i < cartoonJson.length; i++) {
                     var tmp = cartoonJson[i];
                    if (tmp == null || tmp == '') {
                        continue;
                    }
                    //动画图片地址(表中存储的是完整的地址)
                    var photoUrl = (tmp[4] != null && tmp[4] != "") ? tmp[4] : "";
                    var cartoonName = decodeURIComponent(tmp[1]);
                    var link = '/Cartoon/detail/id/' + tmp[0] + '/sid/' + tmp[2] + '/ref/dbyd';
                    var item = {
                        "id":tmp[0],
                        "cartoonName":cartoonName,
                        "shortCartoonName":cartoonName.substring(0,10),
                        "link":link,
                        "delType":2,
                        "photoUrl":photoUrl,
                        "viewNo":tmp[2],
                        "nextEpisodeName":""
                    };
                    data.cartoonResult.push(item);
                }   
                $('#mod-log-his-dh').html(template.render('script-mod-log-his-dh', data));
                
              
                AC.Page.Core.setPanelPagination("#mod-log-his-dh",4);
        }
    },
    deleteReadHistory: function(obj, type, id) {
        if (type == 1 || type == 2) {
            var cookieName = 'readRecord';
            if (type == 2) {
                cookieName = 'ac_cartoon_readhistory';
            }

            var readRecord = AC.Page.cookie(cookieName);
            var historyJson = eval(readRecord) || [];
            var total = historyJson.length;
            if (total > 0) {
                for (var i = 0; i < total; i++) {
                    if (id == historyJson[i][0]) {
                        historyJson.splice(i, 1);
                        break;
                    }
                }

                AC.Page.cookie(cookieName, JSON.stringify(historyJson));
                $(obj).parent('li').remove();
                
                if (total == 1) {
                    if (type == 1){
                        $("#mod-log-his-mh").html('<div class="mod-userlog-tips"><h3>暂无阅读记录</h3><p class="ui-mb40">赶紧开始精彩的漫画之旅吧</p></div>');
                    } else if (type == 2) {
                        $("#mod-log-his-dh").html('<div class="mod-userlog-tips"><h3>暂无阅读记录</h3><p class="ui-mb40">赶紧开始精彩的动画之旅吧</p></div>');
                    }
                }
            }
        }

        if (type == 3 || type == 4) {
            var url = '/ComicView/delReadRecord';
            if (type == 4) {
                url = '/Cartoon/delReadHistory';
            }
            $.post(url, { 
                'cid' : id,
                'tokenKey' : AC.Page.Core.token
            }, function(data) {
                if (data.status == 1) {
                    $(obj).parent('li').remove();
                    
                    if (type == 3){
                        var total = $("#mod-log-his-mh .mod-slider-item-wr").length;
                        if (total == 1) {
                            $("#mod-log-his-mh").html('<div class="mod-userlog-tips"><h3>暂无阅读记录</h3><p class="ui-mb40">赶紧开始精彩的漫画之旅吧</p></div>');
                        }
                    } else if (type == 4) {
                        var total = $("#mod-log-his-dh .mod-slider-item-wr").length;
                        if (total == 1) {
                            $("#mod-log-his-dh").html('<div class="mod-userlog-tips"><h3>暂无观看记录</h3><p class="ui-mb40">赶紧开始精彩的动画之旅吧</p></div>');
                        }
                    }
                }
            }, 'json');
        }
    },

    loadMessageNotify: function(autoFlag) {
        if (autoFlag) {
            if (AC.Page.Core.hasLogin == "1") {
                $.get('/Ajax/getMessageNotify',{stamp: Math.random()}, function(result) {
                    if(result.msgList){
                        AC.Page.Core.setMessageCount(result.msgList.length);
                    }
                    
                }, 'json');
            }
        } else {
            if (AC.Page.Core.hasLogin == "1") {
                $.get('/Ajax/getMessageNotify',{stamp: Math.random()}, function(result) {
                    AC.Page.Core.showMessageNotify(result);
                }, 'json');
            }
        }
    },

    setMessageCount: function(msgCount) {
        if (msgCount > 0) {
            //顶部右上角
            $('#mod-top-user .mod-user-msg-num').show();
            $('#mod-top-user .mod-user-msg-num').text((msgCount >= 99) ? '99+' : msgCount);
            
            //悬浮条右侧
            $('#fix-user .mod-user-msg-num').show();
            $('#fix-user .mod-user-msg-num').text((msgCount >= 99) ? '99+' : msgCount);
        } else {
            //顶部右上角
            $('#mod-top-user .mod-user-msg-num').empty();
            $('#mod-top-user .mod-user-msg-num').hide();
            
            //悬浮条右侧
            $('#fix-user .mod-user-msg-num').empty();
            $('#fix-user .mod-user-msg-num').hide();
        }

        this.msgCount = msgCount;
    },

    showMessageNotify: function(result) {
        var msgCount = result.msgList.length;
        this.setMessageCount(msgCount);
        
        for (var i = 0; i < msgCount; i++) {
            result.msgList[i]["type"] = result.msgList[i].type;// result.msgList[i].type == "2" ? "mod-user-msg-main mod-msg-sys" : "mod-user-msg-main mod-msg-per";
            result.msgList[i]["title"] = decodeURIComponent(result.msgList[i].title);
            result.msgList[i]["id"] = result.msgList[i].id;
        }

        $('#mod-user-msg').html(template.render('script-mod-user-msg', result));

        if (msgCount > 0){
            AC.Page.Core.setPanelPagination("#mod-user-msg",2);
        }
    },

    clearMessageNotify: function(messageId) {
        $.post('/Ajax/clearMessageNotify', {messageId: messageId,stamp: Math.random()}, function(result) {
            var state = result.state;
            var msgId = result.msgId;

            if (state == 1) {
                $('#li_message_' + msgId).remove();

                var msgCount = AC.Page.Core.msgCount - 1;
                var msgNow = $('#ul_message_list li').length;

                if (msgCount <= 0) {
                    $('#div_message_detail').hide();

                    $('#p_message_empty').show();
                } else {
                    if (msgNow == 0) {
                        $('#sp_message_remain').html(msgCount - 5);
                        $('#p_message_remain').show();
                    }
                }

                AC.Page.Core.setMessageCount(msgCount);
            }
        }, 'json');
    },

    //收藏更新和阅读历史
    loadFavoritUpdate: function(autoFlag) {
        if (AC.Page.Core.hasLogin == '1') {
            $.get('/Ajax/getFavoritUpdate',{stamp: Math.random()}, function(result) {
                result.hasLogin = AC.Page.Core.hasLogin;
                AC.Page.Core.showFavoritUpdate(result);
                AC.Page.Core.setFavoritUpdateCount(result.data.length);
            }, 'json');
            
        }else{
            var result = {};
            result['hasLogin'] = '0';
            $("#mod-log-collection").html(template.render('script-mod-log-collection', result));
            
            //给书架中的"阅读历史"绑定单击事件
            $("#mod-top-log-history").die("click").live("click",function(){
                AC.Page.Core.loadReadHistory();
                AC.Page.Core.Timetstamp =  Date.parse(new Date()); 
            });
        }
    },

    setFavoritUpdateCount: function(updateCount) {
        if (updateCount > 0) {
            //顶部右上角
            $('#mod-top-log .mod-user-msg-num').show();
            $('#mod-top-log .mod-user-msg-num').text((updateCount >= 99) ? '99+' : updateCount);
            
            //悬浮栏右侧
            $("#fix-log").next().show();
            $("#fix-log").next().text((updateCount >= 99) ? '99+' : updateCount);
        } else {
            //顶部右上角
            $('#mod-top-log .mod-user-msg-num').empty();
            $('#mod-top-log .mod-user-msg-num').hide();
            
            //悬浮栏右侧
            $("#fix-log").next().hide();
            $("#fix-log").next().empty();
        }

        this.updateCount = updateCount;
    },

    //收藏更新
    showFavoritUpdate: function(result) {
        var updateCount = result.data.length;
        this.setFavoritUpdateCount(updateCount);
        
        for (var i = 0; i < updateCount; i++) {
            var title = decodeURIComponent(result.data[i].title);
            result.data[i]["title"] = title;
            result.data[i]["shortTitle"] = title.substring(0,15);
            result.data[i]["link"] = '/ComicView/index/id/' + result.data[i].id + '/seqno/' + result.data[i].viewSeqNo + '/ref/dbsc';
            result.data[i]["finish"] = result.data[i].finishState == 1 ? 'ui-icon-new' : 'ui-icon-done';
            result.data[i]["nextLink"] = '/ComicView/index/id/' + result.data[i].id + '/seqno/' + result.data[i].nextSeqNo + '/ref/dbsc';
            result.data[i]["latedLink"] = '/ComicView/index/id/' + result.data[i].id + '/seqno/' + result.data[i].lateSeqNo + '/ref/dbsc';
            result.data[i]["coverUrl"] = 'http://ugc.qpic.cn/manhua_cover/0/'+ result.data[i].coverUrl +'/100';
            result.data[i]["comicLink"] = "/Comic/comicInfo/id/"+result.data[i].id;
        }
        
        $("#mod-log-collection").html(template.render('script-mod-log-collection', result));

        //给书架中的"阅读历史"绑定单击事件
        $("#mod-top-log-history").die("click").live("click",function(){
            AC.Page.Core.loadReadHistory();
            AC.Page.Core.Timetstamp =  Date.parse(new Date()); 
        });
        
        //阅读历史--动画
        $(".mod-top-log-animate").die("click").live("click",function(){
            
            AC.Page.Core.setPanelPagination("#mod-log-his-dh",4);
        });
        
        AC.Page.Core.setPanelPagination("#mod-log-fav-mh",3);
    },
    
    setPanelPagination: function(viewName,showCount){
        // 涉及头部及浮层
        // 已登录
        // 退出
        var logView = function(el){
            var wrapp = el,
            list = wrapp.find('.mod-slider-list'),
            pageWidth = list.find('li').width(),
            btnWrapp = wrapp.find('div.mod-slider-btn'),
            nextBtn = btnWrapp.find('a.mod-slider-btn-next'),
            prevBtn = btnWrapp.find('a.mod-slider-btn-prev'),
            delBtn = list.find('.ui-btn-del'),
            messageCount = list.find("div").length,
            p = messageCount > 0 ? Math.ceil(messageCount / showCount) : 1, // 页数
            n = 0,// 当前所在页号
            isMov = true;

            var logView = {
                init: function(){
                    this.bindEvent();
                },
                bindEvent: function(){
                    var self = this;

                    nextBtn.click(function(){
                        if(!isMov)return false;
                        n += 1;
                        if(n >= p){
                            n = p-1;
                            return false;
                        }
                        self.setAnimate();
                    });

                    prevBtn.click(function(){
                        if(!isMov)return false;
                        n -= 1;
                        if(n<0){
                            n = 0;
                            return false;
                        }
                        self.setAnimate();
                    });

                    delBtn.click(function(){
                        var work = $(this).parents('.mod-slider-item-wr'),
                        parent = work.parent(),
                        index = work.attr('index');
                        work.fadeOut(function(){
                            var ts = $(this).siblings().size(),
                            ps = parent.siblings().size();
                            if( ts == 0 ) {
                                parent.remove();
                                n -= 1;
                                self.setAnimate(n);
                            }

                            if( ts == 0 && ps == 0){
                                list.parent().prev().show();
                                return false;
                            }

                            work.remove();

                            var next = parent.nextAll(),
                            nextSize = next.size();
                            parent.append(parent.next().find('div:first'));
                            next.each(function(i){
                                var n = $(this).next()
                                $(this).append(n.find('div:first'));
                                $.trim($(this).html()) === '' && $(this).remove();
                            });
                            p = list.find('li').size();
                            if(p<=1){
                                btnWrapp.remove();
                            }
                        });
                    })
                },
                setAnimate: function(){
                    var position = pageWidth * n
                    if(isMov){
                        isMov = false;
                        list.stop().animate({
                            'margin-left': -position
                        }, 500, function(){
                            isMov = true;
                        });
                    }
                }
            }
            logView.init();
        }

        if (viewName == "#mod-user-msg"){
            // 头部个人信息
            logView($('#mod-user-msg'));
                AC.Page.Core.dropBox({
                handle: $('.mod-top-user-msg'),
                panel: $('#user-info'),
                offsetLeft: -241,
                offsetTop: 15
            });
        }else if(viewName == ""){
            if(AC.Page.Core.hasLogin == '1'){
                AC.Page.Core.dropBox({
                    handle: $('.mod-top-user-msg'),
                    panel: $('#user-info'),
                    offsetLeft: -241,
                    offsetTop: 15
                });
            }
                
             // 头部书架
             AC.Page.Core.dropBox({
                handle: $('.mod-top-user-log'),
                panel: $('#log-info'),
                offsetLeft: -276,
                offsetTop: 15
            });
        }
        else{
            if (viewName == "#mod-log-fav-mh"){
                logView($('#mod-log-fav-mh'));
            }else if(viewName == "#mod-log-his-dh"){
                logView($('#mod-log-his-dh'));
            }else if(viewName == "#mod-log-his-mh"){
                logView($('#mod-log-his-mh'));
            }
            // 头部书架
            AC.Page.Core.dropBox({
                handle: $('.mod-top-user-log'),
                panel: $('#log-info'),
                offsetLeft: -276,
                offsetTop: 15
            });
        }
    },
    
    dropBox: function(opts){
        var deley = opts.deley || 100,
        handle = opts.handle || null,
        panel = opts.panel || null,
        offsetLeft = parseInt(opts.offsetLeft) || 0,
        offsetTop = parseInt(opts.offsetTop) || 0,
        positionLeft = 0,
        positionTop = 0;
        var dropBox = {
            timer: null,
            isActive: true,
            view: function(){
                if(handle[0] && panel[0]){
                  this.bindEvent();  
                }
            },
            bindEvent: function(){ 
                var self = this;
                $(window).resize(function(){
                    self.getPosition();    
                });
                handle.hover(function(){
                    self.getPosition()
                    self.dropShow();
                    self.timer && clearTimeout(self.timer);
                },function(){
                    self.timer = setTimeout(function(){
                        self.dropHide();
                    }, deley);
                }); 

                panel.hover(function(){
                    self.timer && clearTimeout(self.timer);    
                },function(){
                    setTimeout(function(){
                        self.dropHide();
                    }, 100);
                });                    
            },
            getPosition: function(){
                positionLeft = parseInt(handle.offset().left + offsetLeft);
                positionTop = parseInt(handle.height() + handle.offset().top + offsetTop);
                opts.fixed && (positionTop =  parseInt($(document).scrollTop() + handle.height() + offsetTop));    
            },
            dropShow: function(){
                var self = this;
                if(!this.isActive)return;
                panel.css({
                    'position': 'absolute',
                    'z-index': 60,
                    'left': positionLeft,
                    'top': positionTop
                }).show();
                this.isActive = false;
            },
            dropHide: function(){
                panel.hide();
                this.isActive = true;
            }
        }
        dropBox.view();
    },
 
    sideTool: function(){
        var sideWr = $('#mod-side-tool'), // 侧边工具容器
        scrollTopElement = $('#scroll-top'), // 返回顶部按钮
        sideTimer;
        
        if(sideWr[0]){
            $(window).bind('scroll', function() {
                var scrollTopVal = $(this).scrollTop();
                if(sideTimer){
                    clearTimeout(sideTimer);
                }
            
                sideTimer = setTimeout(function(){
                    // 是否显示侧边工具
                    scrollTopVal > 400 ? sideWr.fadeIn() : sideWr.fadeOut();                    
                }, 200);
            });

            scrollTopElement.click(function(){
                $('html, body').animate({
                    scrollTop: 0
                }, 100);
            });
        }

    }
};
