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

class Article extends Model
{
    // 获取文章分类
    public function cates($order = '')
    {
        $sort = 'sort desc';
        if ($order != '') {
            $sort = $order;
        }
        $cates = \think\facade\Db::name('ArticleCate')->order($sort)->select()->toArray();
        foreach ($cates as $key => $value) {
            $cates[$key]['count'] = \think\facade\Db::name('Article')->where(['article_cate_id' => $value['id'], 'status' => 1])->count();
        }
        return $cates;
    }

    // 获取文章列表
    function list($limit = 10, $is_rand = 0, $cate_id = 0, $is_home = 0, $order = '') {
        $map = [];
        $map[] = ['a.status', '=', 1];
        if ($cate_id > 0) {
            $map[] = ['a.article_cate_id', '=', $cate_id];
        }
        if ($is_home > 0) {
            $map[] = ['a.is_home', '=', $is_home];
        }
        $sort = 'a.id desc';
        if ($order != '') {
            $sort = $order;
        }
        if ($is_rand == 0) {
            $list = Db::name('Article')
                ->alias('a')
                ->join('file f', 'a.thumb=f.id', 'LEFT')
                ->field('a.id,a.title,a.thumb,a.create_time,a.read,a.desc,f.filepath')
                ->where($map)
                ->order($sort)
                ->limit($limit)
                ->select()->toArray();
        } else {
            $list = Db::name('Article')
                ->alias('a')
                ->join('file f', 'a.thumb=f.id', 'LEFT')
                ->field('a.id,a.title,a.thumb,a.create_time,a.read,a.desc,f.filepath')
                ->where($map)
                ->orderRaw("rand() , " . $sort)
                ->limit($limit)
                ->select()->toArray();
        }
        foreach ($list as &$v) {
            //关键字
            $keyword_array = Db::name('ArticleKeywords')
                ->field('k.id,k.title')
                ->alias('a')
                ->join('keywords k', 'k.id = a.keywords_id', 'LEFT')
                ->where(array('a.aid' => $v['id'], 'k.status' => 1))
                ->limit(4)
                ->select()
                ->toArray();
            $v['keyword_array'] = $keyword_array;
        }
        return $list;
    }

    // 获取文章分页列表
    public function page_list($param = [])
    {
        $map = [];
        $map[] = ['a.status', '=', 1];
        if (!empty($param['id']) && $param['id'] > 0) {
            $map[] = ['a.article_cate_id', '=', $param['id']];
            $seo['cate_id'] = $param['id'];
        }
        if (!empty($param['k'])) {
            $map[] = ['a.title|a.desc', 'like', '%' . $param['k'] . '%'];
            if (strlen($param['k']) > 1) {
                $has = Db::name('SearchKeywords')->where(['title' => $param['k']])->find();
                if (!empty($has)) {
                    Db::name('SearchKeywords')->where('id', $has['id'])->inc('times')->update();
                } else {
					if(!isRobot()){
						Db::name('SearchKeywords')->strict(false)->field(true)->insert(['title' => $param['k']]);
					}
                }
            }
        }
        $rows = empty($param['limit']) ? 10 : $param['limit'];
        $list = Db::name('Article')
            ->alias('a')
            ->join('file f', 'a.thumb=f.id', 'LEFT')
            ->field('a.id,a.title,a.thumb,a.create_time,a.read,a.desc,f.filepath')
            ->where($map)
            ->order('a.id desc')
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

    // 获取文章详情
    public function detail($id)
    {
        $article = Db::name('article')->where(['id' => $id])->find();
        if (empty($article)) {
            pageError('文章不存在');
        }
        $keyword_array = Db::name('ArticleKeywords')
            ->field('i.aid,i.keywords_id,k.title')
            ->alias('i')
            ->join('keywords k', 'k.id = i.keywords_id', 'LEFT')
            ->order('i.create_time asc')
            ->where(array('i.aid' => $id, 'k.status' => 1))
            ->select()->toArray();
        $article['keyword_array'] = $keyword_array;
        $article['keyword_names'] = implode(',', array_column($keyword_array, 'title'));
        return $article;
    }
}
