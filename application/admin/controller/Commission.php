<?php

namespace app\admin\controller;

use think\Db;
use think\Request;
use app\common\model\User;
use app\common\model\House;
use app\common\model\Customer;
use app\common\model\Commission as CommissionModel;
use app\common\model\CommissionLog;
use app\common\controller\Backend;

/**
 * 佣金管理
 *
 * @icon fa fa-circle-o
 */
class Commission extends Backend
{
    
    /**
     * Commission模型对象
     * @var \app\admin\model\Commission
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Commission;

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
            // $groups = $this->auth->getGroups();
            // if($groups['group_id'] == 1 || $groups['group_id'] == 2) {

            // }
            if($search) {
                $searcharr = ['User.nickname', 'House.name', 'Admin.nickname', 'Customer.name', 'Customer.phone'];
                $where[implode("|", $searcharr)] = ["LIKE", "%{$search}%"];
            }
            if(isset($filter['public'])) {
                $where['public'] = $filter['public'];
            }

            $total = $this->model->alias('Commission')
                ->join('sy_user User', 'Commission.user_id=User.id', 'left')
                ->join('sy_customer Customer', 'Commission.customer_id=Customer.id', 'left')
                ->join('sy_house House', 'Commission.house_id=House.id', 'left')
                ->field("Commission.id")
                ->where($where)
                ->count();

            $list = $this->model->alias('Commission')
                ->join('sy_user User', 'Commission.user_id=User.id', 'left')
                ->join('sy_customer Customer', 'Commission.customer_id=Customer.id', 'left')
                ->join('sy_house House', 'Commission.house_id=House.id', 'left')
                ->field("Commission.id,User.nickname user_nickname,House.name house_name,Customer.name customer_name,Commission.house_price,Commission.commission_rate,Commission.commission,Commission.description,Commission.createtime,Commission.updatetime")
                ->where($where)
                ->order('id', 'desc')
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    //推介人列表
    public function userlist(Request $request)
    {
        if ($this->request->isAjax()) {
            $offset = $request->get("offset", 0);
            $limit = $request->get("limit", 10);
            $keyword = $request->param('keyword', '', 'trim');
            $where = [];
            if($keyword) {
                $where['User.username|User.phone'] = ['like', '%'. $keyword .'%'];
            }
            $total = Customer::alias('Customer')
                ->join('sy_user User', 'Customer.user_id=User.id', 'left')
                ->field('User.id,User.username,User.phone')
                ->where($where)
                ->group('Customer.user_id')
                ->count();
            $list = Customer::alias('Customer')
                ->join('sy_user User', 'Customer.user_id=User.id', 'left')
                ->field('User.id,User.username,User.phone')
                ->where($where)
                ->group('Customer.user_id')
                ->order('User.id', 'desc')
                ->limit($offset, $limit)
                ->select();
            $list = collection($list)->toArray();

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    //推介人的客户列表 $id:推介人id
    public function usercustomer(Request $request, $id)
    {
        if ($this->request->isAjax()) {
            $offset = $request->get("offset", 0);
            $limit = $request->get("limit", 10);
            $keyword = $request->param('keyword', '', 'trim');
            $where['Customer.user_id'] = $id;
            if($keyword) {
                $where['name|phone'] = ['like', '%'. $keyword .'%'];
            }
            $total = Customer::alias('Customer')
                ->join('sy_house House', 'Customer.house_id=House.id', 'left')
                ->field('Customer.id customer_id,Customer.name customer_name,Customer.phone,House.id house_id,House.name house_name,House.tag house_tag')
                ->where($where)
                ->count();
            $list = Customer::alias('Customer')
                ->join('sy_house House', 'Customer.house_id=House.id', 'left')
                ->field('Customer.id customer_id,Customer.name,Customer.phone,House.id house_id,House.name house_name,House.tag')
                ->where($where)
                ->limit($offset, $limit)
                ->select();
            $list = collection($list)->toArray();

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    public function add(Request $request)
    {
        if ($this->request->isPost()) {
            $param = $this->request->post("row/a");
            $validate = validate('Commission');
            if(!$validate->scene('save')->check($param)) {
                return show(401, $validate->getError());
            }
            $data = [
                'user_id' => $param['user_id'],
                'customer_id' => $param['customer_id'],
                'house_id' => $param['house_id'],
                'house_price' => $param['house_price'],
                'commission_rate' => $param['commission_rate'],
                'commission' => $param['commission'],
                'description' => $param['description'],
            ];
            Db::startTrans();
            $commission = new CommissionModel($data);
            $commission_res = $commission->allowField(true)->save();
            if($commission_res) {
                $user = User::get($param['user_id']);
                if($user == false) {
                    Db::rollback();
                    return show(400, '用户不存在');
                }
                $commission_log_data = [
                    'user_id' => $param['user_id'],
                    'money' => $param['commission'],
                    'before' => $user['commission_income'],
                    'after' => bcadd($param['commission'], $user['commission_income']),
                    'memo' => $param['description'],
                ];
                $commission_log = new CommissionLog($commission_log_data);
                $commission_log_res = $commission_log->allowField(true)->save();
                if($commission_log_res) {
                    $user_res = User::where('id', $param['user_id'])->setInc('commission_income', $param['commission']);
                    if($user_res) {
                        Db::commit();
                        $this->success();
                    }else{
                        Db::rollback();
                        $this->error('用户更新佣金失败');
                    }
                }else{
                    Db::rollback();
                    $this->error('佣金日志添加失败');
                }
            }else{
                Db::rollback();
                $this->error('添加失败');
            }
        }
        return $this->view->fetch();
    }
}
