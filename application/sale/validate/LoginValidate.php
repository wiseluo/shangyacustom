<?php

namespace app\sale\Validate;

use think\Validate;

class LoginValidate extends Validate
{
    protected $rule = [
        'code' => 'require',
    ];
    
    protected $message = [
        'code.require' => '授权码必填',
    ];
    
    protected $scene = [
        'wechatAppletLogin' => ['code'],
    ];
    
}

