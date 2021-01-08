define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'sale/client/index' + location.search,
                    assignpublic_url: 'sale/client/assignpublic',
                    assignsalesman_url: 'sale/client/assignsalesman',
                    table: 'sale_client',
                }
            });

            //移入公海
            $(document).on('click', '#toolbar .btn-assignpublic', function () {
                var that = this;
                var ids = Table.api.selectedids(table).join(",");
                var params = {
                    url: $.fn.bootstrapTable.defaults.extend.assignpublic_url,
                    data: {
                        client_ids: ids,
                    }
                }
                Layer.confirm(__('是否移入公海?'), function () {
                    Fast.api.ajax(params,function () {
                        Layer.closeAll();
                        table.bootstrapTable('refresh');
                    }, function () {
                        Layer.closeAll();
                    });
                });
                return false;
            });
            //分配客户给销售员
            $(document).on('click', '#toolbar .btn-assignsalesman', function () {
                var that = this;
                var ids = Table.api.selectedids(table).join(",");
                var url = $.fn.bootstrapTable.defaults.extend.assignsalesman_url + '?client_ids='+ids
                Fast.api.open(url, __('Assignsalesman'), $(that).data() || {});
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
                        {field: 'username', title: __('Username')},
                        {field: 'name', title: __('Name')},
                        {field: 'phone', title: __('Phone')},
                        {field: 'gender', title: __('Gender'), searchList: {"male":__('Gender male'),"female":__('Gender female'),"secrecy":__('Gender secrecy')}, formatter: Table.api.formatter.normal},
                        {field: 'public', title: __('Public'), searchList: {"0":__('Public 0'),"1":__('Public 1')}, formatter: Table.api.formatter.normal},
                        {field: 'label', title: __('Label')},
                        {field: 'follow_type', title: __('Follow_type'), searchList: {"incall":__('Follow_type incall'),"tocall":__('Follow_type tocall')}, formatter: Table.api.formatter.normal},
                        {field: 'intention_level', title: __('Intention_level'), searchList: {"A":__('Intention_level a'),"B":__('Intention_level b'),"C":__('Intention_level c'),"D":__('Intention_level d')}, formatter: Table.api.formatter.normal},
                        {field: 'register_instruction', title: __('Register_instruction')},
                        {field: 'client_type', title: __('Client_type'), searchList: {"personal":__('Client_type personal'),"company":__('Client_type company')}, formatter: Table.api.formatter.normal},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        assignsalesman: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'sale/sale/index',
                    table: 'assignsalesman',
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
                        {field: 'username', title: __('username')}
                    ]
                ],
                search: false,
                pagination: false
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