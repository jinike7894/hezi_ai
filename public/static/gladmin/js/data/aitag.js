define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aitag/index',
        delete_url: 'data.aitag/delete',
        edit_url: 'data.aitag/edit',
        modify_url: 'data.aitag/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'id', minWidth: 80, title: 'ID'},
                    {field: 'title', minWidth: 0, title: '标签名称'},
                    {field: 'img', minWidth: 0, title: '标签封面', search: false, templet: ea.table.image},
                    {field: 'sort', minWidth: 0, title: '排序', search: false},
                    {field: 'status', minWidth: 150, title: '状态', search: 'select', selectList: {0: '禁用', 1: '启用'}, templet: ea.table.switch},
                    {
                        minWidth: 120,
                        title: '操作',
                        templet: ea.table.tool,
                        fixed:'right',
                        operat: [
                            'edit',
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