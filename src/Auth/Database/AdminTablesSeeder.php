<?php

namespace Zjalen\Leadmin\Auth\Database;

use Illuminate\Database\Seeder;
use Zjalen\Leadmin\Auth\Models\AdminMenu;
use Zjalen\Leadmin\Auth\Models\AdminPermission;
use Zjalen\Leadmin\Auth\Models\AdminRole;
use Zjalen\Leadmin\Auth\Models\AdminUser;

class AdminTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create a user.
        AdminUser::truncate();
        AdminUser::create([
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'name'     => '超级管理员',
        ]);

        // create a role.
        AdminRole::truncate();
        AdminRole::create([
            'name' => '超级管理',
            'slug' => 'administrator',
        ]);

        // add role to user.
        AdminUser::first()->roles()->save(AdminRole::first());

        //create a permission
        AdminPermission::truncate();
        AdminPermission::insert([
            [
                'name'        => '超级管理权限',
                'slug'        => '*',
                'http_method' => '',
                'http_path'   => '*',
            ],
            [
                'name'        => '登录',
                'slug'        => 'auth.login',
                'http_method' => '',
                'http_path'   => "admin/auth/login\r\nadmin/auth/logout",
            ],
            [
                'name'        => '用户设置',
                'slug'        => 'auth.setting',
                'http_method' => 'GET,PUT',
                'http_path'   => 'admin/auth/setting',
            ],
            [
                'name'        => '权限管理',
                'slug'        => 'auth.management',
                'http_method' => '',
                'http_path'   => "admin/auth/roles\r\nadmin/auth/permissions\r\nadmin/auth/menu\r\nadmin/auth/logs",
            ],
        ]);

        AdminRole::first()->permissions()->save(AdminPermission::first());

        // add default menus.
        AdminMenu::truncate();
        AdminMenu::insert([
            [
                'parent_id' => 0,
                'order'     => 2,
                'title'     => '系统管理',
                'icon'      => 'fa-tasks',
                'url'       => '',
            ],
            [
                'parent_id' => 2,
                'order'     => 3,
                'title'     => '用户管理',
                'icon'      => 'fa-users',
                'url'       => 'admin/auth/users',
            ],
            [
                'parent_id' => 2,
                'order'     => 4,
                'title'     => '角色管理',
                'icon'      => 'fa-black-tie',
                'url'       => 'admin/auth/roles',
            ],
            [
                'parent_id' => 2,
                'order'     => 5,
                'title'     => '权限管理',
                'icon'      => 'fa-exclamation-triangle',
                'url'       => 'admin/auth/permissions',
            ],
            [
                'parent_id' => 2,
                'order'     => 6,
                'title'     => '菜单管理',
                'icon'      => 'fa-navicon',
                'url'       => 'admin/auth/menu',
            ],
            [
                'parent_id' => 0,
                'order'     => 7,
                'title'     => '辅助工具',
                'icon'      => 'fa-wrench',
                'url'       => '',
            ],
            [
                'parent_id' => 7,
                'order'     => 8,
                'title'     => '脚手架',
                'icon'      => 'fa-keyboard-o',
                'url'       => 'admin/scaffold',
            ],
        ]);

        // add role to menu.
        AdminMenu::find(2)->roles()->save(AdminRole::first());
        AdminMenu::find(7)->roles()->save(AdminRole::first());
    }
}
