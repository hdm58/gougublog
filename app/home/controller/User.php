<?php
/**
 * @copyright Copyright (c) 2021 勾股工作室
 * @license https://opensource.org/licenses/Apache-2.0
 * @link https://blog.gougucms.com
 */

declare (strict_types = 1);

namespace app\home\controller;

use app\home\BaseController;
use think\facade\Db;
use think\facade\View;

class User extends BaseController
{
    public function index()
    {
        $uid = get_login_user('id');
        $userInfo = Db::name('User')->where(['id' => $uid])->find();
        $userInfo['showname'] = empty($userInfo['nickname']) ? $userInfo['username'] : $userInfo['nickname'];
        $userInfo['sex'] = ($userInfo['sex'] == 1) ? '男' : '女';
        add_user_log('view', '个人中心');
        View::assign('userInfo', $userInfo);
        return view();
    }

    public function info_edit()
    {
        $uid = get_login_user('id');
        $userInfo = Db::name('User')->where(['id' => $uid])->find();
        add_user_log('view', '编辑个人信息');
        View::assign('userInfo', $userInfo);
        return view();
    }

    public function edit_submit()
    {
        $param = get_params();
        $param['birthday'] = strtotime($param['birthday']);
        $res = Db::name('User')->where(['id' => $param['id']])->strict(false)->field(true)->update($param);
        if ($res !== false) {
            add_user_log('edit', '个人信息', $param['id'], $param);
            to_assign(0, '操作成功');
        } else {
            to_assign(1, '操作失败');
        }
    }

}
