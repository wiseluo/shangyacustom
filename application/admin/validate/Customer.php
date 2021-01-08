<?php

namespace app\admin\validate;

use think\Validate;

class Customer extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'adviser_id' => 'require|integer',
        'customer_ids' => 'require',
        'customer_id' => 'require|integer',
        'contact' => 'require',
        'visit_way' => 'require',
        'visit_time' => 'require',
        'content' => 'require',
    ];
    /**
     * 提示消息
     */
    protected $message = [
        'adviser_id.require' => '销售顾问id必填',
        'adviser_id.integer' => '销售顾问id必须是整数',
        'customer_ids.require' => '客户id必填',
        'customer_id.require' => '客户id必填',
        'customer_id.integer' => '客户id必须是整数',
        'contact.require' => '联系人必填',
        'visit_way.require' => '拜访方式必填',
        'visit_time.require' => '拜访时间必填',
        'content.require' => '拜访内容必填',
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => [],
        'edit' => [],
        'assignsalesman'  => ['adviser_id', 'customer_ids'],
        'assignpublic' => ['customer_ids'],
        'visitcustomer' => ['customer_id', 'contact', 'visit_way', 'visit_time', 'content'],
    ];
    
}
