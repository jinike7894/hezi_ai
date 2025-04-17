define(["jquery", "easy-admin"], function ($, ea) {

    var table = layui.table;
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.url/index',
        export_url: 'data.url/export',
        refreshurl_url: 'data.url/refresh',
        modify_url: 'data.url/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
				toolbar: ['refresh','refreshurl'],
                cols: [[
                    {type: "checkbox"},
                    {field: 'url', Width: 380, title: '链接地址', edit: 'text'},
                    {
                        width: 180,
                        title: '操作',
                        templet: ea.table.tool,
                        operat: [
                            [{
                                text: '刷新缓存',
                                url: init.refreshurl_url,
                                method: 'post',
                                auth: 'authorize',
                                class: 'layui-btn layui-btn-normal layui-btn-xs',
                            }],
                        ]
                    }
                ]],
            });
            
            $('body').on('click', '[data-table-refreshurl]', function () {
                var tableId = $(this).attr('data-table-refreshurl'),
                    url = $(this).attr('data-url');
                console.log(url);
                tableId = tableId || init.table_render_id;
                url = url != undefined ? ea.url(url) : window.location.href;
                var checkStatus = table.checkStatus(tableId),
                    data = checkStatus.data;
                if (data.length <= 0) {
                    ea.msg.error('请勾选需要刷新链接的数据');
                    return false;
                }
                var ids = [];
                $.each(data, function (i, v) {
                    ids.push(v.id);
                });
                ea.msg.confirm('确定刷新链接？', function () {
                    ea.request.post({
                        url: url,
                        data: {
                            id: ids
                        },
                    }, function (res) {
                        ea.msg.success(res.msg, function () {
                            renderTable();
                        });
                    });
                });
                return false;
            });

            ea.listen();
        },
        refreshurl: function () {
           ea.listen();
        },
        edit: function () {
            ea.listen();
        }
    };
    return Controller;
});