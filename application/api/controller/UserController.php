<?php
namespace app\api\controller;

use think\Request;
use app\common\model\User;
use app\common\model\Customer;
use app\common\model\PromotionLog;

class UserController extends BaseController
{
    public function info(Request $request)
    {
        $user = User::get(request()->user()['id']);
        $data = [
            'id'=> $user['id'],
            'nickname'=> $user['nickname'],
            'username'=> $user['username'],
            'avatar'=> $user['avatar'],
            'gender'=> $user['gender'],
            'commission_income'=> $user['commission_income'],
            'promotion_income'=> $user['promotion_income'],
            'status'=> $user['status'],
        ];
        return show(200, '成功', $data);
    }

    public function authentication(Request $request)
    {
        $user = User::get(request()->user()['id']);
        $data = [
            'id'=> $user['id'],
            'username'=> $user['username'],
            'identity_card'=> $user['identity_card'],
            'phone'=> $user['phone'],
        ];
        return show(200, '成功', $data);
    }

    public function team(Request $request)
    {
        $param['pagesize'] = $request->param('pagesize',10);

        $where['referee_id'] = $request->user()['id'];
        $res = User::where($where)
            ->field('id,phone,openid_wx,nickname,username,avatar,gender,createtime')
            ->order('id desc')
            ->paginate($param['pagesize'])
            ->toArray();
        return show(200, '成功', $res);
    }

    public function customer(Request $request)
    {
        $param['pagesize'] = $request->param('pagesize',10);

        $where['user_id'] = $request->user()['id'];
        $res = Customer::where($where)
            ->field('id,name,phone,gender,adviser_id,createtime')
            ->order('id desc')
            ->paginate($param['pagesize'])
            ->toArray();
        return show(200, '成功', $res);
    }

    public function promotionLog(Request $request)
    {
        $param['pagesize'] = $request->param('pagesize',10);

        $where['user_id'] = $request->user()['id'];
        $res = PromotionLog::where($where)
            ->order('id desc')
            ->paginate($param['pagesize'])
            ->toArray();
        return show(200, '成功', $res);
    }

}
