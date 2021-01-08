<?php

namespace app\sale\model;

use think\Model;
use traits\model\SoftDelete;

/**
 * 客户模型
 */
class Client extends Model
{
    use SoftDelete;
    // 表名
    protected $name = 'sale_client';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    // protected $append = [
    //     'gender_text',
    //     'follow_type_text',
    //     'intention_level_text',
    //     'intention_product_text',
    //     'intention_room_text',
    //     'house_purpose_text',
    //     'intention_house_text',
    //     'intention_area_text',
    //     'intention_price_range_text',
    //     'client_type_text'
    // ];
    
    public function getGenderList($value)
    {
        $list = ['male' => '男', 'female' => '女', 'secrecy' => '保密'];
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getFollowTypeList($value)
    {
        $list = ['incall' => '来电', 'tocall' => '去电'];
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getIntentionLevelList($value)
    {
        $list = ['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D'];
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getIntentionProductList($value)
    {
        $list = ['one-house' => '一房', 'two-house' => '二房', 'three-house' => '三房', 'four-house' => '四房'];
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getIntentionRoomList($value)
    {
        $list = ['one-room' => '一室', 'two-room' => '两室', 'three-room' => '三室', 'four-room-above' => '四室及以上'];
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getHousePurposeList($value)
    {
        $list = ['school' => '为子女上学', 'pension' => '养老', 'live-invest' => '自住兼投资', 'holiday' => '度假'];
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getIntentionHouseList($value)
    {
        $list = ['multiple' => '复式', 'bungalow' => '平房'];
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getIntentionAreaList($value)
    {
        $list = ['60m²' => '60m²以下', '60m²-80m²' => '60m²-80m²', '100m²-120m²' => '100m²-120m²', '120m²-150m²' => '120m²-150m²', '150m²-200m²' => '150m²-200m²', '200m²' => '200m²以上'];
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getIntentionPriceRangeList($value)
    {
        $list = ['1.5-1.7W' => '1.5-1.7W', '1.7-1.8W' => '1.7-1.8W', '1.8-1.9W' => '1.8-1.9W', '1.9W' => '1.9W以上'];
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getClientTypeList($value)
    {
        $list = ['personal' => '个人', 'company' => '公司'];
        return isset($list[$value]) ? $list[$value] : '';
    }

}
