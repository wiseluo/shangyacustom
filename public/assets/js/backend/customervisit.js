define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    add_url: 'customervisit/add/customer_id/' + Fast.api.query('ids')
                }
            });

            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: 'customervisit/index/ids/' + Fast.api.query('ids'),
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'contact', title: __('contact')},
                        {field: 'visit_way', title: __('visit_way')},
                        {field: 'visit_time', title: __('visit_time')},
                        {field: 'content', title: __('content')}
                    ]
                ],
                search: false,
                pagination: false
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
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});