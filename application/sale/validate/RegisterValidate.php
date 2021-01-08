<?php

namespace app\sale\Validate;

use think\Validate;

class RegisterValidate extends Validate
{

    protected $rule = [
        'openid' => 'require',
        'phone' => 'require|integer|checkPhone',
        'username' => 'require|max:20',
        'nickname' => 'require|max:50',
        'avatar' => 'require',
        'gender' => 'require|in:0,1,2',
    ];
    
    protected $message = [
        'openid.require' => '微信标识必填',
        'phone.require' => '手机号必填',
        'phone.integer' => '手机号必须是数字',
        'username.require' => '姓名必填',
        'username.max' => '姓名不能超过20位',
        'nickname.require' => '昵称必填',
        'nickname.max' => '昵称不能超过50位',
        'avatar.require' => '头像必填',
        'gender.require' => '性别必填',
    ];
    
    protected $scene = [
        'wechatRegister' => ['openid', 'phone', 'username', 'nickname', 'avatar', 'gender'],
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

