<?php
/**
 * @copyright Copyright (c) 2021 勾股工作室
 * @license https://opensource.org/licenses/Apache-2.0
 * @link https://blog.gougucms.com
 */

declare (strict_types = 1);

namespace app\model;

use think\facade\Db;
use think\Model;

class Action extends Model
{
    // 获取列表
    function list($limit = 10, $order = '') {
        $map = [];
        $sort = 'id desc';
        if ($order != '') {
            $sort = $order;
        }
        $list = Db::name('Action')->order($sort)->limit($limit)->select();
        return $list;
    }

    // 获取分页列表
    public function page_list($param = [])
    {
        $rows = empty($param['limit']) ? 10 : $param['limit'];
        $list = Db::name('Action')->order('id desc')->paginate(['list_rows' => $rows, 'query' => $param]);
        return $list;
    }
}
