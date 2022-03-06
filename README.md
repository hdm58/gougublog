## 勾股BLOG
[![勾股Blog](https://img.shields.io/badge/license-Apache%202-blue.svg)](https://gitee.com/gougucms/blog/)
[![勾股Blog](https://img.shields.io/badge/GouguBlog-2.0.16-brightgreen.svg)](https://gitee.com/gougucms/blog/)
[![star](https://gitee.com/gougucms/blog/badge/star.svg?theme=dark)](https://gitee.com/gougucms/blog/stargazers)
[![fork](https://gitee.com/gougucms/blog/badge/fork.svg?theme=dark)](https://gitee.com/gougucms/blog/members)

## 链接
- 演示地址：https://blog.gougucms.com
- gitee：https://gitee.com/gougucms/blog.git
- 文档地址：[https://blog.gougucms.com/home/book/detail/bid/2.html](https://blog.gougucms.com/home/book/detail/bid/2.html)

- 项目会不定时进行更新，建议⭐star⭐和👁️watch👁️一份。

- 后台体验地址：[https://blog.gougucms.com/admin/index/index.html](https://blog.gougucms.com/admin/index/index.html)
- 后台体验账号：gougucms     密码：gougucms
- 开发交流QQ群：24641076

### 开源项目
1. [![勾股OA](https://img.shields.io/badge/GouguOA-2.0.9-brightgreen.svg)](https://gitee.com/gougucms/office) [开源项目系列之勾股OA](https://gitee.com/gougucms/office)
2. [![勾股CMS](https://img.shields.io/badge/GouguCMS-2.0.18-brightgreen.svg)](https://gitee.com/gougucms/gougucms) [开源项目系列之勾股CMS](https://gitee.com/gougucms/gougucms)
3. [![勾股BLOG](https://img.shields.io/badge/GouguBLOG-2.0.16-brightgreen.svg)](https://gitee.com/gougucms/blog) [开源项目系列之勾股BLOG](https://gitee.com/gougucms/blog)

### 前言
一直计划开发一个博客用来整理技术，分享知识，记录生活，如今博客的基本功能已经完成；特开源以供有同样想法的程序猿们免费使用; 同时也可以作为初学ThinkPHP的童鞋们的参考的源代码；没有版权限制，欢迎随意折腾与交流。更多功能后期还会继续完善，欢迎⭐ STAR ⭐关注获取最新更新！

### 介绍
1. 勾股博客是一款基于ThinkPHP6 + Layui + MySql打造的，简单实用的开源免费的博客系统。
2. 博客后台各管理模块，一目了然，操作简单，博客前台具有简约，易用，访问统计，内存占用低等特点。
3. 博客除了基本的博客文章功能，还具备类似微博的分享简短实时个人动态信息的功能、及类似阿里语雀的文档功能，知识可按目录、章节归类整理分享。
4. 博客整合了经典富文本编辑器（ueditor）与现今流行的Mardown编辑器（editor.md）于自身，可以在后台配置根据自己的使用习惯切换不同的编辑器。
5. 系统易于功能扩展，代码维护，方便二次开发。可以用来做个人博客，工作室官网，小说网站，自媒体官网等网站，二次开发之后也可以作为资讯、展品展示等门户网站。

### 目录结构

初始的目录结构如下：

~~~
www  系统部署目录（或者子目录）
├─app           		应用目录
│  ├─admin              后台模块目录
│  │  ├─controller      控制器目录
│  │  ├─middleware      中间层目录
│  │  ├─model           模型目录
│  │  ├─validate        校验器目录
│  │  ├─view            视图模板目录
│  │  ├─BaseController.php      基础控制器
│  │  ├─common.php      模块函数文件
│  │
│  ├─home               前台模块目录
│  │  ├─controller      控制器目录
│  │  ├─middleware      中间层目录
│  │  ├─model           模型目录
│  │  ├─validate        校验器目录
│  │  ├─view            视图模板目录
│  │  ├─BaseController.php      基础控制器
│  │  ├─common.php      模块函数文件
│  │
│  ├─install            安装模块目录(系统安装完后，建议删除)
│  │  ├─controller      控制器目录
│  │  ├─data            初始化数据库文件
│  │  ├─validate        校验器目录
│  │  ├─view            视图模板目录
│  │
├─config                配置文件目录
│  ├─app.php            系统主要配置文件
│  ├─database.php       数据库配置文件
│
├─extend                扩展类库目录
│  ├─avatars            自动生成头像文件目录
│  ├─backup             数据库备份文件目录
│
├─public                WEB目录（对外访问目录，域名绑定的目录）
│  ├─backup          	数据库备份目录
│  ├─static          	css、js等静态资源目录
│  │   ├─admin          系统后台css、js文件
│  │   ├─home         	系统前台css、js文件
│  │   ├─layui         	layui目录
│  │   ├─mdeditor       editor.md编辑器目录
│  │   ├─ueditor        百度编辑器目录
│  ├─storage            上传的图片等资源目录
│  ├─tpl                TP中转界面模板目录
│  ├─index.php          入口文件
│  ├─router.php         快速测试文件
│  └─.htaccess          用于apache的重写
│
├─route                 路由目录
├─vendor              	第三方类库目录(Composer依赖库目录)
│
├─runtime               应用的运行时目录（可写，可定制）
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
~~~


### 功能矩阵

系统后台集成了主流的通用功能，如：登录验证、系统配置、操作日志管理、角色权限、功能管理（后台菜单管理）、导航设置、网站地图、轮播广告、TAG关键字管理、友情链接、文件上传、数据备份/还原、博客文章功能、语雀文档功能、用户管理、用户操作日志、用户注册/登录、 博客归档、博客动态、访问统计等。更多的个性化功能可以基于当前博客系统便捷做二次开发。

具体功能如下：

~~~
系统
│        		
├─系统管理           		
│  ├─系统配置
│  ├─功能模块
│  ├─菜单节点
│  ├─权限角色
│  ├─管 理 员
│  ├─操作日志
│  ├─数据备份
│  ├─系统配置
│  ├─数据还原
│
├─基础数据
│  ├─导航设置
│  ├─网站地图
│  ├─轮播广告
│  ├─友情链接
│  ├─SEO关键字
│  ├─搜索关键词
│ 
├─平台用户
│  ├─用户等级
│  ├─用户列表
│  ├─操作记录
│  ├─操作日志
│
├─博客文章
│  ├─文章分类
│  ├─文章列表
│
├─博客动态
│  ├─动态列表
│
├─语雀知识库
│  ├─知识库列表
│
├─...
~~~


### 安装教程

一、勾股博客推荐你使用阿里云和腾讯云服务器。

阿里云服务器官方长期折扣优惠地址：

点击访问，(https://www.aliyun.com/activity/daily/bestoffer?userCode=dmrcx154) 

腾讯云服务器官方长期折扣优惠地址：

点击访问，(https://curl.qcloud.com/PPEgI0oV) 


二、服务器运行环境要求。

~~~
    PHP >= 7.1  
    Mysql >= 5.5.0 (需支持innodb引擎)  
    Apache 或 Nginx  
    PDO PHP Extension  
    MBstring PHP Extension  
    CURL PHP Extension  
    Composer (用于管理第三方扩展包)
~~~

三、系统安装

**方式一：完整包安装**

   第一步：前往官网博客下载页面 (https://blog.gougucms.com) 下载完整包解压到你的项目目录

   第二步：添加虚拟主机并绑定到项目的public目录    

   第三步：访问 http://www.yoursite.com/install/index 进行安装 


**方式二：命令行安装（推荐）**

推荐使用命令行安装，因为采用命令行安装的方式可以和勾股BLOG随时保持更新同步。使用命令行安装请提前准备好Git、Composer。

Linux下，勾股BLOG的安装请使用以下命令进行安装。  

第一步：克隆勾股博客到你本地  
    git clone https://gitee.com/gougucms/blog.git 

第二步：进入目录  
    cd blog  
    
第三步：下载PHP依赖包 
    
composer install  
	
注意：composer的版本最好是2.0.8版本，否则可能下载PHP依赖包失败，composer降级：composer self-update 2.0.8
    
第四步：添加虚拟主机并绑定到项目的public目录  
    
第五步：访问 http://www.yoursite.com/install/index 进行安装

**PS：如需要重新安装，请删除目录里面 config/install.lock 的文件，即可重新安装。**

### 常见问题

1.  安装失败，可能存在php配置文件禁止了putenv 和 proc_open函数。解决方法，查找php.ini文件位置，打开php.ini，搜索 disable_functions 项，看是否禁用了putenv 和 proc_open函数。如果在禁用列表里，移除putenv proc_open然后退出，重启php即可。

2.  如果安装后打开页面提示404错误，请检查服务器伪静态配置，如果是宝塔面板，网站伪静态请配置使用thinkphp规则。

3.  如果提示当前权限不足，无法写入配置文件config/database.php，请检查database.php是否可读，还有可能是当前安装程序无法访问父目录，请检查PHP的open_basedir配置。

4.  如果composer install失败，请尝试在命令行进行切换配置到国内源，命令如下composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/。

5.  如果composer install失败，请尝试composer降级：composer self-update 2.0.8。

6.  访问 http://www.yoursite.com/install/index ，请注意查看伪静态请配置是否设置了thinkphp规则。

7.  遇到问题请到QQ群：24641076 反馈。

### 截图预览
![输入图片说明](https://blog.gougucms.com/storage/image/b0.png)
![输入图片说明](https://blog.gougucms.com/storage/image/b1.png)
![输入图片说明](https://blog.gougucms.com/storage/image/b2.png)
![输入图片说明](https://blog.gougucms.com/storage/image/b3.png)


### 开源协议  
勾股BLOG遵循Apache2开源协议发布，并提供免费使用。 

### 开源助力
- 开源的系统少不了大家的参与，如果大家在体验的过程中发现有问题或者BUG，请到Issue里面反馈，谢谢！
- 毫无保留的真开源，如果觉得勾股BLOG不错，不要吝啬您的赞许和鼓励，请给我们⭐ STAR ⭐吧！

