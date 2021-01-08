<?php
namespace app\api\controller;

use think\Request;
use app\common\model\Customer;

class CustomerController extends BaseController
{
    public function promote(Request $request)
    {
        $param = $request->param();
        $validate = validate('CustomerValidate');
        if(!$validate->scene('promote')->check($param)) {
            return show(401, $validate->getError());
        }
        // $customer = Customer::where('phone', $param['phone'])->find();
        // if($customer) {
        //     return show(200, '该客户已被推介，不能重复推介');
        // }

        $customer_data = [
            'user_id' => $request->user()['id'],
            'name' => $param['name'],
            'phone' => $param['phone'],
            'identity_card' => $param['identity_card'],
            'gender' => $param['gender'],
            'house_id' => $param['house_id'],
            'adviser_id' => $param['adviser_id'],
            'intention_room_data' => $param['intention_room_data'],
            'intention_area_data' => $param['intention_area_data'],
        ];
        
        $customer = new Customer($customer_data);
        $res = $customer->allowField(true)->save();
        if($res){
            return show(200, '操作成功');
        }else{
            return show(400, '操作失败');
        }
        
    }

}
