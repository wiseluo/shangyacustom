define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    add_url: 'houselayout/layout/house_id/' + Fast.api.query('ids'),
                    cancel_url: 'houselayout/del/id/'
                }
            });
        
            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: 'houselayout/index/ids/' + Fast.api.query('ids'),
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('layoutName')},
                        {field: 'tag', title: __('Tag')},
                        {field: 'build_area', title: __('Build_area'), operate:'BETWEEN'},
                        {field: 'dwelling_area', title: __('Dwelling_area'), operate:'BETWEEN'},
                        {field: 'lower_total_price', title: __('Lower_total_price'), operate:'BETWEEN'},
                        {field: 'upper_total_price', title: __('Upper_total_price'), operate:'BETWEEN'},
                        {field: 'interval', title: __('Interval')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                        buttons: [
                            {
                                name: 'zidingyi',
                                text: __('取消关联'),
                                title: __('取消关联'),
                                classname: 'btn btn-xs btn-danger btn-cancel',
                                url: 'houselayout/del',
                                icon: 'fa fa-trash',
                                visible: function (row) {
                                    //返回true时按钮显示,返回false隐藏
                                    return true;
                                }
                            }   
                        ]}
                    ]
                ],
                search: false,
                pagination: false
            });
        
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        layout: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'houselayout/layout/house_id/' + $('.house_id').val(),
                    table: 'layout',
                }
            });
        
            var table = $("#table");
        
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name')},
                        {field: 'tag', title: __('Tag')},
                        {field: 'build_area', title: __('Build_area'), operate:'BETWEEN'},
                        {field: 'dwelling_area', title: __('Dwelling_area'), operate:'BETWEEN'},
                        {field: 'lower_total_price', title: __('Lower_total_price'), operate:'BETWEEN'},
                        {field: 'upper_total_price', title: __('Upper_total_price'), operate:'BETWEEN'},
                        {field: 'interval', title: __('Interval')},
                        {field: 'layout_images', title: __('Layout_images'), events: Table.api.events.image, formatter: Table.api.formatter.images},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime}
                    ]
                ]
            });
        
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
    };
    return Controller;
});