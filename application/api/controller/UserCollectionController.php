<?php
namespace app\api\controller;

use think\Request;
use app\common\model\UserCollection;

class UserCollectionController extends BaseController
{
    public function index(Request $request)
    {
        $param['keyword'] = $request->param('keyword', '');
        
        $where['UserCollection.user_id'] = $request->user()['id'];
        if($param['keyword']) {
            $where['House.name|House.tag'] = ['like', '%'. $param['keyword'] .'%'];
        }
        
        $res = UserCollection::alias('UserCollection')
            ->join('sy_house House', 'UserCollection.house_id=House.id', 'left')
            ->field("UserCollection.id,House.name,House.tag,House.lower_price_sqm,House.upper_price_sqm,House.province,House.city,House.area,House.commission_rate,House.house_images")
            ->where($where)
            ->order('UserCollection.id desc')
            ->select();
            
        return show(200, '成功', $res);
    }

    public function save(Request $request)
    {
        $param = $request->param();
        $validate = validate('UserCollectionValidate');
        if(!$validate->scene('save')->check($param)) {
            return json(['code'=> 401, 'type'=> 'save', 'msg'=> $validate->getError()]);
        }
        $user_id = $request->user()['id'];
        $user_collection = UserCollection::where(['user_id'=> $user_id, 'house_id'=> $param['house_id']])->find();
        if($user_collection) {
            $res = UserCollection::where('id', $user_collection['id'])->delete();
            if ($res) {
                return show(200, '取消收藏成功', ['user_collection'=> 0]);
            }else{
                return show(401, '取消收藏失败');
            }
        }

        $collection_data = [
            'user_id' => $user_id,
            'house_id' => $param['house_id'],
        ];
        $user_collection = new UserCollection($collection_data);
        $res = $user_collection->allowField(true)->save();
        if ($res) {
            return show(200, '收藏成功', ['user_collection'=> 1]);
        }else{
            return show(401, '收藏失败');
        }
    }

    public function delete($id)
    {
        $res = UserCollection::where('id', $id)->delete();
        if ($res) {
            return show(200, '删除成功');
        }else{
            return show(401, '删除失败');
        }
    }
}
