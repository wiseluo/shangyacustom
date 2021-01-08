<?php

namespace app\api\Validate;

use think\Validate;

class WorktableValidate extends Validate
{

    protected $rule = [
        'name' => 'require|max:20',
        'phone' => 'require|integer|checkPhone',
        'type' => 'require|in:1,2',
    ];
    
    protected $message = [
        'name.require' => '客户姓名必填',
        'name.max' => '客户姓名不能超过20位',
        'phone.require' => '手机号必填',
        'phone.integer' => '手机号必须是数字',
        'type.require' => '类型必填',
    ];
    
    protected $scene = [
        'save' => ['name', 'phone', 'type'],
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

