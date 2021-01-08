<?php
namespace app\sale\controller\auth;

use think\Request;
use think\Controller;
use app\sale\common\SaleJwtTool;
use app\common\model\User;
use app\api\common\WechatAppletTool;

class LoginController extends Controller
{
    public function wechatAppletLogin(Request $request)
    {
        $param = $request->param();
        $validate = validate('LoginValidate');
        if(!$validate->scene('wechatAppletLogin')->check($param)) {
            return json(['code'=> 401, 'msg'=> $validate->getError()]);
        }
        // $user = User::get(1);
        // $token = SaleJwtTool::setAccessToken($user);
        // return show(200, '登录成功', ['token'=> $token]);
        
        $wechatAppletTool = new WechatAppletTool();
        $userinfo_res = $wechatAppletTool->getJscode2sessionWxApi($param['code']);
        if($userinfo_res['status']) {
            $user = User::where(['openid_wx'=> $userinfo_res['data']['openid']])->find();
            if($user) {
                if($user['type'] == 1) {
                    if($user['status'] == 2) {
                        $token = SaleJwtTool::setAccessToken($user);
                        return show(200, '登录成功', ['token'=> $token]);
                    }else if($user['status'] == 0){
                        return show(400, '未认证', ['token'=> '', 'status'=> 0]);
                    }else if($user['status'] == 1){
                        return show(400, '已提交认证申请，正在审核', ['token'=> '', 'status'=> 1]);
                    }else if($user['status'] == 3){
                        return show(400, '认证拒绝', ['token'=> '', 'status'=> 3]);
                    }
                }else{
                    $user_obj = new User();
                    $res = $user_obj->allowField(true)->save(['type'=> 1, 'status'=> 0], ['id'=> $user['id']]); //设为销售员，未认证
                    return show(200, '请绑定注册', ['token'=> '', 'openid'=> $userinfo_res['data']['openid']]);
                }
            }else{
                return show(200, '请注册认证', ['token'=> '', 'openid'=> $userinfo_res['data']['openid']]);
            }
        }else{
            return show(401, $userinfo_res['msg']);
        }
    }

}
