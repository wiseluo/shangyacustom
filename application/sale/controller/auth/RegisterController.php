<?php
namespace app\sale\controller\auth;

use think\Request;
use think\Controller;
use app\sale\common\SaleJwtTool;
use app\common\model\User;

class RegisterController extends Controller
{
    public function wechatRegister(Request $request)
    {
        $param = $request->param();
        $validate = validate('RegisterValidate');
        if(!$validate->scene('wechatRegister')->check($param)) {
            return json(['code'=> 401, 'msg'=> $validate->getError()]);
        }
        $user = User::where(['openid_wx'=> $param['openid']])->find();
        if($user) { //账号已存在(用普通用户登录过)
            if($user['type'] == 1) {
                $user_data = [
                    'phone' => $param['phone'],
                    'username'=> $param['username'],
                    'nickname'=> $param['nickname'],
                    'avatar'=> $param['avatar'],
                    'gender'=> $param['gender'],
                    'status'=> 1,
                ];
                $user_obj = new User();
                $res = $user_obj->allowField(true)->save($user_data, ['id'=> $user['id']]);
                return show(200, '提交成功，请等待审核通过');
            }else{
                return show(401, '非销售员账号，不能注册认证');
            }
        }
        $user_data = [
            'openid_wx' => $param['openid'],
            'phone' => $param['phone'],
            'username'=> $param['username'],
            'nickname'=> $param['nickname'],
            'avatar'=> $param['avatar'],
            'gender'=> $param['gender'],
            'type'=> 1,
            'status'=> 1,
        ];
        $user_obj = new User($user_data);
        $res = $user_obj->allowField(true)->save();
        if($res) {
            return show(200, '提交成功，请等待审核通过');
        }else{
            return show(401, '注册认证失败');
        }
    }

}
