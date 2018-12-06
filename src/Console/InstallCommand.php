<?php

namespace Zjalen\Leadmin\Console;

use Illuminate\Console\Command;
use Zjalen\Leadmin\Auth\Database\AdminTablesSeeder;
use Zjalen\Leadmin\Auth\Models\AdminUser;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'leadmin:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the leadmin package';

    /**
     * Install directory.
     *
     * @var string
     */
    protected $directory = '';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->initDatabase();

        $this->initAdminDirectory();
    }

    public function initDatabase()
    {
        $this->call('migrate');

        $userModel = AdminUser::class;

        if ($userModel::count() == 0) {
            $this->call('db:seed', ['--class' => AdminTablesSeeder::class]);
        }
    }

    /**
     * Initialize the admAin directory.
     *
     * @return void
     */
    protected function initAdminDirectory()
    {
        $this->directory = app_path('Leadmin');

        if (is_dir($this->directory)) {
            $this->line("<error>{$this->directory} directory already exists !</error> ");

            return;
        }

        if (!is_dir(app_path('Models'))) {
            $this->laravel['files']->makeDirectory(app_path('Models'), 0755, true, true);
        }

        $this->makeDir('/');
        $this->line('<info>Admin directory was created:</info> '.str_replace(base_path(), '', $this->directory));

        $this->makeDir('Controllers');
        $this->makeDir('Requests');

        $this->createModelFile();
        $this->createRoutesFile();
    }


    /**
     * Create Model.
     *
     * @return void
     */
    public function createModelFile()
    {
        $model = app_path('Models').'/Model.php';
        $contents = $this->getStub('Model');

        $this->laravel['files']->put(
            $model,
            $contents
        );
        $this->line('<info>Model file was created:</info> '.$model);
    }

    /**
     * Create routes file.
     *
     * @return void
     */
    protected function createRoutesFile()
    {
        $file = $this->directory.'/routes.php';

        $contents = $this->getStub('routes');
        $this->laravel['files']->put($file, $contents);
        $this->line('<info>Routes file was created:</info> '.str_replace(base_path(), '', $file));
    }

    /**
     * Get stub contents.
     *
     * @param $name
     *
     * @return string
     */
    protected function getStub($name)
    {
        return $this->laravel['files']->get(__DIR__."/stubs/$name.stub");
    }

    /**
     * Make new directory.
     *
     * @param string $path
     */
    protected function makeDir($path = '')
    {
        $this->laravel['files']->makeDirectory("{$this->directory}/$path", 0755, true, true);
    }
}
