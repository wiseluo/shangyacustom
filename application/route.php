<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

/* 用户接口 start */
//登录注册
Route::post('api/wechat/applet_login','api/auth.LoginController/wechatAppletLogin'); //微信小程序授权登录
Route::post('api/wechat/register', 'api/auth.RegisterController/wechatRegister'); //微信绑定注册
Route::get('api/smscode', 'api/SmsController/smsCode'); //获取短信验证码
Route::get('api/city', 'api/CommonController/city'); //城市列表
Route::get('api/house_city', 'api/CommonController/houseCity'); //楼盘城市列表

Route::get('api/adsense', 'api/AdsenseController/index'); //首页广告位
Route::get('api/referee_qrcode', 'api/RefereeController/qrcode'); //用户推广二维码

Route::get('api/user/info', 'api/UserController/info'); //用户信息
Route::get('api/user/authentication', 'api/UserController/authentication'); //实名认证信息
Route::get('api/user/team', 'api/UserController/team'); //我的团队
Route::get('api/user/customer', 'api/UserController/customer'); //我的推介
Route::get('api/user/promotion_log', 'api/UserController/promotionLog'); //推广收益明细
Route::resource('api/user/collection', 'api/UserCollectionController'); //我的收藏
Route::get('api/commission', 'api/CommissionController/commissionList'); //佣金结算列表
Route::get('api/commission_income', 'api/CommissionController/commissionIncome'); //获取佣金收益金额
Route::get('api/commission_log', 'api/CommissionController/commissionLog'); //佣金收益明细

Route::resource('api/house', 'api/HouseController'); //楼盘
Route::get('api/houselayout/:id', 'api/HouseLayoutController/index'); //楼盘关联户型列表
Route::resource('api/layout', 'api/LayoutController'); //户型

Route::get('api/salesman', 'api/AdminController/salesman'); //销售员列表
Route::post('api/customer/promote','api/CustomerController/promote'); //推介客户
Route::post('api/worktable', 'api/WorktableController/save'); //提交工作台

/* 用户接口 end */

/* 销售接口 start */
Route::post('sale/wechat/applet_login','sale/auth.LoginController/wechatAppletLogin'); //微信小程序授权登录
Route::post('sale/wechat/register', 'sale/auth.RegisterController/wechatRegister'); //微信绑定注册
Route::post('sale/upload/img', 'sale/UploadController/uploadImage'); //上传图片

Route::get('sale/house', 'sale/HouseController/index'); //楼盘列表
Route::get('sale/sale/info', 'sale/UserController/info'); //销售员信息
Route::resource('sale/client', 'sale/ClientController'); //客户
Route::get('sale/client/overdue', 'sale/ClientController/overdueIndex'); //逾期客户列表
Route::resource('sale/client/follow', 'sale/ClientFollowController'); //客户跟进
Route::resource('sale/client/house', 'sale/ClientHouseController'); //客户购房意向
Route::get('sale/client/referral_log', 'sale/ClientController/referralLog'); //客户转介路径

/* 销售接口 end */

return [
    
];
