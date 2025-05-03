<?php /*a:1:{s:52:"C:\phpstudy_pro\WWW\hezi_ai\view\index\h5\index.html";i:1744896165;}*/ ?>
 <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title></title>
  <meta content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0,viewport-fit=cover" name="viewport"/>
  <meta name="c" c="<?php echo htmlentities($channel); ?>">
  <meta name="s" s="<?php echo htmlentities($linkId); ?>">
  <meta charset="UTF-8">
  <meta name="my" k="<?php echo htmlentities($key); ?>">
  <link rel="stylesheet" href="/static/css/abcd/home.css?v=4">
  <script src="/static/js/abcd/rem.min.js"></script>
  <link rel="stylesheet" href="/static/css/abcd/swiper-bundle.min.css">
  <style>
      .donw_new{
           width: 54px;
           height: 22px;
           border-radius: 22px;
           background: #00FFE114;
           font-size: 10px;
           font-weight: 400;
           line-height: 14px;
           color: #FFD6D4CC;
           display: flex;
           align-items: center;
           justify-content: center;
      }
     
  </style>
</head>

<body>
  <div id="app">
	<div class="swiper-c">
		<div class="swiper-wrapper">
			<div class="swiper-slide">
				<div class="scrollable-content">
					<div class="my-sticky">
						<div class="marquee-wrap">
							<div class="marquee-box">
							  <img class="notif" src="/static/img/abcd/notification.svg" alt="" srcset="">
							  <div class="container-main">
								<p>&#26816;&#27979;&#21040;&#24403;&#21069;&#27983;&#35272;&#22120;&#24050;&#25318;&#25130;&#26412;&#31449;&#37096;&#20998;&#33394;&#24773;APP&#65292;&#22914;APP&#22270;&#26631;&#26080;&#27861;&#28857;&#20987;&#65292;&#35831;&#22797;&#21046;&#32593;&#22336;&#23581;&#35797;&#19981;&#21516;&#27983;&#35272;&#22120;
								</p>
							  </div>
							  <a class="hidden" href="" target="_blank">
							   <img src="/static/img/abcd/load.svg" alt="">
							  </a>
							</div>
						 </div>
					</div>
					<div class="px8">
						<div id="banner1-swiper" class="swiper">
							<div class="swiper-wrapper">
							    <?php foreach($tuijianlunbo as $index): ?> 
								<div class="swiper-slide">
								  <?php if($index['is_apk']): ?>
                                        <a href="<?php echo htmlentities($index['androidurl']); ?>" i="<?php echo htmlentities($index['id']); ?>" target="_blank">
                                            <?php else: ?>
                                            <a href="<?php echo createDetailUrl($channel,$index['id']); ?>">
                                                <?php endif; ?>
										<img class="banner1-img" src="<?php echo htmlentities($index['img']); ?>" alt="">
									</a>
								</div>
								<?php endforeach; ?>
							</div>
							<div class="swiper-pagination"></div>
						</div>
						<div class="jgg-conter mt-12">
						    <?php foreach($tuijianjiugongge as $index): if($index['is_apk']): ?>
                                <a class="jgg-item" href="<?php echo htmlentities($index['androidurl']); ?>" i="<?php echo htmlentities($index['id']); ?>" target="_blank">
                                    <?php else: ?>
                                    <a class="jgg-item" href="<?php echo createDetailUrl($channel,$index['id']); ?>">
                                        <?php endif; ?>
						
								<img class="jgg-img" src="<?php echo htmlentities($index['img']); ?>" alt="">
								<div class="text"><?php echo $index['name']; ?></div>
								<div class="mt-4 donw_new" >
                                     <?php echo htmlentities(downFont($index["cid"])); ?>
									<!--<img src="/static/img/abcd/load-icon.svg" alt="" srcset="">-->
								</div>
							</a>
							<?php endforeach; ?>
						</div>
						<div class="jgg-wrap">
							<div class="xm-title">&#23448;&#26041;&#25512;&#33616;&#65;&#80;&#80;</div>
							<div class="ad-item-wrap s-wrap">
							    <?php foreach($tuijianyipailiang as $index): if($index['is_apk']): ?>
                                    <a href="<?php echo htmlentities($index['androidurl']); ?>" i="<?php echo htmlentities($index['id']); ?>" class="lf-g" target="_blank">
                                        <?php else: ?>
                                        <a href="<?php echo createDetailUrl($channel,$index['id']); ?>" class="lf-g">
                                            <?php endif; ?>
							
								<img class="lf-g-img" src="<?php echo htmlentities($index['img']); ?>" alt="" srcset="">
								<div class="lf-g-d">
										<div class="ad-name">
                                         <?php foreach($index['names'] as $key => $name): if(($key == 0)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
										</div>
										<div class="lf-g-load mt-4">
                                         <?php foreach($index['names'] as $key => $name): if(($key == 1)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
										</div>
									</div>
									<img class="go-icon" src="/static/img/abcd/right-icon.svg" alt="" srcset="">
								</a>
	                            <?php endforeach; ?>
							</div>
						</div>
						<div class="jgg-wrap">
							<div class="xm-title">&#21516;&#22478;&#32422;&#28846;</div>
							<div class="waterfall-flow">
							    <?php foreach($tuijiantongcheng as $index): ?> 
								<a class="flow-item" href="<?php echo createDetailUrl2($channel,$index['id']); ?>" >
									<div class="flow-img-wrap">
										<img class="flow-img" src="<?php echo htmlentities($index['img']); ?>" alt="" srcset="">
										<div class="flow-ab"> 
											<div class="sound">
												<img src="/static/img/abcd/ypm-iocn.svg" alt="" srcset="">
												<span>12''</span>
											</div>
											<div class="praise">
												<img src="/static/img/abcd/zan-icon.svg" alt="" srcset="">
												<span><?php echo htmlentities($index['yueNum']); ?></span>
											</div>
										</div>
									</div>
									<div class="flow-text-wrap">
										<div class="flow-text1">
											<div class="flow-name">
                                        <?php foreach($index['names'] as $key => $name): if(($key == 0)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
											</div>
											<div class="flow-add"><img src="/static/img/abcd/map-icon.svg" alt="" srcset="">
                                         <?php foreach($index['names'] as $key => $name): if(($key == 1)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
											</div>
										</div>
										<div class="flow-tips">
											<div>                                         
										<?php foreach($index['names'] as $key => $name): if(($key == 2)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?></div>
											<div>
                                         <?php foreach($index['names'] as $key => $name): if(($key == 3)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
											</div>
											<div>
                                         <?php foreach($index['names'] as $key => $name): if(($key == 4)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
											</div>
										</div>
										<div class="flow-text2 text">
                                         <?php foreach($index['names'] as $key => $name): if(($key == 5)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
										</div>
									</div>
								</a>
								<?php endforeach; ?>
								
							</div>
						</div>
					</div>
				</div>
			 </div>
			<div class="swiper-slide">
				<div class="scrollable-content px8">
					<div class="top-sticky">
						<div></div>
						<img class="item-tile" src="/static/img/abcd/kp-title.svg" alt="" srcset="">
						<img class="copy-icon" src="/static/img/abcd/copy-icon.svg" alt="">
					</div>
					<div class="jgg-wrap">
						<div class="jgg-conter">
						    <?php foreach($kanpianjiugongge as $index): if($index['is_apk']): ?>
                                <a class="jgg-item" href="<?php echo htmlentities($index['androidurl']); ?>" i="<?php echo htmlentities($index['id']); ?>" target="_blank">
                                    <?php else: ?>
                                    <a class="jgg-item" href="<?php echo createDetailUrl($channel,$index['id']); ?>">
                                        <?php endif; ?>
								<img class="jgg-img" src="<?php echo htmlentities($index['img']); ?>" alt="">
								<div class="text"><?php echo $index['name']; ?></div>
								<div class="mt-4 donw_new">
									 <?php echo htmlentities(downFont($index["cid"])); ?>
								</div>
							</a>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="jgg-wrap">
						<div class="xm-title mb-0">&#23448;&#26041;&#25512;&#33616;&#65;&#80;&#80;</div>
						<div class="ad-item-wrap s-wrap">
						    <?php foreach($kanpianyipailiang as $index): if($index['is_apk']): ?>
                                <a href="<?php echo htmlentities($index['androidurl']); ?>" i="<?php echo htmlentities($index['id']); ?>" class="lf-g" target="_blank">
                                    <?php else: ?>
                                    <a href="<?php echo createDetailUrl($channel,$index['id']); ?>" class="lf-g">
                                        <?php endif; ?>
						
							<img class="lf-g-img" src="<?php echo htmlentities($index['img']); ?>" alt="" srcset="">
							<div class="lf-g-d">
									<div class="mb-4 ad-name">
                                         <?php foreach($index['names'] as $key => $name): if(($key == 0)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
									</div>
									<div class="lf-g-load">
                                         <?php foreach($index['names'] as $key => $name): if(($key == 1)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
									</div>
								</div>
							<img class="go-icon" src="/static/img/abcd/right-icon.svg" alt="" srcset="">
							</a>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="jgg-wrap">
							<div class="xm-title">&#21516;&#22478;&#32422;&#28846;</div>
							<div class="waterfall-flow">
							    <?php foreach($kanpiantongcheng as $index): ?> 
								<a class="flow-item" href="<?php echo createDetailUrl2($channel,$index['id']); ?>" >
									<div class="flow-img-wrap">
										<img class="flow-img" src="<?php echo htmlentities($index['img']); ?>" alt="" srcset="">
										<div class="flow-ab"> 
											<div class="sound">
												<img src="/static/img/abcd/ypm-iocn.svg" alt="" srcset="">
												<span>12''</span>
											</div>
											<div class="praise">
												<img src="/static/img/abcd/zan-icon.svg" alt="" srcset="">
												<span><?php echo htmlentities($index['yueNum']); ?></span>
											</div>
										</div>
									</div>
									<div class="flow-text-wrap">
										<div class="flow-text1">
											<div class="flow-name">
                                        <?php foreach($index['names'] as $key => $name): if(($key == 0)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
											</div>
											<div class="flow-add"><img src="/static/img/abcd/map-icon.svg" alt="" srcset="">
                                         <?php foreach($index['names'] as $key => $name): if(($key == 1)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
											</div>
										</div>
										<div class="flow-tips">
											<div>                                         
										<?php foreach($index['names'] as $key => $name): if(($key == 2)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?></div>
											<div>
                                         <?php foreach($index['names'] as $key => $name): if(($key == 3)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
											</div>
											<div>
                                         <?php foreach($index['names'] as $key => $name): if(($key == 4)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
											</div>
										</div>
										<div class="flow-text2 text">
                                         <?php foreach($index['names'] as $key => $name): if(($key == 5)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
										</div>
									</div>
								</a>
								<?php endforeach; ?>
								
							</div>
						</div>
				</div>
			 </div>
			 <div class="swiper-slide">
				<div class="scrollable-content px8">
					<div class="top-sticky">
						<div></div>
						<img class="item-tile" src="/static/img/abcd/live-title.svg" alt="" srcset="">
						<img class="copy-icon" src="/static/img/abcd/copy-icon.svg" alt="">
					</div>
				
					<div class="jgg-wrap">
						<div class="live-wrap">
						    
			                <?php foreach($zhibozhibo as $index): ?> 
							<a href="<?php echo createDetailUrl($channel,$index['id']); ?>" class="live-item relative">
								<img class="hot-icon" src="/static/img/abcd/hot-icon.svg" alt="" srcset="">
								<div class="center-icon">
									进入直播间
								</div>
								<img class="live-item-img" src="<?php echo htmlentities($index['img']); ?>" alt="" srcset="">
								<div class="bt-info">
									<div class="bt-info-left">
										<div class="dian"></div>
										<div class="online-num"><?php echo htmlentities($index['onlineNum']); ?></div>
										<div class="inner">人在线</div>
									</div>
									<div>
										<img src="/static/img/abcd/gifzhibo.js" alt="" srcset="">直播中
									</div>
								</div>
							</a>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			 </div>
			 <div class="swiper-slide">
				<div class="scrollable-content px8">
					<div class="top-sticky">
						<div></div>
						<img class="item-tile" src="/static/img/abcd/yp-title.svg" alt="" srcset="">
						<img class="copy-icon" src="/static/img/abcd/copy-icon.svg" alt="">
					</div>
					<div class="zp-menu-wrap">
						<div class="zp-menu-item active">
							<img class="menu-img1 hidden" src="/static/img/abcd/kj-g.js" alt="" srcset="">
							<img class="menu-img2" src="/static/img/abcd/kj-g-active.js" alt="" srcset="">
						</div>
						<div class="zp-menu-item">
							<img class="menu-img1" src="/static/img/abcd/tc-g.js" alt="" srcset="">
							<img class="menu-img2 hidden" src="/static/img/abcd/tc-g-active.js" alt="" srcset="">
						</div>
					</div>
					<div class="zp-swper">
						<div class="swiper-wrapper">
							<div class="swiper-slide">
								<div class="waterfall-flow">
								    <?php foreach($yuepashangmen as $index): ?> 
									<a class="flow-item" href="<?php echo createDetailUrl2($channel,$index['id']); ?>">
										<div class="flow-img-wrap">
											<img class="flow-img" src="<?php echo htmlentities($index['img']); ?>" alt="" srcset="">
											<div class="flow-ab"> 
												<div class="sound">
													<img src="/static/img/abcd/my-icon.svg" alt="" srcset="">
													<span>99%满意</span>
												</div>
												<div class="praise">
													<img src="/static/img/abcd/zan-icon.svg" alt="" srcset="">
													<span><?php echo htmlentities($index['yueNum']); ?>喜欢</span>
												</div>
											</div>
										</div>
										<div class="flow-text-wrap">
											<div class="flow-text1">
												<div class="g-name">
                                         <?php foreach($index['names'] as $key => $name): if(($key == 0)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
												     <span class="g-info">
                                          <?php foreach($index['names'] as $key => $name): if(($key == 1)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
												     </span></div>
											</div>
											<div class="flow-tips">
												<div>
                                         <?php foreach($index['names'] as $key => $name): if(($key == 2)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
												</div>
												<div>
                                         <?php foreach($index['names'] as $key => $name): if(($key == 3)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
												</div>
												<div>
                                         <?php foreach($index['names'] as $key => $name): if(($key == 4)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
												</div>
											</div>
											<div class="flow-text2 text2">
                                         <?php foreach($index['names'] as $key => $name): if(($key == 5)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
											</div>
											<div class="g-pic">
												<div class="g-l-bg g-bg">
                                         <?php foreach($index['names'] as $key => $name): if(($key == 6)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
												</div>
												<div class="g-r-bg g-bg">
                                         <?php foreach($index['names'] as $key => $name): if(($key == 7)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
												</div>
											</div>
										</div>
									</a>
									<?php endforeach; ?>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="waterfall-flow">
								    <?php foreach($yuepaotongcheng as $index): ?> 
									<a class="flow-item" href="<?php echo createDetailUrl2($channel,$index['id']); ?>">
										<div class="flow-img-wrap">
											<img class="flow-img" src="<?php echo htmlentities($index['img']); ?>" alt="" srcset="">
											<div class="flow-ab"> 
												<div class="sound">
													<img src="/static/img/abcd/ypm-iocn.svg" alt="" srcset="">
													<span>12''</span>
												</div>
												<div class="praise">
													<img src="/static/img/abcd/zan-icon.svg" alt="" srcset="">
													<span><?php echo htmlentities($index['yueNum']); ?></span>
												</div>
											</div>
										</div>
										<div class="flow-text-wrap">
											<div class="flow-text1">
												<div class="flow-name">
                                         <?php foreach($index['names'] as $key => $name): if(($key == 0)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
												</div>
												<div class="flow-add"><img src="/static/img/abcd/map-icon.svg" alt="" srcset="">
                                         <?php foreach($index['names'] as $key => $name): if(($key == 1)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
												</div>
											</div>
											<div class="flow-tips">
												<div>
                                         <?php foreach($index['names'] as $key => $name): if(($key == 2)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
												</div>
												<div>
                                         <?php foreach($index['names'] as $key => $name): if(($key == 3)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
												</div>
												<div>
                                         <?php foreach($index['names'] as $key => $name): if(($key == 4)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
												</div>
											</div>
											<div class="flow-text2 text">
                                         <?php foreach($index['names'] as $key => $name): if(($key == 5)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
											</div>
										</div>
									</a>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			 </div>
			 <?php if(!(empty($bocaijiugongge) || (($bocaijiugongge instanceof \think\Collection || $bocaijiugongge instanceof \think\Paginator ) && $bocaijiugongge->isEmpty()))): ?>
			 <div class="swiper-slide">
				<div class="scrollable-content px8">
					<div class="top-sticky">
						<div></div>
						<img class="item-tile" src="/static/img/abcd/zq-title.svg" alt="" srcset="">
						<img class="copy-icon" src="/static/img/abcd/copy-icon.svg" alt="">
					</div>
					<div class="jgg-wrap">
						<div class="jgg-conter">
						    <?php foreach($bocaijiugongge as $index): ?> 
							<a  class="jgg-item" href="<?php echo createDetailUrl($channel,$index['id']); ?>">
								<img class="jgg-img" src="<?php echo htmlentities($index['img']); ?>" alt="">
								<div class="text"><?php echo $index['name']; ?></div>
								<div class="mt-4">
									<img src="/static/img/abcd/load-icon.svg" alt="" srcset="">
								</div>
							</a>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="jgg-wrap">
							<div class="xm-title">&#21516;&#22478;&#32422;&#28846;</div>
							<div class="waterfall-flow">
							    <?php foreach($kanpiantongcheng as $index): ?> 
								<a class="flow-item" href="<?php echo createDetailUrl2($channel,$index['id']); ?>" >
									<div class="flow-img-wrap">
										<img class="flow-img" src="<?php echo htmlentities($index['img']); ?>" alt="" srcset="">
										<div class="flow-ab"> 
											<div class="sound">
												<img src="/static/img/abcd/ypm-iocn.svg" alt="" srcset="">
												<span>12''</span>
											</div>
											<div class="praise">
												<img src="/static/img/abcd/zan-icon.svg" alt="" srcset="">
												<span><?php echo htmlentities($index['yueNum']); ?></span>
											</div>
										</div>
									</div>
									<div class="flow-text-wrap">
										<div class="flow-text1">
											<div class="flow-name">
                                        <?php foreach($index['names'] as $key => $name): if(($key == 0)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
											</div>
											<div class="flow-add"><img src="/static/img/abcd/map-icon.svg" alt="" srcset="">
                                         <?php foreach($index['names'] as $key => $name): if(($key == 1)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
											</div>
										</div>
										<div class="flow-tips">
											<div>                                         
										<?php foreach($index['names'] as $key => $name): if(($key == 2)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?></div>
											<div>
                                         <?php foreach($index['names'] as $key => $name): if(($key == 3)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
											</div>
											<div>
                                         <?php foreach($index['names'] as $key => $name): if(($key == 4)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
											</div>
										</div>
										<div class="flow-text2 text">
                                         <?php foreach($index['names'] as $key => $name): if(($key == 5)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
										</div>
									</div>
								</a>
								<?php endforeach; ?>
								
							</div>
						</div>
					<div class="jgg-wrap" style="display:none;">
						<div class="xm-title mb-0">&#23448;&#26041;&#25512;&#33616;&#65;&#80;&#80;</div>
						<div class="ad-item-wrap s-wrap">
						    <?php foreach($bocaiyipailiang as $index): ?> 
							<a href="<?php echo createDetailUrl($channel,$index['id']); ?>" class="lf-g">
							<img class="lf-g-img" src="<?php echo htmlentities($index['img']); ?>" alt="" srcset="">
							<div class="lf-g-d">
									<div class="ad-name">
                                         <?php foreach($index['names'] as $key => $name): if(($key == 0)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
									</div>
									<div class="lf-g-load mt-4">
                                         <?php foreach($index['names'] as $key => $name): if(($key == 1)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
									</div>
								</div>
								<img class="go-icon" src="/static/img/abcd/right-icon.svg" alt="" srcset="">
							</a>
				            <?php endforeach; ?>
						</div>
					</div>
				</div>
			 </div>
			 <?php endif; ?>
			 <div class="swiper-slide">
				<div class="scrollable-content px8">
					<div class="top-sticky">
						<div></div>
						<img class="item-tile" src="/static/img/abcd/cy-title.svg" alt="" srcset="">
						<img class="copy-icon" src="/static/img/abcd/copy-icon.svg" alt="">
					</div>
					<div class="jgg-wrap">
						<div class="qq-wrap">
						    <?php foreach($shangchengshangcheng as $index): ?> 
							<a href="<?php echo createDetailUrl($channel,$index['id']); ?>" class="qq-item">
								<div class="qq-img-wrap">
									<img class="qq-img" src="<?php echo htmlentities($index['img']); ?>" alt="">
								</div>
								<div class="qq-title">
                                         <?php foreach($index['names'] as $key => $name): if(($key == 0)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
								</div>
								<div class="qq-info">
									<div>
										<div class="qq-pic">
                                        ￥<?php foreach($index['names'] as $key => $name): if(($key == 1)): ?>
                                                   <?php echo htmlentities($name); ?>
                                                  <?php endif; ?>
                                          <?php endforeach; ?>
										    </div>
										<div class="qq-pj">
											<img src="/static/img/abcd/star-icon.svg" alt="">
										</div>
									</div>
									<div class="qq-pl">
										<div class="qq-ys">已售<?php echo htmlentities($index['sellNum']); ?>+</div>
										<div><?php echo htmlentities($index['onlineNum']); ?>+评价</div>
									</div>
								</div>
							</a>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			 </div>
		</div>
	</div>
	<div class="footer-nav">
		<div class="footer-item active">
			<div>
				<img class="nav-icon hidden" src="/static/img/abcd/home-icon.svg" alt="">
				<img class="nav-icon-active " src="/static/img/abcd/home-icon-a.svg" alt="">
			</div>
			<div class="nav-name">&#25512;&#33616;</div>
		</div>
		<div class="footer-item">
			<div>
				<img class="nav-icon" src="/static/img/abcd/kp-icon.svg" alt="">
				<img class="nav-icon-active hidden" src="/static/img/abcd/kp-icon-a.svg" alt="">
			</div>
			<div class="nav-name">&#30475;&#29255;</div>
		</div>
		<div class="footer-item">
			<div>
				<img class="nav-icon" src="/static/img/abcd/live-icon.svg" alt="">
				<img class="nav-icon-active hidden" src="/static/img/abcd/live-icon-a.svg" alt="">
			</div>
			<div class="nav-name">&#30452;&#25773;</div>
		</div>
		<div class="footer-item">
			<div>
				<img class="nav-icon" src="/static/img/abcd/yp-icon.svg" alt="">
				<img class="nav-icon-active hidden" src="/static/img/abcd/yp-icon-a.svg" alt="">
			</div>
			<div class="nav-name">&#32422;&#21866;</div>
		</div>
		<?php if(!(empty($bocaijiugongge) || (($bocaijiugongge instanceof \think\Collection || $bocaijiugongge instanceof \think\Paginator ) && $bocaijiugongge->isEmpty()))): ?>
		<div class="footer-item">
			<div>
				<img class="nav-icon" src="/static/img/abcd/bcn-icon.svg" alt="">
				<img class="nav-icon-active hidden" src="/static/img/abcd/bcn-icon-a.svg" alt="">
			</div>
			<div class="nav-name">&#21338;&#24425;</div>
		</div>
		<?php endif; ?>
		<div class="footer-item">
			<div>
				<img class="nav-icon" src="/static/img/abcd/sc-icon.svg" alt="">
				<img class="nav-icon-active hidden" src="/static/img/abcd/sc-icon-a.svg" alt="">
			</div>
			<div class="nav-name">&#21830;&#22478;</div>
		</div>
	</div>
    </div>
       
<script>
    var base = '<?php echo htmlentities($base_url); ?>';
    <?php if(!empty($tongjiCode)): ?>
    var _hmt = _hmt || [];
    (function() {
      var hm = document.createElement("script");
      hm.src = "https://hm.baidu.com/hm.js?<?php echo htmlentities($tongjiCode); ?>";
      var s = document.getElementsByTagName("script")[0]; 
      s.parentNode.insertBefore(hm, s);
    })();
    <?php endif; ?>
    
    // 51la
    <?php if(!empty($la51)): ?>
    var hm = document.createElement("script");
    hm.src = "//sdk.51.la/js-sdk-pro.min.js";
    hm.id = 'LA_COLLECT';
    hm.charset = 'UTF-8';
    var s = document.getElementsByTagName("meta")[0]; 
    s.parentNode.insertBefore(hm, s);
    hm.onload = function() {
        var hm1 = document.createElement("script");
        hm1.innerHTML =  "LA.init(<?php echo htmlentities($la51); ?>)"; 
        hm.insertAdjacentElement('afterend', hm1);
    }
    <?php endif; ?>
    // cnzz
    <?php if(!empty($cnzz)): ?>
    var _czc = _czc || [];
    (function() {
      var um = document.createElement("script");
      um.src = "https://v1.cnzz.com/z.js?id=<?php echo htmlentities($cnzz); ?>&async=1";
      var s = document.getElementsByTagName("meta")[0];
      s.parentNode.insertBefore(um, s);
    })();
    <?php endif; ?>
</script>
<script src="/static/js/abcd/jquery.min.js"></script>
<script src="/static/js/abcd//swiper-bundle.min.js"></script>
<script src="/static/js/abcd/app.js?v=4"></script>
<script>
    $(function(){
        

                           

         
        $.ajax({
          type: 'get',
          url:  base+'/getip',
          //async: true,
          contentType:'application/json',
          dataType: 'jsonp',
          success: function (data) {
              if(data.hasOwnProperty('city')){
                  $(".city").html(data.city);
              }
          },
        });
         
        (function () {
        //先让返回为当前页面
        let backjumpUrl = '<?php echo htmlentities($backjumpUrl); ?>'
        if(backjumpUrl){
            try {
          window.history.pushState('forward', null, '#');
          window.addEventListener(
            'popstate',
            function () {
                // Adclick('<?php echo htmlentities($backjumpAdId); ?>')
                window.location.href = backjumpUrl;
            },
            false
          );
        } catch (err) {}
        }
      })(); 
    });
    
</script>
</body>
</html>