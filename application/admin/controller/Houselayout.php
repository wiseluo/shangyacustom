<?php

namespace app\admin\controller;

use think\Request;
use app\common\controller\Backend;
use app\common\model\House;
use app\common\model\Layout;
use app\common\model\HouseLayout as HouseLayoutModel;

/**
 * 楼盘户型关联关系管理
 *
 */
class Houselayout extends Backend
{
    /**
     * 关联户型列表
    */
    public function index($ids)
    {
        if ($this->request->isAjax()) {
            $where['HouseLayout.house_id'] = $ids;
            $list = HouseLayoutModel::alias('HouseLayout')
                ->join('sy_layout Layout', 'HouseLayout.layout_id=Layout.id', 'left')
                ->field("HouseLayout.id,Layout.name,Layout.tag,Layout.build_area,Layout.dwelling_area,Layout.lower_total_price,Layout.upper_total_price,Layout.interval")
                ->where($where)
                ->order('HouseLayout.id', 'asc')
                ->select();

            $list = collection($list)->toArray();

            $result = array("total" => 0, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 户型列表
    */
    public function layout(Request $request, $house_id)
    {
        if ($this->request->isAjax()) {
            $offset = $request->get("offset", 0);
            $limit = $request->get("limit", 10);

            $total = Layout::count();
            $list = Layout::order('id', 'desc')
                ->limit($offset, $limit)
                ->select();
            $list = collection($list)->toArray();

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        $this->view->assign("house_id", $house_id);
        return $this->view->fetch();
    }

    /**
     * 添加关联户型
    */
    public function save(Request $request)
    {
        $param = $request->param();
        $validate = validate('HouseLayout');
        if(!$validate->scene('save')->check($param)) {
            return show(401, $validate->getError());
        }
        $house = House::get($param['house_id']);
        if($house == false) {
            return show(401, '楼盘不存在');
        }
        $layout_ids = explode(',', $param['layout_ids']);
        $house_layout = HouseLayoutModel::where('house_id', $param['house_id'])->field('layout_id')->select();
        $layout_arr = [];
        foreach($house_layout as $k => $v) {
            $layout_arr[] = $v['layout_id'];
        }
        $layout_diff = array_diff($layout_ids, $layout_arr);

        foreach($layout_diff as $k => $v) {
            $layout = Layout::get($v);
            if($layout == false) {
                continue;
            }
            $data = [
                'house_id' => $param['house_id'],
                'layout_id' => $v,
            ];
            $house_layout = new HouseLayoutModel($data);
            $res = $house_layout->allowField(true)->save();
        }
        return show(200, '关联成功');
    }

    public function del($id)
    {
        $house_layout = HouseLayoutModel::get($id);
        if($house_layout == false) {
            return show(401, '关联关系不存在');
        }
        $del_res = HouseLayoutModel::where('id', $id)->delete(true);
        if($del_res) {
            return show(200, '删除成功');
        }else{
            return show(400, '删除失败');
        }
    }
}
