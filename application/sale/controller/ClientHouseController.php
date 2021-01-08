<?php
namespace app\sale\controller;

use think\Request;
use app\sale\model\Client;
use app\common\model\House;
use app\sale\model\ClientHouse;

class ClientHouseController extends BaseController
{
    public function index(Request $request)
    {
        $param['pagesize'] = $request->param('pagesize',10);
        $param['client_id'] = $request->param('client_id', 0);

        $where = [];
        if($param['client_id']) {
            $where['client_id'] = $param['client_id'];
        }else{
            return show(400, '客户id必填');
        }
        
        $res = ClientHouse::where($where)
            ->order('id desc')
            ->paginate($param['pagesize'])
            ->toArray();
        return show(200, '成功', $res);
    }

    public function save(Request $request)
    {
        $param = $request->param();
        $validate = validate('ClientHouseValidate');
        if(!$validate->scene('save')->check($param)) {
            return json(['code'=> 401, 'type'=> 'save', 'msg'=> $validate->getError()]);
        }
        $client = Client::get($param['client_id']);
        if($client == null) {
            return show(401, '客户不存在');
        }
        $house = House::get($param['house_id']);
        if($house == null) {
            return show(401, '楼盘不存在');
        }
        $client_house = ClientHouse::where(['client_id'=> $param['client_id'], 'house_id'=> $param['house_id']])->find();
        if($client_house) {
            return show(401, '意向楼盘已添加');
        }
        $house_data = [
            'client_id' => $param['client_id'],
            'house_id' => $param['house_id'],
            'house_name' => $house['name'],
        ];

        $ch = new ClientHouse($house_data);
        $res = $ch->allowField(true)->save();
        if ($res) {
            return show(200, '添加成功');
        }else{
            return show(401, '添加失败');
        }
    }

    public function delete($id)
    {
        $res = ClientHouse::where('id', $id)->delete();
        if ($res) {
            return show(200, '删除成功');
        }else{
            return show(401, '删除失败');
        }
    }
}
