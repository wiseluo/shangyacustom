<?php

namespace app\admin\controller;

use think\Request;
use app\common\controller\Backend;

/**
 * 地区管理
 *
 * @icon fa fa-circle-o
 */
class Area extends Backend
{
    
    /**
     * Area模型对象
     * @var \app\admin\model\Area
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Area;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    
    /**
     * 获取三级联动列表
     */
    public function linkagelist(Request $request)
    {
        $param['pid'] = $request->param('pid', 0, 'trim');

        $where['pid'] = $param['pid'];
        $list = $this->model
            ->field("id,name,level")
            ->where($where)
            ->order('id', 'asc')
            ->select();

        $list = collection($list)->toArray();

        return show(200, '成功', $list);
    }

}
