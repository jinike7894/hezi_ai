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
                    {field: 'channelCode', width: 80, title: '渠道'},
                    {field: 'register_user', minWidth: 0, title: '注册用户'},
                    {field: 'user_charge_count', minWidth: 0, title: '充值用户'},
                    {field: 'total_charge_amount', minWidth: 0, title: '充值金额'},
                    {field: 'user_click_count', minWidth: 0, title: '广告点击用户'},
                    {field: 'clicks', minWidth: 0, title: '广告点击数'},
                    {field: 'date', minWidth: 0, title: '查询时间'},
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