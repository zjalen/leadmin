<?php

/**
 * Created by PhpStorm.
 * User: Zjalen
 * Date: 2018/8/31
 * Time: 17:12
 */

namespace Zjalen\Leadmin\Controllers;

use Illuminate\Routing\Controller;
use Zjalen\Leadmin\Scaffold\RequestCreator;
use Illuminate\Http\Request;
use Zjalen\Leadmin\Scaffold\ModelCreator;
use Zjalen\Leadmin\Scaffold\ControllerCreator;
use Zjalen\Leadmin\Scaffold\MigrationCreator;
use Illuminate\Support\Facades\Artisan;

class ScaffoldController extends Controller
{
    public function index()
    {
        return view('leadmin.helpers.scaffold');
    }

    public function store(Request $request)
    {
        $paths = [];
        $message = '';
        $config = $request->post('config');
        $table_config = $request->post('table_config');
        $table_data = $request->post('table_data');
        if (!$config || !$table_config || !$table_data){
            return ['error_code'=> 1, 'error_message'=>'参数有误'];
        }
        try {
            // 创建模型
            if ($config['create_model']){
                $modelCreator = new ModelCreator($config['table'], $config['model']);

                $paths['model'] = $modelCreator->create(
                    $table_config['table_index'],
                    $table_config['include_timestamp'] == 'on',
                    $table_config['soft_delete'] == 'on',
                    $table_data
                );
            }
            sleep(1);

            // 创建验证类
            if ($config['create_request']){
                $RequestCreator = new RequestCreator($config['request']);

                $paths['request'] = $RequestCreator->create(
                    $table_data
                );
            }
            sleep(1);

            // 创建控制器
            if ($config['create_controller']){
                $ControllerCreator = new ControllerCreator($config['controller']);

                $paths['controller'] = $ControllerCreator->create(
                    $config['model'],$table_data, $config['request']
                );
            }
            sleep(1);

            // 创建migration
            if ($config['create_migration']){
                $migrationName = 'create_'.$config['table'].'_table';

                $paths['migration'] = (new MigrationCreator(app('files')))->buildBluePrint(
                    $table_data,
                    $table_config['table_index'],
                    $table_config['include_timestamp'] == 'on',
                    $table_config['soft_delete'] == 'on'
                )->create($migrationName, database_path('migrations'), $config['table']);
            }

            // 运行migration
            if ($config['run_migration']) {
                Artisan::call('migrate');
                $message = Artisan::output();
            }

        } catch (\Exception $exception) {

            // Delete generated files if exception thrown.
            app('files')->delete($paths);

            return $this->backWithException($exception);
        }

        return $this->backWithSuccess($paths, $message);
    }

    protected function backWithException(\Exception $exception)
    {
        return [
            'error_code'   => '2',
            'error_message' => $exception->getMessage(),
        ];
    }

    protected function backWithSuccess($paths, $message)
    {
        return [
            'error_code'   => '0',
            'error_message' => '创建成功',
        ];
    }
}