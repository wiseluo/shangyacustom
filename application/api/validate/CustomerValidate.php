<?php

namespace app\api\Validate;

use think\Validate;

class CustomerValidate extends Validate
{

    protected $rule = [
        'name' => 'require|max:20',
        'phone' => 'require|integer|checkPhone',
        'identity_card' => 'require|length:6',
        'gender' => 'require|checkGender',
        'house_id' => 'require|integer',
        'adviser_id' => 'require|max:20',
        'intention_room_data' => 'require',
        'intention_area_data' => 'require',
        'agreement' => 'require|eq:1',
    ];
    
    protected $message = [
        'name.require' => '客户姓名必填',
        'name.max' => '客户姓名不能超过20位',
        'phone.require' => '手机号必填',
        'phone.integer' => '手机号必须是数字',
        'identity_card.require' => '身份证后六位必填',
        'identity_card.length' => '身份证后六位长度错误',
        'gender.require' => '性别必填',
        'house_id.require' => '楼盘ID必填',
        'adviser_id.require' => '跟办顾问必填',
        'intention_room_data.require' => '意向房型必填',
        'intention_area_data.require' => '意向面积必填',
        'agreement.require' => '阅读并同意协议必填',
    ];
    
    protected $scene = [
        'promote' => ['name', 'phone', 'gender', 'house_id', 'adviser_id', 'intention_room_data', 'intention_area_data', 'agreement'],
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

    public function checkGender($value, $rule, $data)
    {
        if(in_array($value, ['male','female','secrecy'])) {
            return true;
        }else{
            return '性别格式不正确';
        }
    }
}

