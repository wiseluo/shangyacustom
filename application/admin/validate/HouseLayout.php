<?php

namespace app\admin\validate;

use think\Validate;

class HouseLayout extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'house_id' => 'require|integer',
        'layout_ids' => 'require',

    ];
    /**
     * 提示消息
     */
    protected $message = [
        'house_id.require' => '楼盘id必填',
        'house_id.integer' => '楼盘id必须是整数',
        'layout_ids.require' => '户型id必填',
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'save' => ['house_id','layout_ids']
    ];
    
}
