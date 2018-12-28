<?php

namespace Zjalen\Leadmin;

use Zjalen\Leadmin\Controllers\LogAuthController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;

/**
 * Class Admin.
 */
class Leadmin
{

    /**
     * The Leadmin version.
     *
     * @var string
     */
    const VERSION = '1.6.7';


    /**
     * @var array
     */
    public static $extensions = [];

    /**
     * @var []Closure
     */
    public static $booting;

    /**
     * @var []Closure
     */
    public static $booted;

    /**
     * Returns the long version of Laravel-admin.
     *
     * @return string The long application version
     */
    public static function getLongVersion()
    {
        return sprintf('Leadmin <comment>version</comment> <info>%s</info>', self::VERSION);
    }

    /**
     * @param $model
     *
     * @return mixed
     */
    public function getModel($model)
    {
        if ($model instanceof Model) {
            return $model;
        }

        if (is_string($model) && class_exists($model)) {
            return $this->getModel(new $model());
        }

        throw new InvalidArgumentException("$model is not a valid model");
    }

    /**
     * Left sider-bar menu.
     *
     * @return array
     */
    public function menu()
    {
        $menuModel = config('leadmin.database.menu_model');

        return (new $menuModel())->toTree();
    }

    /**
     * Get admin title.
     *
     * @return Config
     */
    public function title()
    {
        return config('leadmin.title');
    }

    /**
     * Get current login user.
     *
     * @return mixed
     */
    public function user()
    {
        return Auth::guard('leadmin')->user();
    }


    /**
     * Register the auth routes.
     *
     * @return void
     */
    public function registerAuthRoutes()
    {
        $attributes = [
            'prefix'     => 'admin',
            'middleware' => [ 'web', 'leadmin'],
        ];

        app('router')->group($attributes, function ($router) {

            /* @var \Illuminate\Routing\Router $router */
            $router->namespace('Zjalen\Leadmin\Controllers')->group(function ($router) {

                $router->post('admin_menus/saveMenus','AdminMenuController@saveMenus');
                $router->post('file_upload', 'AdminUserController@fileUpload');
                /* @var \Illuminate\Routing\Router $router */
                $router->resource('auth/users', 'AdminUserController');
                $router->resource('auth/roles', 'AdminRoleController');
                $router->resource('auth/permissions', 'AdminPermissionController');
                $router->resource('auth/menu', 'AdminMenuController', ['only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']]);
                $router->resource('auth/logs', 'AdminLogController', ['only' => ['index', 'destroy']]);
                $router->resource('scaffold', 'ScaffoldController', ['only' => ['index', 'store']]);
            });

            $authController = LogAuthController::class;

            /* @var \Illuminate\Routing\Router $router */
            $router->get('auth/login', $authController.'@login');
            $router->post('auth/login', $authController.'@authCheck');
            $router->get('auth/captcha', $authController.'@captcha');
            $router->get('auth/logout', $authController.'@logout');
            $router->get('auth/setting', $authController.'@getSetting');
            $router->put('auth/setting', $authController.'@putSetting');
        });
    }

    /**
     * Extend a extension.
     *
     * @param string $name
     * @param string $class
     *
     * @return void
     */
    public static function extend($name, $class)
    {
        static::$extensions[$name] = $class;
    }

    /**
     * @param callable $callback
     */
    public static function booting(callable $callback)
    {
        static::$booting[] = $callback;
    }

    /**
     * @param callable $callback
     */
    public static function booted(callable $callback)
    {
        static::$booted[] = $callback;
    }

    /*
     * Disable Pjax for current Request
     *
     * @return void
     */
    public function disablePjax()
    {
        if (request()->pjax()) {
            request()->headers->set('X-PJAX', false);
        }
    }
}
