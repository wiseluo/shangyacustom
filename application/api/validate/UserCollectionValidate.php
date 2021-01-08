<?php

namespace app\api\Validate;

use think\Validate;

class UserCollectionValidate extends Validate
{

    protected $rule = [
        'house_id' => 'require|integer',
    ];
    
    protected $message = [
        'house_id.require' => '楼盘id必填',
        'house_id.integer' => '楼盘id必须是数字',
    ];
    
    protected $scene = [
        'save' => ['house_id'],
    ];
    
}

