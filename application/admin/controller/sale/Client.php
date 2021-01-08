<?php

namespace app\admin\controller\sale;

use think\Request;
use app\admin\model\sale\Sale;
use app\sale\model\Client as ClientModel;
use app\sale\model\ClientReferralLog;
use app\common\controller\Backend;

/**
 * 销售客户管理
 *
 * @icon fa fa-circle-o
 */
class Client extends Backend
{
    
    /**
     * Client模型对象
     * @var \app\admin\model\sale\Client
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\sale\Client;
        $this->view->assign("genderList", $this->model->getGenderList());
        $this->view->assign("followTypeList", $this->model->getFollowTypeList());
        $this->view->assign("intentionLevelList", $this->model->getIntentionLevelList());
        $this->view->assign("intentionProductList", $this->model->getIntentionProductList());
        $this->view->assign("intentionRoomList", $this->model->getIntentionRoomList());
        $this->view->assign("housePurposeList", $this->model->getHousePurposeList());
        $this->view->assign("intentionHouseList", $this->model->getIntentionHouseList());
        $this->view->assign("intentionAreaList", $this->model->getIntentionAreaList());
        $this->view->assign("intentionPriceRangeList", $this->model->getIntentionPriceRangeList());
        $this->view->assign("clientTypeList", $this->model->getClientTypeList());
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
                    $where['user_id'] = $v['uid'];
                }
            }
            
            if($search) {
                $searcharr = ['User.username', 'Client.name', 'Client.phone'];
                $where[implode("|", $searcharr)] = ["LIKE", "%{$search}%"];
            }
            if(isset($filter['public'])) {
                $where['public'] = $filter['public'];
            }

            $total = $this->model->alias('Client')
                ->join('sy_user User', 'Client.user_id=User.id', 'left')
                ->field("Client.id")
                ->where($where)
                ->count();

            $list = $this->model->alias('Client')
                ->join('sy_user User', 'Client.user_id=User.id', 'left')
                ->field("Client.id,User.username username,Client.name,Client.phone,Client.gender,Client.label,Client.follow_type,Client.intention_level,Client.register_instruction,Client.client_type,Client.public,Client.createtime,Client.updatetime")
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

    //分配到公海，移入公海
    public function assignpublic(Request $request)
    {
        $param = $request->param();
        $validate = validate('Client', 'validate\sale');
        if(!$validate->scene('assignpublic')->check($param)) {
            return show(401, $validate->getError());
        }
        $client_ids = explode(',', $param['client_ids']);
        foreach($client_ids as $k => $v) {
            $client = ClientModel::get($v);
            if($client == false) {
                continue;
            }
            $client_obj = new ClientModel();
            $res = $client_obj->allowField(true)->save(['user_id'=> 0, 'public'=> 1], ['id'=> $v]);
            if($res) {
                $log = new ClientReferralLog();
                $log_data = [
                    'client_id'=> $v,
                    'referral_id'=> $this->auth->id,
                    'receiver_id'=> 0,
                    'operation'=> '被'. $this->auth->nickname .'分配到公海',
                ];
                $log->allowField(true)->save($log_data);
            }
        }
        $this->success();
    }

    //分配客户给销售员
    public function assignsalesman(Request $request)
    {
        if ($this->request->isAjax()) {
            $param = $request->param();
            $validate = validate('Client', 'validate\sale');
            if(!$validate->scene('assignsalesman')->check($param)) {
                return show(401, $validate->getError());
            }
            $user = Sale::where(['id'=> $param['user_id'], 'type'=> 1])->find();
            if($user == false) {
                return show(401, '销售员不存在');
            }
            $client_ids = explode(',', $param['client_ids']);
            foreach($client_ids as $k => $v) {
                $client = ClientModel::get($v);
                if($client == false) {
                    continue;
                }
                $client = new ClientModel();
                $res = $client->allowField(true)->save(['user_id'=> $param['user_id'], 'public'=> 0], ['id'=> $v]);
                if($res) {
                    $log = new ClientReferralLog();
                    $log_data = [
                        'client_id'=> $v,
                        'referral_id'=> $this->auth->id,
                        'receiver_id'=> $param['user_id'],
                        'operation'=> '被'. $this->auth->nickname .'分配至'. $user['username'],
                    ];
                    $log->allowField(true)->save($log_data);
                }
            }
            return show(200, '分配成功');
        }
        return $this->view->fetch();
    }
}
