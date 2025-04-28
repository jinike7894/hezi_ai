define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aibalancebill/index',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'id', minWidth: 80, title: 'ID', search: false},
                    {field: 'channelCode', minWidth: 0, title: '渠道'},
                    {field: 'name', minWidth: 0, title: '用户名'},
                    {field: 'original_amount', minWidth: 0, title: '账变前余额', search: false},
                    {field: 'amount', minWidth: 0, title: '账变金额', search: false},
                    {field: 'after_amount', minWidth: 0, title: '账变后余额', search: false},
                    {field: 'bill_type', minWidth: 0, title: '账变类型', search: 'select', selectList: {0: '佣金', 1: '提款', 2: '其他', 3: '后台提款'}},
                    {field: 'create_time', minWidth: 0, title: '账变时间', search: 'range'},
                    {field: 'operator', minWidth: 0, title: '操作账号', search: false},
                    {field: 'remark', minWidth: 0, title: '备注', search: false},
                ]],
            });
            ea.listen();
        },
    };
    return Controller;
});