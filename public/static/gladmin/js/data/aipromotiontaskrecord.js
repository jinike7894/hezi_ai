define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aipromotiontaskrecord/index',
        edit_url: 'data.aipromotiontaskrecord/edit',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'id', minWidth: 10, title: 'ID',search: false},
                    {field: 'name', minWidth: 10, title: '产品名称',search: true},
                    {field: 'uid', minWidth: 80, title: '用户',search: true},
                    {field: 'task_price', minWidth: 80, title: '金额',search: false},
                    {field: 'status', width: 80, title: '状态',search: true,selectList: {1: '<span style="color:red">待审核</span>', 2: '<span style="color:green">已完成</span>', 3: '未通过'}},
                    {field: 'activity_img', minWidth: 80, title: '截图',search: false,templet: ea.table.image},
                    {field: 'apply_time', minWidth: 80, title: '领取时间',search: false},
                    {field: 'create_time', minWidth: 80, title: '创建时间',search: false},
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