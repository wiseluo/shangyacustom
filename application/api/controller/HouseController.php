<?php
namespace app\api\controller;

use think\Request;
use app\common\model\House;
use app\common\model\UserCollection;

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

    public function read(Request $request, $id)
    {
        $house = House::get($id);
        $user_collection = UserCollection::where(['user_id'=> $request->user()['id'], 'house_id'=> $id])->find();
        if($user_collection) {
            $house['user_collection'] = 1;
        }else{
            $house['user_collection'] = 0;
        }
        if ($house) {
            return show(200, '成功', $house);
        }else{
            return show(401, '楼盘不存在');
        }
    }

}
