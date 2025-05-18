define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.type/index',
        add_url: 'data.type/add',
        edit_url: 'data.type/edit',
        delete_url: 'data.type/delete',
        show_url: 'data.type/show',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                totalRow: true,
                cols: [[
                    {type: "checkbox",totalRowText: '总计:', fixed:'left'},
                    {field: 'id', width: 80, title: 'ID'},
                    {field: 'title', minWidth: 0, title: '分类名称'},
                    {field: 'clicks', minWidth: 0, title: '点击数',sort:true,search: false},
                    {field: 'ratio', minWidth: 0, title: '点击数占比',sort:true,search: false,templet:function(d){return d.ratio + '%'}},
                    {field: 'date', minWidth: 0, title: '查询时间',hide:true,search: 'range',delete:true},
                    {
                        width: 250,
                        title: '操作',
                        templet: ea.table.tool,
                        operat: [
                            'edit',
                            'delete',
                            [{
                                text: '查看',
                                url: init.show_url,
                                field: 'id,date',
                                method: 'open',
                                auth: 'show',
                                class: 'layui-btn layui-btn-normal layui-btn-xs',
                                extend: 'data-full="true"',
                            }]
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
        },
        show: function () {
            var init = {
                table_elem: '#currentTable',
                table_render_id: 'currentTableRenderId',
                index_url: 'data.type/show?id=' + getUrlParam('id') + '&date=' + getUrlParam('date'),
            };
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                defaultToolbar: ['exports'],
                cols: [[
                    {field: 'channelCode', minWidth: 0, title: '推广渠道',search:false},
                    {field: 'total_clicks', Width: 80, title: '点击数', search: false,sort:true},
                    {field: 'ratio', minWidth: 0, title: '点击数占比',sort:true,search: false,templet:function(d){return d.ratio + '%'}},
                    {field: 'date', minWidth: 0, title: '查询时间',search: false,delete:true},
                ]],
            });
            ea.listen();
        }
    };
    return Controller;
});
function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r != null) return unescape(r[2]); return null; //返回参数值
}