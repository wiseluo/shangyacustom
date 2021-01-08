<?php
namespace app\api\controller;

use think\Request;
use app\common\model\Admin;

class AdminController extends BaseController
{
    public function salesman(Request $request)
    {
        $param['pagesize'] = $request->param('pagesize',10);
        $where['Aga.group_id'] = 3;
        $where['HouseAdmin.house_id'] = $request->param('house_id', 0);
        
        $res = Admin::alias('Admin')
            ->join('sy_auth_group_access Aga', 'Admin.id=Aga.uid', 'left')
            ->join('sy_house_admin HouseAdmin', 'HouseAdmin.admin_id=Admin.id', 'left')
            ->field('Admin.id,Admin.nickname')
            ->where($where)
            ->order('Admin.id desc')
            ->paginate($param['pagesize'])
            ->toArray();
        return show(200, '成功', $res);
    }

}
