define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;
		
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.users/index',
        delete_url: 'data.users/delete',
        add_url: 'data.users/add',
        edit_url: 'data.users/edit',
        modify_url: 'data.users/modify',
        batchadd_url: 'data.users/batchAdd'
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh', 'add' ,[{
                    text: '批量添加',
                    url: init.batchadd_url,
                    method: 'open',
                    auth: 'batchadd',
                    class: 'layui-btn layui-btn-normal layui-btn-sm',
                    //extend: 'data-full="true"',
                }], 'delete'],
                cols: [[
                    {type: "checkbox"},
                    {field: 'id', width: 80, title: 'ID'},
                    {field: 'username', minWidth: 150, title: '用户名', searchOp:'%*'},
                    {field: 'nickname', Width: 80, title: '昵称', search: false},
                    {field: 'status', Width: 80, title: '状态', search: 'select', selectList: {0: '禁用', 1: '启用'}, templet: ea.table.switch},
                    {field: 'create_time', minWidth: 190, title: '添加时间', search: false},
                    {
                        width: 250,
                        title: '操作',
                        templet: ea.table.tool,
                        operat: [
                            'edit',
                            'delete'
                        ]
                    }
                ]],
            });
            ea.listen();
        },
        add: function () {
            ea.listen();
        },
        batchAdd: function () {
            ea.listen();
        },
        edit: function () {
            ea.listen();
        }
    };
    return Controller;
});