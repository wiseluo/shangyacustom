<?php
namespace app\api\controller;

use think\Request;
use app\common\model\User;
use app\common\model\Commission;
use app\common\model\CommissionLog;

class CommissionController extends BaseController
{
    public function commissionLog(Request $request)
    {
        $param['pagesize'] = $request->param('pagesize',10);

        $where['user_id'] = $request->user()['id'];
        $res = CommissionLog::where($where)
            ->order('id desc')
            ->paginate($param['pagesize'])
            ->toArray();
        return show(200, '成功', $res);
    }

    public function commissionList(Request $request)
    {
        $param['pagesize'] = $request->param('pagesize',10);

        $where['Commission.user_id'] = $request->user()['id'];
        $res = Commission::alias('Commission')
            ->join('sy_customer Customer', 'Customer.id=Commission.customer_id')
            ->join('sy_house House', 'House.id=Commission.house_id')
            ->field('Commission.id,Customer.name customer_name,House.name house_name,Commission.house_price,Commission.commission_rate,Commission.commission,Commission.description,Commission.createtime')
            ->where($where)
            ->order('id desc')
            ->paginate($param['pagesize'])
            ->toArray();
        return show(200, '成功', $res);
    }

    public function commissionIncome(Request $request)
    {
        $user = User::get($request->user()['id']);
        return show(200, '成功', ['commission_income'=> $user['commission_income']]);
    }
}
