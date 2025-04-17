define(["jquery", "easy-admin", "echarts", "echarts-theme", "miniAdmin", "miniTab"], function ($, ea, echarts) {
    
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.trend/index',
        export_url: 'data.trend/export',
    };

    var Controller = {
        index: function () {
            var ydata=null;
            var tdata = null;
            ea.table.render({
                init: init,
				toolbar: ['refresh'],
                cols: [[
                    {type: "checkbox"},
                    {field: 'id', width: 80, title: 'ID'},
                    {field: 'channelCode', Width: 80, title: '渠道',searchOp:'='},
                    {field: 'pid', Width: 60, title: '产品ID',searchOp:'='},
                    {field: 'tatal_clicks', Width: 60, title: '点击数'},
                    {field: 'period', Width: 60, title: '时段'},
                ]],
                done:function(res,curr,count){
                    var echartsRecords = echarts.init(document.getElementById('echarts-records'), 'walden');
                    var optionRecords = {
                        title: {
                            text: '点击统计'
                        },
                        tooltip: {
                            trigger: 'axis'
                        },
                        legend: {
                            data: ['上周','昨天', '今天']
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        toolbox: {
                            feature: {
                                saveAsImage: {}
                            }
                        },
                        xAxis: {
                            type: 'category',
                            boundaryGap: false,
                            data: ['00:00','00:30', '01:00', '01:30', '02:00', '02:30', '03:00', '03:30', '04:00', '04:30', '05:00', '05:30', '06:00', '06:30', '07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00', '22:30', '23:00', '23:30']
                            },
                            yAxis: {
                                type: 'value'
                            },
                            series: [
                                {
                                    name: '上周',
                                    type: 'line',
                                    data: res.week
                                },
                                {
                                    name: '昨天',
                                    type: 'line',
                                    data: res.ydata
                                },
                                {
                                    name: '今天',
                                    type: 'line',
                                    data: res.tdata
                                }
                            ]
                        };
                        echartsRecords.setOption(optionRecords);
                        window.addEventListener("resize", function () {
                            echartsRecords.resize();
                        });
                    }
                });
            ea.listen();
        }
    };
    return Controller;
});
