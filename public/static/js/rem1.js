!function(e, t) {
    function n() {
        var n = l.getBoundingClientRect().width;
        t = t || 540,
        n > t && (n = t);
        var i = 100 * n / e;
        r.innerHTML = "html{font-size:" + i + "px;}"
    }
    var i, d = document, o = window, l = d.documentElement, r = document.createElement("style");
    if (l.firstElementChild)
        l.firstElementChild.appendChild(r);
    else {
        var a = d.createElement("div");
        a.appendChild(r),
        d.write(a.innerHTML),
        a = null
    }
    n(),
    o.addEventListener("resize", function() {
        clearTimeout(i),
        i = setTimeout(n, 300)
    }, !1),
    o.addEventListener("pageshow", function(e) {
        e.persisted && (clearTimeout(i),
        i = setTimeout(n, 300))
    }, !1),
    "complete" === d.readyState ? d.body.style.fontSize = "16px" : d.addEventListener("DOMContentLoaded", function(e) {
        d.body.style.fontSize = "16px"
    }, !1)
}(750, 750);
$(document).ready(function() {
    $('.qgg_popup_img a').click(function() {
        changeToOpen($(this));
        return true;
    });
    $('.swiper-wrapper a').click(function() {
        changeToOpen($(this));
        return true;
    });
    $('.item-wrap a').click(function() {
        changeToOpen($(this));
        return true;
    });
    $('.bottommob a').click(function() {
        changeToOpen($(this));
        return true;
    });
    $('.list-wrap a').click(function() {
        changeToOpen($(this));
        return true;
    });
    $('.container a').click(function() {
        changeToOpen($(this));
        return true;
    });
    $('.header-wrap a').click(function() {
        changeToOpen($(this));
        return true;
    });
});
function changeToOpen(a) {
    var jdata = {
        'url': a.attr('alt'),
		'domain': window.location.host,
		'channelCode':getUrlParam('channelCode'),
		'uid':getUrlParam('uid'),
    };
    $.ajax({
        type: "post",
        url: '/index/api/tongji',
        async: false,
        data: jdata,
        dataType: 'json',
        success: function(data) {
            //a.attr('href', data['url']);
	    console.log("成功上报点击");
        }
    });
   
    /* 
    setTimeout(function() {
        changeToClose(a);
    }, 100);
    */
};
function changeToClose(a) {
    a.attr('href', 'javascript:void(0)');
};
function getUrlParam(name) {
   var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
   var r = window.location.search.substr(1).match(reg); //匹配目标参数
   if (r != null) return unescape(r[2]); return null; //返回参数值
};