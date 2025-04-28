define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aiuserecord/index',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'id', minWidth: 80, title: 'ID', search: false},
                    {field: 'channelCode', minWidth: 0, title: '渠道'},
                    {field: 'username', minWidth: 0, title: '用户名', search: false},
                    {field: 'uid', minWidth: 0, title: '用户ID',hide:true,delete:true},
                    {field: 'ai_type', minWidth: 0, title: '产品', search: 'select', selectList: {0: '视频换脸', 1: '图片换脸', 2: '自动换脸', 3: '手动换脸'}},
                    {field: 'is_use_vip', minWidth: 0, title: '消耗方式', search: 'select', selectList: {0: '金币', 1: '次数'}},
                    {field: 'template_name', minWidth: 0, title: '模版', search: false},
                    {field: 'img', minWidth: 0, title: '用户图片', search: false, templet: ea.table.image},
                    {field: 'create_time', Width: 0, title: '提交时间',search: 'range',sort:true},
                    {field: 'ai_generate_source', minWidth: 0, title: '预览', templet: ea.table.image},
                    {field: 'status', minWidth: 0, title: '状态', search: 'select', selectList: {0: '排队中', 1: '<span style="color:red">成功</span>', 2: '失败'}},
                ]],
            });
            ea.listen();
        },
    };
    return Controller;
});