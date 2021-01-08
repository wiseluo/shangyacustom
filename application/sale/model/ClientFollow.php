<?php

namespace app\sale\model;

use think\Model;
use traits\model\SoftDelete;

/**
 * 客户跟进模型
 */
class ClientFollow extends Model
{
    use SoftDelete;
    // 表名
    protected $name = 'sale_client_follow';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'follow_type_text',
    ];

    public function getFollowTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['follow_type']) ? $data['follow_type'] : '');
        $list = ['incall' => '来电', 'tocall' => '去电'];
        return isset($list[$value]) ? $list[$value] : '';
    }
}
