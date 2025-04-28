define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aiorder/index',
        correct_url: 'data.aiorder/correct',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'id', minWidth: 80, title: 'ID', search: false,},
                    {field: 'channelCode', minWidth: 0, title: '渠道'},
                    {field: 'username', minWidth: 0, title: '用户名',search: false,},
                    {field: 'uid', minWidth: 0, title: '用户ID',hide:true,delete:true},
                    {field: 'name', minWidth: 0, title: '购买产品'},
                    {field: 'price', Width: 0, title: '金额',sort:true},
                    {field: 'rate', minWidth: 0, title: '费率', search: false,},
                    {field: 'receipt', minWidth: 0, title: '到账', search: false,},
                    {field: 'pay_type_name', Width: 0, title: '支付方式', sort:true},
                    {field: 'pay_status', minWidth: 0, title: '支付状态', search: 'select', selectList: {0: '未支付', 1: '<span style="color:red">已支付</span>', 2: '支付失败'}},
                    {field: 'create_time', Width: 0, title: '下单时间', search: 'range',sort:true},
                    {field: 'pay_time', Width: 0, title: '支付时间', search: 'range',sort:true},
                    {field: 'order_num', Width: 0, title: '订单号', sort:true},
                    {
                        minWidth: 120,
                        title: '操作',
                        templet: ea.table.tool,
                        fixed:'right',
                        operat: [
                            [{
                                text: '冲正',
                                url: init.correct_url,
                                method: 'request',
                                auth: 'correct',
                                class: 'layui-btn layui-btn-normal layui-btn-xs',
                                field: 'id',
                            }],
                        ]
                    }
                ]],
            });
            ea.listen();
        },
        correct: function () {
            ea.listen();
        }
    };
    return Controller;
});