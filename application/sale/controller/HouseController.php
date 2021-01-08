<?php
namespace app\sale\controller;

use think\Request;
use app\common\model\House;

class HouseController extends BaseController
{
    public function index(Request $request)
    {
        $param['pagesize'] = $request->param('pagesize',10);
        $param['city_id'] = $request->param('city_id', '');
        $param['keyword'] = $request->param('keyword', '');

        $where = [];
        if($param['city_id']) {
            $where['city_id'] = $param['city_id'];
        }
        if($paam['keyword']) {
            $where['name|tag'] = ['like', '%'. $param['keyword'] .'%'];
        }
        
        $res = House::field('id,name,tag,lower_price_sqm,upper_price_sqm,province,city,area,commission_rate,house_images')
            ->where($where)
            ->order('id desc')
            ->paginate($param['pagesize'])
            ->toArray();
        return show(200, '成功', $res);
    }

}
