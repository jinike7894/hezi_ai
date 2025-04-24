define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aipointsbill/index',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'id', minWidth: 80, title: 'ID'},
                    {field: 'channelCode', minWidth: 0, title: '渠道号'},
                    {field: 'username', minWidth: 0, title: '用户名'},
                    {field: 'original_points', minWidth: 0, title: '账变前金币'},
                    {field: 'points', minWidth: 0, title: '账变金币'},
                    {field: 'after_points', minWidth: 0, title: '账变后金币'},
                    {field: 'bill_type', minWidth: 0, title: '账变类型', search: 'select', selectList: {0: '广告点击', 1: '产品使用', 2: '套餐购买', 3: '后台操作'}},
                    {field: 'create_time', minWidth: 0, title: '账变时间'},
                    {field: 'operator', minWidth: 0, title: '操作账号'},
                    {field: 'remark', minWidth: 0, title: '备注'},
                ]],
            });
            ea.listen();
        },
    };
    return Controller;
});