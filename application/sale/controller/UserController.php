<?php
namespace app\sale\controller;

use think\Request;
use app\common\model\User;

class UserController extends BaseController
{
    public function info(Request $request)
    {
        $user = User::get(request()->user()['id']);
        $data = [
            'id'=> $user['id'],
            'nickname'=> $user['nickname'],
            'username'=> $user['username'],
            'avatar'=> $user['avatar'],
            'gender'=> $user['gender'],
            'status'=> $user['status'],
        ];
        return show(200, '成功', $data);
    }

}
