//define(["jquery", "easy-admin"], function ($, ea) {
define(["jquery", "easy-admin", "treetable", "iconPickerFa", "autocomplete"], function ($, ea) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;
		
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.clicks/index',
        modify_url: 'data.clicks/modify',
        show_url: 'data.clicks/show',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: "checkbox"},
                    {field: 'id', width: 80, title: 'ID',search:false},
                    {field: 'channelCode', minWidth: 0, title: '推广渠道',sort:true},
                    {field: 'sj_num', minWidth: 120, title: '安装数', search: false,sort:true},
                    // {field: 'total_shows', Width: 80, title: '展示次数', search: false,sort:true},
                    {field: 'total_clicks', Width: 80, title: '点击数', search: false,sort:true},
                    // {field: 'total_downfinish', Width: 80, title: '下载数', search: false,sort:true},
                    {field: 'ratio1', Width: 80, title: '点击/安装数', search: false,sort:true,templet:function(d) {return d.ratio1}},
                    // {field: 'ratio', Width: 80, title: '下载/点击', search: false,sort:true,templet:function(d) {return d.ratio}},
                    {field: 'date', minWidth: 0, title: '查询时间',search: 'range',delete:true},			
                    {
                        width: 130,
                        title: '操作',
                        templet: ea.table.tool,
                        fixed:'right',
                        operat: [
                            [{
                                text: '查看',
                                url: init.show_url,
                                field: 'channelCode,date',
                                method: 'open',
                                auth: 'show',
                                class: 'layui-btn layui-btn-normal layui-btn-xs',
                                extend: 'data-full="true"',
                            }]
                        ]
                    }
                ]],
            });
            ea.listen();
        },
        
        show: function () {
            var init = {
                table_elem: '#currentTable',
                table_render_id: 'currentTableRenderId',
                index_url: 'data.clicks/show?channelCode=' + getUrlParam('channelCode') + '&date=' + getUrlParam('date'),
            };
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                defaultToolbar: ['exports'],
                cols: [[
                    {field: 'id', width: 80, title: 'ID',search:false},
                    {field: 'channelCode', minWidth: 0, title: '推广渠道',search:false},
                    {field: 'pro_name', width: 130, title: '外显名称',search:false,templet:function(d) {return d.pro_name + '('+d.pid+')'},sort:true},
                    {field: 'k_pro_name', width: 130, title: '客户名称',search:false,sort:true},
                    {field: 'total_shows', Width: 80, title: '展示次数', search: false,sort:true},
                    {field: 'total_clicks', Width: 80, title: '点击数', search: false,sort:true},
                    {field: 'total_downfinish', Width: 80, title: '下载数', search: false,sort:true},
                    {field: 'ratio1', Width: 80, title: '点击/展示', search: false,sort:true,templet:function(d) {return d.ratio1}},
                    {field: 'ratio', Width: 80, title: '下载/点击', search: false,sort:true,templet:function(d) {return d.ratio}},
                    {field: 'date', minWidth: 0, title: '查询时间',search: false,delete:true},
                ]],
            });
            ea.listen();
        }
    };
    return Controller;
});
 function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r != null) return unescape(r[2]); return null; //返回参数值
}