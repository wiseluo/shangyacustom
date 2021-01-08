<?php

namespace app\admin\controller;

use think\Db;
use app\common\controller\Backend;
use app\common\model\HouseLayout as HouseLayoutModel;
use app\common\model\HouseAdmin as HouseAdminModel;

/**
 * 楼盘管理
 *
 * @icon fa fa-circle-o
 */
class House extends Backend
{
    
    /**
     * House模型对象
     * @var \app\admin\model\House
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\House;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    
    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->field("id,name,tag,concat_ws('-', lower_price_sqm, upper_price_sqm) price_sqm,concat_ws('-', lower_area, upper_area) area,concat_ws('-', lower_total_price, upper_total_price) total_price,type,property_company,commission_rate,createtime,updatetime")
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        $this->assignconfig('houseadmin', $this->auth->check('houseadmin/index'));
        $this->assignconfig('houselayout', $this->auth->check('houselayout/index'));
        return $this->view->fetch();
    }

    /**
     * 删除 一并删除户型关联关系，销售员关联关系
     */
    public function del($ids = "")
    {
        if ($ids) {
            $pk = $this->model->getPk();
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                $this->model->where($this->dataLimitField, 'in', $adminIds);
            }
            $list = $this->model->where($pk, 'in', $ids)->select();

            $count = 0;
            Db::startTrans();
            try {
                foreach ($list as $k => $v) {
                    $count += $v->delete();
                    HouseLayoutModel::where('house_id', $v->id)->delete();
                    HouseAdminModel::where('house_id', $v->id)->delete();
                }
                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($count) {
                $this->success();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

}
