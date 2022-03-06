<?php
/**
 * @copyright Copyright (c) 2021 勾股工作室
 * @license https://opensource.org/licenses/Apache-2.0
 * @link https://blog.gougucms.com
 */

declare (strict_types = 1);

namespace app\home\controller;

use app\home\BaseController;
use app\model\Action as ActionModel;
use app\model\Article as ArticleModel;
use app\model\Keywords as KeywordsModel;
use think\facade\Db;
use think\facade\View;

class Index extends BaseController
{
    public function index()
    {
        $slide = Db::name('SlideInfo')
            ->alias('s')
            ->join('file f', 's.img=f.id', 'LEFT')
            ->join('slide sl', 's.slide_id=sl.id', 'LEFT')
            ->field('s.id,s.title,s.src,f.filepath')
            ->where(['s.status' => 1, 'sl.name' => 'INDEX_SLIDE'])
            ->select();
        $links = Db::name('Links')->order('sort desc')->select();

		$book = Db::name('Book')->where(['status' => 1,'is_index'=>1])->order('sort desc')->select();
        $links = Db::name('Links')->order('sort desc')->select();
		$hot_articles = ArticleModel::where(['status' => 1])->order('read desc')->limit(10)->select();
        //博客统计信息
        $blog = get_system_config('blog');
        $startdate = strtotime($blog['create_time']);
        $nowtime = date('Y-m-d', time());
        $enddate = strtotime($nowtime);
        $counts = [];
        $counts['days'] = round(($enddate - $startdate) / 3600 / 24);
        $counts['title'] = $blog['title'];
        $counts['avatar'] = $blog['avatar'];
        $counts['article'] = ArticleModel::where(['status' => 1])->count();
        $counts['action'] = Db::name('UserLog')->count();
		$counts['download'] = Db::name('UserLog')->where(array('type'=>'down'))->count();
        View::assign([
            'seo' => get_system_config('web'),
            'search_keywords' => (new KeywordsModel())->search_key(),
            'hot_tag' => (new KeywordsModel())->tags(15),
            'article' => (new ArticleModel())->list(15),
            'cates' => (new ArticleModel())->cates(),
            'tags' => (new KeywordsModel())->tags(20, 1),
            'action' => (new ActionModel())->list(7),
            'hot_articles' => $hot_articles,
            'slide' => $slide,
            'book' => $book,
            'links' => $links,
            'counts' => $counts,
            'version' => CMS_VERSION,
        ]);
        add_user_log('view', '首页');
        return View();
    }

    public function logs()
    {
        add_user_log('view', '开发日志');
		$seo = get_system_config('web');
		$seo['title'] = '开发日志—勾股博客';
		View::assign('seo',$seo);
        return View('');
    }

	public function down()
    {
        $version = CMS_VERSION;
        add_user_log('down', $version.'版本代码');
        header("Location: https://blog.gougucms.com/storage/gougublog_".$version."_full.zip");
        //确保重定向后，后续代码不会被执行
        exit;
    }

    /*
     *文章详情重定向跳转，为了兼容旧版访问，新安装系统的可以忽略删除
     */
    public function detail()
    {
        $param = get_params();
		redirect("/home/article/detail/id/".$param['id'].".html")->send();
        //确保重定向后，后续代码不会被执行
        exit;
    }
}
