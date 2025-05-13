define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aipointsbill/index',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'id', minWidth: 80, title: 'ID', search: false},
                    {field: 'channelCode', minWidth: 0, title: '渠道'},
                    {field: 'username', minWidth: 0, title: '用户名'},
                    {field: 'original_points', minWidth: 0, title: '账变前金币', search: false},
                    {
                        field: 'points',
                        minWidth: 0,
                        title: '账变金币',
                        search: false,
                        templet: function (row) {
                            // 根据 bill_type 决定金额正负
                            return row.points_type == 0? '-' + row.points : '+' + row.points;
                        }
                    },
                

                    {field: 'after_points', minWidth: 0, title: '账变后金币', search: false},
                    {field: 'bill_type', minWidth: 0, title: '账变类型', search: 'select', selectList: {0: '广告点击', 1: '产品使用', 2: '套餐购买', 3: '后台操作'}},
                    {field: 'create_time', minWidth: 0, title: '账变时间',search: 'range',},
                    {field: 'operator', minWidth: 0, title: '操作账号', search: false},
                    {field: 'remark', minWidth: 0, title: '备注', search: false},
                ]],
            });
            ea.listen();
        },
    };
    return Controller;
});