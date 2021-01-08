<?php

namespace app\admin\controller;

use think\Request;
use app\common\model\Admin;
use app\common\model\Customer as CustomerModel;
use app\common\model\CustomerVisit;
use app\common\controller\Backend;

/**
 * 客户管理
 *
 * @icon fa fa-circle-o
 */
class Customer extends Backend
{
    
    /**
     * Customer模型对象
     * @var \app\admin\model\Customer
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Customer;
        $this->view->assign("publicList", $this->model->getPublicList());
        $this->view->assign("genderList", $this->model->getGenderList());
        $this->view->assign("intentionRoomDataList", $this->model->getIntentionRoomDataList());
        $this->view->assign("intentionAreaDataList", $this->model->getIntentionAreaDataList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    
    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            $offset = $this->request->get("offset", 0);
            $limit = $this->request->get("limit", 0);
            $search = $this->request->get("search", '');
            $filter = $this->request->get("filter", '');
            $filter = (array)json_decode($filter, true);
            $filter = $filter ? $filter : [];
            
            $where = [];
            $groups = $this->auth->getGroups();
            foreach($groups as $k => $v) {
                if($v['group_id'] == 1 || $v['group_id'] == 2) {
                    break;
                }else if($v['group_id'] == 3) { //销售员
                    $where['adviser_id'] = $v['uid'];
                }
            }
            
            if($search) {
                $searcharr = ['User.nickname', 'House.name', 'Admin.nickname', 'Customer.name', 'Customer.phone'];
                $where[implode("|", $searcharr)] = ["LIKE", "%{$search}%"];
            }
            if(isset($filter['public'])) {
                $where['public'] = $filter['public'];
            }

            $total = $this->model->alias('Customer')
                ->join('sy_user User', 'Customer.user_id=User.id', 'left')
                ->join('sy_house House', 'Customer.house_id=House.id', 'left')
                ->join('sy_admin Admin', 'Customer.adviser_id=Admin.id', 'left')
                ->field("Customer.id")
                ->where($where)
                ->count();

            $list = $this->model->alias('Customer')
                ->join('sy_user User', 'Customer.user_id=User.id', 'left')
                ->join('sy_house House', 'Customer.house_id=House.id', 'left')
                ->join('sy_admin Admin', 'Customer.adviser_id=Admin.id', 'left')
                ->field("Customer.id,User.nickname user_nickname,House.name house_name,Admin.nickname adviser_nickname,Customer.name,Customer.phone,Customer.identity_card,Customer.gender,Customer.intention_room_data,Customer.intention_area_data,Customer.public,Customer.createtime,Customer.updatetime")
                ->where($where)
                ->order('id', 'desc')
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        $this->assignconfig('customervisit', $this->auth->check('customervisit/index')); //为了JS能获取到，同时判读权限 判断是否有customervisit/index权限
        return $this->view->fetch();
    }

    //分配到公海，移入公海
    public function assignpublic(Request $request)
    {
        $param = $request->param();
        $validate = validate('Customer');
        if(!$validate->scene('assignpublic')->check($param)) {
            return show(401, $validate->getError());
        }
        $customer_ids = explode(',', $param['customer_ids']);
        foreach($customer_ids as $k => $v) {
            $customer = CustomerModel::get($v);
            if($customer == false) {
                continue;
            }
            $customer_obj = new CustomerModel();
            $res = $customer_obj->allowField(true)->save(['adviser_id'=> 0, 'public'=> 1], ['id'=> $v]);
        }
        $this->success();
    }

    //分配客户
    public function assignsalesman(Request $request)
    {
        if ($this->request->isAjax()) {
            $param = $request->param();
            $validate = validate('Customer');
            if(!$validate->scene('assignsalesman')->check($param)) {
                return show(401, $validate->getError());
            }
            $admin = Admin::get($param['adviser_id']);
            if($admin == false) {
                return show(401, '销售员不存在');
            }
            $customer_ids = explode(',', $param['customer_ids']);
            foreach($customer_ids as $k => $v) {
                $customer = CustomerModel::get($v);
                if($customer == false) {
                    continue;
                }
                $customer = new CustomerModel();
                $res = $customer->allowField(true)->save(['adviser_id'=> $param['adviser_id'], 'public'=> 0], ['id'=> $v]);
            }
            return show(200, '分配成功');
        }
        return $this->view->fetch();
    }

}