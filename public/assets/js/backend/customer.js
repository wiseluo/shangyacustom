define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'customer/index' + location.search,
                    assignpublic_url: 'customer/assignpublic',
                    assignsalesman_url: 'customer/assignsalesman',
                    table: 'customer',
                }
            });

            //移入公海
            $(document).on('click', '#toolbar .btn-assignpublic', function () {
                var that = this;
                var ids = Table.api.selectedids(table).join(",");
                var params = {
                    url: $.fn.bootstrapTable.defaults.extend.assignpublic_url,
                    data: {
                        customer_ids: ids,
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
                var url = $.fn.bootstrapTable.defaults.extend.assignsalesman_url + '?customer_ids='+ids
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
                        {field: 'user_nickname', title: __('User_nickname')},
                        {field: 'name', title: __('Name')},
                        {field: 'phone', title: __('Phone')},
                        {field: 'identity_card', title: __('Identity_card')},
                        {field: 'gender', title: __('Gender'), searchList: {"male":__('Gender male'),"female":__('Gender female'),"secrecy":__('Gender secrecy')}, formatter: Table.api.formatter.normal},
                        {field: 'house_name', title: __('House_name')},
                        {field: 'adviser_nickname', title: __('Adviser_nickname')},
                        {field: 'public', title: __('Public'), searchList: {"0":__('Public 0'),"1":__('Public 1')}, formatter: Table.api.formatter.normal},
                        {field: 'intention_room_data', title: __('Intention_room_data'), searchList: {"one-room":__('Intention_room_data one-room'),"two-room":__('Intention_room_data two-room'),"three-room":__('Intention_room_data three-room'),"four-room":__('Intention_room_data four-room'),"five-room-above":__('Intention_room_data five-room-above')}, operate:'FIND_IN_SET', formatter: Table.api.formatter.label},
                        {field: 'intention_area_data', title: __('Intention_area_data'), searchList: {"60m²":__('Intention_area_data 60m²'),"60m²-80m²":__('Intention_area_data 60m²-80m²'),"100m²-120m²":__('Intention_area_data 100m²-120m²'),"120m²-150m²":__('Intention_area_data 120m²-150m²'),"150m²-200m²":__('Intention_area_data 150m²-200m²'),"200m²":__('Intention_area_data 200m²')}, operate:'FIND_IN_SET', formatter: Table.api.formatter.label},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate, 
                            buttons: [
                                {
                                    name: 'customervisit',
                                    text: __('拜访客户'),
                                    title: __('拜访客户'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-angellist',
                                    url: 'customervisit/index',
                                    visible: function (row) {
                                        //返回true时按钮显示,返回false隐藏
                                        return true;
                                    }
                                }
                            ]
                        }
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
                    index_url: 'houseadmin/salesman',
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
                        {field: 'nickname', title: __('nickname')}
                    ]
                ],
                search: false,
                pagination: false
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
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
                url: 'customer/recyclebin' + location.search,
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
                                    url: 'customer/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'customer/destroy',
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
        // add: function () {
        //     Controller.api.bindevent();
        // },
        // edit: function () {
        //     Controller.api.bindevent();
        // },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});