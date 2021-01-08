<?php
namespace app\api\controller;

use think\Request;
use app\common\model\Area;

class CommonController extends BaseController
{
    public function city(Request $request)
    {
        $param['keyword'] = $request->param('keyword', '', 'trim');

        $where['level'] = 2;
        if($param['keyword']) {
            $where['name'] = ['like', '%'. $param['keyword'] .'%'];
        }

        $res = Area::where($where)
            ->field('id,name,first')
            ->order('first asc')
            ->select();

        return show(200, '', $res);
    }

    public function houseCity(Request $request)
    {
        $param['keyword'] = $request->param('keyword', '', 'trim');

        $where['Area.level'] = 2;
        if($param['keyword']) {
            $where['Area.name'] = ['like', '%'. $param['keyword'] .'%'];
        }

        $res = Area::alias('Area')
            ->join('sy_house House', 'House.city_id=Area.id', 'left')
            ->where($where)
            ->whereNotNull('House.id')
            ->field('Area.id,Area.name,Area.first')
            ->group('Area.id')
            ->order('first asc')
            ->select();

        return show(200, '', $res);
    }
}
