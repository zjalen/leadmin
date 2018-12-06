<?php

namespace Zjalen\Leadmin;

use Zjalen\Leadmin\Auth\Models\AdminUser;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        'Zjalen\Leadmin\Console\InstallCommand',
        'Zjalen\Leadmin\Console\UninstallCommand',
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'leadmin.auth'        => \Zjalen\Leadmin\Auth\Middleware\Authenticate::class,
//        'admin.pjax'        => \Zjalen\Leadmin\Auth\Middleware\Pjax::class,
//        'admin.log'         => \Zjalen\Leadmin\Auth\Middleware\LogOperation::class,
        'leadmin.permission'  => \Zjalen\Leadmin\Auth\Middleware\Permission::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'leadmin' => [
            'leadmin.auth',
//            'admin.pjax',
//            'admin.log',
            'leadmin.permission',
        ],
    ];

    /**
     * @throws \ReflectionException
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'leadmin');

        if (file_exists($routes = app_path('Leadmin/routes.php'))) {
            $this->loadRoutesFrom($routes);
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../resources/views' => resource_path('views/leadmin')], 'leadmin-views');
            $this->publishes([__DIR__.'/../resources/assets/js/components' => resource_path('assets/js/components/leadmin')], 'leadmin-assets');
            $this->publishes([__DIR__.'/../public' => public_path('vendor/leadmin')], 'leadmin-public');
        }

//        //remove default feature of double encoding enable in laravel 5.6 or later.
//        $bladeReflectionClass = new \ReflectionClass('\Illuminate\View\Compilers\BladeCompiler');
//        if ($bladeReflectionClass->hasMethod('withoutDoubleEncoding')) {
//            Blade::withoutDoubleEncoding();
//        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->loadAdminAuthConfig();

        $this->registerRouteMiddleware();

        $this->commands($this->commands);
    }

    /**
     * Setup auth configuration.
     *
     * @return void
     */
    protected function loadAdminAuthConfig()
    {
        $array_auth = [

        'controller' => \Zjalen\Leadmin\Controllers\LogAuthController::class,

        'guards' => [
            'leadmin' => [
                'driver'   => 'session',
                'provider' => 'leadmin',
            ],
        ],

        'providers' => [
            'leadmin' => [
                'driver' => 'eloquent',
                'model'  => AdminUser::class,
            ],
        ]];
        config(array_dot($array_auth, 'auth.'));
    }

    /**
     * Register the route middleware.
     *
     * @return void
     */
    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        // register middleware group.
        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }
}
