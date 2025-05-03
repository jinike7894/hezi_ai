define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aiagentwithdrawal/index',
        edit_url: 'data.aiagentwithdrawal/edit',
        qrcode_url: 'data.aiagentwithdrawal/qrcode',
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
                    {field: 'withdrawal_order_num', minWidth: 0, title: '订单号'},
                    {field: 'amount', minWidth: 0, title: '提款金额-cny', search: false},
                    {field: 'usdt', minWidth: 0, title: '提款金额-usdt', search: false},
                    {field: 'rate', minWidth: 0, title: '汇率', search: false},
                    {field: 'coin_wallet_type', minWidth: 0, title: '收款类型', search: false},
                    {field: 'coin_wallet_address', minWidth: 100, title: '收款地址', search: false},
                    {field: 'create_time', Width: 0, title: '提交时间',search: 'range',sort:true},
                    {field: 'finish_time', Width: 0, title: '完成时间',search: 'range',sort:true},
                    {field: 'status', minWidth: 0, title: '状态', search: 'select', selectList: {0: '未打款', 1: '<span style="color:red">已打款</span>', 2: '拒绝'}},
                    {
                        minWidth: 120,
                        title: '操作',
                        templet: ea.table.tool,
                        fixed:'right',
                        operat: [
                            [{
                                text: '二维码',
                                url: init.qrcode_url,
                                method: 'open',
                                auth: 'stock',
                                class: 'layui-btn layui-btn-normal layui-btn-xs',
                                field: 'id',
                            }],
                            'edit',
                        ]
                    }
                ]],
            });
            ea.listen();
        },
        qrcode: function () {
            ea.listen();
        },
        edit: function () {
            ea.listen();
        }
    };
    return Controller;
});