//define(["jquery", "easy-admin"], function ($, ea) {
define(["jquery", "easy-admin", "treetable", "iconPickerFa", "autocomplete"], function ($, ea) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;
		
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.pgather/kehu',
        modify_url: 'data.data/modify',
        show_url: 'data.pgathershow/index',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {field: 'xingzhi', minWidth: 100, title: '性质', search: false,sort:true},
                    {field: 'k_name', minWidth: 100, title: '客户名称',sort:true},
                     {field: 'name', minWidth: 100, title: '外显名称',sort:true},
                   
                    {field: 'clicks', Width: 80, title: '点击次数', search: false,sort:true},
                    {field: 'xingzhi_num', minWidth: 80, title: '性质数字', search: false,sort:true},
                    // {field: 'bc_click', minWidth: 120, title: 'bc点击', search: false,sort:true},
                    // {field: 'bc_ra', minWidth: 120, title: 'bc占比', search: false,sort:true},
                    // {field: 'paotai_click', minWidth: 120, title: '炮台点击', search: false,sort:true},
                    // {field: 'paotai_ra', minWidth: 120, title: '炮台占比', search: false,sort:true},
                    // {field: 'bofangqi_click', minWidth: 120, title: '播放器点击', search: false,sort:true},
                    // {field: 'bofangqi_ra', minWidth: 120, title: '播放器占比', search: false,sort:true},
                    // {field: 'bofangqi_click', minWidth: 120, title: '直播点击', search: false,sort:true},
                    // {field: 'bofangqi_ra', minWidth: 120, title: '直播占比', search: false,sort:true},
                    // {field: 'yaotai_click', minWidth: 120, title: '药台点击', search: false,sort:true},
                    // {field: 'yaotai_ra', minWidth: 120, title: '药台占比', search: false,sort:true},
                    {field: 'date', minWidth: 100, title: '日期',sort:true,search: 'range'},
                    // {
                    //     minWidth: 80,
                    //     title: '操作',
                    //     templet: ea.table.tool,
                    //     operat: [
                    //         [{
                    //             text: '查看',
                    //             url: init.show_url,
                    //             method: 'open',
                    //             auth: 'show',
                    //             class: 'layui-btn layui-btn-normal layui-btn-xs',
                    //             extend: 'data-full="true"',
                    //         }],
                    //     ]
                    // }
                ]],
            });
            ea.listen();
        }
    };
    return Controller;
});