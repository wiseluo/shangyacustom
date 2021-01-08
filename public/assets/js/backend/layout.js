define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'layout/index' + location.search,
                    add_url: 'layout/add',
                    edit_url: 'layout/edit',
                    del_url: 'layout/del',
                    multi_url: 'layout/multi',
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
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        // relationlayout: function () {
        //     // 初始化表格参数配置
        //     Table.api.init({
        //         extend: {
        //             index_url: 'layout/index' + location.search,
        //             table: 'layout',
        //         }
        //     });

        //     var table = $("#table");

        //     // 初始化表格
        //     table.bootstrapTable({
        //         url: $.fn.bootstrapTable.defaults.extend.index_url,
        //         pk: 'id',
        //         sortName: 'id',
        //         columns: [
        //             [
        //                 {checkbox: true},
        //                 {field: 'id', title: __('Id')},
        //                 {field: 'name', title: __('Name')},
        //                 {field: 'tag', title: __('Tag')},
        //                 {field: 'build_area', title: __('Build_area'), operate:'BETWEEN'},
        //                 {field: 'dwelling_area', title: __('Dwelling_area'), operate:'BETWEEN'},
        //                 {field: 'lower_total_price', title: __('Lower_total_price'), operate:'BETWEEN'},
        //                 {field: 'upper_total_price', title: __('Upper_total_price'), operate:'BETWEEN'},
        //                 {field: 'interval', title: __('Interval')},
        //                 {field: 'layout_images', title: __('Layout_images'), events: Table.api.events.image, formatter: Table.api.formatter.images},
        //                 {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
        //                 {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime}
        //             ]
        //         ]
        //     });

        //     // 为表格绑定事件
        //     Table.api.bindevent(table);
        // },
        recyclebin: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'dragsort_url': ''
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: 'layout/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name'), align: 'left'},
                        {
                            field: 'deletetime',
                            title: __('Deletetime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            width: '130px',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'Restore',
                                    text: __('Restore'),
                                    classname: 'btn btn-xs btn-info btn-ajax btn-restoreit',
                                    icon: 'fa fa-rotate-left',
                                    url: 'layout/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'layout/destroy',
                                    refresh: true
                                }
                            ],
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});