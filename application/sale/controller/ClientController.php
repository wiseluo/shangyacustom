<?php
namespace app\sale\controller;

use think\Db;
use think\Request;
use app\common\model\House;
use app\sale\model\Client;
use app\sale\model\ClientFollow;
use app\sale\model\ClientHouse;
use app\sale\model\ClientReferralLog;

class ClientController extends BaseController
{
    public function index(Request $request)
    {
        $param['page'] = $request->param('page', 1);
        $param['pagesize'] = $request->param('pagesize', 10);
        $param['keyword'] = $request->param('keyword', '');
        $param['order'] = $request->param('order', 'id');
        $param['gender'] = $request->param('gender', '');

        $where = [];
        $where['Client.user_id'] = request()->user()['id'];
        if($param['keyword']) {
            $where['Client.name|Client.phone|Client.label'] = ['like', '%'. $param['keyword'] .'%'];
        }
        if($param['gender']) {
            $where['Client.gender'] = $param['gender'];
        }
        if($param['order'] == '') {
            $param['order'] = 'id';
        }
        $order = 'Client.'. $param['order'];

        $subQuery = ClientFollow::field('id,client_id,follow_time,follow_type,content')
            ->order('id desc')
            ->buildSql();
        $subQuery2 = Db::table($subQuery.' cfs')
                ->field('id,client_id,follow_time,follow_type,content follow_content')
                ->group('client_id')
                ->buildSql();

        $res = Client::alias('Client')
            ->join($subQuery2 .' cf', 'cf.client_id=Client.id', 'left')
            ->where($where)
            ->field('Client.id,Client.name,Client.phone,cf.follow_time,cf.follow_type,follow_content')
            ->order($order .' desc')
            ->paginate($param['pagesize'])
            ->toArray();
        return show(200, '成功', $res);
    }

    public function overdueIndex(Request $request)
    {
        $param['pagesize'] = $request->param('pagesize',10);

        $where = [];
        $where['Client.user_id'] = request()->user()['id'];
        $where['Client.next_follow_date'] = ['<= time', date('Y-m-d', time() +604800)]; //七天逾期

        $subQuery = ClientFollow::field('id,client_id,follow_time,follow_type,content')
            ->order('id desc')
            ->buildSql();
        $subQuery2 = Db::table($subQuery.' cfs')
                ->field('id,client_id,follow_time,follow_type,content follow_content')
                ->group('client_id')
                ->buildSql();

        $res = Client::alias('Client')
            ->join($subQuery2 .' cf', 'cf.client_id=Client.id', 'left')
            ->where($where)
            ->field('Client.id,Client.name,Client.phone,Client.next_follow_date,cf.follow_time,cf.follow_type,follow_content')
            ->order('id desc')
            ->paginate($param['pagesize'])
            ->toArray();
        return show(200, '成功', $res);
    }

    public function read($id)
    {
        $client = new Client();
        $data = $client->where('id', $id)->find();
        if ($data) {
            $data['intention_project'] = ClientHouse::where('client_id', $id)->select();
            $data['gender_text'] = $client->getGenderList($data['gender']);
            $data['follow_type_text'] = $client->getFollowTypeList($data['follow_type']);
            $data['intention_level_text'] = $client->getIntentionLevelList($data['intention_level']);
            $data['intention_product_text'] = $client->getIntentionProductList($data['intention_product']);
            $data['intention_room_text'] = $client->getIntentionRoomList($data['intention_room']);
            $data['house_purpose_text'] = $client->getHousePurposeList($data['house_purpose']);
            $data['intention_house_text'] = $client->getIntentionHouseList($data['intention_house']);
            $data['intention_area_text'] = $client->getIntentionAreaList($data['intention_area']);
            $data['intention_price_range_text'] = $client->getIntentionPriceRangeList($data['intention_price_range']);
            $data['client_type_text'] = $client->getClientTypeList($data['client_type']);
            return show(200, '成功', $data);
        }else{
            return show(401, '客户不存在');
        }
    }

    public function save(Request $request)
    {
        $param = $request->param();
        $validate = validate('ClientValidate');
        if(!$validate->scene('save')->check($param)) {
            return json(['code'=> 401, 'type'=> 'save', 'msg'=> $validate->getError()]);
        }
        $client_data = $this->clientData($param);
        $client_data['user_id'] = request()->user()['id'];

        $client = new Client($client_data);
        $res = $client->allowField(true)->save();
        if ($res) {
            //$this->saveClientHouse($param['intention_project'], $client->id);
            return show(200, '添加成功');
        }else{
            return show(401, '添加失败');
        }
    }

    public function update(Request $request, $id)
    {
        $param = $request->param();
        $validate = validate('ClientValidate');
        if(!$validate->scene('update')->check($param)) {
            return json(['code'=> 401, 'type'=> 'update', 'msg'=> $validate->getError()]);
        }
        $client = Client::get(['id'=> $id, 'user_id'=> request()->user()['id']]);
        if (!$client) {
            return show(401, '客户不存在');
        }
        $client_data = $this->clientData($param);

        $client = new Client();
        $res = $client->allowField(true)->save($client_data, ['id'=> $id]);
        if ($res) {
            //ClientHouse::where('client_id', $id)->delete();
            //$this->saveClientHouse($param['intention_project'], $id);
            return show(200, '编辑成功');
        }else{
            return show(401, '编辑失败');
        }
    }

    private function clientData($param)
    {
        $client_data = [
            'name' => $param['name'],
            'phone' => $param['phone'],
            'gender' => $param['gender'],
            'follow_type' => $param['follow_type'],
            'intention_level' => $param['intention_level'],
            'register_instruction' => $param['register_instruction'],
            'next_follow_date' => $param['next_follow_date'],
            'intention_product' => $param['intention_product'],
            'intention_room' => $param['intention_room'],
            'house_purpose' => $param['house_purpose'],
            'concern_factor' => $param['concern_factor'],
            'intention_house' => $param['intention_house'],
            'intention_area' => $param['intention_area'],
            'intention_price_range' => $param['intention_price_range'],
            'cognitive_approach' => $param['cognitive_approach'],
            'client_type' => $param['client_type'],
        ];

        $data_percent = 80;
        if(isset($param['label']) && $param['label'] != ''){
            $client_data['label'] = $param['label'];
            $data_percent = 85;
        }
        if(isset($param['remark']) && $param['remark'] != ''){
            $client_data['remark'] = $param['remark'];
            $data_percent = 90;
        }
        if(isset($param['images']) && $param['images'] != ''){
            $client_data['images'] = $param['images'];
            $data_percent = 95;
        }
        if(isset($param['cognitive_approach']) && $param['cognitive_approach'] != ''){
            $client_data['cognitive_approach'] = $param['cognitive_approach'];
            $data_percent = 100;
        }

        $client_data['data_percent'] = $data_percent;
        return $client_data;
    }

    public function saveClientHouse($intention_project, $id)
    {
        $houses = explode(',', $intention_project);
        foreach($houses as $k => $v) {
            $house = House::get($v);
            $house_data = [
                'client_id' => $id,
                'house_id' => $v,
                'house_name' => $house['name'],
            ];
            $client_house = new ClientHouse();
            $client_house->allowField(true)->save($house_data);
        }
    }

    public function delete($id)
    {
        $res = Client::destroy(['id'=> $id]);
        if ($res) {
            return show(200, '删除成功');
        }else{
            return show(401, '删除失败');
        }
    }

    //转介路径
    public function referralLog(Request $request)
    {
        $param['pagesize'] = $request->param('pagesize',10);
        $param['client_id'] = $request->param('client_id', 0);
        
        $log = ClientReferralLog::where('client_id', $param['client_id'])
            ->order('id desc')
            ->paginate($param['pagesize'])
            ->toArray();
        return show(200, '成功', $log);
    }
}
