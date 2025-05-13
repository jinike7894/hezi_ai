define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aireport/index',
        edit_url: 'data.aiu$countser/edit',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'date', minWidth: 0, title: '日期',search: false},
                    {field: 'registered_users', minWidth: 0, title: '注册用户', search: false},
                    {field: 'first_charge_count', minWidth: 0, title: '首冲人数', search: false},
                    {field: 'repeat_charge_count', minWidth: 0, title: '复充人数', search: false},
                    {field: 'total_charge_count', minWidth: 0, title: '总充人数', search: false},
                    {field: 'first_charge_amount', minWidth: 0, title: '首冲金额', search: false},
                    {field: 'repeat_charge_amount', minWidth: 0, title: '复充金额', search: false},
                    {field: 'total_charge_amount', minWidth: 0, title: '总充金额', search: false},
                    {
                        field: 'total_settlement_amount',
                        minWidth: 0,
                        title: '结算金额',
                        search: false,
                        templet: function (row) {
                            return parseFloat(row.total_settlement_amount).toFixed(2);
                        }
                    },

                    {field: 'total_agent_amount', minWidth: 0, title: '代理收益', search: false},
                    {field: 'platform_profit', minWidth: 0, title: '平台收益', search: false},
                    {field: 'total_coin_consumed', minWidth: 0, title: '点数消耗', search: false},
                    {field: 'total_rate_cost', minWidth: 0, title: '消耗支出', search: false},
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