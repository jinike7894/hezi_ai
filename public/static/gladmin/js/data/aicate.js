define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aicate/index',
        delete_url: 'data.aicate/delete',
        edit_url: 'data.aicate/edit',
        modify_url: 'data.aicate/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'id', minWidth: 80, title: 'ID'},
                    {field: 'title', minWidth: 0, title: '视频分类标题'},
                    {field: 'sort', minWidth: 0, title: '排序', search: false},
                    {field: 'is_recommend', minWidth: 150, title: '是否推荐', search: 'select', selectList: {0: '否', 1: '是'}, templet: ea.table.switch},
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