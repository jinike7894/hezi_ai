define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aiagentdata/index',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'agent_id', minWidth: 80, title: '代理ID', search: false},
                    {field: 'channelCode', minWidth: 0, title: '渠道'},
                    {field: 'username', minWidth: 0, title: '用户名'},
                    {field: 'sub', minWidth: 0, title: '注册用户', search: false},
                    {field: 'recharge_user', minWidth: 0, title: '充值用户', search: false},
                    {field: 'recharge_amount', minWidth: 0, title: '充值金额', search: false},
                    {field: 'user_click_count', minWidth: 0, title: '广告点击用户', search: false},
                    {field: 'click', minWidth: 0, title: '广告点击数', search: false},
                    {field: 'create_time', Width: 0, title: '时间',hide:'true',search: 'range',sort:true},
                    {field: 'date', Width: 0, title: '时间',search: false},
                ]],
            });
            ea.listen();
        },
    };
    return Controller;
});