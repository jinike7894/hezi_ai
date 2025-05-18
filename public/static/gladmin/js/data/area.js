//define(["jquery", "easy-admin"], function ($, ea) {
define(["jquery", "easy-admin", "treetable", "iconPickerFa", "autocomplete"], function ($, ea) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;
		
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.area/index',
        modify_url: 'data.area/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: "checkbox"},
                    {field: 'id', width: 80, title: 'ID'},
                    {field: 'type', minWidth: 150, title: '区域', searchOp:'='},
                    {field: 'date', minWidth: 0, title: '查询时间',search: 'range',delete:true},
                    {field: 'channelCode', minWidth: 0, title: '推广渠道',search:'select',selectList:{'name':'/gladmin/data.data/getchannelCode'},dong:true},
                    {field: 'clicks', Width: 80, title: '点击次数', search: false,sort:true},
                    {field: 'cost', Width: 80, title: '点击收益', search: false},
                    {field: 'income', Width: 80, title: '产品收益',search: false,sort:true},					
                ]],
            });
            ea.listen();
        }
    };
    return Controller;
});