<?php

namespace app\sale\Validate;

use think\Validate;

class SaleValidate extends Validate
{

    protected $rule = [
        'phone' => 'require|integer|checkPhone',
        'username' => 'require|max:20',
    ];
    
    protected $message = [
        'phone.require' => '手机号必填',
        'phone.integer' => '手机号必须是数字',
        'username.require' => '姓名必填',
        'username.max' => '姓名不能超过20位',
    ];
    
    protected $scene = [
        'authApply' => ['phone', 'username'],
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

