<?php
namespace app\api\controller;

use think\Request;
use app\common\model\HouseLayout;

class HouseLayoutController extends BaseController
{
    public function index($id)
    {
        $where['HouseLayout.house_id'] = $id;
        
        $res = HouseLayout::alias('HouseLayout')
            ->join('sy_layout Layout', 'HouseLayout.layout_id=Layout.id', 'left')
            ->field("Layout.id,Layout.name,Layout.tag,Layout.build_area,Layout.dwelling_area,Layout.lower_total_price,Layout.upper_total_price,Layout.interval,Layout.layout_images")
            ->where($where)
            ->order('HouseLayout.id desc')
            ->select();
            
        return show(200, '成功', $res);
    }

}
