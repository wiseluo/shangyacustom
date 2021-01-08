<?php
namespace app\api\controller\auth;

use think\Request;
use think\Controller;
use app\api\common\JwtTool;
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
        /* to cancel */
        // $user = User::where(['phone'=> $param['phone']])->find();
        // if($user) { //手机号已存在
        //     return show(401, '手机号已存在，不能重复绑定');
        // }
        if($param['openid'] == 'undefined') {
            return show(401, '微信标识错误');
        }
        $user = User::where(['openid_wx'=> $param['openid']])->find();
        if($user) {
            if($user['type'] == 1) { //销售员
                $user_data = [
                    'phone' => $param['phone'],
                    'username'=> $param['username'],
                    'identity_card'=> $param['identity_card'],
                    'nickname'=> $param['nickname'],
                    'avatar'=> $param['avatar'],
                    'gender'=> $param['gender'],
                    'status'=> 2,
                ];
                $user_obj = new User();
                $res = $user_obj->allowField(true)->save($user_data, ['id'=> $user['id']]);
                if($res) {
                    $user_data['id'] = $user['id'];
                    $token = SaleJwtTool::setAccessToken($user_data);
                    return show(200, '登录成功', ['token'=> $token, 'type'=> 0]);
                }else{
                    return show(401, '绑定注册失败');
                }
            }else{
                return show(401, '微信已存在，不能重复注册');
            }
        }
        $referee_id = (isset($param['referee_id']) && $param['referee_id'] != '') ? $param['referee_id'] : 0;
        $user_data = [
            'openid_wx' => $param['openid'],
            'phone' => $param['phone'],
            'username'=> $param['username'],
            'identity_card'=> $param['identity_card'],
            'nickname'=> $param['nickname'],
            'avatar'=> $param['avatar'],
            'gender'=> $param['gender'],
            'type'=> 0,
            'status'=> 2,
            'referee_id' => $referee_id, //推介人id
        ];
        $user_obj = new User($user_data);
        $res = $user_obj->allowField(true)->save();
        if($res) {
            $user_data['id'] = $user_obj['id'];
            $token = JwtTool::setAccessToken($user_data);
            return show(200, '登录成功', ['token'=> $token, 'type'=> 0]);
        }else{
            return show(401, '绑定注册失败');
        }
    }

}
