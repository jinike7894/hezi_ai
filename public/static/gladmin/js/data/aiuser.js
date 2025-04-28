define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aiuser/index',
        edit_url: 'data.aiuser/edit',
        changepw_url: 'data.aiuser/changepw',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'id', minWidth: 80, title: '注册ID', search: false,},
                    {field: 'channelCode', width: 80, title: '渠道'},
                    {field: 'username', minWidth: 0, title: '用户名'},
                    {field: 'balance', minWidth: 0, title: '余额'},
                    {field: 'have_coin_wallet', minWidth: 0, title: '绑定收款卡', search: false,},
                    {field: 'points', minWidth: 0, title: '剩余金币', search: false,},
                    {field: 'points', minWidth: 0, title: 'Vip等级-待定', search: false,},
                    {field: 'remaining_days', Width: 80, title: '剩余天数', search: false,sort:true},
                    {field: 'create_time', minWidth: 0, title: '注册时间',search: 'range',},
                    {field: 'create_time', minWidth: 0, title: '注册ip-待定', search: false,},
                    {
                        minWidth: 120,
                        title: '操作',
                        templet: ea.table.tool,
                        fixed:'right',
                        operat: [
                            [{
                                text: '修改密码',
                                url: init.changepw_url,
                                method: 'open',
                                auth: 'stock',
                                class: 'layui-btn layui-btn-normal layui-btn-xs',
                                field: 'id',
                            }],
                            'edit',
                        ]
                    }
                ]],
            });
            ea.listen();
        },
        changepw: function () {
            ea.listen();
        },
        edit: function () {
            ea.listen();
        }
    };
    return Controller;
});