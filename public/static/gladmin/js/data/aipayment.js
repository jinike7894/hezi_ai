define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aipayment/index',
        delete_url: 'data.aipayment/delete',
        edit_url: 'data.aipayment/edit',
        modify_url: 'data.aipayment/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'id', minWidth: 80, title: 'ID'},
                     {field: 'pid', minWidth: 0, title: '支付pid'},

                    {field: 'name', minWidth: 0, title: '名称'},
                    {field: 'discount', Width: 80, title: '优惠金额',search: false, sort:true},
                    {field: 'appid', Width: 80, title: '商户id', search: false,sort:true},
                    {field: 'secret', Width: 80, title: '密钥', search: false,sort:true},
                    {field: 'rate', Width: 80, title: '费率', search: false,sort:true},
                    {field: 'sort', Width: 80, title: '排序', search: false,sort:true},
                    {
                        minWidth: 120,
                        title: '操作',
                        templet: ea.table.tool,
                        fixed:'right',
                        operat: [
                            'edit',
                            'delete',
                        ]
                    }
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