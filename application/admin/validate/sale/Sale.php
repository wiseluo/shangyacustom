<?php

namespace app\admin\validate\sale;

use think\Validate;

class Sale extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'status' => 'require|in:2,3'
    ];
    /**
     * 提示消息
     */
    protected $message = [
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => [],
        'edit' => [],
        'examine' => ['status'],
    ];
    
}
