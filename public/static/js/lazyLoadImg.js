/**
 * @author eleven on 2022/10/10
 * @des 实现一个基于jquery的图片懒加载工具以提高页面性能。
 * 注意需要进行懒加载的图片其dom由
 * <img src=xxx.jpg>
 *  更改为
 *  《img class="js-load"  data-src=xxx.jpg>
 *   或者配置为一个占位图片
 *  《img class="js-load" src=占位图片 data-src=xxx.jpg>
 *
 *  mew $$LazyLoadImg(".js-load").ready();
 * @params {}
 * @return
 */
(function ($) {
    /**
     * @author eleven on 2022/10/10
     * @des 为了性能优化这里再实现一个内部使用的节流函数
     * 防止滚动时调用次数过多
     * @params {}
     * @return
     */
    function _throttle(callback, loopTime = 50) {
        //闭包一个执行开关
        let flag = true;
        return function () {
            if (!flag) {
                return false;
            }
            flag = false;
            //开关打开时才能执行，并设置下次开启
            setTimeout(() => {
                flag = true;
            }, loopTime)
            callback.call(null, arguments);
        }
    }

    class LazyLoadImg {
        /*
        * queryList 默认支持传递已经查找的dom对象和css选择器字符串
        * threshold 默认图片提前200px就被加载
        * */
        constructor(queryList,threshold = 200) {
            if (typeof queryList === 'string') {
                queryList = $(queryList);
            }
            //过滤下图片，需要存在data-src
            this.$images = $.makeArray(queryList.filter("*[data-src]")).map(item => {
                return $(item);
            });
            this.$threshold = threshold;
        }

        /**
         * @author eleven on 2022/10/10
         * @des 关注页面移动，当图片处于页面可视范围内时，会自动进行加载
         * @params {}
         * @return
         */
        ready() {
            console.log("ready!");
            let self = this;
            let $window = $(window);
            let $images = this.$images;

            function scrollHandle() {
                // 获取页面滚动的高度:
                if ($images.length === 0) {
                    window.removeEventListener('scroll', _scrollHandle);
                    _scrollHandle = null;
                    console.log("[lazyLoadImg] 已完成加载，移除事件");
                    return false;
                }
                //获取当前可视区域底部距离页面开始的总高度，并让其多加载200像素高度的内容
                let top = $window.scrollTop() + $window.height() + self.$threshold;
                let loadCount = 0;
                for (let i = 0; i < $images.length; i++) {
                    //判断是否在可视范围。
                    if ($images[i].offset().top > top) {
                        break;
                    } else {
                        loadCount++;
                    }
                }
                //进行图片加载
                self.$load($images.splice(0, loadCount));
            }

            let _scrollHandle = _throttle(scrollHandle);

            window.addEventListener("scroll", _scrollHandle);
            //第一次需要触发下
            _scrollHandle();
        }

        //加载图片
        $load(list) {
            list.forEach( item=> {
                item.attr('src', item.data('src'));
            });
        }

        /**
         * @author eleven on 2022/10/10
         * @des 直接进行加载，不需要进行事件绑定和等方法，适用于切换选项卡时的处理
         * @params {}
         * @return
         */
        done() {
            if (this.$images.length) {
                this.$load(this.$images);
                this.$images.length = 0;
            } else {
                console.log("已全部加载");
            }
        }
    }


    //暴露到外部
    window.$$LazyLoadImg = LazyLoadImg;
})(jQuery)

