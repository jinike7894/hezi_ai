define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aivideo/index',
        delete_url: 'data.aivideo/delete',
        edit_url: 'data.aivideo/edit',
        modify_url: 'data.aivideo/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'id', minWidth: 80, title: 'ID'},
                    {field: 'title', minWidth: 0, title: '视频标题'},
                    {field: 'points', Width: 80, title: '需要钻石数量',search: false, sort:true},
                    {field: 'pic', minWidth: 0, title: '视频封面', search: false, templet: ea.table.image},
                    {field: 'duration', Width: 80, title: '时长', search: false,sort:true},
                    {field: 'isvip', minWidth: 0, title: '是否需要vip', search: 'select', selectList: {0: '否', 1: '是'}},
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