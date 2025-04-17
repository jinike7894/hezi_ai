function copy(n) {
	var t = document.createElement("input");
	t.value = n || window.location.href,
	document.body.appendChild(t),
	t.select(),
	document.execCommand("Copy"),
	t.className = "oInput",
	t.style.display = "none",
	alert("复制成功,请去其它浏览器粘贴")
} !
function() {
	function n(n) {
		if (isNaN(n) || n < 0) return ! 1;
		o.removeClass("active"),
		o.eq(n).addClass("active")
	}
	function t(n) {
		var t, e, r, i = [];
		for (t = 0; t < n.length; t++)(e = n.charCodeAt(t)) < 128 ? i.push(e) : e < 2048 ? i.push(192 + (e >> 6 & 31), 128 + (63 & e)) : ((r = 55296 ^ e) >> 10 == 0 ? (e = (r << 10) + (56320 ^ n.charCodeAt(++t)) + 65536, i.push(240 + (e >> 18 & 7), 128 + (e >> 12 & 63))) : i.push(224 + (e >> 12 & 15)), i.push(128 + (e >> 6 & 63), 128 + (63 & e)));
		return i
	}
	function e(n) {
		var e, r, i, a = new Uint8Array(t(n)),
		o = 16 + (a.length + 8 >>> 6 << 4),
		n = new Uint8Array(o << 2);
		for (n.set(new Uint8Array(a.buffer)), n = new Uint32Array(n.buffer), i = new DataView(n.buffer), e = 0; e < o; e++) n[e] = i.getUint32(e << 2);
		n[a.length >> 2] |= 128 << 24 - 8 * (3 & a.length),
		n[o - 1] = a.length << 3;
		var c = [],
		l = [function() {
			return d[1] & d[2] | ~d[1] & d[3]
		},
		function() {
			return d[1] ^ d[2] ^ d[3]
		},
		function() {
			return d[1] & d[2] | d[1] & d[3] | d[2] & d[3]
		},
		function() {
			return d[1] ^ d[2] ^ d[3]
		}],
		u = function(n, t) {
			return n << t | n >>> 32 - t
		},
		s = [1518500249, 1859775393, -1894007588, -899497514],
		d = [1732584193, -271733879, null, null, -1009589776];
		for (d[2] = ~d[0], d[3] = ~d[1], e = 0; e < n.length; e += 16) {
			var f = d.slice(0);
			for (r = 0; r < 80; r++) c[r] = r < 16 ? n[e + r] : u(c[r - 3] ^ c[r - 8] ^ c[r - 14] ^ c[r - 16], 1),
			i = u(d[0], 5) + l[r / 20 | 0]() + d[4] + c[r] + s[r / 20 | 0] | 0,
			d[1] = u(d[1], 30),
			d.pop(),
			d.unshift(i);
			for (r = 0; r < 5; r++) d[r] = d[r] + f[r] | 0
		}
		i = new DataView(new Uint32Array(d).buffer);
		for (var e = 0; e < 5; e++) d[e] = i.getUint32(e << 2);
		return Array.prototype.map.call(new Uint8Array(new Uint32Array(d).buffer),
		function(n) {
			return (n < 16 ? "0": "") + n.toString(16)
		}).join("")
	}
	function r(n) {
		var t = {
			ua: navigator.userAgent
		},
		e = $('meta[name="c"]').attr("c");
		return e && (t.channel = e),
		t.width = window.screen.width,
		t.height = window.screen.height,
		Object.assign(t, n)
	}
	function i(n) {
		return a.baseUrl + n
	}
	var a = {
		baseUrl: base || "",
		clickUrl: "/api/data/tongji",
		installUrl: "/api/data/install"
	};
	new Swiper("#swiper-banner", {
		loop: !0,
		autoplay: !0,
		observer: !0,
		pagination: {
			el: ".swiper-pagination"
		}
	});
	var o = $(".nav-c .item");
	let aIndex = o.index($(".nav-c .item.active"));
	c = new Swiper(".swiper-c", {
		autoHeight: !0,
		initialSlide: aIndex,
		on: {
			slideChange: function() {
				changefnav(this.activeIndex)
			}
		}
	});
	o.on("click",
	function() {
		var t = o.index(this);
		n(t),
		c.slideTo(t)
	});
	var l = 0,
	u = 0; !
	function(n) {
		function t() {
			r = window.innerWidth || document.documentElement.clientWidth,
			e = r > 500 ? .75 : 375 / r
		}
		var e = 1,
		r = 375;
		window.addEventListener("resize", t),
		window.addEventListener("touchstart",
		function(t) {
			if (!t.isTrusted) return n && n(376, 300 + Math.floor(2e3 * Math.random())),
			!1;
			var i = t.touches[0],
			a = i.clientX + window.scrollX;
			r > 500 && (a -= Math.floor((r - 500) / 2));
			var o = i.clientY + window.scrollY,
			c = Math.floor(e * a),
			l = Math.floor(e * o);
			c >= 0 && c <= 375 && n && n(c, l)
		},
		!0),
		t()
	} (function(n, t) {
		l = n,
		u = t
	});
	function adClick(n) {
		if (!n.originalEvent.isTrusted) return ! 1;
		var t = $(this).attr("i");
		if(!t)return;
	   var o = {
			c: l + "*" + u,
			type: "download",
			id: t,
			linkId: $('meta[name="s"]').attr("s")
		},
		c = r(o),
		s = $('meta[name="my"]').attr("k"),
		d = e(s + e(s + c.channel + c.type + c.id));
		c.sign = d,
		$.post(a.clickUrl, c,
		function() {
			console.log("click done!")
		})
	};
	window.adClick = adClick;
	function createdSwiper (id,config ={
        slidesPerView: 1.68, // 一次显示 110 个 Slides
        spaceBetween: 4, // Slides 之间的间距
        touchMoveStopPropagation:true,}) {
        return new Swiper(id, config);
    }
    createdSwiper('#banner1-swiper',{
        loop: !0,
        autoplay: !0,
        observer: !0,
        pagination: {
            el: '.swiper-pagination'
        }
     })
     let zpswper = createdSwiper('.zp-swper',{ 
        touchMoveStopPropagation:false,
        autoHeight: !0,
        on: {
            slideChange: function() {
                changezp(this.activeIndex)
            }
        }
    })
    let menus = $('.zp-menu-wrap .zp-menu-item')
    menus.click(function(){
        let i = menus.index(this);
        zpswper.slideTo(i)
    })
    function changezp (i){
        if (isNaN(i) || i < 0) return ! 1;
        $('.zp-menu-wrap .menu-img1').removeClass('hidden')
        $('.zp-menu-wrap .menu-img2').addClass('hidden')
        menus.removeClass("active"),
        menus.eq(i).addClass("active")
        $('.zp-menu-item.active .menu-img1').addClass('hidden')
        $('.zp-menu-item.active .menu-img2').removeClass('hidden')
    }
	$("a").click(adClick);
	let footerNav = $('.footer-item')
    let navIcon = $('.nav-icon')
    let navIconA = $('.nav-icon-active')
    footerNav.click(function(){
       let i = footerNav.index(this)
       c.slideTo(i) 
    //    changefnav(i)
    })
    function changefnav(i){
        if (isNaN(i) || i < 0) return ;
        navIconA.addClass('hidden')
        navIcon.removeClass('hidden')
        footerNav.removeClass("active");
        footerNav.eq(i).addClass("active");
        $('.footer-item.active .nav-icon').addClass('hidden')
        $('.footer-item.active .nav-icon-active').removeClass("hidden");
        window.sessionStorage.setItem('_i',String(n))
        setTimeout(() => {
            c.update();
        }, 500);    
    };
	let bTtabs = $(".bc-tabs .bc-item")
	function selectBc(index){
	   if (isNaN(index) || index < 0) return ! 1;
		bTtabs.removeClass("active"),
		bTtabs.eq(index).addClass("active")
	}
    let Bcs = new Swiper("#swiper-bc", {
    	    autoHeight: !0,
    		on: {
    			slideChange: function() {
    				selectBc(this.activeIndex)
    			}
    		}
    	   });
	bTtabs.click(function(){
	    let i = $(this).index()
	    selectBc(i)
	    Bcs.slideTo(i)
	});
	  $('#swiper-detail') &&  new Swiper("#swiper-detail", {
        loop: !0,
        autoplay: !0,
        observer: !0,
        pagination: {
            el: ".swiper-pagination"
        }
    });
    $('.copy-icon').click(copy)
	$(document).ready(function() {
		var n = {
			linkId: $('meta[name="s"]').attr("s")
		},
		t = r(n),
		o = $('meta[name="my"]').attr("k"),
		c = e(o + e(o + t.channel + t.linkId));
		t.sign = c,
		$.post(i(a.installUrl), t,
		function() {
			console.log("install done!")
		})
	})
} ();