<?php
/**
 * @copyright Copyright (c) 2021 勾股工作室
 * @license https://opensource.org/licenses/Apache-2.0
 * @link https://blog.gougucms.com
 */

declare (strict_types = 1);

namespace app\home\controller;

use app\home\BaseController;
use app\model\Article as ArticleModel;
use app\model\Keywords as KeywordsModel;
use think\facade\Db;
use think\facade\View;

class Article extends BaseController
{
    /*
     *分类文章
     */
    public function cate()
    {
        $seo = get_system_config('web');
        $seo['cate_id'] = 0;
        $param = get_params();
        if (!empty($param['id']) && $param['id'] > 0) {
            $seo['cate_id'] = $param['id'];
        }
        $list = (new ArticleModel())->page_list($param);
        // 获取分页显示
        $page = $list->render();
        $seo['action'] = 'cate';
        if ($seo['cate_id'] > 0) {
            $cate = Db::name('ArticleCate')->where('id', $seo['cate_id'])->find();
            $seo['title'] = $cate['title'].'分类的文章—勾股博客';
            $seo['keywords'] = $cate['keywords'];
            $seo['desc'] = $cate['desc'];
        }
        View::assign([
            'seo' => $seo,
            'article' => $list,
            'page' => $page,
            'cates' => (new ArticleModel())->cates(),
            'tags' => (new KeywordsModel())->tags(20, 1),
            'hot_articles' => (new ArticleModel())->list(10, 1),
        ]);
        add_user_log('view', $seo['title'] . '文章列表页');
        return View();
    }

    /*
     *标签文章
     */
    public function tags()
    {
        $param = get_params();
        $list = (new KeywordsModel())->article_list($param);
        // 获取分页显示
        $page = $list->render();

        $seo = [];
        $seo['cate_id'] = 0;
        $seo['action'] = 'tags';
        $seo['keywords'] = KeywordsModel::where('id', $param['id'])->value('title');
        $seo['title'] = $seo['keywords']. '关联的文章';
        $seo['desc'] = $seo['title'] . '关联的文章';

        View::assign([
            'seo' => $seo,
            'article' => $list,
            'page' => $page,
            'cates' => (new ArticleModel())->cates(),
            'tags' => (new KeywordsModel())->tags(20, 1),
            'hot_articles' => (new ArticleModel())->list(10, 1),
        ]);
        add_user_log('view', $seo['desc']);
        return View('cate');
    }

    /*
     *文章搜索
     */
    public function search()
    {
        $param = get_params();
        $list = (new ArticleModel())->page_list($param);
        // 获取分页显示
        $page = $list->render();

        $seo = [];
        $seo['cate_id'] = 0;
        $seo['action'] = 'search';
        $seo['keywords'] = $param['k'];
        $seo['title'] = $seo['keywords']. '关联的文章';
        $seo['desc'] = $seo['title'] . '关联的文章';

        View::assign([
            'seo' => $seo,
            'article' => $list,
            'page' => $page,
            'cates' => (new ArticleModel())->cates(),
            'tags' => (new KeywordsModel())->tags(20, 1),
            'hot_articles' => (new ArticleModel())->list(10, 1),
        ]);
        add_user_log('search', $seo['desc']);
        return View('cate');
    }

    /*
     *文章详情
     */
    public function detail()
    {
        $param = get_params();
        $article = (new ArticleModel())->detail($param['id']);
        ArticleModel::where('id', $param['id'])->inc('read')->update();

        $seo = [];
        $seo['cate_title'] = 0;
        $seo['keywords'] = $article['keyword_names'];
        $seo['title'] = $article['title'];
        $seo['desc'] = $article['desc'];

        View::assign([
            'seo' => $seo,
            'detail' => $article,
            'cates' => (new ArticleModel())->cates(),
            'tags' => (new KeywordsModel())->tags(20, 1),
            'hot_articles' => (new ArticleModel())->list(10, 1),
        ]);
        add_user_log('view', $seo['title'] . '文章详情', $param['id']);
        return View();
    }
}
