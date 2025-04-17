//define(["jquery", "easy-admin"], function ($, ea) {
define(["jquery", "easy-admin", "treetable", "iconPickerFa", "autocomplete"], function ($, ea) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;
		
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.sub/index',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: "checkbox"},
                    {field: 'id', width: 80, title: 'ID',search: false},
                    {field: 'channelCode', minWidth: 0, title: '推广主渠道'},
                    {field: 'subid', Width: 80, title: '子渠道', sort:true},	
                    {field: 'sum', Width: 80, title: 'ip', search: false,sort:true},
                    {field: 'click', Width: 80, title: '点击数', search: false,sort:true},
                    {field: 'date', minWidth: 0, title: '查询日期',search: 'range'},
                ]],
            });
            ea.listen();
        }
    };
    return Controller;
});