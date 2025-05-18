$(function () {
	// 点击判断是否有下载按钮	
	$(".btn-download > a").on('click', function (event) {
		var obj = $(this).next(".urltype");
		var itit = $(this).find(".title").html();
		var ilen = obj.length;
		var itxt = obj.html();
		if (ilen) {
			event.preventDefault();
			$("#modalTitle").html(itit);
			$("#modalUrl").html(itxt);
			$("#myurl").addClass("blur");
			$("#modal").addClass("in");
			$("#backdrop").addClass("in");
			$("body").css("overflow", "hidden");

			$(".modal-url .btn-url").click(function () {
				var url = $(this).attr("href");
				var newWeb = window.open('_blank');
				newWeb.location = url;
				$("body").css("overflow", "");
				$("#myurl").removeClass("blur");
				$("#modal").removeClass("in");
				$("#backdrop").removeClass("in");
				return false;
			});
		}
	});

	// 关闭下载弹出
	$("#backdrop,#modalClose").on('click', function (event) {
		event.preventDefault();
		$("body").css("overflow", "");
		$("#myurl").removeClass("blur");
		$("#modal").removeClass("in");
		$("#backdrop").removeClass("in");
		return false;
	});

});