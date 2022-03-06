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

class Book extends Model
{
    // 获取文章分页列表
    public function page_list($param = [])
    {
        $map = [];
        $map[] = ['status', '=', 1];
        $rows = 9;
        $list = Db::name('Book')
            ->field('id,title,thumb,desc,create_time')
            ->where($map)
			->order('sort desc,id asc')
            ->paginate(['list_rows' => $rows, 'query' => $param])
            ->each(function ($item, $key) {
                $item['docs'] = Db::name('Doc')->where(array('book_id' => $item['id'], 'type' => 1))->count();
                $item['view'] = Db::name('Doc')->where(array('book_id' => $item['id'], 'type' => 1))->sum('read');
                $item['create_time'] = date('Y-m-d',$item['create_time']);
                return $item;
            });
        return $list;
    }
}
