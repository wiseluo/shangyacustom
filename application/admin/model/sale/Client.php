<?php

namespace app\admin\model\sale;

use think\Model;
use traits\model\SoftDelete;

class Client extends Model
{

    use SoftDelete;

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'sale_client';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'gender_text',
        'follow_type_text',
        'intention_level_text',
        'intention_product_text',
        'intention_room_text',
        'house_purpose_text',
        'intention_house_text',
        'intention_area_text',
        'intention_price_range_text',
        'client_type_text'
    ];
    

    
    public function getGenderList()
    {
        return ['male' => __('Gender male'), 'female' => __('Gender female'), 'secrecy' => __('Gender secrecy')];
    }

    public function getFollowTypeList()
    {
        return ['incall' => __('Follow_type incall'), 'tocall' => __('Follow_type tocall')];
    }

    public function getIntentionLevelList()
    {
        return ['A' => __('Intention_level a'), 'B' => __('Intention_level b'), 'C' => __('Intention_level c'), 'D' => __('Intention_level d')];
    }

    public function getIntentionProductList()
    {
        return ['one-house' => __('Intention_product one-house'), 'two-house' => __('Intention_product two-house'), 'three-house' => __('Intention_product three-house'), 'four-house' => __('Intention_product four-house')];
    }

    public function getIntentionRoomList()
    {
        return ['one-room' => __('Intention_room one-room'), 'two-room' => __('Intention_room two-room'), 'three-room' => __('Intention_room three-room'), 'four-room-above' => __('Intention_room four-room-above')];
    }

    public function getHousePurposeList()
    {
        return ['school' => __('House_purpose school'), 'pension' => __('House_purpose pension'), 'live-invest' => __('House_purpose live-invest'), 'holiday' => __('House_purpose holiday')];
    }

    public function getIntentionHouseList()
    {
        return ['multiple' => __('Intention_house multiple'), 'bungalow' => __('Intention_house bungalow')];
    }

    public function getIntentionAreaList()
    {
        return ['60m²' => __('Intention_area 60m²'), '60m²-80m²' => __('Intention_area 60m²-80m²'), '100m²-120m²' => __('Intention_area 100m²-120m²'), '120m²-150m²' => __('Intention_area 120m²-150m²'), '150m²-200m²' => __('Intention_area 150m²-200m²'), '200m²' => __('Intention_area 200m²')];
    }

    public function getIntentionPriceRangeList()
    {
        return ['1.5-1.7W' => __('Intention_price_range 1.5-1.7w'), '1.7-1.8W' => __('Intention_price_range 1.7-1.8w'), '1.8-1.9W' => __('Intention_price_range 1.8-1.9w'), '1.9W' => __('Intention_price_range 1.9w')];
    }

    public function getClientTypeList()
    {
        return ['personal' => __('Client_type personal'), 'company' => __('Client_type company')];
    }


    public function getGenderTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['gender']) ? $data['gender'] : '');
        $list = $this->getGenderList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getFollowTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['follow_type']) ? $data['follow_type'] : '');
        $list = $this->getFollowTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIntentionLevelTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['intention_level']) ? $data['intention_level'] : '');
        $list = $this->getIntentionLevelList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIntentionProductTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['intention_product']) ? $data['intention_product'] : '');
        $list = $this->getIntentionProductList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIntentionRoomTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['intention_room']) ? $data['intention_room'] : '');
        $list = $this->getIntentionRoomList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getHousePurposeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['house_purpose']) ? $data['house_purpose'] : '');
        $list = $this->getHousePurposeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIntentionHouseTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['intention_house']) ? $data['intention_house'] : '');
        $list = $this->getIntentionHouseList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIntentionAreaTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['intention_area']) ? $data['intention_area'] : '');
        $list = $this->getIntentionAreaList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIntentionPriceRangeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['intention_price_range']) ? $data['intention_price_range'] : '');
        $list = $this->getIntentionPriceRangeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getClientTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['client_type']) ? $data['client_type'] : '');
        $list = $this->getClientTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
