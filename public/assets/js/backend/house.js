define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'house/index' + location.search,
                    add_url: 'house/add',
                    edit_url: 'house/edit',
                    del_url: 'house/del',
                    multi_url: 'house/multi',
                    table: 'house',
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
                        {field: 'price_sqm', title: __('Price_sqm'), operate:'BETWEEN'},
                        {field: 'area', title: __('Area'), operate:'BETWEEN'},
                        {field: 'total_price', title: __('Total_price'), operate:'BETWEEN'},
                        {field: 'type', title: __('Type')},
                        {field: 'property_company', title: __('Property_company')},
                        {field: 'commission_rate', title: __('Commission_rate')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
												buttons: [
													{
														name: 'houseadmin',
														text: __('关联销售员'),
														title: __('关联销售员'),
														classname: 'btn btn-xs btn-primary btn-dialog',
														icon: 'fa fa-angellist',
														url: 'houseadmin/index',
														visible: function (row) {
															//返回true时按钮显示,返回false隐藏
															return true;
														}
													},
													{
														name: 'houselayout',
														text: __('关联户型'),
														title: __('关联户型'),
														classname: 'btn btn-xs btn-primary btn-dialog',
														icon: 'fa fa-angellist',
														url: 'houselayout/index',
														visible: function (row) {
															//返回true时按钮显示,返回false隐藏
															return true;
														}
													}
												],
												formatter: function (value, row, index) { //隐藏自定义的关联按钮
														var that = $.extend({}, this);
														var table = $(that.table).clone(true);
														//权限判断
														if(Config.houseadmin != true){  //通过Config.houseadmin 获取后台存的houseadmin
																console.log('没有关联权限');
																$(table).data("operate-houseadmin", null); 
																that.table = table;
														}
														if(Config.houselayout != true){  //通过Config.houselayout 获取后台存的houselayout
																console.log('没有关联权限');
																$(table).data("operate-houselayout", null); 
																that.table = table;
														}
														return Table.api.formatter.operate.call(that, value, row, index);
												}}
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
                url: 'house/recyclebin' + location.search,
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
                                    url: 'house/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'house/destroy',
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
            require(['bootstrap-datetimepicker'], function () {
                var options = {
                    format: 'YYYY-MM-DD',
                    icons: {
                        time: 'fa fa-clock-o',
                        date: 'fa fa-calendar',
                        up: 'fa fa-chevron-up',
                        down: 'fa fa-chevron-down',
                        previous: 'fa fa-chevron-left',
                        next: 'fa fa-chevron-right',
                        today: 'fa fa-history',
                        clear: 'fa fa-trash',
                        close: 'fa fa-remove'
                    },
                    showTodayButton: true,
                    showClose: true,
                    ignoreReadonly: true
                };
                $('.datetimepicker').parent().css('position', 'relative');
                $('.datetimepicker').datetimepicker(options);
            });
            Controller.api.bindevent();
        },
        edit: function () {
            require(['bootstrap-datetimepicker'], function () {
                var options = {
                    format: 'YYYY-MM-DD',
                    icons: {
                        time: 'fa fa-clock-o',
                        date: 'fa fa-calendar',
                        up: 'fa fa-chevron-up',
                        down: 'fa fa-chevron-down',
                        previous: 'fa fa-chevron-left',
                        next: 'fa fa-chevron-right',
                        today: 'fa fa-history',
                        clear: 'fa fa-trash',
                        close: 'fa fa-remove'
                    },
                    showTodayButton: true,
                    showClose: true,
                    ignoreReadonly: true
                };
                $('.datetimepicker_edit').parent().css('position', 'relative');
                $('.datetimepicker_edit').datetimepicker(options);
            });
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