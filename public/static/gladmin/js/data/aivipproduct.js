define(["jquery", "easy-admin"], function ($, ea,Vue) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aivipproduct/index',
        delete_url: 'data.aivipproduct/delete',
        edit_url: 'data.aivipproduct/edit',
        modify_url: 'data.aivipproduct/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'id', minWidth: 80, title: 'ID'},
                    {field: 'name', minWidth: 0, title: '产品名称'},
                    {field: 'day', Width: 80, title: 'vip天数',search: false, sort:true},
                    {field: 'free_day', Width: 80, title: '赠送天数', search: false,sort:true},
                    {field: 'ai_video_face', Width: 80, title: '每日视频换脸次数', search: false,sort:true},
                    {field: 'ai_img_face', minWidth: 0, title: '每日图片换脸次数',search: false,},
                    {field: 'ai_auto_face', minWidth: 0, title: '每日自动脱衣次数',search: false,},
                    {field: 'ai_manual_face', minWidth: 0, title: '每日手动脱衣次数',search: false,},
                    {field: 'price', minWidth: 0, title: '价格',search: false,},
                    {field: 'sort', minWidth: 0, title: '排序（小在前）',search: false,},
                    {
                        minWidth: 120,
                        title: '操作',
                        templet: ea.table.tool,
                        fixed:'right',
                        operat: [
                            'edit',
                            'delete',
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