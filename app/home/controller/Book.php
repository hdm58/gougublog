<?php
/**
 * @copyright Copyright (c) 2021 勾股工作室
 * @license https://opensource.org/licenses/Apache-2.0
 * @link https://blog.gougucms.com
 */

declare (strict_types = 1);

namespace app\home\controller;

use app\home\BaseController;
use app\model\Book as bookModel;
use app\model\Doc as DocModel;
use think\facade\Db;
use think\facade\View;

class Book extends BaseController
{
    public function index()
    {
		$param = get_params();
		$list = (new bookModel())->page_list($param);
		// 获取分页显示
        $page = $list->render();
		$seo = [];
		$seo['keywords'] = '文档,开发文档，帮助指南';
		$seo['title'] = '博客文档';
		$seo['desc'] = '常用的日常开发文档和系统的使用说明。';
		View::assign([
			'seo' => $seo,
			'list'=> $list,
			'page' => $page,
		]);
		add_user_log('view', '文档列表页');
		return View();
    }
	
    public function list()
    {
		if (request()->isAjax()) {
			$param = get_params();
			$bid = empty($param['bid']) ? 0 : $param['bid'];
			$id = empty($param['id']) ? 0 : $param['id'];
			$where = array();
			$where[] = ['book_id', '=', $bid];
			$where[] = ['status', '>', 0];
			$list = DocModel::where($where)
				->field('id,pid as pId,title as name,type,link,sort')
				->order('sort asc,id asc')
				->select()->toArray();
			return to_assign(0, '', $list);
		}
		else{
			add_user_log('view', '文档列表页');
			return View();
		}
    }
    /*
     *文档详情
     */
    public function detail()
    {
        $param = get_params();
		$bid = empty($param['bid']) ? 0 : $param['bid'];
		$id = empty($param['id']) ? 0 : $param['id'];
		if (request()->isAjax()) {
			$info = Db::name('Doc')->where(['id'=>$id])->find();
			Db::name('Doc')->where(['id'=>$id])->inc('read')->update();
			add_user_log('view', $info['title'] . '文档详情', $id);
			return to_assign(0, '', $info);
		}
		else{
			$where = array();
			$where[] = ['book_id', '=', $bid];
			$where[] = ['status', '>', 0];
			$list = DocModel::where($where)
				->field('id,pid,title,type,book_id,link,read,sort,create_time,update_time')
				->order('sort asc,id asc')
				->select()->toArray();
			$menu = list_to_tree($list);
			$detail = Db::name('Book')->where(['id'=>$bid])->find();
			$info=[];
			$seo = [];
			$seo['keywords'] = $detail['title'];
			$seo['title'] = $detail['title'];
			$seo['desc'] = $detail['desc'];
			if($id==0 && !empty($list)){
				$id = $list[0]['id'];
			}
			$info = Db::name('Doc')->where(['id'=>$id])->find();
			Db::name('Doc')->where(['id'=>$id])->inc('read')->update();
			$seo['keywords'] = $detail['title'];
			$seo['title'] = $info['title'];
			$seo['desc'] = $info['desc'];
			View::assign([
				'seo' => $seo,
				'detail' => $detail,
				'menu' => $menu,
				'info' => $info,
				'bid' => $bid,
				'id' => $id
			]);
			add_user_log('view', $seo['title'] . '文档详情', $id);
			return View();
		}
    }
}
