<?php
/**
 * @copyright Copyright (c) 2021 勾股工作室
 * @license https://opensource.org/licenses/Apache-2.0
 * @link https://blog.gougucms.com
 */

declare (strict_types = 1);

namespace app\home\controller;

use app\home\BaseController;
use app\model\Keywords as KeywordsModel;
use app\model\Article as ArticleModel;
use think\facade\Db;
use think\facade\View;

class Archives extends BaseController
{
    /*
     *文章归档
     */
    public function index()
    {
        $tag_count = KeywordsModel::count();
        $article_count = ArticleModel::where(['status' => 1])->count();
        $cate_count = Db::name('ArticleCate')->count();
        $view_count = Db::name('UserLog')->count();

        $article = Db::name('Article')->where(['status' => 1])->field('id,title,create_time')->order('id desc')->select()->toArray();
        $date_archives = date_document($article);
        $month_archives = month_document($article);
        $year_archives = year_document($month_archives);
		$seo = get_system_config('web');
		$seo['title'] = '文章归档—勾股博客';
        View::assign([
            'seo' => $seo,
            'cate_count' => $cate_count,
            'tag_count' => $tag_count,
            'article_count' => $article_count,
            'view_count' => $view_count,
            'year_archives' => $year_archives,
            'date_archives' => json_encode($date_archives),
        ]);
        add_user_log('view', '文章归档页');
        return View();
    }
}
