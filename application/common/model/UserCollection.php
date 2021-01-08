<?php

namespace app\common\model;

use think\Model;

class UserCollection extends Model
{

    // 表名
    protected $name = 'user_collection';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    // 追加属性
    protected $append = [
    ];

}
