<?php

namespace app\api\Validate;

use think\Validate;
use app\api\common\IdentityCardTool;

class RegisterValidate extends Validate
{

    protected $rule = [
        'openid' => 'require',
        'username' => 'require|max:20',
        'identity_card' => 'require|checkIdentityCard',
        'phone' => 'require|integer|checkPhone',
        'nickname' => 'require|max:50',
        'avatar' => 'require',
        'gender' => 'require|in:0,1,2',
    ];
    
    protected $message = [
        'openid.require' => '微信标识必填',
        'username.require' => '姓名必填',
        'username.max' => '姓名不能超过20位',
        'identity_card.require' => '证件号码必填',
        'phone.require' => '手机号必填',
        'phone.integer' => '手机号必须是数字',
        'nickname.require' => '昵称必填',
        'nickname.max' => '昵称不能超过50位',
        'avatar.require' => '头像必填',
        'gender.require' => '性别必填',
    ];
    
    protected $scene = [
        'wechatRegister' => ['openid', 'username', 'identity_card', 'phone', 'nickname', 'avatar', 'gender'],
    ];
    
    public function checkIdentityCard($value, $rule, $data)
    {
        return true; /* to delete */

        if (IdentityCardTool::isValid($value)) {
            return true;
        } else {
            return '证件号码不是一个合法的证件号码';
        }
    }
    
    public function checkPhone($value, $rule, $data)
    {
        return true; /* to delete */

        $match = '/^(13|14|15|17|18)[0-9]{9}$/';
        $result = preg_match($match, $value);
        if($result) {
            return true;
        }else{
            return '手机号不正确';
        }
    }
}

