define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aipointproduct/index',
        delete_url: 'data.aipointproduct/delete',
        edit_url: 'data.aipointproduct/edit',
        modify_url: 'data.aipointproduct/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'id', minWidth: 80, title: 'ID'},
                    {field: 'name', minWidth: 0, title: '产品名称'},
                    {field: 'points', Width: 80, title: '点数',search: false, sort:true},
                    {field: 'price', Width: 80, title: '价格', search: false,sort:true},
                    {field: 'free_points', Width: 80, title: '免费点数', search: false,sort:true},
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