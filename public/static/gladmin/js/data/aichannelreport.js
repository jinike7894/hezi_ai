define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aichannelreport/index',
        edit_url: 'data.aichannelreport/edit',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'date', minWidth: 0, title: '查询时间', search: 'range'},
                    {field: 'channelCode', width: 80, title: '渠道',searchOp: '='},
                    {field: 'register_user', minWidth: 0, title: '注册用户',search: false},
                    {field: 'user_charge_count', minWidth: 0, title: '充值用户',search: false},
                    {field: 'total_charge_amount', minWidth: 0, title: '充值金额',search: false},
                    {field: 'user_click_count', minWidth: 0, title: '广告点击用户',search: false},
                    {field: 'clicks', minWidth: 0, title: '广告点击数',search: false},
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