define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aiactclickrecord/index',
        edit_url: 'data.aiactclickrecord/edit',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'id', minWidth: 80, title: 'ID',search: false},
                    {field: 'cate_title', width: 80, title: '类别',search: false},
                    {field: 'type_title', minWidth: 110, title: '分类',search:'select',selectList:{'name':'/gladmin/data.type/getptype'},fieldAlias:'pid',dong:true},
                    {field: 'name', minWidth: 0, title: '外显名称'},
                    {field: 'k_name', minWidth: 0, title: '客户名称'},
                    {field: 'androidurl', minWidth: 0, title: '产品链接',search: false},
                    {field: 'clicks', minWidth: 0, title: '点击数',search: false},
                    {field: 'points', minWidth: 0, title: '赠送金币',search: false},
                    {field: 'date', minWidth: 0, title: '查询时间',search: 'range'},
                ]],
            });
            ea.listen();
        },
        edit: function () {
            ea.listen();
        }
    };
    return Controller;
});