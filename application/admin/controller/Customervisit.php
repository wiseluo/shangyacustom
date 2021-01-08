<?php

namespace app\admin\controller;

use think\Request;
use app\common\model\Customer as CustomerModel;
use app\common\model\CustomerVisit as CustomerVisitModel;
use app\common\controller\Backend;

/**
 * 客户管理
 *
 * @icon fa fa-circle-o
 */
class Customervisit extends Backend
{
    public function _initialize()
    {
        parent::_initialize();
    }

    //客户拜访记录列表
    public function index(Request $request, $ids)
    {
        if ($this->request->isAjax()) {
            $offset = $request->get("offset", 0);
            $limit = $request->get("limit", 10);

            $where['customer_id'] = $ids;
            $total = CustomerVisitModel::where($where)->count();
            $list = CustomerVisitModel::where($where)
                ->order('id', 'desc')
                ->limit($offset, $limit)
                ->select();
            $list = collection($list)->toArray();
            
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    //拜访客户
    public function add(Request $request, $customer_id)
    {
        if ($this->request->isAjax()) {
            $param = $this->request->post("row/a");
            $validate = validate('Customer');
            if(!$validate->scene('visitcustomer')->check($param)) {
                return show(401, $validate->getError());
            }
            $customer = CustomerModel::get($param['customer_id']);
            if($customer == false) {
                return show(401, '客户不存在');
            }
            $data = [
                'customer_id' => $param['customer_id'],
                'visitor_id' => $this->auth->id,
                'contact' => $param['contact'],
                'visit_way' => $param['visit_way'],
                'visit_time' => $param['visit_time'],
                'content' => $param['content'],
            ];
            $customer_visit = new CustomerVisitModel();
            $res = $customer_visit->allowField(true)->save($data);
            if($res) {
                $this->success();
            }else{
                $this->error('拜访失败');
            }
        }
        $this->view->assign("customer_id", $customer_id);
        return $this->view->fetch();
    }


}