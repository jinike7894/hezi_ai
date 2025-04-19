define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aiuser/index',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'id', minWidth: 80, title: 'ID'},
                    {field: 'username', minWidth: 0, title: '用户名'},
                    {field: 'unique_code', Width: 80, title: '设备码',search: false, sort:true},
                    {field: 'vip_expiration', Width: 80, title: 'vip过期时间', search: false,sort:true},
                    {field: 'points', Width: 80, title: 'ai点数', search: false,sort:true},
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