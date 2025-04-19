define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aiorder/index',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'id', minWidth: 80, title: 'ID'},
                    {field: 'name', minWidth: 0, title: '产品名称'},
                    {field: 'order_num', Width: 80, title: '订单号',search: false, sort:true},
                    {field: 'pid', Width: 80, title: '产品id', search: false,sort:true},
                    {field: 'uid', Width: 80, title: '用户id', search: false,sort:true},
                    {field: 'original_price', Width: 80, title: '原价', search: false,sort:true},
                    {field: 'price', Width: 80, title: '订单价格', search: false,sort:true},
                    {field: 'is_vip', Width: 80, title: '是否vip订单', search: false,sort:true},
                    {field: 'pay_time', Width: 80, title: '支付时间', search: false,sort:true},
                    {field: 'pay_type_id', Width: 80, title: '支付方式', search: false,sort:true},
                    {field: 'pay_status', Width: 80, title: '支付状态', search: false,sort:true},
                    {field: 'vip_expired_time', Width: 80, title: ' 过期时间', search: false,sort:true},
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