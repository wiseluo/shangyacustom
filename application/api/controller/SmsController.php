<?php

namespace app\api\controller;

use think\Request;
use think\Controller;

class SmsController extends Controller
{
    public function smsCode(Request $request)
    {
        $param = $request->param();
        $validate = validate('SmsValidate');
        if(!$validate->scene('smsCode')->check($param)) {
            return show(401, $validate->getError());
        }
        $res = model('api/SmsService', 'service')->smsCodeService($param['phone']);
        if($res['status']) {
            return show(200, $res['msg']);
        }else{
            return show(401, $res['msg']);
        }
    }
    
}
