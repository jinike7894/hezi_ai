(function (c, d) {
  var e = document.documentElement || document.body,
    a = "orientationchange" in window ? "orientationchange" : "resize",
    b = function () {
      var f = e.clientWidth;
      e.style.fontSize = (f >= 750) ? "100px" : 100 * (f / 750) + "px"
      // e.style.fontSize = (f >= 580) ? "77px" : 77 * (f / 750) + "px"
    };
  b();
  c.addEventListener(a, b, false)
})(window);

//document.body.addEventListener('touchstart', function () { });

var os = function () {
  var ua = navigator.userAgent,
    isWindowsPhone = /(?:Windows Phone)/.test(ua),
    isSymbian = /(?:SymbianOS)/.test(ua) || isWindowsPhone,
    isAndroid = /(?:Android)/.test(ua),
    isFireFox = /(?:Firefox)/.test(ua),
    isChrome = /(?:Chrome|CriOS)/.test(ua),
    isTablet = /(?:iPad|PlayBook)/.test(ua) || (isAndroid && !/(?:Mobile)/.test(ua)) || (isFireFox && /(?:Tablet)/.test(ua)),
    isPhone = /(?:iPhone)/.test(ua) && !isTablet,
    isPc = !isPhone && !isAndroid && !isSymbian;
  return {
    isTablet: isTablet,
    isPhone: isPhone,
    isAndroid: isAndroid,
    isPc: isPc
  };
}();


//复制功能 domain
function copyIndexTcDomainfun1(event) {
	console.log('复制功能')
    /**
	 * 复制内容有空格
	const range = document.createRange();
	range.selectNode(document.querySelector(event));
	const selection = window.getSelection();
	if(selection.rangeCount > 0) selection.removeAllRanges();
	selection.addRange(range);
	document.execCommand("copy");
	*/
	
	let copyVal = document.querySelector(event).innerText
	const input = document.createElement("input"); // 直接构建input
  	input.value = copyVal.trim(); // 设置内容
  	document.body.appendChild(input); // 添加临时实例
  	input.select(); // 选择实例内容
  	document.execCommand("Copy"); // 执行复制
  	document.body.removeChild(input); // 删除临时实例
	
	layer.open({
		shadeClose: false,
        skin: "msg",
		content: "复制成功！",
		time: 3
	});
// 	const range = document.createRange();
// 	range.selectNode(document.querySelector(event));
// 	const selection = window.getSelection();
// 	if(selection.rangeCount > 0) selection.removeAllRanges();
// 	selection.addRange(range);
// 	document.execCommand("copy");
// 	layer.open({
// 		shadeClose: false,
//         skin: "msg",
// 		content: "复制成功！",
// 		time: 3
// 	});

}

