<?php
/**
 * @copyright Copyright (c) 2021 勾股工作室
 * @license https://opensource.org/licenses/Apache-2.0
 * @link https://blog.gougucms.com
 */

declare (strict_types = 1);

namespace app\home;

use think\App;
use think\facade\View;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $this->app->request;
        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
        $params = [
            'isLogin' => 0,
            'uid' => 0,
            'nickname' => '',
            'mobile_status' => 0,
            'version' => get_config('webconfig.version'),
        ];		
		$params['module'] = strtolower(app('http')->getName());
		$params['controller'] = strtolower(app('request')->controller());
		$params['action'] = strtolower(app('request')->action());		
		$params['url'] = '/'.$params['module'].'/'.$params['controller'].'/'.$params['action'];
        $login_top = '<a id="topLogin" class="nav-a" href="/home/login/index"><span class="login-span">登录</span></a><a class="nav-a" href="/home/login/reg"><span class="reg-span">注册</span></a>';
        $info = $this->checkLogin();
        if ($info) {
            $login_top = '<a class="nav-img" href="/home/user/index"><img src="' . $info['headimgurl'] . '" alt="' . $info['username'] . '" />' . $info['username'] . '</a>';
            $params['isLogin'] = 1;
            $params['uid'] = $info['id'];
            $params['nickname'] = $info['nickname'];
            $params['username'] = $info['username'];
        }

        View::assign([
            'COMMON_NAV' => get_navs('NAV_HOME'),
            'WEBSITE_NAV' => get_navs('NAV_WEBSITE'),
            'webconfig' => get_config('webconfig'),
            'params' => $params,
            'login_top' => $login_top,
        ]);
    }
    // 检测用户登录状态
    protected function checkLogin()
    {
        $session_user = get_config('app.session_user');
        $login_user = \think\facade\Session::get($session_user);
        if ($login_user && is_array($login_user)) {
            return $login_user;
        } else {
            return false;
        }
    }
}
