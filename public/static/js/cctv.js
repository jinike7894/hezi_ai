

/**
 * 横幅
 * 顶部 ：101
 * 中间 ：102
 * 底部 ：103
 */
var cctvBannerTopIndex_365 = [],cctvBannerCenterIndex_365 = [] , cctvBannerBottomIndex = [];
var cctvBannerTopDomIndex_365 ='',cctvBannerCenterDomIndex_365 ='',cctvBannerBottomDomIndex ='';
/**
 * 横幅 apiUrl
 * 列表广告一个页面一个数组，按排序来分排序越小，就在页面的上方；
 */
var cctvListIndex = [],cctvListList = [] , cctvListInfo = [];
var cctvListDomIndex ='',cctvListDomList ='',cctvListDomInfo ='';

//getIndexListFun()
function getIndexListFun(){
	source['url'] = getCctvList
	getFunssss(source, successfn, errorfn,'正在加载。。')
	function successfn(res){
		cctvBannerTopIndex_365 = res.data.topAds
		cctvBannerCenterIndex_365 = res.data.middleAds
		cctvListIndex = res.data.videoListAds
		framtCctvFun()
		
		//顶票
		let topHtml = CreateCctvZhidingTopDom(res.data.topFloatAds[0])
		$(".cctv_head_top_dom").html(topHtml)

		
		//底票
		let bottomHtml = CreateCctvDiPiaoDom(res.data.bottomAds[0])
		$(".cctv_bottom_dom").html(bottomHtml)
		
		//文字列表
		let text_listHtml = ''
        //创建文字列表
        res.data.wordListAds = res.data.wordListAds.reverse()
        res.data.wordListAds.forEach((item,index) => {
            if(item){
                item['tagarr'] = item.name.split(',')
                text_listHtml +=CreateCctvTextDom(item,index,'文字列表'); 
            }
        })
        $(".show_desc").html(text_listHtml)
        
        //列表中间的li广告 -列表
    	if(cctvListIndex.length > 0){
    		$(".list_t ").children().eq(2).after(`
    		    <a class="show_download need-stat" onclick="goDownWebFun('${cctvListIndex[0]['cover_redirect']}','${cctvListIndex[0]['cover_redirect']}','${cctvListIndex[0]['cover_redirect']}','${cctvListIndex[0]['name']}','视频专区')"  herf="<?=$bqsr['titleurl']?>" target="_blank">
			      <img src="${cctvListIndex[0]['cover']}">

			        <p>${cctvListIndex[0]['name']}</p>
			        <span></span>
		        </a>
    		    `);
    	}
		
		//设置顶部样式  
		setTimeout(() => {
			let wapHeadHeight = $(".cctv-header-top-dv").height(); // H5头部高度
			let marginTop = wapHeadHeight ;
			$(".cctv-header-top-zanwei").css("height", marginTop); 
			
			let wapHeadHeight_dh = $(".header-wrap").height(); // H5头部高度
			let marginTop_dh = wapHeadHeight_dh ;
			$(".main-content").css("margin-top", marginTop_dh+5); 
			
		},3000);
	}
}


function framtCctvFun(){
	//顶部横幅生成
	cctvBannerTopIndex_365.forEach((item,index) => {
		if(item != ''){
			cctvBannerTopDomIndex_365 += CreateCctvBannerTopDom365(item,index)
		}
	})
	if($(".cctvBannerTopDom")){
		$(".cctvBannerTopDom").html(cctvBannerTopDomIndex_365)
		new Swiper('.banner-wrap .swiper-container', {
			pagination: '.banner-wrap .swiper-pagination',
			autoplay: {
				delay: 5000,
			},
			slidesPerView: 1,
			paginationClickable: true,
			spaceBetween: 0,
			loop: true,
			// observer: true, //修改swiper自己或子元素时，自动初始化swiper
			// observeParents: true, //修改swiper的父元素时，自动初始化swiper
		});
	}
	
	//中间横幅生成--中间轮播
	cctvBannerCenterIndex_365.forEach((item,index) => {
		if(item != ''){
			cctvBannerCenterDomIndex_365 += CreateCctvBannerCenterDom(item,index)
		}
	})
	if($(".cctvBannerCenterDom")){
		$(".cctvBannerCenterDom").html(cctvBannerCenterDomIndex_365)
		new Swiper('.ad-1-wrap .swiper-container', {
			autoplay: {
				delay: 4000,
			},
			slidesPerView: 1,
			spaceBetween: 0,
			loop: true,	
		});
	}
}

/**
*
* 创建中间横幅dom
* index 广告索引
* data  横幅数据
* onclick="visitReport('${index}')" 
*
**/
function CreateCctvBannerCenterDom(data,index){
	if(data){
		return `
			<a class="swiper-slide" onclick="DownAppStatisticsFun('${data.name}','中间轮播广告')"  href="${data.cover_redirect}" target="_blank" >
				<img src="${data.cover}" alt="${data.title}">
			</a>
		`
	}
}

/**
*
* 创建顶部横幅dom
* index 广告索引
* data  横幅数据
* onclick="visitReport(${data.id})"   
* https://cdn.cnbj1.fds.api.mi-img.com/middle.community.vip.bkt/eb07da412f37e84590acba6b47e3a425
* 
**/
function CreateCctvBannerTopDom365(data,index){
	if(data){
		return `
			<a class="swiper-slide" href="${data.cover_redirect}" onclick="DownAppStatisticsFun('${data.name}','顶部轮播广告')" target="_blank">
				<img src=" ${data.cover}" alt="${data.title}">
			</a>
		`
	}
}

function CreateCctvTextDom(data,index){
    if(data){
		return `
		    <ul class="need-stat" onclick="goDownWebFun('${data.cover_redirect}','${data.cover_redirect}','${data.cover_redirect}','${data.name}','文字列表')">
				<li>${data.tagarr[0]}</li>
				<li>${data.tagarr[1]}</li>
				<li>${data.tagarr[2]}</li>
				<li>${data.tagarr[3]}</li>
			</ul>
			
		`
	}
}
//stat('${data.cover_redirect}', 'tag', 9)"
function stat(url,type,index){
    window.open(url)
}


/**
*
* 创建顶部顶票dom
* index 广告索引
* data  横幅数据
* onclick="visitReport(${data.id})"
* 
**/
function CreateCctvZhidingTopDom(data,index){
	if(data){
		return `
		    <a class=" " target="_blank" href="${data.cover_redirect}" onclick="DownAppStatisticsFun('${data.name}','顶飘广告')" style="display: block;">
                <img src="${data.cover}" alt="null" style="width:100%;max-height: 80px;">
            </a>
			
		`
	}
}

// <a class="cctv_head_top cctv_bottom_top" href="${data.cover_redirect}" target="_blank" onclick="DownAppStatisticsFun('${data.name}','顶飘广告')">
// 				<div class="pic">
// 					<img src="${data.cover}" alt="${data.name}">
// 				</div>
// 				<div class="text">全国免费约炮</div>
// 				<div class="desc">与少妇学妹空姐 负距离接触 走肾不走心</div>
// 				<div class="btn">立刻约炮</div>
// 			</a>
			

/**
*
* 创建顶部顶票dom
* index 广告索引
* data  横幅数据
* onclick="visitReport(${data.id})"
* 
**/
function CreateCctvDiPiaoDom(data,index){
	if(data){
		return `
		    <a class=" " target="_blank" href="${data.cover_redirect}" onclick="DownAppStatisticsFun('${data.name}','顶飘广告')" style="display: block;">
                <img src="${data.cover}" alt="null" style="width:100%;max-height: 80px;">
            </a>
			
		`
	}
}

// 	<a class=" " href="${data.cover_redirect}" target="_blank" onclick="DownAppStatisticsFun('${data.name}','底飘广告')">
// 				<div class="pic">
// 					<img src="${data.cover}" alt="${data.name}">
// 				</div>
// 				<div class="text">成人视频</div>
// 				<div class="desc">萝莉少妇在线抠逼，淫荡超你想象！</div>
// 				<div class="btn">立即下载</div>
// 			</a>


/**
  * 统计  2021-02-02
*/
function visitReport(sitpage) {
    var sitpage2=null;
    if (sitpage) {
        sitpage2 = '第'+sitpage+'条横幅点击'
	    ajaxPost(apiPublic+'/api/statistics/visitReport?site_id='+site_id+'&site_page='+sitpage2, '', function (data) {
	    });
    }
}

