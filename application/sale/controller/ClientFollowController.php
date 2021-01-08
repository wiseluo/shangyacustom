<?php
namespace app\sale\controller;

use think\Request;
use app\sale\model\Client;
use app\sale\model\ClientFollow;

class ClientFollowController extends BaseController
{
    public function index(Request $request)
    {
        $param['pagesize'] = $request->param('pagesize',10);
        $param['client_id'] = $request->param('client_id', 0);
        
        $where = [];
        if($param['client_id']) {
            $where['client_id'] = $param['client_id'];
        }
        
        $res = ClientFollow::where($where)
            ->order('id desc')
            ->paginate($param['pagesize'])
            ->toArray();
        return show(200, '成功', $res);
    }

    public function save(Request $request)
    {
        $param = $request->param();
        $validate = validate('ClientFollowValidate');
        if(!$validate->scene('save')->check($param)) {
            return json(['code'=> 401, 'type'=> 'save', 'msg'=> $validate->getError()]);
        }
        $client = Client::get($param['client_id']);
        if($client == null) {
            return show(401, '客户不存在');
        }
        $follow_data = [
            'client_id' => $param['client_id'],
            'follow_id' => request()->user()['id'],
            'follow_name' => request()->user()['username'],
            'follow_time' => $param['follow_time'],
            'content' => $param['content'],
            'follow_type' => $param['follow_type'],
        ];

        $follow = new ClientFollow($follow_data);
        $res = $follow->allowField(true)->save();
        if ($res) {
            return show(200, '添加成功');
        }else{
            return show(401, '添加失败');
        }
    }

}
