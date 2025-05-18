define(["jquery", "easy-admin"], function ($, ea) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;
		
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.pgathershow/index?id=' + getUrlParam('id'),
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                limit: 50,
                cols: [[
                    {type: "checkbox"},
                    {field: 'name', minWidth: 100, title: '外显名称',sort:true},
                    {field: 'k_name', minWidth: 100, title: '客户名称',sort:true},
                    {field: 'date', minWidth: 0, title: '查询时间',hide:true,search: 'range',delete:true},
                    {field: 'channelCode', minWidth: 0, title: '推广渠道',search:'select',selectList:{'name':'/gladmin/data.data/getchannelCode'},dong:true,sort: true},
                    // {field: 'shows', Width: 80, title: '展示次数', search: false,sort:true},
                    {field: 'clicks', Width: 80, title: '点击次数', search: false,sort:true},
                    // {field: 'downfinish', Width: 80, title: '下载次数', search: false,sort:true},
                    // {field: 'ratio1', Width: 80, title: '点击/展示', search: false,sort:true,templet:function(d) {return d.ratio1 + '%'}},
                    // {field: 'ratio', Width: 80, title: '下载/点击', search: false,sort:true,templet:function(d) {return d.ratio + '%'}},
                ]],
            });
            ea.listen();
        },
    };
    return Controller;
});
 function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r != null) return unescape(r[2]); return null; //返回参数值
}