//define(["jquery", "easy-admin"], function ($, ea) {
define(["jquery", "easy-admin", "treetable", "iconPickerFa", "autocomplete"], function ($, ea) {

    var table = layui.table,
        treetable = layui.treetable,
        iconPickerFa = layui.iconPickerFa,
        autocomplete = layui.autocomplete;
		
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'data.channel/index',
        modify_url: 'data.channel/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh'],
                cols: [[
                    {field: 'id', width: 80, title: 'ID',search: false},
                    {field: 'channelCode', minWidth: 0, title: '推广渠道',searchOp: '=',sort:true},
                    {field: 'sj_num', Width: 80, title: '安装数', search: false,sort:true},	
                    // {field: 'sum', Width: 80, title: '安装量(扣量后)', search: false,sort:true},
                    {field: 'clicks', Width: 80, title: '点击数', search: false,sort:true},
                    // {field: 'downfinish', Width: 80, title: '下载数', search: false,sort:true},
                    // {field: 'estimate', Width: 80, title: '预估(按点击)', search: false,sort:true},
                    // {field: 'ratio', Width: 80, title: '扣量比例', search: false,sort:true},
                    // {field: 'autoc', Width: 80, title: '自动调整', search: false,selectList: {0: '关闭', 1: '<font color="red"><b>开启</b></font>'}},
                    {field: 'remark', minWidth: 0, title: '备注',search:false},
                    {field: 'status', Width: 80, title: '结算状态', search: 'select', selectList: {0: '未结算', 1: '已结算'}, templet: ea.table.switch},
                    {field: 'date', minWidth: 0, title: '查询日期',search: 'range',delete:true},
                ]],
            });
            ea.listen();
        },
        report: function () {
            var init = {
                table_elem: '#currentTable',
                table_render_id: 'currentTableRenderId',
                index_url: 'data.channel/report',
            };
           
            ea.table.render({
                totalRow: true,
                init: init,
                toolbar: ['refresh'],
                limit:200,
                cols: [[
                    {field: 'channelCode', minWidth: 110, title: '渠道id',searchOp: '=',sort:true,totalRowText: '总计:', fixed:'left'},
                    {field: 'remark', minWidth: 140, title: '渠道名称',search:false,sort:true},
                    {field: 'pv', minWidth: 120, title: 'pv', search: false,sort:true},
                    {field: 'down_pv', minWidth: 120, title: '下载pv', search: false,sort:true},
                    {field: 'uv', minWidth: 120, title: 'uv', search: false,sort:true},
                    {field: 'sj_num', minWidth: 120, title: '安装数', search: false,sort:true, totalRow: '{{ parseInt(d.TOTAL_NUMS*10)/10 || 0 }}',},
                    {field: 'sum', minWidth: 120, title: '扣量安装',sort:true},
                    {field: 'zhuanhua', minWidth: 120, title: '转化',sort:true},
                    {field: 'clicks', minWidth: 100, title: '点击数', search: false,sort:true},
                    {field: 'djb', minWidth: 100, title: '点击比', search: false,sort:true},
                    {field: 'price', minWidth: 100, title: '单价(元)', search: false,sort:true},
                    {field: 'djcb', minWidth: 110, title: '点击成本(元)', search: false,sort:true},
                    {field: 'trcb', minWidth: 120, title: '投入成本(元)',searchOp: '=',sort:true},
                    
                    {field: 'down_uv', minWidth: 120, title: '下载uv', search: false,sort:true},
                    // {field: 'auto_down_pv', minWidth: 120, title: '自动下载pv', search: false,sort:true},
                    // {field: 'auto_down_uv', minWidth: 120, title: '自动下载uv', search: false,sort:true},
                    
                    
                    
                    // {field: 'sum', minWidth: 120, title: '安装量(扣量后)', search: false,sort:true},
                   
                    // {field: 'sjdj', minWidth: 110, title: '实际单价(元)', search: false,sort:true},
                    
                    // {field: 'shows', minWidth: 100, title: '展示数', search: false,sort:true},
                    // {field: 'zsb', minWidth: 100, title: '展示比', search: false,sort:true},
                    
                    
                    // {field: 'downfinish', minWidth: 100, title: '下载数', search: false,sort:true},
                    // {field: 'xzb', minWidth: 100, title: '下载比', search: false,sort:true},
                    {field: 'date', minWidth: 110, title: '查询日期',search: 'range',delete:true},
                   
                    {field: 'bc_clicks', minWidth: 100, title: '博彩点击数', search: false,sort:true},
                    {field: 'bc_djb', minWidth: 100, title: '博彩点击比', search: false,sort:true},
                    {field: 'bofangqi_clicks', minWidth: 100, title: '播放器点击数', search: false,sort:true},
                    {field: 'bofangqi_djb', minWidth: 100, title: '播放器点击比', search: false,sort:true},
                    {field: 'paotai_clicks', minWidth: 100, title: '炮台点击数', search: false,sort:true},
                    {field: 'paotai_djb', minWidth: 100, title: '炮台点击比', search: false,sort:true},
                    {field: 'zhibo_clicks', minWidth: 100, title: '直播点击数', search: false,sort:true},
                    {field: 'zhibo_djb', minWidth: 100, title: '直播点击比', search: false,sort:true},
        
                ]],
            });
            ea.listen();
        }
    };
    return Controller;
});

