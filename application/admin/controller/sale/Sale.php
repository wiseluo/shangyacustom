<?php

namespace app\admin\controller\sale;

use think\Request;
use app\common\controller\Backend;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Sale extends Backend
{
    
    /**
     * Sale模型对象
     * @var \app\admin\model\Sale
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\sale\Sale;
        $this->view->assign("genderList", $this->model->getGenderList());
        $this->view->assign("statusList", $this->model->getStatusList());
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
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        $this->assignconfig('examine', $this->auth->check('sale/sale/examine'));
        return $this->view->fetch();
    }

    /*
     * 销售审核
    */
    public function examine(Request $request, $ids)
    {
        $row = $this->model->get($ids);
        if ($this->request->isAjax()) {
            $param = $request->param();
            $validate = validate('Sale', 'validate\sale');
            if(!$validate->scene('examine')->check($param)) {
                return show(401, $validate->getError());
            }
            if($row['status'] == 0) {
                return show(400, '销售未提交认证申请，不能审核');
            }
            $res = $row->allowField(true)->save(['status'=> $param['status']]);
            if($res) {
                return show(200, '操作成功');
            }else{
                return show(400, '操作失败');
            }
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }
}
