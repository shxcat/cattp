# 通用管理系统框架

> 本系统基于 ThinkPHP 5.0 RC4 进行开发
> 由于使用了PHP新特性, 因此仅支持 PHP 7.0 以上版本
> 前端框架使用 `AmazeUI 2.7.1`, 参考: [http://amazeui.org/](http://amazeui.org/)

## Demo

本项目Demo地址: [https://demo.54cbw.com](https://demo.54cbw.com)

个人网站: [https://54cbw.com](https://54cbw.com) 正在努力建设中... :)

## 目录结构

本系统目录结构如下：

~~~
www  WEB部署目录（或者子目录）
├─application           应用目录
│  ├─admin              Admin 模块目录
│  │  ├─controller      控制器目录
│  │  ├─validate        验证器目录
│  │  ├─model           模型目录
│  │  └─view            视图目录
│  └─index              Index 模块目录
│     ├─controller      控制器目录
│     ├─validate        验证器目录
│     ├─model           模型目录
│     └─view            视图目录
│
├─command               CLI应用目录
│  ├─controller         CLI控制器目录
│  ├─model              CLI模型目录
│  ├─runtime            CLI运行时目录
│  ├─command.php        CLI命令配置文件
│  └─config.php         CLI配置文件
│
├─config                配置文件目录
│  ├─admin              Admin 模块配置
│  │  ├─config.php      Admin 模块配置文件
│  │  └─tags.php        Admin 模块行为配置文件
│  ├─index              Index 模块配置
│  │  ├─config.php      Index 模块配置文件
│  │  └─tags.php        Index 模块行为配置文件
│  ├─config.php         全局配置文件
│  ├─database.php       全局数据库配置文件
│  ├─route.php          全局路由配置文件
│  └─tags.php           全局行为配置文件
│
├─public                WEB目录（对外访问目录）
│  ├─index.php          入口文件
│  ├─router.php         快速测试文件
│  └─.htaccess          用于apache的重写
│
├─framework             ThinkPHP 5.0 框架目录
├─extend                扩展类库目录
├─runtime               应用的运行时目录（可写，可定制）
├─vendor                第三方类库目录（Composer依赖库）
├─composer.json         composer 定义文件
├─README.md             README 文件
├─think                 命令行入口文件
~~~

## 安装

1. 服务器需要开启 `pathinfo` 和 `rewrite`
2. 使用 `composer install` 初始化项目
3. 创建数据库 `think_admin_sys`
```
CREATE DATABASE `think_admin_sys` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;  
```
4. 导入数据库脚本, 文件路径: `install/think_admin_sys.sql`
5. 修改数据库配置文件: `config/database.php`


## 界面

![后台首页](http://git.oschina.net/uploads/images/2016/0729/120555_a68d661f_5981.png)