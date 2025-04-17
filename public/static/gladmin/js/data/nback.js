define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.nback/index',
        add_url: 'data.nback/add',
        edit_url: 'data.nback/edit',
        modify_url: 'data.nback/modify',
        delete_url: 'data.nback/delete',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: "checkbox"},
                    {field: 'id', width: 80, title: 'ID'},
                    {field: 'channelCode', minWidth: 0, title: '推广渠道'},
                    {field: 'noback', title: '禁止返回', width: 120, search: 'select', selectList: {0: '禁用', 1: '启用'}, templet: ea.table.switch},
                    {field: 'nopc', title: '屏蔽PC', width: 120, search: 'select', selectList: {0: '禁用', 1: '启用'}, templet: ea.table.switch},
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
        edit: function () {
            ea.listen();
        }
    };
    return Controller;
});