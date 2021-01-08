<?php

namespace app\sale\Validate;

use think\Validate;

class ClientFollowValidate extends Validate
{

    protected $rule = [
        'client_id' => 'require|integer',
        'follow_time' => 'require|dateFormat:Y-m-d H:i:s',
        'content' => 'require',
        'follow_type' => 'require',
    ];
    
    protected $message = [
        'client_id.require' => '客户id必填',
        'follow_time.require' => '跟进时间必填',
        'content.require' => '跟进内容必填',
        'follow_type.require' => '跟进方式必填',
    ];
    
    protected $scene = [
        'save' => ['client_id', 'follow_time', 'content', 'follow_type'],
    ];
    
}

