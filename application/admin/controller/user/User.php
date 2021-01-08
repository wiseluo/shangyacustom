<?php

namespace app\admin\controller\user;

use app\common\controller\Backend;

/**
 * 会员管理
 *
 * @icon fa fa-user
 */

class User extends Backend
{

    protected $relationSearch = true;


    /**
     * @var \app\admin\model\User
     */
    protected $model = null;
    protected $searchFields = 'id,username,nickname,mobile';
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('User');
    }

    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with('group')
                    ->where($where)
                    ->order($sort, $order)
                    ->count();
            $list = $this->model
                    ->with('group')
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            foreach ($list as $k => $v)
            {
                $v->hidden(['password', 'salt']);
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $this->view->assign('groupList', build_select('row[group_id]', \app\admin\model\UserGroup::column('id,name'), $row['group_id'], ['class' => 'form-control selectpicker']));
        $statusList=array(
            '未认证',
            '认证申请',
            '已认证',
            '认证拒绝',
        );
        $this->view->assign("genderList", $this->model->getGenderList());
        $this->view->assign('statusList',$statusList);
        return parent::edit($ids);
    }

    //设为销售员
    public function assignsale($ids)
    {
        $user = $this->model->get($ids);
        if($user == false) {
            $this->error('用户不存在');
        }else if($user['type'] == 1) {
            $this->success('该用户已经是销售员了');
        }
        $res = $user->allowField(true)->save(['type'=> 1]);
        if($res) {
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }
}
