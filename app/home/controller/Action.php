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

class Action extends BaseController
{
    /*
     *动态
     */
    public function index()
    {
        $param = get_params();
        $param['limit'] = 30;
        $list = (new ActionModel())->page_list($param);
        // 获取分页显示
        $page = $list->render();
		$seo = get_system_config('blog');
        View::assign([
            'seo' => $seo,
            'list' => $list,
            'page' => $page,
            'cates' => (new ArticleModel())->cates(),
            'tags' => (new KeywordsModel())->tags(20, 1),
            'hot_articles' => (new ArticleModel())->list(10, 1),
        ]);
        add_user_log('view', '动态列表页');
        return View();
    }
}
