<?php

namespace app\admin\validate\sale;

use think\Validate;

class Client extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'user_id' => 'require|integer',
        'client_ids' => 'require',
    ];
    /**
     * 提示消息
     */
    protected $message = [
        'user_id.require' => '销售员id必填',
        'user_id.integer' => '销售员id必须是整数',
        'client_ids.require' => '客户id必填',
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => [],
        'edit' => [],
        'assignsalesman'  => ['adviser_id', 'client_ids'],
        'assignpublic' => ['client_ids'],
    ];
    
}
