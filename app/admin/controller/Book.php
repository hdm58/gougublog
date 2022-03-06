<?php
/**
 * @copyright Copyright (c) 2021 勾股工作室
 * @license https://opensource.org/licenses/Apache-2.0
 * @link https://blog.gougucms.com
 */

declare (strict_types = 1);

namespace app\admin\controller;

use app\admin\BaseController;
use app\admin\model\Book as BookList;
use app\admin\model\Doc;
use app\admin\validate\BookCheck;
use think\exception\ValidateException;
use think\facade\Db;
use think\facade\View;

class Book extends BaseController
{
    public function index()
    {
		if (request()->isAjax()) {
            $param = get_params();
            $where = array();
            if (!empty($param['keywords'])) {
                $where[] = ['id|title|desc', 'like', '%' . $param['keywords'] . '%'];
            }
            $where[] = ['status', '=', 1];
            $rows = empty($param['limit']) ? get_config('app . page_size') : $param['limit'];
            $content = BookList::where($where)
				->order('create_time asc')
				->paginate($rows, false, ['query' => $param])
				->each(function ($item, $key) {
					$item->count = Db::name('Doc')->where(['book_id'=>$item->id,'status'=>1])->count();
				});
            return table_assign(0, '', $content);
        } else {
            return view();
        }
    }

    //分类添加
    public function add()
    {
        $param = get_params();
        if (request()->isAjax()) {
            if (!empty($param['id']) && $param['id'] > 0) {
                $param['update_time'] = time();
				try {
					validate(BookCheck::class)->scene('edit')->check($param);
				} catch (ValidateException $e) {
					// 验证失败 输出错误信息
					return to_assign(1, $e->getError());
				}
                $res = BookList::strict(false)->field(true)->update($param);
                if ($res) {
                    add_log('edit', $param['id'], $param);
                }
                return to_assign();
            } else {
                try {
                    validate(BookCheck::class)->scene('add')->check($param);
                } catch (ValidateException $e) {
                    // 验证失败 输出错误信息
                    return to_assign(1, $e->getError());
                }
                $param['admin_id'] = 1;
                $param['create_time'] = time();
                $insertId = BookList::strict(false)->field(true)->insertGetId($param);
                if ($insertId) {
                    add_log('add', $insertId, $param);
                }
                return to_assign();
            }
        }
		else{
			$id = empty($param['id']) ? 0 : $param['id'];
			View::assign('id', $id);
			if ($id > 0) {
				$detail = Db::name('Book')->where(['id'=>$id])->find();
				View::assign('detail', $detail);
			}
			return view();
		}
    }

	//删除文章分类
    public function delete()
    {
        $id = get_params("id");
        $data['status'] = '-1';
        $data['id'] = $id;
        $data['update_time'] = time();
        if (Db::name('Book')->update($data) !== false) {
            add_log('delete', $id);
            return to_assign(0, "删除成功");
        } else {
            return to_assign(1, "删除失败");
        }
    }

	//文章列表
    public function view()
    {
        $param = get_params();
        if (request()->isAjax()) {
            $where = array();
            if (!empty($param['id'])) {
                $where[] = ['book_id', '=', $param['id']];
            }
            $where[] = ['status', '>', 0];
            $list = Doc::where($where)
                ->field('id,pid,title,type,book_id,read,sort,create_time,update_time')
                ->order('sort asc,id asc')
                ->select();
            return to_assign(0, '', $list);
        } else {
			$id = empty($param['id']) ? 0 : $param['id'];
			View::assign('id', $id);
			if ($id > 0) {
				$detail = Db::name('Book')->where(['id'=>$id])->find();
				$detail['count'] = Db::name('Doc')->where(['book_id'=>$id,'status'=>1])->count();
				View::assign('detail', $detail);
			}
			return view();
        }
    }
	
	//获取文档节点列表
    public function doc_tree()
    {
		$param = get_params();
		$where[] = ['status', '>', 0];
		$where[] = ['book_id', '=', $param['id']];
        $list = Doc::where($where)
                ->field('id,pid,title,type,book_id,sort')
                ->order('sort asc,id asc')
                ->select();
		foreach($list as $k => &$v)
		{
			$v['title']=sub_str($v['title'],9);
		}	
        $tree = get_tree($list, 0, 4);
        $data['trees'] = $tree;
        return json($data);
    }

    //文章添加&&编辑
    public function doc_add()
    {
		$param = get_params();
        if (request()->isAjax()) {			
			if (isset($param['table-align'])) {
				unset($param['table-align']);
			}
			if (isset($param['docContent-html-code'])) {
				$param['content'] = $param['docContent-html-code'];
				$param['md_content'] = $param['docContent-markdown-doc'];
				unset($param['docContent-html-code']);
				unset($param['docContent-markdown-doc']);
			}
            if (isset($param['id']) && $param['id'] > 0) {
                $param['update_time'] = time();
                $res = Db::name('Doc')->strict(false)->field(true)->update($param);
                $aid = $param['id'];
                if ($res) {
                    add_log('edit', $param['id'], $param);
					return to_assign();
                }
				else {
					return to_assign(1, '操作失败');
				}
            } else {
				$param['admin_id'] = 1;
                $param['create_time'] = time();
				if (isset($param['content'])) {
					$param['desc'] = getDescriptionFromContent($param['content'], 100);
				}
				$aid = Db::name('Doc')->strict(false)->field(true)->insertGetId($param);
				if ($aid) {
					add_log('add', $aid, $param);
					return to_assign(0,'保存成功',$aid);
				}
				else {
					return to_assign(1, '操作失败');
				}
            }
        }
		else{
			$id = empty($param['id']) ? 0 : $param['id'];
			$pid = empty($param['pid']) ? 0 : $param['pid'];
			View::assign('id', $id);
			View::assign('pid', $pid);
			if ($id > 0) {
				$detail = Db::name('Doc')->where(['id'=>$id])->find();
				View::assign('detail', $detail);
			}
			return view();
		}

    }

    //删除文档
    public function doc_delete()
    {
        $id = get_params("id");
        $count = Db::name('Doc')->where(["pid" => $id,"status"=>1])->count();
        if ($count > 0) {
            return to_assign(1, "该分类下还有子文档，无法删除");
        }
        $data['status'] = '-1';
        $data['id'] = $id;
        $data['delete_time'] = time();
        if (Db::name('Doc')->update($data) !== false) {
            add_log('delete', $id);
            return to_assign(0, "删除成功");
        } else {
            return to_assign(1, "删除失败");
        }
    }
}
