define(["jquery", "easy-admin", "vue"], function ($, ea,Vue) {
    
    var form = layui.form,
        table = layui.table;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.product/index',
        add_url: 'data.product/add',
        edit_url: 'data.product/edit',
        delete_url: 'data.product/delete',
        export_url: 'data.product/export',
        modify_url: 'data.product/modify',
        batchedit_url: 'data.product/batchEdit',
        copy_url: 'data.product/copy',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh', 'add' ,[{
                    text: '批量替换链接',
                    url: init.batchedit_url,
                    method: 'open',
                    auth: 'batchedit',
                    checkbox: true,
                    class: 'layui-btn layui-btn-normal layui-btn-sm',
                    //extend: 'data-full="true"',
                }], 'delete'],
                cols: [[
                    {type: "checkbox"},
                    {field: 'id', width: 80, title: 'ID'},
                    {field: 'title', minWidth: 110, title: '分类',search:'select',selectList:{'name':'/gladmin/data.type/getptype'},fieldAlias:'pid',dong:true},
                    {field: 'cate_title', minWidth: 110, title: '类别',search:'select',selectList:{'name':'/gladmin/data.category/getpcate'},fieldAlias:'cid',dong:true, sort: true},
                    {field: 'sort', minWidth: 155, title: '排序(越小越靠前)', edit: 'int',search: false,sort:true},
                    {field: 'name', minWidth: 120, title: '外显名称', edit: 'textarea',sort:true},
                    {field: 'k_name', minWidth: 120, title: '客户名称', edit: 'text',sort:true},
                    {field: 'due_date', minWidth: 120, title: '到期日期', search: false},
                    {field: 'img', minWidth: 100, title: '产品图片', search: false, templet: ea.table.image},
                    {field: 'androidurl', minWidth: 150, title: '产品链接', edit: 'text',sort:true},
                    {field: 'downnum', minWidth: 150, title: '下载次数', edit: 'int', search: false},
                    {field: 'slogan', minWidth: 150, title: '简介', edit: 'text'},
                    {field: 'status', filter:'status1', title: '暂停', width: 85, search: false, templet: ea.table.switch, 
                        switchfunc: function(res) {
                            var data = {
                                id: res.id,
                                field: 'status',
                                value: res.checked == 1 ? 1 : 2,
                            };
                            ea.request.post({
                                url: init.modify_url,
                                prefix: true,
                                data: data,
                            }, function (res) {
                                table.reload(init.table_render_id);
                            }, function (res) {
                                ea.msg.error(res.msg, function () {
                                    table.reload(init.table_render_id);
                                });
                            }, function () {
                                table.reload(init.table_render_id);
                            });
                        }
                    },
                    {field: 'status', title: '状态', width: 85, search: 'select', selectList: {0: '禁用', 1: '启用',2: '暂停'}, templet: ea.table.switch},
                    {
                        width: 190,
                        title: '操作',
                        templet: ea.table.tool,
                        fixed: 'right',
                        operat: [
                            'edit',
                            'delete',
                            [{
                                text: '复制',
                                url: init.copy_url,
                                method: 'open',
                                auth: 'copy',
                                class: 'layui-btn layui-btn-normal layui-btn-xs',
                                //extend: 'data-full="true"',
                            }],
                        ]
                    },
                ]],
                done: function(res, curr, count){
                    $('.layui-table-body.layui-table-main tbody tr').each(function(index, val) {
                        // $($(".layui-table-fixed-l .layui-table-body tbody tr")[index]).height($(val).height());
                        $($(".layui-table-fixed-r .layui-table-body tbody tr")[index]).height($(val).height());
                    })
                },
            });

            ea.listen();
        },
        add: function () {
            var app = new Vue({
                el: '#app',
                data: {
                    is_best: is_best,
                }
            });
            form.on('radio(is_best)', function (data) {
                app.is_best = this.value;
            });
            ea.listen();
        },
        edit: function () {
            var app = new Vue({
                el: '#app',
                data: {
                    is_best: is_best,
                }
            });
            form.on('radio(is_best)', function (data) {
                app.is_best = this.value;
            });
            ea.listen();
        },
        batchEdit: function () {
            ea.listen();
        },
        copy: function() {
            var app = new Vue({
                el: '#app',
                data: {
                    is_best: is_best,
                }
            });
            form.on('radio(is_best)', function (data) {
                app.is_best = this.value;
            });
            ea.listen();
        },
    };
      
    return Controller;
});