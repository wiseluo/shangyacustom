<?php

namespace app\sale\Validate;

use think\Validate;

class ClientValidate extends Validate
{

    protected $rule = [
        'name' => 'require|max:20',
        'phone' => 'require|integer|checkPhone',
        'gender' => 'require|checkGender',
        'intention_project' => 'require',
        'follow_type' => 'require|checkFollowType',
        'intention_level' => 'require|checkIntentionLevel',
        'register_instruction' => 'require',
        'next_follow_date' => 'require|dateFormat:Y-m-d',
        'intention_product' => 'require|checkIntentionProduct',
        'intention_room' => 'require',
        'house_purpose' => 'require',
        'concern_factor' => 'require',
        'intention_house' => 'require',
        'intention_area' => 'require',
        'intention_price_range' => 'require',
        'client_type' => 'require',
    ];
    
    protected $message = [
        'name.require' => '客户姓名必填',
        'name.max' => '客户姓名不能超过20位',
        'phone.require' => '手机号必填',
        'phone.integer' => '手机号必须是数字',
        'gender.require' => '性别必填',
        'intention_project.require' => '意向项目必填',
        'follow_type.require' => '跟进方式必填',
        'intention_level.require' => '意向级别必填',
        'register_instruction.require' => '登记说明必填',
        'next_follow_date.require' => '下次跟进必填',
        'intention_product.require' => '意向产品必填',
        'intention_room.require' => '意向户型必填',
        'house_purpose.require' => '购房用途必填',
        'concern_factor.require' => '关注因素必填',
        'intention_house.require' => '意向房型必填',
        'intention_area.require' => '意向面积必填',
        'intention_price_range.require' => '意向价格区间必填',
        'client_type.require' => '客户类型必填',
    ];
    
    protected $scene = [
        'save' => ['name', 'phone', 'gender', 'follow_type', 'intention_level', 'register_instruction', 'next_follow_date', 'intention_product', 'intention_room', 'house_purpose', 'concern_factor', 'intention_house', 'intention_area', 'intention_price_range', 'client_type'],
        'update' => ['name', 'phone', 'gender', 'follow_type', 'intention_level', 'register_instruction', 'next_follow_date', 'intention_product', 'intention_room', 'house_purpose', 'concern_factor', 'intention_house', 'intention_area', 'intention_price_range', 'client_type'],
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

    public function checkFollowType($value, $rule, $data)
    {
        if(in_array($value, ['incall','tocall'])) {
            return true;
        }else{
            return '跟进方式不正确';
        }
    }

    public function checkIntentionLevel($value, $rule, $data)
    {
        if(in_array($value, ['A','B','C','D'])) {
            return true;
        }else{
            return '意向级别不正确';
        }
    }

    public function checkIntentionProduct($value, $rule, $data)
    {
        if(in_array($value, ['one-house','two-house','three-house','four-house'])) {
            return true;
        }else{
            return '意向级别不正确';
        }
    }
}

