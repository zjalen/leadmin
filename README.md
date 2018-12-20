# leadmin (Laravel + element + admin)
laravel 基于 element 的一个快捷表格表单式管理后台

*目前功能正在完善中，商业项目请谨慎使用*
## 说明
基于 `Laravel 5.5` LTS 版本开发，*更高版本请自行测试*。前端基于 Vue 开源项目 `Element UI` 开发，完成了基于表格和表单的前端显示样式组件，
将前端重复工作转移到后端指定格式数据输出，自动渲染前端页面的形式。提供了脚手架工具，可视化开发 mysql 数据库，并自动生成统一格式
控制器、模型、请求类、前端表格和表单，附带 RBAC 权限控制。

### 特色功能
1. 由后台数据格式控制前端渲染，自带通用表格表单等页面，根据数据自适应。
2. 自带用户、角色、权限、菜单控制。
3. 可在原有页面基础上，使用 vue `import` 引用方式在原有基础上自定义页面
4. 推荐标准开发模式，可视化自动生成 控制器、模型、请求类表单验证、migration 生成数据库

## 使用方法

1. composer 安装组件
```php
composer require zjalen/leadmin dev-master --dev  //开发版本
```

2. 发布资源
```php
php artisan vendor:publish --provider="Zjalen\Leadmin\AdminServiceProvider"
```

3.使用自带权限控制

```php
php artisan leadmin:install
```

完成之后，打开php服务，访问路由 `/admin` 即可，例如：
```php
php artisan serve
```
打开：`http://localhost:8000/admin`

## 注意事项
默认图片上传在 `storage/app/public` 里面，需要您自己将路径引用到 `public` ，laravel 自带命令：
```php
php artisan storage:link
```

## 后续完成
- 1.文档完善、注释完善
- 2.更多组件优化
- 3.多语言支持
- 4.更丰富自定义使用

## 其他
图片验证码引用自 `"mews/captcha" : "~2.2"` 

部分设计模式参考自项目 `encore/laravel-admin`
