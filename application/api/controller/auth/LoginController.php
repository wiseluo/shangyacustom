<?php
namespace app\api\controller\auth;

use think\Request;
use think\Controller;
use app\api\common\JwtTool;
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
        $wechatAppletTool = new WechatAppletTool();
        $userinfo_res = $wechatAppletTool->getJscode2sessionWxApi($param['code']);
        if($userinfo_res['status']) {
            $referee_id = (isset($param['referee_id']) && $param['referee_id'] != '') ? $param['referee_id'] : 0;
            $user = User::where(['openid_wx'=> $userinfo_res['data']['openid']])->find();
            if($user) {
                if($user['type'] == 1) { //销售员
                    if($user['status'] == 2) { //认证通过
                        $token = SaleJwtTool::setAccessToken($user);
                        return show(200, '登录成功', ['token'=> $token, 'type'=> 1]);
                    }else if($user['status'] == 0){
                        return show(400, '未认证', ['token'=> '', 'openid'=> $userinfo_res['data']['openid'], 'type'=> 1, 'status'=> 0]);
                    }else if($user['status'] == 1){
                        return show(400, '已提交认证申请，正在审核', ['token'=> '', 'openid'=> $userinfo_res['data']['openid'], 'type'=> 1, 'status'=> 1]);
                    }else if($user['status'] == 3){
                        return show(400, '认证拒绝', ['token'=> '', 'openid'=> $userinfo_res['data']['openid'], 'type'=> 1, 'status'=> 3]);
                    }
                }else{
                    $token = JwtTool::setAccessToken($user);
                    return show(200, '登录成功', ['token'=> $token, 'type'=> 0, 'referee_id'=> $referee_id]);
                }
            }else{
                return show(200, '请注册认证', ['token'=> '', 'type'=> 0, 'openid'=> $userinfo_res['data']['openid'], 'referee_id'=> $referee_id]);
            }
        }else{
            return show(401, $userinfo_res['msg']);
        }
    }

}
