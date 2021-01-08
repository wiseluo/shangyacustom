<?php

namespace app\sale\Validate;

use think\Validate;

class ClientHouseValidate extends Validate
{

    protected $rule = [
        'client_id' => 'require|integer',
        'house_id' => 'require|integer',
    ];
    
    protected $message = [
        'client_id.require' => '客户id必填',
        'house_id.require' => '楼盘id必填',
    ];
    
    protected $scene = [
        'save' => ['client_id', 'house_id'],
    ];
    
}

