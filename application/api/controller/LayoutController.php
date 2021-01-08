<?php
namespace app\api\controller;

use think\Request;
use app\common\model\Layout;

class LayoutController extends BaseController
{
    public function index(Request $request)
    {
        $param['pagesize'] = $request->param('pagesize',10);
        $where = [];
        
        $res = Layout::where($where)
            ->field('id,name,tag,build_area,dwelling_area,lower_total_price,upper_total_price,interval,layout_images')
            ->order('id desc')
            ->paginate($param['pagesize'])
            ->toArray();
        return show(200, '成功', $res);
    }

    public function read($id)
    {
        $layout = Layout::get($id);
        if ($layout) {
            return show(200, '成功', $layout);
        }else{
            return show(401, '户型不存在');
        }
    }

}
