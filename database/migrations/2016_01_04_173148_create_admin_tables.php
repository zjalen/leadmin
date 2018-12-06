<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('database.default');

        Schema::connection($connection)->create('admin_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 20)->unique()->comment('用户名');
            $table->string('password', 255)->comment('密码');
            $table->string('remember_token', 120)->comment('记住密码')->nullable();
            $table->string('name', 50)->comment('名字');
            $table->string('email', 100)->comment('邮箱')->nullable();
            $table->string('avatar', 100)->comment('头像')->nullable();
            $table->timestamps();
        });

        Schema::connection($connection)->create('admin_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->comment('名称')->unique();
            $table->string('slug', 50)->comment('标识')->unique();
            $table->timestamps();
        });

        Schema::connection($connection)->create('admin_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->comment('名称');
            $table->string('slug', 50)->comment('标识');
            $table->string('http_method', 50)->comment('请求方法');
            $table->string('http_path', 50)->comment('请求路由');
            $table->timestamps();
        });

        Schema::connection($connection)->create('admin_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0)->comment('父级');
            $table->integer('order')->default(0)->comment('排序');
            $table->string('title', 50)->comment('标题');
            $table->string('icon', 50)->comment('图标');
            $table->string('url', 100)->comment('链接')->nullable();
            $table->timestamps();
        });

        Schema::connection($connection)->create('admin_role_users', function (Blueprint $table) {
            $table->integer('role_id')->comment('角色id');
            $table->integer('user_id')->comment('用户id');
            $table->index(['role_id', 'user_id']);
            $table->timestamps();
        });

        Schema::connection($connection)->create('admin_role_permissions', function (Blueprint $table) {
            $table->integer('role_id')->comment('角色id');
            $table->integer('permission_id')->comment('权限id');
            $table->index(['role_id', 'permission_id']);
            $table->timestamps();
        });

        Schema::connection($connection)->create('admin_role_menu', function (Blueprint $table) {
            $table->integer('role_id')->comment('角色id');
            $table->integer('menu_id')->comment('菜单id');
            $table->index(['role_id', 'menu_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $connection = config('database.default');

        Schema::connection($connection)->dropIfExists('admin_users');
        Schema::connection($connection)->dropIfExists('admin_roles');
        Schema::connection($connection)->dropIfExists('admin_permissions');
        Schema::connection($connection)->dropIfExists('admin_menu');
        Schema::connection($connection)->dropIfExists('admin_role_users');
        Schema::connection($connection)->dropIfExists('admin_role_permissions');
        Schema::connection($connection)->dropIfExists('admin_role_menu');
    }
}
