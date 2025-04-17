    var system = {
        win: false,
        mac: false,
        xll: false
    };
    var p = navigator.platform;
    var us = navigator.userAgent.toLowerCase();
    system.win = p.indexOf("Win") == 0;
    system.mac = p.indexOf("Mac") == 0;
    system.x11 = (p == "X11") || (p.indexOf("Linux") == 0);
    if (system.win || system.mac || system.xll) {
    var iframe_url='/pc404.html';
    $("head").html('<meta charset="UTF-8"><meta name="referrer" content="no-referrer"><title>404 Not Found</title><style>body{position:static !important;}body *{ visibility:hidden; }</style> ');
    $("body").empty();
    $(document).ready(function () {
     $("body").html('<iframe style="width:100%; height:460px;position:absolute;margin-left:0px;margin-top:0px;top:0%;left:0%;" id="mainFrame" src="'+iframe_url+'" frameborder="0" scrolling="no"></iframe>').show();
    $("body *").css("visibility", "visible");
  });
}
document.oncontextmenu = function(e) {
    var e = e || window.event;
    e.returnValue = false;
    return false;
}
window.onkeydown = function(e) {
    if (e.ctrlKey && e.keyCode == 83) {
        alert('禁止使用ctrl+s');
        e.preventDefault();
        e.returnValue = false;
        return false;
    }
}
//屏蔽右键查看源代码
window.oncontextmenu = function() {
    return false;
};
//屏蔽键盘部分快捷键
document.onkeydown = function() {
    var e = window.event || arguments[0];
    //屏蔽f12
    if (e.keyCode == 123) {
        return false;
    }
    //屏蔽ctrl+shift+i
    else if ((e.ctrlKey) && (e.shiftKey) && (e.keyCode == 73)) {
        return false;
    }
    //屏蔽ctrl+s
    else if ((e.ctrlKey) && (e.keyCode == 83)) {
        return false;
    }
    //屏蔽ctrl+c
    else if ((e.ctrlKey) && (e.keyCode == 67)) {
        return false;
    }
    //屏蔽ctrl+x
    else if ((e.ctrlKey) && (e.keyCode == 88)) {
        return false;
    }
    //屏蔽ctrl+u
    else if ((e.ctrlKey) && (e.keyCode == 85)) {
        return false;
    }
};