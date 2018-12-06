<?php

namespace Zjalen\Leadmin\Auth\Database;

use Illuminate\Database\Seeder;

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
        Administrator::truncate();
        Administrator::create([
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'name'     => '超级管理员',
        ]);

        // create a role.
        Role::truncate();
        Role::create([
            'name' => '超级管理',
            'slug' => 'administrator',
        ]);

        // add role to user.
        Administrator::first()->roles()->save(Role::first());

        //create a permission
        Permission::truncate();
        Permission::insert([
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

        Role::first()->permissions()->save(Permission::first());

        // add default menus.
        Menu::truncate();
        Menu::insert([
            [
                'parent_id' => 0,
                'order'     => 1,
                'title'     => '工作面板',
                'icon'      => 'fa-bar-chart',
                'url'       => 'panel',
            ],
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
        Menu::find(2)->roles()->save(Role::first());
        Menu::find(7)->roles()->save(Role::first());
    }
}
