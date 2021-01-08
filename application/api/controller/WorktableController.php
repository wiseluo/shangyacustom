<?php
namespace app\api\controller;

use think\Request;
use app\common\model\Worktable;

class WorktableController extends BaseController
{
    public function save(Request $request)
    {
        $param = $request->param();
        $validate = validate('WorktableValidate');
        if(!$validate->scene('save')->check($param)) {
            return show(401, $validate->getError());
        }
        $data = [
            'name' => $param['name'],
            'phone' => $param['phone'],
            'type' => $param['type'],
        ];
        $worktable = new Worktable($data);
        $res = $worktable->allowField(true)->save();
        if ($res) {
            return show(200, '提交成功');
        }else{
            return show(401, '提交失败');
        }
    }

}
