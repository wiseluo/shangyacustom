define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'sale/sale/index' + location.search,
                    table: 'sale',
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
                        {field: 'phone', title: __('Phone')},
                        {field: 'nickname', title: __('Nickname')},
                        {field: 'username', title: __('Username')},
                        {field: 'identity_card', title: __('Identity_card')},
                        {field: 'avatar', title: __('Avatar'), events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'gender', title: __('Gender'), searchList: {"0":__('Gender 0'),"1":__('Gender 1'),"2":__('Gender 2')}, formatter: Table.api.formatter.normal},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1'),"2":__('Status 2'),"3":__('Status 3')}, formatter: Table.api.formatter.status},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'examine',
                                    text: __('审核'),
                                    title: __('审核'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-angellist',
                                    url: 'sale/sale/examine',
                                    visible: function (row) {
                                        //返回true时按钮显示,返回false隐藏
                                        return true;
                                    }
                                }
                            ],
                            formatter: function (value, row, index) { //隐藏自定义的拜访按钮
                                var that = $.extend({}, this);
                                var table = $(that.table).clone(true);
                                //权限判断
                                if(Config.examine != true){  //通过Config.examine 获取后台存的examine
                                    console.log('没有拜访权限');
                                    $(table).data("operate-examine", null);
                                    that.table = table;
                                }else{
                                    console.log('有拜访权限');
                                }
                                return Table.api.formatter.operate.call(that, value, row, index);
                            }
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        examine: function () {
            var sale_id = $('#c-id').val();
            $(document).find('.submit_btn').click(function(){
                let status = $("input[name='row[status]']:checked").val();
                console.log(status)
                saleExamine(status)
            })
            function saleExamine(status) {
                $.ajax({
                    type: "post",
                    url: "sale/sale/examine/ids/". sale_id,
                    data: {'status': status},
                    dataType: "json",
                    async: false,
                    success: function (res) {
                        //从服务器获取数据进行绑定
                        console.log(res)
                        layer.msg(res.msg)
                        if(res.code == 200) {
                            parent.location.reload()
                        }

                    },
                    error: function () { alert("Error"); }
                });
            }
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