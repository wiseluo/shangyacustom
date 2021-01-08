<?php

namespace app\api\Validate;

use think\Validate;

class SmsValidate extends Validate
{
    protected $rule = [
        'phone' => 'require|integer|checkPhone',
    ];
    
    protected $message = [
        'phone.require' => '手机号必填',
        'phone.integer' => '手机号必须是数字',
    ];
    
    protected $scene = [
        'smsCode' => ['phone'],
    ];
    
    public function checkPhone($value, $rule, $data)
    {
        $match = '/^(13|14|15|17|18)[0-9]{9}$/';
        $result = preg_match($match, $value);
        if($result) {
            return true;
        }else{
            return '手机号不正确';
        }
    }
}

