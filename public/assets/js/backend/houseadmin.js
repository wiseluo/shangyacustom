define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    add_url: 'houseadmin/salesman/house_id/' + Fast.api.query('ids'),
                    cancel_url: 'houseadmin/del/id/'
                }
            });

            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: 'houseadmin/index/ids/' + Fast.api.query('ids'),
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'nickname', title: __('nickname')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                        buttons: [
                            {
                                name: 'zidingyi',
                                text: __('取消关联'),
                                title: __('取消关联'),
                                classname: 'btn btn-xs btn-danger btn-cancel',
                                url: $.fn.bootstrapTable.defaults.extend.cancel_url,
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

        salesman: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'houseadmin/salesman',
                    table: 'admin',
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
                        {field: 'nickname', title: __('nickname')}
                    ]
                ],
                search: false,
                pagination: false
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
    };
    return Controller;
});