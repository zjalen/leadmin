<?php

namespace Zjalen\Leadmin\Console;

use Illuminate\Console\Command;

class UninstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'leadmin:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the leadmin package files';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->confirm('Are you sure to uninstall leadmin?')) {
            return;
        }

        $this->removeFilesAndDirectories();

        $this->line('<info>Uninstalling leadmin!</info>');
    }

    /**
     * Remove files and directories.
     *
     * @return void
     */
    protected function removeFilesAndDirectories()
    {
        $this->laravel['files']->deleteDirectory(app_path('Leadmin'));
        $this->laravel['files']->deleteDirectory(public_path('vendor/leadmin/'));
        $this->laravel['files']->deleteDirectory(resource_path('assets/js/components/leadmin/'));
        $this->laravel['files']->deleteDirectory(resource_path('views/leadmin/'));
    }
}
