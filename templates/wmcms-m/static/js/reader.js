var noop = function() {};
if( !getCookie ){
    function getCookie(name) {
    	var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
    	arr = document.cookie.match(reg);
    	if (arr){
    		return arr[2];
    	}else {
    		return null;
    	}
    }
}
if( !setCookie ){
    function setCookie(name, value,expires, path, domain ) {
    	if(expires){
    		expires = new Date(+new Date() + expires);
    	}else{
    		expires = new Date(+new Date() + 60*60*24*365*1000);
    	}
    	var tempcookie = name + '=' + escape(value) +
    		((expires) ? '; expires=' + expires.toGMTString() : '') +
    		((path) ? '; path=' + path : '') +
    		((domain) ? '; domain=' + domain : '');
    	if(tempcookie.length < 4096) {
    		document.cookie = tempcookie;
    	}
    }
}

function SwipeReader(options) {
    if (!options) {
        options = {};
    }
    this.$wrapper = $(options.wrapperSelector);
    this.$scroller = function() {
        return this.$wrapper.find(options.scrollerSelector);
    }.bind(this);
    this.$pager = $(options.pagerSelector);
    this.onCenterClick = options.onCenterClick || noop;
    this.onSwipePrev = options.onSwipePrev || noop;
    this.onSwipeNext = options.onSwipeNext || noop;
    this.onWillSwipeNext = options.onWillSwipeNext || noop;
    this.gap = options.gap || 16;
    this.pager = {
        cur: 1,
        max: 1
    };
    this.enabled = false;
    this.isPreloading = false;
    this.initEvents();
    this.initPager();
}

SwipeReader.prototype = {
    initEvents: function() {
        var self = this;
        var $wrapper = self.$wrapper;
        var $scroller = self.$scroller;
        var isMoving = false;
        var firstTouch;
        var touch = {};
        var startLeft = 0;
        $wrapper.on({
            touchstart: function(event) {
                if (!self.enabled) {
                    return;
                }
                var target = event.target || {};
                if (/^(?:a|input|label|i|use|svg)$/i.test(target.tagName) || $(target).parents("a").length) {
                    return;
                }
                isMoving = true;
                firstTouch = event.touches[0] || event;
                touch.x1 = firstTouch.pageX;
                touch.y1 = firstTouch.pageY;
                startLeft = $scroller().data("left") || 0;
            },
            touchmove: function(event) {
                if (!isMoving) {
                    return;
                }
                event.preventDefault();
                firstTouch = event.touches[0] || event;
                touch.x2 = firstTouch.pageX;
                touch.y2 = firstTouch.pageY;
                var scrollLeft = startLeft - (touch.x2 - touch.x1);
                $scroller().css({
                    "-webkit-transform": "translateX(" + -scrollLeft + "px)",
                    transform: "translateX(" + -scrollLeft + "px)"
                });
            },
            touchend: function() {
                if (!isMoving) {
                    return;
                }
                isMoving = false;
                var distanceX = touch.x2 - touch.x1;
                var distanceMin = self.gap;
                if (!touch.x2 || Math.abs(distanceX) < distanceMin) {
                    var winWidth = window.innerWidth;
                    var range = [ winWidth / 3, winWidth * 2 / 3 ];
                    if (touch.x1 <= range[0]) {
                        self.turnLeft();
                    } else if (touch.x1 >= range[1]) {
                        self.turnRight();
                    } else {
                        self.scrollTo(startLeft);
                        self.onCenterClick();
                    }
                } else if (distanceX > distanceMin) {
                    self.turnLeft();
                } else if (distanceX < distanceMin) {
                    self.turnRight();
                } else {
                    self.scrollTo(startLeft);
                }
                firstTouch = null;
                touch = {};
                startLeft = 0;
            }
        });
    },
    initPager: function() {
        this.resetPager();
        this.refreshPager();
    },
    resetPager: function() {
        var elScroller = this.$scroller()[0];
        var pager = this.pager;
        var gap = this.gap;
        pager.cur = 1;
        pager.max = Math.round((elScroller.scrollWidth + gap) / (elScroller.clientWidth + gap));
    },
    refreshPager: function() {
        var pager = this.pager;
        this.$pager.html([ pager.cur, pager.max ].join("/"));
    },
    scrollTo: function(x) {
        var $scroller = this.$scroller();
        var left = $scroller.data("left") || 0;
        var step = function() {
            left += (x - left) * .5;
            if (Math.abs(x - left) < 1) {
                $scroller.data("left", x).css({
                    "-webkit-transform": "translateX(" + -x + "px)",
                    transform: "translateX(" + -x + "px)"
                });
            } else {
                $scroller.css({
                    "-webkit-transform": "translateX(" + -left + "px)",
                    transform: "translateX(" + -left + "px)"
                });
                if (window.requestAnimationFrame) {
                    requestAnimationFrame(step);
                } else {
                    setTimeout(step, 17);
                }
            }
        };
        step();
    },
    turnTo: function(current) {
        var pager = this.pager;
        var gap = this.gap;
        var width = this.$scroller().width();
        pager.cur = current;
        this.scrollTo((current - 1) * (width + gap));
        this.refreshPager();
    },
    turnLeft: function() {
        var self = this;
        var pager = self.pager;
        var current = pager.cur - 1;
        if (current < 1) {
            pager.cur = 1;
            self.enabled = false;
            self.onSwipePrev(function() {
                var $scroller = self.$scroller();
                var maxLeft = $scroller[0].scrollWidth - $scroller.width();
                self.enabled = true;
                self.$scroller().data("left", maxLeft).css({
                    "-webkit-transform": "translateX(" + -maxLeft + "px)",
                    transform: "translateX(" + -maxLeft + "px)"
                });
                self.initPager();
                pager.cur = pager.max;
                self.refreshPager();
            }, function() {
                self.enabled = true;
            });
        } else {
            this.turnTo(current);
        }
    },
    turnRight: function() {
        var self = this;
        var pager = self.pager;
        var current = pager.cur + 1;
        if (current > pager.max) {
            pager.cur = 1;
            self.enabled = false;
            self.onSwipeNext(function() {
                self.enabled = true;
                self.$scroller().css({
                    "-webkit-transform": "",
                    transform: ""
                });
                self.initPager();
            }, function() {
                self.enabled = true;
            });
        } else {
            self.turnTo(current);
            if (current > pager.max - 3 && !self.isPreloading) {
                self.isPreloading = true;
                self.onWillSwipeNext(function() {
                    self.isPreloading = false;
                }, function() {
                    self.isPreloading = false;
                });
            }
        }
    },
    enable: function() {
        this.enabled = true;
        this.refresh();
    },
    disable: function() {
        this.enabled = false;
        this.$scroller().css({
            "-webkit-transform": "",
            transform: ""
        });
    },
    refresh: function() {
        this.initPager();
    },
    restart: function() {
        this.$scroller().css({
            "-webkit-transform": "",
            transform: ""
        });
        this.initPager();
    }
};

function scaleStepable(value, arrValueStep, arrScaleStep) {
    if (arrValueStep.length !== arrScaleStep.length) {
        return value;
    }
    var scaleedValue = value;
    var valueStepIndex = arrValueStep.length;
    while (valueStepIndex--) {
        if (value > arrValueStep[valueStepIndex]) {
            scaleedValue = (value - arrValueStep[valueStepIndex]) * arrScaleStep[valueStepIndex];
            for (var i = valueStepIndex; i > 0; i--) {
                scaleedValue += (arrValueStep[i] - arrValueStep[i - 1]) * arrScaleStep[i - 1];
            }
            scaleedValue += arrValueStep[0] * 1;
            break;
        }
    }
    return scaleedValue;
}

function ScrollReader(options) {
    if (!options) {
        options = {};
    }
    this.$wrapper = $(options.wrapperSelector);
    this.$scroller = $(options.scrollerSelector || window);
    this.$prev = $(options.prevSelector);
    this.$next = $(options.nextSelector);
    this.$nextTrigger = $(options.nextTriggerSelector);
    this.$mark = $(options.markSelector);
    this.onPrevClick = options.onPrevClick || noop;
    this.onCenterClick = options.onCenterClick || noop;
    this.onNextClick = options.onNextClick || noop;
    this.onScroll = options.onScroll || noop;
    this.onScrollPrev = options.onScrollPrev || noop;
    this.onScrollNext = options.onScrollNext || noop;
    this.onWillScrollNext = options.onWillScrollNext || noop;
    this.triggerMinHeight = options.triggerMinHeight || 58;
    this.enabled = false;
    this.isPreloading = false;
    this.initEvents();
}

ScrollReader.prototype = {
    initEvents: function() {
        var self = this;
        var $wrapper = self.$wrapper;
        var $scroller = self.$scroller;
        var $prev = self.$prev;
        var $next = self.$next;
        var $nextTrigger = self.$nextTrigger;
        var isMoving = false;
        var firstTouch;
        var touch = {};
        var pull = {};
        var jump;
        $wrapper.on({
            touchstart: function(event) {
                if (!self.enabled) {
                    return;
                }
                var target = event.target || {};
                if (/^(?:a|input|label|i|use|svg)$/i.test(target.tagName) || $(target).parents("a").length) {
                    return;
                }
                isMoving = true;
                firstTouch = event.touches[0] || event;
                touch.x1 = firstTouch.pageX;
                touch.y1 = firstTouch.pageY;
            },
            touchmove: function(event) {
                if (!isMoving) {
                    return;
                }
                firstTouch = event.touches[0] || event;
                touch.x2 = firstTouch.pageX;
                touch.y2 = firstTouch.pageY;
                var dy = touch.y2 - touch.y1;
                var scrollTop = $scroller.scrollTop();
                var maxScrollTop = $wrapper.offset().top + $wrapper.height() - $scroller.height();
                if (scrollTop <= 0 && dy > 0) {
                    event.preventDefault();
                    if (pull.type !== "down") {
                        pull.type = "down";
                        pull.y1 = touch.y2;
                    }
                    pull.y2 = touch.y2;
                    pull.dy = pull.y2 - pull.y1;
                    var limitDownHeight = 52;
                    var downOffsetTop = Math.abs(parseInt($prev.css("margin-top"), 10));
                    if (pull.dy - limitDownHeight > 0) {
                        jump = -1;
                        $prev.css({
                            "border-bottom-width": scaleStepable(pull.dy - limitDownHeight, [ 20, 40, 60, 80, 100 ], [ .5, .4, .3, .2, .1 ]),
                            height: limitDownHeight,
                            transition: "none"
                        });
                    } else {
                        jump = 0;
                        $prev.css({
                            "border-bottom-width": Math.max(downOffsetTop - pull.dy, 0),
                            height: pull.dy,
                            transition: "none"
                        });
                    }
                } else if (scrollTop >= maxScrollTop && dy < 0) {
                    event.preventDefault();
                    if (pull.type !== "up") {
                        pull.type = "up";
                        pull.y1 = touch.y2;
                    }
                    pull.y2 = touch.y2;
                    pull.dy = pull.y1 - pull.y2;
                    var limitUpHeight = 48;
                    if (pull.dy > 0) {
                        if (pull.dy > limitUpHeight) {
                            jump = 1;
                        } else {
                            jump = 0;
                        }
                        $next.prev().css({
                            position: "relative",
                            top: -scaleStepable(pull.dy, [ 20, 40, 60, 80, 100 ], [ .5, .4, .3, .2, .1 ])
                        });
                    } else {
                        jump = 0;
                        $next.prev().css({
                            position: "relative",
                            top: 0
                        });
                    }
                } else {
                    self.checkWillScrollNext();
                }
            },
            touchend: function(e) {
                if (!isMoving) {
                    return;
                }
                isMoving = false;
                var restore = function() {
                    $prev.css({
                        "border-bottom-width": "",
                        height: "",
                        transition: ""
                    });
                    $next.prev().css({
                        position: "",
                        top: "",
                        "-webkit-transition": "",
                        transition: ""
                    });
                };
                if (!touch.y2) {
                    self.handlerClick(touch.x1, touch.y1, e);
                } else if (jump === -1) {
                    $prev.css({
                        "border-bottom-width": 0,
                        transition: ""
                    });
                    self.enabled = false;
                    self.onScrollPrev(function() {
                        restore();
                        var maxScrollTop = $wrapper.offset().top + $wrapper.height() - $scroller.height();
                        $scroller.scrollTop(maxScrollTop - 58 - 48);
                        self.enabled = true;
                    }, function() {
                        restore();
                        self.enabled = true;
                    });
                } else if (jump === 1) {
                    $next.prev().css({
                        position: "relative",
                        top: 0,
                        "-webkit-transition": "top .25s",
                        transition: "top .25s"
                    });
                    self.enabled = false;
                    self.onScrollNext(function() {
                        restore();
                        self.enabled = true;
                    }, function() {
                        restore();
                        self.enabled = true;
                    });
                } else if (pull.type) {
                    restore();
                }
                firstTouch = null;
                touch = {};
                pull = {};
                jump = null;
            }
        });
        $scroller.on("scroll", function() {
            self.onScroll($scroller.scrollTop());
        });
        $nextTrigger.on("click", function() {
            layer_login=layer.open({type: 2,content: '加载中'});
            self.enabled = false;
            self.onScrollNext(function() {
                layer.close(layer_login);
                self.enabled = true;
            }, function() {
                layer.close(layer_login);
                self.enabled = true;
            });
        });
    },
    enable: function() {
        this.enabled = true;
        this.refresh();
    },
    disable: function() {
        this.enabled = false;
        this.$scroller.scrollTop(0);
    },
    refresh: function() {},
    restart: function() {
        this.$scroller.scrollTop(0);
    },
    handlerClick: function(x, y, e) {
        var self = this;
        var $wrapper = self.$wrapper;
        var $scroller = self.$scroller;
        var height = $scroller.height();
        var scrollTop = $scroller.scrollTop();
        var range = [ scrollTop + height / 3, scrollTop + height * 2 / 3 ];
        var oneScreenContentHeight = height - 60;
        if (y <= range[0]) {
            self.adjusthMark(Math.max(scrollTop + 44, 0));
            self.onPrevClick(e);
            self.scrollTo(Math.max(scrollTop - oneScreenContentHeight, 0));
        } else if (y >= range[1]) {
            var maxScrollTop = $wrapper.offset().top + $wrapper.height() - $scroller.height();
            self.adjusthMark(Math.min(scrollTop + height, maxScrollTop));
            self.onNextClick(e);
            self.scrollTo(Math.min(scrollTop + oneScreenContentHeight, maxScrollTop));
            self.checkWillScrollNext();
        } else {
            self.onCenterClick();
        }
    },
    checkWillScrollNext: function() {
        var self = this;
        var $wrapper = self.$wrapper;
        var $scroller = self.$scroller;
        var scrollTop = $scroller.scrollTop();
        var maxScrollTop = $wrapper.offset().top + $wrapper.height() - $scroller.height();
        if (scrollTop >= Math.min(maxScrollTop * .8, maxScrollTop - 200) && !self.isPreloading) {
            self.isPreloading = true;
            self.onWillScrollNext(function() {
                self.isPreloading = false;
            }, function() {
                self.isPreloading = false;
            });
        }
    },
    scrollTo: function(y, callback) {
        var $scroller = this.$scroller;
        var scrollTop = $scroller.scrollTop();
        if (y < 0) {
            y = 0;
        }
        var step = function() {
            scrollTop += (y - scrollTop) * .2;
            if (Math.abs(y - scrollTop) < 1) {
                $scroller.scrollTop(y);
                if ($.isFunction(callback)) {
                    callback();
                }
            } else {
                $scroller.scrollTop(scrollTop);
                if (window.requestAnimationFrame) {
                    requestAnimationFrame(step);
                } else {
                    setTimeout(step, 17);
                }
            }
        };
        step();
    },
    adjusthMark: function(y) {
        var self = this;
        var $wapper = self.$wrapper;
        var $mark = self.$mark;
        if (!$mark.data("init")) {
            $mark.data("init", true);
            $wapper.on("touchstart", function() {
                if (!self.enabled) {
                    return;
                }
                clearTimeout($mark.timer);
                $mark.css("opacity", 0);
            });
        }
        $mark.css({
            opacity: 1,
            top: y
        });
        $mark.timer = setTimeout(function() {
            $mark.css("opacity", 0);
        }, 1500);
    }
};


var ACTIVE = "active", ENABLED = "enabled";
var Toggle = function(selector, options) {
    var self = this;
    if (!selector) {
        return self;
    }
    if ($.isFunction(options)) {
        options = {
            callback: options
        };
    }
    var defaults = {
        mode: "visible",
        container: $("body"),
        callback: function() {}
    };
    var params = $.extend({}, defaults, options || {});
    self.callback = params.callback;
    self.mode = params.mode;
    if (!selector.size && $.isArray(selector)) {
        selector = selector.join();
    }
    if (typeof selector == "string") {
        params.container.on("click", selector, function(event) {
            self.toggle($(this), event);
        });
    } else if (selector.length) {
        selector.on("click", function(event) {
            self.toggle($(this), event);
        });
    }
    self.aria(selector);
};
Toggle.prototype.aria = function(selector) {
    var self = this;
    if (self.mode == "visible" && typeof selector == "string") {
        $(selector).each(function() {
            var trigger = $(this);
            trigger.attr({
                role: "menuitem",
                "aria-expanded": trigger.hasClass(ACTIVE)
            });
        });
    }
};
Toggle.prototype.toggle = function(trigger, event) {
    var self = this;
    var target = trigger;
    var isActive;
    if (self.mode == "visible") {
        target = $("#" + trigger.attr("data-rel"));
        isActive = trigger.hasClass(ACTIVE);
        if (isActive) {
            trigger.removeClass(ACTIVE).attr("aria-expanded", "false");
            target.removeClass(ACTIVE);
        } else {
            trigger.addClass(ACTIVE).attr("aria-expanded", "true");
            target.addClass(ACTIVE);
        }
    } else if (self.mode == "more") {
        isActive = typeof trigger.attr("open") == "string";
        if (isActive) {
            trigger.removeAttr("open");
        } else {
            trigger.attr("open", "");
        }
    }
    self.callback.call(trigger, trigger, target, isActive, event);
};


function isLocalStorageSupported() {
    var testKey = "test";
    try {
        localStorage.setItem(testKey, testKey);
        localStorage.removeItem(testKey);
        return true;
    } catch (ex) {
        return false;
    }
}

var isSupported = isLocalStorageSupported();

var Storage = {
    get : function(key) {
        if (!isSupported) {
            return null;
        }
        try {
            return JSON.parse(localStorage.getItem(key));
        } catch (ex) {
            return null;
        }
    },
    set : function(key, value) {
        if (!isSupported) {
            return null;
        }
        try {
            return localStorage.setItem(key, JSON.stringify(value));
        } catch (ex) {
            return null;
        }
    },
    remove : function(key) {
        if (!isSupported) {
            return null;
        }
        return localStorage.removeItem(key);
    },
    clear : function() {
        if (!isSupported) {
            return null;
        }
        return localStorage.clear();
    }
};


(function ($) {
    "use strict";
    var $ = window.Zepto;
    var ACTIVE = "active";
    var Aside;
    $.fn.aside = function(options) {
        if (!this.$aside) {
            this.$aside = new Aside(this, options);
        }
    };
    Aside = function(el, options) {
        var defaults = {
            scrollable: ".scrollable",
            onInit: function() {},
            onShow: function() {},
            onHide: function() {},
            onScroll: function() {}
        };
        var elTrigger = $(el);
        var elBody = $(document.body);
        if (elTrigger.length === 0) return;
        var params = $.extend({}, defaults, options);
        var idAside = el.data("rel");
        var elAside = $("#" + idAside);
        var isActive = function() {
            return location.hash.replace("#&", "") === idAside;
        };
        var self = this;
        self.el = {
            trigger: elTrigger,
            aside: elAside,
            overlay: elAside.find(".aside-overlay"),
            content: elAside.find(".aside-content")
        };
        self.callback = {
            init: params.onInit,
            show: params.onShow,
            hide: params.onHide,
            scroll: params.onScroll
        };
        self.title = elTrigger.attr("title");
        elTrigger.attr({
            role: "button",
            "aria-haspopup": "true"
        });
        elTrigger.on("click", function(event) {
            event.preventDefault();
            self.show(this.href);
        });
        elAside.prependTo(elBody);
        self.el.overlay.on("click", function() {
            self.hide();
        }).on("touchmove", function(event) {
            event.preventDefault();
        }).attr({
            role: "button"
        });
        self.scrollable(params.scrollable);
        self.swipeOut(params.scrollable);
        if (isActive()) {
            self.show();
        }
        window.addEventListener("popstate", function() {
            if (isActive()) {
                self.show();
            } else {
                self.hide(true);
            }
        });
    };
    Aside.prototype.show = function(hash) {
        var self = this;
        var el = self.el, callback = self.callback;
        var elTrigger = el.trigger;
        var elAside = el.aside;
        if (elAside.hasClass(ACTIVE)) {
            return;
        }
        elAside.addClass(ACTIVE);
        el.overlay.attr("title", "点击关闭浮层");
        elTrigger.attr("title", "浮层已显示");
        if (!elTrigger.data("init")) {
            elTrigger.data("init", true);
            callback.init.call(self);
        }
        $("html").addClass("noscroll");
        callback.show.call(self);
        if (history.pushState && hash) {
            history.pushState(null, null, hash);
        }
    };
    Aside.prototype.hide = function(isFromPopState) {
        var self = this;
        var el = self.el, callback = self.callback;
        var elTrigger = el.trigger;
        var elAside = el.aside;
        $("html").removeClass("noscroll");
        if (!elAside.hasClass(ACTIVE)) {
            return;
        }
        elAside.removeClass(ACTIVE);
        el.overlay.attr("title", "浮层已关闭");
        elTrigger.attr("title", self.title);
        callback.hide.call(self);
        if (history.pushState && !isFromPopState) {
            var href = location.href.split("#")[0];
            history.go(-1);
            setTimeout(function() {
                history.replaceState(null, null, href);
            }, 0);
        }
    };
    Aside.prototype.scrollable = function(selectorScrollable) {
        var self = this;
        var elAside = self.el.aside;
        if (!selectorScrollable || elAside.data("isBindScroll")) {
            return self;
        }
        var isSBBrowser = /mx\d.*mqqbrowser/i.test(navigator.userAgent);
        var data = {
            posY: 0,
            maxscroll: 0
        };
        elAside.on("touchstart", selectorScrollable, function(event) {
            var events = event.touches[0] || event;
            data.elScroll = $(this);
            data.posY = events.pageY;
            data.scrollY = data.elScroll.scrollTop();
            data.maxscroll = this.scrollHeight - this.clientHeight;
        });
        elAside.on("touchmove", function(event) {
            if (data.maxscroll <= 0 || isSBBrowser) {
                event.preventDefault();
            }
            var elScroll = data.elScroll;
            var scrollTop = elScroll.scrollTop();
            var events = event.touches[0] || event;
            var distanceY = events.pageY - data.posY;
            if (isSBBrowser) {
                elScroll.scrollTop(data.scrollY - distanceY);
                elScroll.trigger("scroll");
                return;
            }
            if (distanceY > 0 && scrollTop == 0) {
                event.preventDefault();
                return;
            }
            if (distanceY < 0 && scrollTop + 1 >= data.maxscroll) {
                event.preventDefault();
                return;
            }
            self.callback.scroll.call(self, elScroll);
        });
        elAside.data("isBindScroll", true);
    };
    Aside.prototype.swipeOut = function(selectorScrollable) {
        var self = this;
        var elAside = self.el.aside;
        var elContent = self.el.content;
        var data = {};
        elContent.on("touchstart", function(event) {
            var events = event.touches[0] || event;
            data.posX = events.pageX;
            data.distanceX = 0;
            if (data.transition) {
                return;
            }
            elContent.css({
                "-webkit-transition": "none",
                transition: "none"
            });
            var target = event.target, elTarget = $(target);
            var elScroll = elTarget.parents(selectorScrollable);
            if (elTarget.is(selectorScrollable)) {
                elScroll = elTarget;
            }
            if (elScroll.length && elScroll[0].scrollHeight > elScroll[0].clientHeight) {
                data.moving = false;
                return;
            }
            data.moving = true;
        });
        elAside.on("touchmove", function(event) {
            var events = event.touches[0] || event;
            var distanceX = events.pageX - data.posX;
            data.distanceX = distanceX;
            if (distanceX < 0) {
                distanceX = 0;
            }
            if (!data.moving) {
                return;
            }
            elContent.css({
                "-webkit-transform": "translateX(" + distanceX + "px)",
                transform: "translateX(" + distanceX + "px)"
            });
            event.preventDefault();
        });
        elAside.on("touchend", function() {
            elContent.css({
                "-webkit-transition": "",
                transition: ""
            });
            if (!data.moving) {
                return;
            }
            data.transition = true;
            if (data.distanceX > 100) {
                self.hide();
                elContent.css({
                    "-webkit-transform": "translateX(100%)",
                    transform: "translateX(100%)"
                });
            } else {
                elContent.css({
                    "-webkit-transform": "translateX(0)",
                    transform: "translateX(0)"
                });
            }
            setTimeout(function() {
                elContent.css({
                    "-webkit-transform": "",
                    transform: ""
                });
                delete data.transition;
            }, 151);
            data.moving = false;
        });
        return self;
    };
    return Aside;
})(Zepto);

var CL = "range", rangePrefix = CL + "-";
$.fn.range = function(options) {
    return $(this).each(function() {
        if (this.$range) {
            this.$range = new Range($(this), options);
        }
    });
};
var Range = function(el, options) {
    var self = this;
    var defaults = {
        container: $("body"),
        shadow: false,
        buttons: [ "", "" ],
        onChangeEnd: function() {},
        tips: function(value) {
            return value;
        }
    };
    var params = $.extend({}, defaults, options || {});
    var min = el.attr("min") || 0, max = el.attr("max") || 100, step = el.attr("step") || 1;
    var container = $("<div></div>").attr("class", el.attr("class")).addClass(CL);
    var track = $("<div></div>").addClass(rangePrefix + "track");
    var thumb = $("<a></a>").addClass(rangePrefix + "thumb").attr({
        role: "slider",
        "aria-valuenow": el.val(),
        "aria-valuemax": max,
        "aria-valuemin": min
    });
    var shadow = $();
    if (params.shadow == true) {
        shadow = $("<a></a>").addClass(rangePrefix + "shadow").attr("title", "之前位置");
    }
    el.before(container);
    if (container.width() == 0) {
        container.width(el.width());
    }
    track.append(thumb);
    container.append(track).prepend(shadow);
    container.on("click", function(event) {
        var target = event && event.target;
        if (!target) {
            return;
        }
        if (shadow.length && target == shadow[0]) {
            var value = $(target).attr("data-value"), valueEl = el.val();
            if (value && valueEl != value) {
                self.value(value);
                params.onChangeEnd.call(el, self);
            }
        } else if (target != thumb[0]) {
            var distance = event.clientX - (thumb.offset().left - $(window).scrollLeft()) - thumb.width() / 2;
            self.value(el.val() * 1 + (max - min) * distance / $(this).width());
            params.onChangeEnd.call(el, self);
        }
    });
    var posThumb = {
        distance: 0
    };
    thumb.on("touchstart", function(event) {
        var events = event.touches[0] || event;
        posThumb.x = events.pageX;
        posThumb.value = el.val() * 1;
        posThumb.distance = 0;
        var tips = posThumb.value;
        if ($.isFunction(params.tips)) {
            tips = params.tips.call(el, posThumb.value);
        }
        thumb.attr("data-tips", tips).attr("aria-valuenow", tips).addClass("active");
    });
    params.container.on({
        touchmove: function(event) {
            var events = event.touches[0] || event;
            if (typeof posThumb.x == "number") {
                var distance = events.pageX - posThumb.x;
                posThumb.distance = distance;
                self.value(posThumb.value + (max - min) * distance / container.width());
                var tips = el.val();
                if ($.isFunction(params.tips)) {
                    tips = params.tips.call(el, tips);
                }
                thumb.attr("data-tips", tips).attr("aria-valuenow", tips);
                event.preventDefault();
            }
        },
        touchend: function() {
            if (Math.abs(posThumb.distance) > 0) {
                params.onChangeEnd.call(el, self);
            }
            posThumb.x = null;
            posThumb.value = null;
            thumb.removeClass("active");
            posThumb.distance = 0;
        }
    });
    if ($.isArray(params.buttons)) {
        params.buttons.forEach(function(button, index) {
            if (!button) {
                return;
            }
            if (typeof button == "string") {
                button = $(button);
            }
            if (button.length) {
                button.on("click", function() {
                    var indexBtn = +$(this).data("index");
                    var max = el.attr("max");
                    var min = el.attr("min") || "1";
                    var step = el.attr("step") || "1";
                    var value = el.val(), newValue = value;
                    if (indexBtn == 0) {
                        newValue = value - step;
                        if (newValue < min) {
                            newValue = min;
                        }
                    } else if (indexBtn == 1) {
                        newValue = value * 1 + step * 1;
                        if (newValue > max) {
                            newValue = max;
                        }
                    }
                    if (newValue !== value) {
                        self.value(newValue);
                        params.onChangeEnd.call(el, self);
                    }
                }).data("index", index);
                button.attr("role", "button");
            }
        });
    }
    this.num = {
        min: +min,
        max: +max,
        step: +step
    };
    this.el = {
        input: el,
        container: container,
        track: track,
        thumb: thumb,
        shadow: shadow
    };
    this.callback = {
        changeEnd: params.onChangeEnd.bind(el, self)
    };
    this.obj = {};
    this.value();
    this.shadow();
    return this;
};
Range.prototype.value = function(value) {
    var input = this.el.input, oldvalue = input.val();
    var max = this.num.max, min = this.num.min, step = this.num.step;
    if (!value) {
        oldvalue = value;
        value = $.trim(input.val());
    }
    if (value > max || max - value < step / 2) {
        value = max;
    } else if (value == "" || value < min || value - min < step / 2) {
        value = min;
    } else {
        value = min + Math.round((value - min) / step) * step;
    }
    input.val(value);
    this.position();
    if (value != oldvalue) {
        input.trigger("change");
    }
    return this;
};
Range.prototype.position = function() {
    var input = this.el.input, value = input.val();
    var max = this.num.max, min = this.num.min, step = this.num.step;
    this.el.track.css("borderLeftWidth", this.el.container.width() * (value - min) / (max - min));
    return this;
};
Range.prototype.shadow = function() {
    var el = this.el;
    var track = el.track, shadow = el.shadow;
    if (shadow.length) {
        shadow.css("left", track.css("borderLeftWidth")).attr("data-value", el.input.val());
    }
    return this;
};

function debounce(func, wait) {
    var context;
    var args;
    var result;
    var previous;
    var timer;
    var later = function() {
        var diff = Date.now() - previous;
        if (diff < wait && diff > 0) {
            timer = setTimeout(later, wait - diff);
        } else {
            timer = null;
            result = func.apply(context, args);
            if (!timer) {
                context = args = null;
            }
        }
    };
    return function() {
        context = this;
        args = arguments;
        previous = Date.now();
        if (!timer) {
            timer = setTimeout(later, wait);
        }
        return result;
    };
};
   
   

//阅读模式设置
function switchReadType(val){
	if( !val || val==null ){ return false;}
	//听书
	if( val=='voice' ){
		//居中听书div
	    $('.voice-panel').removeClass('hidden');
		var readTypeTop = (window.screen.availHeight-$('.voice-panel').height()+40)/2;
		$('.voice-panel').css({top:readTypeTop});
	    $('#voiceMask').removeClass('hidden');
	}else{ //文字阅读
	    resetPlayer();
	    $('.voice-panel').addClass('hidden');
	    $('#voiceMask').addClass('hidden');
	    
	}
}
//切换语音合成渠道
function switchVoiceChannel(val){
	if( val ){
    	$('.voice-channel-box span').removeClass('act');
    	$('.voice-channel-box').find('[data-val="'+val+'"]').addClass('act');
    	voiceChannel = val;
    	setCookie('voice_channel',val);
	}
	var voicetList = voicetChannelList[$('.voice-channel-box .act').data('val')];
	var voicetHtml = '';
	for(o in voicetList){
	    voicetHtml += '<option value="'+voicetList[o].voicet_ids+'">'+voicetList[o].voicet_name+'</option>';
	}
	$('.voice-voicet-box select').html(voicetHtml);
}
//切换语速
function switchVoiceSpeed(val){
	if( !val && val!='0' ){ return false;}
	$('.voice-speed-box span').removeClass('act');
	$('.voice-speed-box').find('[data-val="'+val+'"]').addClass('act');
	voiceSpeed = val;
	setCookie('voice_speed',val);
}
//切换自动播放下一章
function switchVoiceAutoNext(val){
	if( val == 'null' || val==null ){ return false; }
	if(val==false || val == 'false'){
		$('#j-voiceAutoNextBtn').removeClass('on');
		$('#j-voiceAutoNextBtn').addClass('off');
		$('#j-voiceAutoNextSwitch').html('关闭');
	}else{
		$('#j-voiceAutoNextBtn').removeClass('off');
		$('#j-voiceAutoNextBtn').addClass('on');
		$('#j-voiceAutoNextSwitch').html('开启');
	}
	voiceAutoNext = val=='true'||val==true?true:false;
	setCookie('voice_auto_next',val);
}
//重置播放器
function resetPlayer(){
	$('.voice-playerBtn').removeClass('hidden');
	$('.voice-player').addClass('hidden');
	$('.voice-playerLoading').addClass('hidden');
	if( player ){
	    player.pause();
	    player.destroy();
	}
}
//播放完毕事件
function playerEnd(){
    if( voiceAutoNext == true ){
        onloadVoice(chapterNextId);
    }
}
//初始化语音合成
function onloadApi(){
    $.ajax({
		type:"POST",url:ttsConfigUrl,dataType:"json",
		success:function(result){
			if(result.code==200){
			    var channelHtml = '';
			    for(o in result.data){
			        var act = '';
			        if( o == 0){
			            act = 'act';
			        }
			        channelHtml += '<span data-val="'+result.data[o].api_name+'" class="voice-channel '+act+'">'+result.data[o].api_ctitle+'</span>';
			        voicetChannelList[result.data[o].api_name] = result.data[o].list;
			    }
			    $('.voice-channel-box').append(channelHtml);
			    switchVoiceChannel(getCookie('voice_channel'));
			}
		}
	});
}
//加载语音合成内容
function onloadVoice(cid){
    if( cid == 0 ){
        layer.open({content:'没有了',skin:'msg',time:2});
    }else if( playerLoading == false ){
        playerLoading = true;
        $('.aplayer').prepend('<div class="playerMask"></div>');
        $.ajax({
    		type:"POST",dataType:"json",url:getChapterUrl,data:{'cid':cid,"format":1,"tts":1},
    		success:function(result){
    		    playerLoading = false;
    		    $('.playerMask').remove();
    		    if(result.code=='200'){
    		        //书籍基本信息
    		        chapterId = result.data.chapter.chapter_id;
    		        chapterName = result.data.chapter.chapter_name;
    		        //上下章节
    		        chapterPrveId = result.data.prev.chapter_id;
    		        chapterNextId = result.data.next.chapter_id;
    
    	            $('.voice-playerLoading').addClass('hidden');
    			    $('.voice-player').removeClass('hidden');
    			    var api = $('.voice-channel-box .act').data('val');
    			    var voicet = $('.voice-voicet-box select').val();
    			    var speed = $('.voice-speed-box .act').data('val');
    			    if( player ){
                	    player.destroy();
                	}
                    player = new APlayer({
                        element: document.getElementById('player'),
                        loop: false,autoplay: false,showlrc: false,
                        music: {
                            title: chapterName,author:novelAuthor,pic:novelCover,
                            url: ttsUrl+'&api='+api+'&voicet='+voicet+'&speed='+speed+'&tts='+result.data.tts
                        }
                    });
                    //播放完毕事件
                    player.on('ended', function () {
                        playerEnd();
                    });
                    player.play();
    		    }else{
                    layer.open({content:result.msg,skin:'msg',time:2});
    		    }
    		},
    	});
    }
}
function SetChapterId(val){
    chapterId = val;
}
function SetChapterPrveId(val){
    chapterId = val;
}
function SetChapterNextId(val){
    chapterId = val;
}
function SetChapterName(val){
    chapterName = val;
}
$(function(){
    switchVoiceSpeed(getCookie('voice_speed'));
    switchVoiceAutoNext(getCookie('voice_auto_next'));
    onloadApi();
    
    //开启听书面板
	$('#voiceBtn').click(function(){
	    switchReadType($(this).data('type'));
	});
	//关闭听书面板
	$('#j_closeVoice').click(function(){
	    switchReadType('word');
	});
    
	//左侧菜单-阅读模式-开始播放
	$('#j_playerInit').click(function(){
	    $('.aplayer').removeClass('hidden');
		$('.voice-playerBtn').addClass('hidden');
		$('.voice-playerLoading').removeClass('hidden');
		console.log(chapterId)
		onloadVoice(chapterId);
	});
	//左侧菜单-阅读模式-上一章
	$('.vioce-prve').click(function(){
	    onloadVoice(chapterPrveId);
	});
	//左侧菜单-阅读模式-下一章
	$('.vioce-next').click(function(){
	    onloadVoice(chapterNextId);
	});
	//左侧菜单-阅读模式-语音渠道切换
    $('.voice-channel-box').on("click","span", function () {
		switchVoiceChannel($(this).data('val'));
		resetPlayer();
	});
	//左侧菜单-阅读模式-发音人切换
    $('.voice-voicet-box').on("change","select", function () {
		resetPlayer();
	});
	//左侧菜单-阅读模式-切换语速
	$('.voice-speed-box span').click(function(){
		switchVoiceSpeed($(this).data('val'));
		resetPlayer();
	});
	//左侧菜单-阅读模式-是否自动播放下一章
	$('#j-voiceAutoNextBtn').click(function(){
		if($(this).attr('class')=='on'){
			switchVoiceAutoNext(false);
		}else{
			switchVoiceAutoNext(true);
		}
	});
});