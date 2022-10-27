$(function(){
	var Cookie = {
        /**
         * method get
         * @param name
         * @returns {null}
         */
        get: function(name){
            var carr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));

            if (carr != null){
                return decodeURIComponent(carr[2]);
            }

            return null;
        },
        /**
         * method set
         * @param name
         * @returns {null}
         */
        set:function(name, value, expires, path, domain){
            if(expires){
                expires = new Date(+new Date() + expires);
            }
            var tempcookie = name + '=' + escape(value) +
                ((expires) ? '; expires=' + expires.toGMTString() : '') +
                ((path) ? '; path=' + path : '') +
                ((domain) ? '; domain=' + domain : '');

            //Ensure the cookie's size is under the limitation
            if(tempcookie.length < 4096) {
                document.cookie = tempcookie;
            }
        },
        clear: function (name, path, domain) {
	        if (this.get(name)) {
	            document.cookie = name + "=" + ((path) ? "; path=" + path : "; path=/") + ((domain) ? "; domain=" + domain : "") + ";expires=Fri, 02-Jan-1970 00:00:00 GMT";
	        }
	    }
    };
    function underline(str){
      return str.replace(/\B([A-Z])/g, '-$1').toLowerCase()
    }
    
    //阅读配置
    var readerConfigWap = Cookie.get('reader_config_wap');
    var skinArr = {"0":"skinDefault","1":"skinBlue","2":"skinGreen","3":"skinLight"};
    var layoutArr = {"v":"layoutV","h":"layoutH"};
    var modeArr = {"d":"night","n":"day"};
    if( !readerConfigWap ){
    	readerConfigWap = 'd|2|0|v';
    }
    readerConfigWap = readerConfigWap.split("|");
    //body黑夜模式
    if( readerConfigWap[0]=='n' ){
        $('body').addClass('read-night');
    }else{
        
        $('body').removeClass('skin-default')
        $('body').addClass(underline(skinArr[readerConfigWap[2]]));
        
    }
    if( readerConfigWap[3]=='h' ){
        $('#pageRead').addClass('H');
    }
    //字体大小
    $('#chapterContent').css('font-size',((readerConfigWap[1]*2+12)/16)+'rem');
    //阅读设置
    $('#pageReadOpt').data('settings-str','dn:'+readerConfigWap[0]+'|fs:'+readerConfigWap[1]+'|skin:'+readerConfigWap[2]+'|vh:'+readerConfigWap[3]);
    //设置字体默认选中
    $('#readFontRange').val(readerConfigWap[1]);
    //设置皮肤默认选中
    $('#'+skinArr[readerConfigWap[2]]).attr("checked","checked");
    //设置默认滑动模式
    $('#'+layoutArr[readerConfigWap[3]]).attr("checked","checked");
    //设置默认日夜模式
    $('#readBtnMode').data("mode",modeArr[readerConfigWap[0]]);
    $('#readBtnMode .read-opt-footer-h').html(readerConfigWap[0]=='d'?'夜间':'日间');
    
    //设置默认菜蔬
    var $settings = $("#pageReadOpt")
    var data = {
        isLogin: book.uid,
        isPublish: $settings.data("book-publish") == "0" ? 0 : 1,
        salesMode: $settings.data("sale-modes") == "2" ? 1 : 0,
        hasSub: $settings.data("has-sub") == "0" ? 0 : 1,
        settingsStr: $settings.data("settingsStr"),
        chapterUrl: book.chapterUrl,
        endUrl: book.url,
        book: book.Info,
        chapters: {},
        /*chapters: function() {
            var chapters = {};
            chapters[book.chapter.id] = book.chapter;
            return chapters;
        }(),*/
        chapter: book.chapter,
        scrollLastChapter: book.chapter,
        lastOfflineChapterId: null,
        isInBookShelf: 0,
        catalog: null
    };
    var isOffline = function() {
        return navigator && navigator.onLine === false;
    };
    var Book = {
	    init: function () {
            var that = this;
            //获取屏幕的高度
            that.winHeight = $(window).height();
            //禁止页面选中文字、copy、右键功能
            that.forbidCopy();
            this.initOptionsLayer();
            this.initCatalogAside();
            this.initChapterProgress();
            //设置字体
            this.initFontSizeSetting();
            //设置皮肤
            this.initSkinSetting();
            //设置翻页类型
            this.initModeSetting();
            //设置黑夜模式
            this.initDayNightSetting();
            //初始化模型
            this.initModes();
            if ($("#pageRead").hasClass("H")) {
                this.swipeReader.enable();
            } else {
                this.scrollReader.enable();
            }
            this.jumpChapter(data.chapter.id);
        },
        /*
         * 禁止页面选中文字、copy、右键功能
         * @method forbidCopy
         */
        forbidCopy: function () {
            //禁止copy
            $('body').on('copy', function () {
                return false;
            });
            //禁止cut
            $('body').on('cut', function () {
                return false;
            });
            //禁止鼠标右键默认弹窗
            $('body').on('contextmenu', function () {
                return false;
            });

        },
        initOptionsLayer: function() {
            var $layer = $("#pageReadOpt");
            var $settingsMenu = $("#readBtnMore");
            $layer.on({
                click: function(event) {
                    var target = event.target;
                    if (target !== this || target.avoidClick) {
                        return;
                    }
                    var $activeLayer = $layer.find(".jsLayerTrigger.active");
                    if ($activeLayer.length) {
                        $activeLayer.trigger("click");
                    }
                    $layer.removeClass("active");
                },
                touchstart: function(event) {
                    this.flagMoveHide = event.target === this;
                },
                touchmove: function(event) {
                    if (this.flagMoveHide) {
                        $layer.trigger("click");
                    }
                    event.preventDefault();
                }
            });
            new Toggle($settingsMenu);
            var $layerTrigger = $(".jsLayerTrigger");
            $layerTrigger.on("click", function(event) {
                var self = this;
                if (event && (event.isTrusted === false || event.hasOwnProperty("_args") || event.pageY === 0 && event.screenY === 0)) {
                    return;
                }
                $layerTrigger.each(function() {
                    var $this = $(this);
                    if (this !== self && $this.hasClass("active")) {
                        $this.trigger("click");
                    }
                });
            });
        },
        initCatalogAside: function() {
            var self = this;
            $("#readBtnChapter").aside({
                scrollable: "#catelogX",
                onInit: function() {
                    var aside = this;
                    var layer_index=layer.open({type: 2,content: '加载中'});
                    $.ajax({ type : "get", url : "/module/novel/menu.php",
                        data : {tid: data.book.tid,nid:data.book.nid}, 
                        async : false, 
                        success : function(result){ 
                            layer.close(layer_index);
                            $("#asideChapterContent").html(result);
                            $("#asideChapterContent").on("click", ".jsChapter a", function(event) {
                                event.preventDefault();
                                aside.hide();
                                $("#pageReadOpt").trigger("click");
                                self.jumpChapter($(this).data("chapter-id"), true);
                            });
                        } 
                    }); 
                },
                onShow: function() {
                    var nowChapterId = data.chapter.id,chapterDom = $('[data-chapter-id="'+nowChapterId+'"]');
                    $('#catelogX').find('.chapter-index.red').removeClass('red');
                    chapterDom.find('.chapter-index').addClass('red');
                    $('#catelogX').scrollTop(0).scrollTop(chapterDom.offset().top - $('#catelogX').offset().top);
                }
            });
        },
        initChapterProgress: function() {
            var self = this;
            var $toggle = $("#readBtnProg");
            var range;
            var chapterIds = [];
            var chapters = {};
            function refresh(chapterId) {
                var chapterIndex = chapterIds.indexOf(parseInt(chapterId));
                if (chapterIndex < 0) {
                    chapterIndex = 0;
                    chapterId = chapterIds[chapterIndex];
                }
                var chapterName = chapters[chapterId];
                var $currentChapter = $("#readProgVal");
                $currentChapter.find("h4").html(chapterName);
                $currentChapter.find("output").html((chapterIndex * 100 / chapterIds.length).toFixed(1) + "%");
            }
            function init(result) {
                $(result).find('.chapter-li-a').each(function(){
                    var chapter_id = $(this).data('chapter-id');
                    chapterIds.push(chapter_id);
                    chapters[chapter_id] = $(this).text();
                    
                });
                refresh(data.chapter.id);
                var $chapterProgressRange = $("#readProgRange");
                $chapterProgressRange.attr("max", chapterIds.length - 1);
                $chapterProgressRange.val(chapterIds.indexOf(parseInt(data.chapter.id)));
                $chapterProgressRange.on("change", function() {
                    var chapterId = chapterIds[this.value];
                    refresh(chapterId);
                });
                range = new Range($chapterProgressRange, {
                    shadow: true,
                    buttons: [ "#readProgPrev", "#readProgNext" ],
                    onChangeEnd: function() {
                        var chapterId = chapterIds[$chapterProgressRange.val()];
                        self.jumpChapter(chapterId, true);
                    },
                });
                range.shadow();
            }
            new Toggle($toggle, {
                callback: function() {
                    if (range) {
                        range.value(chapterIds.indexOf(parseInt(data.chapter.id)));
                        range.shadow();
                    }
                }
            });
            $toggle.one("click", function() {
                if ($('catelogX').length != 0) {
                    init($('#asideChapterContent').html());
                } else {
                    var $el = $("#readOptProg").css("opacity", 0);
                    var layer_index=layer.open({type: 2,content: '加载中'});
										$.ajax({ type : "get", url : "/module/novel/menu.php",
										    data : {tid: data.book.tid,nid:data.book.nid}, 
										    async : false, 
										    success : function(result){ 
										        layer.close(layer_index);
										        $el.css("opacity", 1);
										        init(result);
										    } 
										}); 
                }
            });
        },
        initFontSizeSetting: function() {
						
            var self = this;
            var $toggle = $("#readBtnSet");
            var $range = $("#readFontRange");
            var $chapterContent = $("#chapterContent");
            var lazyHandler = debounce(function(val, e) {
                self.scrollReader.refresh();
                self.swipeReader.refresh();
                self.updateSettings({
                    fs: val
                });
            }, 300);
            new Toggle($toggle);
            new Range($range, {
                buttons: [ "#readFontDown", "#readFontUp" ]
            });
            $range.on("change", function(e) {
                var val = this.value;
                var rem = (val * 2 + 12) / 16;
                $chapterContent.css("font-size", rem + "rem");
                lazyHandler(val, e);
            });
        },
        initSkinSetting: function() {
						
            var self = this;
            var $body = $("body");
            var $dn = $("#readBtnMode");
            var lazyHandler = debounce(function(val) {
                self.updateSettings({
                    skin: val
                });
            }, 300);
            $("#readSetSkin").on("click", '[type="radio"]', function(){
                var $this = $(this);
                $body.removeClass("read-night");
                $body.attr("class", $body.attr("class").replace(/skin-[a-z]+/g, "skin-" + $this.val()));
                if ($dn.data("mode") === "day") {
                    $dn.trigger("click");
                }
                lazyHandler($this.data("index"));
            });
        },
        initModeSetting: function() {
						
            var self = this;
            var lazyHandler = debounce(function(val){
                self.updateSettings({
                    vh: val
                });
            }, 300);
            $("#readSetLayout").on("click", '[type="radio"]', function() {
                if (this.value === "v") {
                    $("#pageRead").removeClass("H");
                    self.scrollReader.enable();
                    self.swipeReader.disable();
                    self.jumpChapter(data.chapter.id);
                } else {
                    $("#pageRead").addClass("H");
                    self.scrollReader.disable();
                    self.swipeReader.enable();
                    self.jumpChapter(data.chapter.id);
                }
                lazyHandler(this.value);
            });
        },
        initDayNightSetting: function() {
            var self = this;
            var $body = $("body");
            var $btn = $("#readBtnMode");
            var $title = $btn.find("h4");
            var DAY = "day";
            var NIGHT = "night";
            var lazyHandler = debounce(function(val) {
                self.updateSettings({
                    dn: val
                });
            }, 300);
            $btn.on("click", function() {
                var mode = $btn.data("mode");
                if (mode === DAY) {
                    $btn.data("mode", NIGHT);
                    $title.html($title.html().replace("日", "夜"));
                    $body.removeClass("read-night");
                    var skin = $body.attr("class").match(/skin-([a-z]+)/);
                    if (skin) {
                        skin = skin[1];
                        $('#readSetSkin [type="radio"][value="' + skin + '"]').prop("checked", true);
                    }
                    lazyHandler("d");
                } else if (mode === NIGHT) {
                    $btn.data("mode", DAY);
                    $title.html($title.html().replace("夜", "日"));
                    $body.addClass("read-night");
                    $('#readSetSkin [type="radio"]').each(function() {
                        var $this = $(this);
                        if ($this.prop("checked")) {
                            $this.prop("checked", false);
                        }
                    });
                    lazyHandler("n");
                }
            });
        },
        initModes: function() {
            var self = this;
            var $goEnd = $("#aGotoBookEnd");
            // 当前章节id
            function isChapterShown(chapterId) {
                return $(".jsChapterWrapper").map(function() {
                    return $(this).data("chapter-id");
                }).indexOf(chapterId) > -1;
            }
            var scrollReader = this.scrollReader = new ScrollReader({
                wrapperSelector: "#readContent",
                prevSelector: "#readLoadPrev",
                nextSelector: "#readLoadNext",
                nextTriggerSelector: "#btnLoadNextChapter",
                markSelector: "#readPageMark",
                onPrevClick: function(e) {
                    //点击向前滚动
                },
                onCenterClick: function() {
                    $settings[0].avoidClick = true;
                    setTimeout(function() {
                        $settings[0].avoidClick = false;
                    }, 500);
                    $settings.addClass("active");
                },
                onNextClick: function(e) {
                    //点击向后滚动
                },
                onScroll: function(y) {
                    var isViewportChapterId;
                    scrollReader.$wrapper.find(".jsChapterWrapper").each(function() {
                        if (isViewportChapterId) {
                            return;
                        }
                        var $this = $(this);
                        var offset = $this.offset();
                        if (offset.top < y && offset.top + offset.height > y) {
                            isViewportChapterId = $this.data("chapter-id");
                        }
                    });
                    if (isViewportChapterId) {
                        self.setReadChapter(isViewportChapterId);
                    }
                },
                onScrollPrev: function(resolve, reject) {
                    var chapterId = data.chapter.prevId;
                    if (chapterId) {
                        self.jumpChapter(chapterId, false, resolve, reject);
                    } else {
                        layer.open({content:'已经是第一章了',skin:'msg',time:2});
                        reject();
                    }
                },
                onScrollNext: function(resolve, reject) {
                    var chapterId = data.scrollLastChapter.nextId;
                    if (chapterId) {
                        self.getChapter(chapterId, false, function(result) {
                            data.scrollLastChapter = result.data.chapterInfo;
                            if (!isChapterShown(chapterId)) {
                                $("#chapterContent").append('<section class="read-section jsChapterWrapper" data-chapter-id="'+result.data.chapterInfo.id+'"><h3>'+result.data.chapterInfo.title+'</h3>'+result.data.chapterInfo.content+'</section>');
                                if (data.scrollLastChapter.nextId) {
                                    scrollReader.$nextTrigger.show();
                                    $goEnd.hide();
                                } else {
                                    scrollReader.$nextTrigger.hide();
                                    $goEnd.show();
                                }
                                resolve();
                            } else {
                                reject();
                            }
                        }, reject);
                    }
                },
                onWillScrollNext: function(resolve, reject) {
                    var chapterId = data.scrollLastChapter.nextId;
                    if (chapterId && !(data.scrollLastChapter.vipStatus && $("#swiChk1").prop("checked"))) {
                        self.getChapter(chapterId, false, function(result) {
                            data.scrollLastChapter = result.data.chapterInfo;
                            if (!isChapterShown(chapterId)) {
                                $("#chapterContent").append('<section class="read-section jsChapterWrapper" data-chapter-id="'+result.data.chapterInfo.id+'"><h3>'+result.data.chapterInfo.title+'</h3>'+result.data.chapterInfo.content+'</section>');
                                if (data.scrollLastChapter.nextId) {
                                    scrollReader.$nextTrigger.show();
                                    $goEnd.hide();
                                } else {
                                    scrollReader.$nextTrigger.hide();
                                    $goEnd.show();
                                }
                                resolve();
                            } else {
                                reject();
                            }
                        }, reject);
                    } else {
                        reject();
                    }
                }
            });
            this.swipeReader = new SwipeReader({
                wrapperSelector: "#readContent",
                scrollerSelector: ".jsChapterWrapper",
                pagerSelector: "#pageNum",
                onCenterClick: function() {
                    $settings[0].avoidClick = true;
                    setTimeout(function() {
                        $settings[0].avoidClick = false;
                    }, 500);
                    $settings.addClass("active");
                },
                onSwipePrev: function(resolve, reject) {
                    var chapterId = data.chapter.prevId;
                    if (chapterId!=0) {
                        self.jumpChapter(chapterId, false, resolve, reject);
                    } else {
                        layer.open({content:'已经是第一章了',skin:'msg',time:2});
                        reject();
                    }
                },
                onSwipeNext: function(resolve, reject) {
                    var chapterId = data.chapter.nextId;
                    if (chapterId) {
                        self.jumpChapter(chapterId, false, resolve, reject);
                    }else{
                        location.href = data.endUrl;
                    }
                },
                onWillSwipeNext: function(resolve, reject) {
                    var chapterId = data.chapter.nextId;
                    if (chapterId) {
                        self.getChapter(chapterId, false, resolve, reject);
                    } else {
                        reject();
                    }
                }
            });
        },
        jumpChapter: function(chapterId, isShowLoading, resolve, reject) {
            var self = this;
            if (!history.replaceState) {
                location.href = data.chapterUrl.replace("chapterId", chapterId);
            } else {
                self.getChapter(chapterId, isShowLoading, function(result) {
                    var chapter = result.data.chapterInfo;
                    data.chapter = data.scrollLastChapter = chapter;
                    if (chapter.nextId) {
                        $("#btnLoadNextChapter").show();
                        $("#aGotoBookEnd").hide();
                    } else {
                        $("#btnLoadNextChapter").hide();
                        $("#aGotoBookEnd").show();
                    }
                    $("#chapterTitle").html(chapter.title);
                    $("#chapterContent").html('<section class="read-section jsChapterWrapper" data-chapter-id="'+chapter.id+'"><h3>'+chapter.title+'</h3>'+chapter.content+'</section>');
                    self.updateReadProgress();
                    history.replaceState(null, null, data.chapterUrl.replace("chapterId", chapterId));
                    if ($.isFunction(resolve)) {
                        resolve();
                    } else if (self.scrollReader.enabled) {
                        self.scrollReader.restart();
                    } else if (self.swipeReader.enabled) {
                        self.swipeReader.restart();
                    }
                }, reject || function() {});
            }
        },
        updateSettings: function(settings) {
            $.each(settings, function(key, value) {
                data.settingsStr = data.settingsStr.replace(new RegExp(key + ":[^|]+"), key + ":" + value);
            });
            var settingsStr = data.settingsStr.replace(/[^:|]+:/g, "");
            Cookie.set('reader_config_wap', settingsStr, 86400000 * 365, '/');
        },
        updateReadProgress: function() {
            if (isOffline()) {
                return;
            }
            if(!data.isLogin){
                return;
            }
            //更新阅读记录
            // $.get("/user/Chapter/UpdRead", {
            //     bookId: data.book.bookId,
            //     chapterId: data.chapter.chapterId,
            //     chapterName: data.chapter.chapterName
            // });
        },
        getChapter: function(chapterId, isShowLoading, resolve, reject) {
            var self = this;
            var layer_index;
			if (chapterId==0) {
			    layer.open({content:'已显示全部章节',skin:'msg',time:2});
					$("#readLoadNext").css("opacity", 0);
			}
            if (isOffline()) {
                $("#readLoadNext").css("opacity", 1);
            }
            if (data.chapters[chapterId]) {
                resolve({
                    data: {
                        bookInfo: data.book,
                        chapterInfo: data.chapters[chapterId]
                    }
                });
            } else {
                if (isOffline()) {
                    if (data.chapter.chapterId === data.lastOfflineChapterId) {
                        $("#readLoadNext").css("opacity", 0);
                        layer.open({content:'已显示全部离线章节',skin:'msg',time:2});
                    }
                    return reject();
                }
                if (isShowLoading) {
                    layer_index=layer.open({type: 2,content: '加载中'});
                }
                $.getJSON("/wmcms/ajax/index.php?action=novel.getchapter", {
											cid:chapterId,
											format:1,
										}, function(result) {
										if (isShowLoading) {
												layer.close(layer_index);
										}
										if (parseInt(result.code) === 200) {
										        var chapterData = result.data.chapter;
										        var novelData = result.data.novel;
										        var prev = result.data.prev;
										        var next = result.data.next;
										        var price = result.data.price;
										        var novelId = chapterData.chapter_nid;
										        var curChapterId = chapterData.chapter_id;
										        var gold2 = book.gold2Name;
										        content = '';
										        if( chapterData.is_sub==0 ){
										            //单章出售
										            if( parseFloat(price.sell_number) > 0 ){
										                content += "<a onclick=\"dingyue(1,"+novelId+","+curChapterId+")\" style=\"background: red;\"><em>订阅本章</em><span><i>"+price.sell_number+"</i>"+gold2+"(共"+chapterData.chapter_number+"字)</span></a>";
										            }
										            //包月出售
										            if( parseFloat(price.sell_month) > 0 ){
										                content += "<a onclick=\"dingyue(2,"+novelId+","+curChapterId+")\" style=\"background: #475f70;\"><em>订阅本月</em><span><i>"+price.sell_month+"</i>"+gold2+"</span></a>";
										            }
										            //全本出售
										            if( parseFloat(price.sell_all) > 0 ){
										                content += "<a onclick=\"dingyue(3,"+novelId+","+curChapterId+")\"><em>订阅全本</em><span><i>"+price.sell_all+"</i>"+gold2+"</span></a>";
										            }
										            content = '<div class="dingyue"><h3 class="lang"><i>这是VIP章节</i>需要订阅后才能阅读</h3>'+content+'</div>';
										        }else{
    										        for(i in chapterData.content){
    										            content += '<p>'+chapterData.content[i]+'</p>';
    										        }
										        }
												var chapter = {
														id : chapterData.chapter_id,
														title:chapterData.chapter_name,
														vipStatus : chapterData.chapter_isvip,
														content : content,
														prevId : prev.chapter_id,
														nextId : next.chapter_id
												};
                                                //章节ID
                                                SetChapterId(chapterData.chapter_id);
                                                SetChapterPrveId(prev.chapter_id);
                                                SetChapterNextId(next.chapter_id);
                                                SetChapterName(chapterData.chapter_name);
                                                
												data.chapters[chapter.id] = chapter;
												data.book = {
														"tid": novelData.type_id,
														"nid": novelData.novel_id,
														"Name": novelData.novel_name,
														"authorName": novelData.novel_name
												};
												resolve({
														data: {
																bookInfo: data.book,
																chapterInfo: chapter
														}
												});
										} else {
												if (isShowLoading) {
														layer.open({content:'获取章节内容失败，请稍候再试',skin:'msg',time:2});
												}
												reject();
										}
								});
            }
        },
        setReadChapter: function(chapterId) {
            var self = this;
            var chapter = data.chapters[chapterId];
            if (!chapter || chapter.id === data.chapter.id) {
                return;
            }
            data.chapter = chapter;
            $("#chapterTitle").html(chapter.title);
            self.updateReadProgress();
            history.replaceState(null, null, data.chapterUrl.replace("chapterId", chapterId));
        }
    }
    Book.init();
		dingyue=function(type,nid,cid){
		    if( type==1 ){
		        typeName='本章';
		    }else if(type==2 ){
		        typeName='本月';
		    }else{
		        typeName='全本';
		    }
            if (confirm('确认要要订阅'+typeName+'吗？')==true){
    		    var url = book.subUrl;
    			$.post(url,{
    				nid:nid,
    				cid:cid,
    				auto:1
    			},function(data){
    				data = JSON.parse(data)
    				layer.open({content: data.msg,skin: 'msg',time: 2});
    				if(data.code==200){
    					location.reload()
    				}
    			});
            }else{ 
                return false; 
            } 
		};
});