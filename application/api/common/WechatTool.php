<?php
namespace app\api\Common;

use Curl\Curl;

class WechatTool
{
    private $appid = ''; //应用唯一标识
    private $app_secret = ''; //应用密钥

    public function __construct()
    {
        $this->appid = config('wechat.app_id');
        $this->app_secret = config('wechat.app_secret');
    }

    /**
     * @param string $code  用户授权码
     * @return Response  微信用户信息
     */
    public function getWxUserinfoByCode($code)
    {
        $token_res = $this->getAccessTokenWxApi($code);
        if($token_res['status'] == 0) {
            return ['status'=> 0, 'msg'=> $token_res['msg']];
        }
        $userinfo_res = $this->getUserinfoWxApi($token_res['data']['access_token'], $token_res['data']['openid']);
        if($userinfo_res['status'] == 0) {
            return ['status'=> 0, 'msg'=> $userinfo_res['msg']];
        }else{
            $userinfo = [
                'access_token'=> $token_res['data']['access_token'],
                'refresh_token'=> $token_res['data']['refresh_token'],
                'openid'=> $token_res['data']['openid'],
                'unionid'=> $token_res['data']['unionid'],
                'sex'=> $userinfo_res['data']['sex'],
                'nickname'=> $userinfo_res['data']['nickname'],
                'headimgurl'=> $userinfo_res['data']['headimgurl'],
            ];
            return ['status'=> 1, 'msg'=> '成功', 'data'=> $userinfo];
        }
    }

    public function getAccessTokenWxApi($code)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='. $this->appid .'&secret='. $this->app_secret .'&code='. $code .'&grant_type=authorization_code';
        $curl = new Curl();
        $curl->get($url);
        if($curl->error) {
            return ['status'=> 0, 'msg'=> '微信获取access_token失败'];
        }else{
            $result = json_decode($curl->response, true);
        }
        if(isset($result['errcode'])) {
            return ['status'=> 0, 'msg'=> '微信接口获取access_token失败-'. $result['errmsg']];
        }else{
            return ['status'=> 1, 'msg'=> '成功', 'data'=> $result];
        }
    }

    public function getUserinfoWxApi($access_token, $openid)
    {
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='. $access_token .'&openid='. $openid .'&lang=zh_CN';
        $curl = new Curl();
        $curl->get($url);
        if($curl->error) {
            return ['status'=> 0, 'msg'=> '微信获取用户信息接口失败'];
        }else{
            $result = json_decode($curl->response, true);
        }
        if(isset($result['errcode'])) {
            return ['status'=> 0, 'msg'=> '微信获取用户信息接口失败-'. $result['errmsg']];
        }else{
            return ['status'=> 1, 'msg'=> '成功', 'data'=> $result];
        }
    }

    public function isAccessTokenInvalidWxApi($access_token, $openid)
    {
        $url = 'https://api.weixin.qq.com/sns/auth?access_token='. $access_token .'&openid='. $openid;
        $curl = new Curl();
        $curl->get($url);
        if($curl->error) {
            return ['status'=> 0, 'msg'=> '微信获取检验授权凭证接口失败'];
        }else{
            $result = json_decode($curl->response, true);
        }
        if($result['errcode'] == 0) {
            return ['status'=> 1, 'msg'=> 'access_token有效'];
        }else{
            return ['status'=> 0, 'msg'=> 'access_token无效'];
        }
    }

    public function refreshTokenWxApi($refresh_token)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='. $this->appid .'&grant_type=refresh_token&refresh_token='. $refresh_token;
        $curl = new Curl();
        $curl->get($url);
        if($curl->error) {
            return ['status'=> 0, 'msg'=> '微信刷新access_token接口失败'];
        }else{
            $result = json_decode($curl->response, true);
        }
        if(isset($result['errcode'])) {
            return ['status'=> 0, 'msg'=> '微信刷新access_token接口失败-'. $result['errmsg']];
        }else{
            return ['status'=> 1, 'msg'=> '成功', 'data'=> $result];
        }
    }
}