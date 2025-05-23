define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aiactivityrecord/index',
        edit_url: 'data.aiactivityrecord/edit',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'id', minWidth: 10, title: 'ID',search: false},
                    {field: 'name', minWidth: 10, title: '产品名称',search: false},
                    {field: 'uid', minWidth: 80, title: '用户',search: false},
                    {field: 'points', minWidth: 80, title: '金币',search: false},
                    {field: 'activity_order_num', minWidth: 80, title: '任务单号',search: true},
                    // {field: 'status', width: 80, title: '状态',search: true,selectList: {1: '否', 1: '<span style="color:green">首单</span>'}},
                    {field: 'activity_img', minWidth: 80, title: '截图',search: false,templet: ea.table.image},
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