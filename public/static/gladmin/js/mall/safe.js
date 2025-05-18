define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'mall.safe/index',
        edit_url: 'mall.safe/edit',
        modify_url: 'mall.safe/modify',
    };

    var Controller = {

        index: function () {
            ea.listen();
        },
        edit: function () {
            ea.listen();
        },
    };
    return Controller;
});