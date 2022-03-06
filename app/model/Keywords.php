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

class Keywords extends Model
{
    // 获取tag列表
    public function tags($limit = 10, $is_rand = 0, $order = '')
    {
        $map = [];
        $sort = 'id desc';
        if ($order != '') {
            $sort = $order;
        }
        if ($is_rand == 0) {
            $tags = Db::name('Keywords')->order($sort)->limit($limit)->select();
        } else {
            $tags = Db::name('Keywords')->orderRaw("rand() , " . $sort)->limit($limit)->select();
        }
        return $tags;
    }

    // 获取搜索关键字列表
    public function search_key($limit = 10, $is_rand = 0, $order = '')
    {
        $map = [];
        $sort = 'times desc';
        if ($order != '') {
            $sort = $order;
        }
        if ($is_rand == 0) {
            $search_key = Db::name('SearchKeywords')->order($sort)->limit($limit)->select();
        } else {
            $search_key = Db::name('SearchKeywords')->orderRaw("rand() , " . $sort)->limit($limit)->select();
        }
        return $search_key;
    }

    // 根据tag获取文章分页列表
    public function article_list($param = [])
    {
        $map = [];
        $map[] = ['ar.status', '=', 1];
        $map[] = ['a.keywords_id', '=', $param['id']];
        $rows = empty($param['limit']) ? 10 : $param['limit'];
        $list = Db::name('ArticleKeywords')
            ->alias('a')
            ->join('Article ar', 'ar.id=a.aid', 'LEFT')
            ->join('file f', 'ar.thumb=f.id', 'LEFT')
            ->field('ar.id,ar.title,ar.thumb,ar.create_time,ar.read,ar.desc,f.filepath')
            ->where($map)
            ->order('ar.id desc')
            ->paginate(['list_rows' => $rows, 'query' => $param])
            ->each(function ($item, $key) {
                $item['keyword_array'] = Db::name('ArticleKeywords')
                    ->field('k.id,k.title')
                    ->alias('a')
                    ->join('keywords k', 'k.id = a.keywords_id', 'LEFT')
                    ->where(array('a.aid' => $item['id'], 'k.status' => 1))
                    ->limit(4)
                    ->select()
                    ->toArray();
                return $item;
            });
        return $list;
    }
}
