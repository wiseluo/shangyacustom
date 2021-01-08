<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Customer extends Model
{

    use SoftDelete;

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'customer';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'gender_text',
        'intention_room_data_text',
        'intention_area_data_text'
    ];
    
    
    public function getPublicList()
    {
        return ['0' => __('Public 0'), '1' => __('Public 1')];
    }

    
    public function getGenderList()
    {
        return ['male' => __('Gender male'), 'female' => __('Gender female'), 'secrecy' => __('Gender secrecy')];
    }

    public function getIntentionRoomDataList()
    {
        return ['one-room' => __('Intention_room_data one-room'), 'two-room' => __('Intention_room_data two-room'), 'three-room' => __('Intention_room_data three-room'), 'four-room' => __('Intention_room_data four-room'), 'five-room-above' => __('Intention_room_data five-room-above')];
    }

    public function getIntentionAreaDataList()
    {
        return ['60m²' => __('Intention_area_data 60m²'), '60m²-80m²' => __('Intention_area_data 60m²-80m²'), '100m²-120m²' => __('Intention_area_data 100m²-120m²'), '120m²-150m²' => __('Intention_area_data 120m²-150m²'), '150m²-200m²' => __('Intention_area_data 150m²-200m²'), '200m²' => __('Intention_area_data 200m²')];
    }


    public function getGenderTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['gender']) ? $data['gender'] : '');
        $list = $this->getGenderList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIntentionRoomDataTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['intention_room_data']) ? $data['intention_room_data'] : '');
        $valueArr = explode(',', $value);
        $list = $this->getIntentionRoomDataList();
        return implode(',', array_intersect_key($list, array_flip($valueArr)));
    }


    public function getIntentionAreaDataTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['intention_area_data']) ? $data['intention_area_data'] : '');
        $valueArr = explode(',', $value);
        $list = $this->getIntentionAreaDataList();
        return implode(',', array_intersect_key($list, array_flip($valueArr)));
    }

    protected function setIntentionRoomDataAttr($value)
    {
        return is_array($value) ? implode(',', $value) : $value;
    }

    protected function setIntentionAreaDataAttr($value)
    {
        return is_array($value) ? implode(',', $value) : $value;
    }


}
