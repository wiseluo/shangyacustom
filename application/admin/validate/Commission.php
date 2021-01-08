<?php

namespace app\admin\validate;

use think\Validate;

class Commission extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'user_id' => 'require|integer',
        'customer_id' => 'require|integer',
        'house_id' => 'require|integer',
        'house_price' => 'require|number|gt:0',
        'commission_rate' => 'require|number|gt:0',
        'commission' => 'require|number|gt:0',
    ];
    /**
     * 提示消息
     */
    protected $message = [
        'user_id.require' => '推介人id必填',
        'user_id.integer' => '推介人id必须是整数',
        'customer_id.require' => '客户id必填',
        'customer_id.integer' => '客户id必须是整数',
        'house_id.require' => '楼盘id必填',
        'house_id.integer' => '楼盘id必须是整数',
        'house_price.require' => '楼盘价格必填',
        'house_price.number' => '楼盘价格必须是数字',
        'commission_rate.require' => '预计佣金比例必填',
        'commission_rate.number' => '预计佣金比例必须是数字',
        'commission.require' => '预计佣金必填',
        'commission.number' => '预计佣金必须是数字',
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => [],
        'edit' => [],
        'save' => ['user_id', 'customer_id', 'house_id', 'house_price', 'commission_rate', 'commission'],
    ];
    
}
