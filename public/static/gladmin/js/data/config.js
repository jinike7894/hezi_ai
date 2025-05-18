define(["jquery", "easy-admin", "vue"], function ($, ea, Vue) {

    var form = layui.form;

    var Controller = {
        index: function () {

            var app = new Vue({
                el: '#app',
                data: {
                    forceupdate: forceupdate,
                    popup_type: popup_type
                }
            });

            form.on("radio(forceupdate)", function (data) {
                app.forceupdate = this.value;
            });

            form.on("radio(popup_type)", function (data) {
                app.popup_type = this.value;
            });

            ea.listen();
        }
    };
    return Controller;
});