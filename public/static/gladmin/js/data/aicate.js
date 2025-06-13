define(["jquery", "easy-admin", "treetable", "iconPickerFa", "autocomplete"], function ($, ea) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.aicate/index',
        add_url: 'data.aicate/add',
        edit_url: 'data.aicate/edit',
        delete_url: 'data.aicate/delete',
        export_url: 'data.aicate/export',
        modify_url: 'data.aicate/modify',
    };

    var Controller = {

        index: function () {
            var renderTable = function () {
                layer.load(2);
                treetable.render({
                    treeColIndex: 1,
                    treeSpid: 0,
                    treeIdName: 'id',
                    treePidName: 'pid',
                    elem: init.table_elem,
                    id: init.table_render_id,
                    toolbar: '#toolbar',
                    page: false,
                    skin: 'line',

                    // @todo 不直接使用ea.table.render(); 进行表格初始化, 需要使用 ea.table.formatCols(); 方法格式化`cols`列数据
                    cols: ea.table.formatCols([[
                        {type: 'checkbox'},
                        {field: 'title', width: 250, title: '分类名称', align: 'left'},
                        {field: 'k_title', width: 250, title: '外显名称', align: 'left'},
                        {
                            field: 'is_home',
                            width: 100,
                            title: '类型',
                            templet: function (d) {
                                if (d.pid === 0) {
                                    return '<span class="layui-badge layui-bg-gray">主菜单</span>';
                                } else {
                                    return '<span class="layui-badge-rim">二级菜单</span>';
                                }
                            }
                        },
                        {field: 'is_recommend', minWidth: 150, title: '是否推荐', search: 'select', selectList: {0: '否', 1: '是'}, templet: ea.table.switch},
                        {field: 'sort', width: 100, title: '排序', edit: 'text'},
                        {
                            minwidth: 200,
                            title: '操作',
                            templet: ea.table.tool,
                            operat: [
                                [{
                                    text: '添加下级',
                                    url: init.add_url,
                                    method: 'open',
                                    auth: 'add',
                                    class: 'layui-btn layui-btn-xs layui-btn-normal',
                                    extend: 'data-full="true"',
                                }, {
                                    text: '编辑',
                                    url: init.edit_url,
                                    method: 'open',
                                    auth: 'edit',
                                    class: 'layui-btn layui-btn-xs layui-btn-success',
                                    extend: 'data-full="true"',
                                }],
                            ]
                        }
                    ]], init),
                    done: function () {
                        layer.closeAll('loading');
                    }
                });
            };

            renderTable();


            ea.table.listenSwitch({filter: 'is_recommend', url: init.modify_url});

            ea.table.listenEdit(init, 'currentTable', init.table_render_id, true);

            ea.listen();
        },
        add: function () {
            ea.listen();
        },
        edit: function () {
            ea.listen();
        },
    };
    return Controller;
});