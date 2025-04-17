/**
 * 
 * 此js，封装一些第三方的常用函数
 * 
 */


/**
 * layer弹窗方法
 */
function layer_tc(nr,time){
	layer.open({
		shadeClose: false,
        skin: 'msg',
		content: nr,
		time: time
	});
}




/**
 * 时间戳转日期
 */
function Format_time(timeStamp){
	var date = new Date(timeStamp*1000); 
	Y = date.getFullYear() + '-';
	M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
	D = date.getDate() + ' ';
	h = date.getHours() + ':';
	m = date.getMinutes() + ':';
	s = date.getSeconds(); 
	var time = Y+M+D+h+m+s
	return time
}

//获取url 参数  getQueryVariable(channel)
function getQueryVariable(variable)
{
    var query = window.location.search.substring(1);
    if(query.indexOf("?") != -1 ){
   		var vars = query.split("?");
   		var vars1 = vars[1].split("&");
   		for (var i=0;i<vars1.length;i++) {
            var pair = vars1[i].split("=");
            if(pair[0] == variable){
           	 	return pair[1];
            }
   		}
    }else{
   		var vars = query.split("&");
   		for (var i=0;i<vars.length;i++) {
           var pair = vars[i].split("=");
           if(pair[0] == variable){
               return pair[1];
               
           }
   		}
    }

    
}



