<?php
/**
 * @copyright Copyright (c) 2021 勾股工作室
 * @license https://opensource.org/licenses/Apache-2.0
 * @link https://blog.gougucms.com
 */

declare (strict_types = 1);

namespace app\admin\controller;

use app\admin\BaseController;
use app\admin\model\Action as ActionList;
use think\facade\Db;
use think\facade\View;

class Action extends BaseController
{
    public function index()
    {
        if (request()->isAjax()) {
            $param = get_params();
            $where = array();
            if (!empty($param['keywords'])) {
                $where[] = ['id|content', 'like', '%' . $param['keywords'] . '%'];
            }
            $where[] = ['status', '>', 0];
            $rows = empty($param['limit']) ? get_config('app . page_size') : $param['limit'];
            $content = ActionList::where($where)->order('create_time desc')->paginate($rows, false, ['query' => $param]);
            return table_assign(0, '', $content);
        } else {
            return view();
        }
    }

    //动态查询
    public function view()
    {
        $id = empty(get_params('id')) ? 0 : get_params('id');
        $action = Db::name('Action')->where(['id' => $id])->find();
        if (!empty($action)) {
            return to_assign(0, "", $action);
        } else {
            return to_assign(0, "查询不到数据");
        }
    }

    //动态内容提交保存
    public function add()
    {
        if (request()->isAjax()) {
            $param = get_params();
            if (!empty($param['id']) && $param['id'] > 0) {
                $param['update_time'] = time();
                $res = Db::name('Action')->strict(false)->field(true)->update($param);
                if ($res) {
                    add_log('edit', $param['id'], $param);
                    return to_assign();
                } else {
                    return to_assign(1, '操作失败');
                }
            } else {
                $param['create_time'] = time();
                $res = Db::name('Action')->strict(false)->field(true)->insertGetId($param);
                if ($res) {
                    add_log('add', $res, $param);
                    return to_assign();
                } else {
                    return to_assign(1, '操作失败');
                }
            }
        }
    }

    //删除动态
    public function delete()
    {
        $id = get_params("id");
        $data['status'] = '-1';
        $data['id'] = $id;
        $data['update_time'] = time();
        if (Db::name('Action')->update($data) !== false) {
            add_log('delete', $id);
            return to_assign(0, "删除成功");
        } else {
            return to_assign(1, "删除失败");
        }
    }
}
