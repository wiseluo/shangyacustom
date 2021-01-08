<?php

namespace app\admin\controller;

use think\Request;
use app\common\controller\Backend;
use app\common\model\House;
use app\common\model\Admin;
use app\common\model\HouseAdmin as HouseAdminModel;

/**
 * 楼盘销售员关联关系管理
 *
 */
class Houseadmin extends Backend
{
    /**
     * 关联销售员列表
    */
    public function index($ids)
    {
        if ($this->request->isAjax()) {
            $where['HouseAdmin.house_id'] = $ids;
            $list = HouseAdminModel::alias('HouseAdmin')
                ->join('sy_admin Admin', 'HouseAdmin.admin_id=Admin.id', 'left')
                ->field("HouseAdmin.id,Admin.nickname,Admin.avatar")
                ->where($where)
                ->order('HouseAdmin.id', 'asc')
                ->select();

            $list = collection($list)->toArray();

            $result = array("total" => 0, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 销售员列表
    */
    public function salesman(Request $request)
    {
        if ($this->request->isAjax()) {
            $offset = $request->get("offset", 0);
            $limit = $request->get("limit", 10);

            $where['Aga.group_id'] = 3;
            $total = Admin::alias('Admin')
                ->join('sy_auth_group_access Aga', 'Admin.id=Aga.uid', 'left')
                ->where($where)
                ->count();
            $list = Admin::alias('Admin')
                ->join('sy_auth_group_access Aga', 'Admin.id=Aga.uid', 'left')
                ->field("Admin.id,Admin.nickname,Admin.avatar")
                ->where($where)
                ->order('Admin.id', 'desc')
                ->limit($offset, $limit)
                ->select();
            $list = collection($list)->toArray();

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        $house_id = $request->param('house_id', 0);
        $this->view->assign("house_id", $house_id);
        return $this->view->fetch();
    }

    /**
     * 添加关联销售员
    */
    public function save(Request $request)
    {
        $param = $request->param();
        $validate = validate('HouseAdmin');
        if(!$validate->scene('save')->check($param)) {
            return show(401, $validate->getError());
        }
        $house = House::get($param['house_id']);
        if($house == false) {
            return show(401, '楼盘不存在');
        }
        $admin_ids = explode(',', $param['admin_ids']);
        $house_admin = HouseAdminModel::where('house_id', $param['house_id'])->field('admin_id')->select();
        $admin_arr = [];
        foreach($house_admin as $k => $v) {
            $admin_arr[] = $v['admin_id'];
        }
        $admin_diff = array_diff($admin_ids, $admin_arr);
        foreach($admin_diff as $k => $v) {
            $admin = Admin::get($v);
            if($admin == false) {
                continue;
            }
            $data = [
                'house_id' => $param['house_id'],
                'admin_id' => $v,
            ];
            $house_admin = new HouseAdminModel($data);
            $res = $house_admin->allowField(true)->save();
        }
        return show(200, '关联成功');
    }

    public function del($id)
    {
        $house_admin = HouseAdminModel::get($id);
        if($house_admin == false) {
            return show(401, '关联关系不存在');
        }
        $del_res = HouseAdminModel::where('id', $id)->delete(true);
        if($del_res) {
            return show(200, '删除成功');
        }else{
            return show(400, '删除失败');
        }
    }
}
