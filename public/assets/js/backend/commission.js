define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'commission/index' + location.search,
                    add_url: 'commission/add',
                    edit_url: 'commission/edit',
                    del_url: 'commission/del',
                    multi_url: 'commission/multi',
                    table: 'commission',
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
                        {field: 'user_nickname', title: __('User_nickname')},
                        {field: 'customer_name', title: __('Customer_name')},
                        {field: 'house_name', title: __('House_name')},
                        {field: 'house_price', title: __('House_price'), operate:'BETWEEN'},
                        {field: 'commission_rate', title: __('Commission_rate'), operate:'BETWEEN'},
                        {field: 'commission', title: __('Commission'), operate:'BETWEEN'},
                        {field: 'description', title: __('Description')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
				userlist: function () {
				    // 初始化表格参数配置
				    Table.api.init({
				        extend: {
				            index_url: 'commission/userlist' + location.search,
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
				                {field: 'username', title: __('username')},
				                {field: 'phone', title: __('phone')}
				            ]
				        ]
				    });
				
				    // 为表格绑定事件
				    Table.api.bindevent(table);
				},
				usercustomer: function () {
				    // 初始化表格参数配置
				    Table.api.init({
				        extend: {
				            index_url: 'commission/usercustomer/id/' + Fast.api.query('id'),
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
				                {field: 'customer_id', title: __('Id')},
				                {field: 'name', title: __('Customer_name')},
				                {field: 'phone', title: __('phone')},
				                {field: 'house_name', title: __('house_name')},
				                {field: 'tag', title: __('house_tag')},
				                {field: 'house_id', title: __('house_id')},
				            ]
				        ]
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
                url: 'commission/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
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
                                    url: 'commission/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'commission/destroy',
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
						$('.user').click(function(){
								Fast.api.open('commission/userlist', __('User_nickname'));
						})
						$('.customer').click(function(){
								let id = $('#c-user_id').val()
								
								Fast.api.open('commission/usercustomer/id/' + id, __('Customer_name'));
						})
            Controller.api.bindevent();
        },
        edit: function () {
						$('.user').click(function(){
								Fast.api.open('commission/userlist', __('User_nickname'));
						})
						$('.customer').click(function(){
								let id = $('#c-user_id').val()
								Fast.api.open('commission/usercustomer/id/' + id, __('Customer_name'));
						})
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