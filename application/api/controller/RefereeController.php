<?php

namespace app\api\controller;

use think\Request;
use Endroid\QrCode\QrCode;

class RefereeController extends BaseController
{
    public function qrcode(Request $request)
    {
        $content = common_func_domain(). '/api/wechat/applet_login?referee=' . $request->user()['id'];
        $qrCode = new QrCode($content);
        // 指定内容类型
        header('Content-Type: '. $qrCode->getContentType());
        // 输出二维码
        echo $qrCode->writeString();
    }
    
}
