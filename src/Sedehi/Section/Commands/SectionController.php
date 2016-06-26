<?php

namespace Sedehi\Section\Commands;

use Artisan;
use File;
use Illuminate\Console\Command;

class SectionController extends Command
{

    use SectionsTrait, \Illuminate\Console\AppNamespaceDetectorTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:controller {section : The name of the section}  {name : The name of the controller} {--resource} {--upload} {--plain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller class in section';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $controllerName;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    private function init()
    {

        $this->makeDirectory($this->argument('section'), 'Controllers/');
        $this->controllerName = ucfirst($this->argument('section')).'/Controllers/'.ucfirst($this->argument('name'));
    }

    public function handle()
    {
        if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/Controllers/'.ucfirst($this->argument('name').'.php')))) {
            return $this->error('controller already exists.');
        }

        $this->init();

        if ($this->option('plain')) {
            if ($this->option('resource')) {
                Artisan::call('make:controller', [
                    'name'       => $this->controllerName,
                    '--resource' => true,
                ]);
            } else {
                Artisan::call('make:controller', ['name' => $this->controllerName]);
            }
        } else {

            if ($this->option('upload')) {

                if (!File::isDirectory(public_path('uploads/'.strtolower($this->argument('section')).'/'))) {
                    File::makeDirectory(public_path('uploads/'.strtolower($this->argument('section')).'/'), 0775, true);
                }
            }

            if ($this->option('resource')) {
                if ($this->option('upload')) {
                    $data = File::get(__DIR__.'/Template/controller/resource/AdminController-upload');
                } else {
                    $data = File::get(__DIR__.'/Template/controller/resource/AdminController');
                }
            } else {

                if ($this->option('upload')) {
                    $data = File::get(__DIR__.'/Template/controller/AdminController-upload');
                } else {
                    $data = File::get(__DIR__.'/Template/controller/AdminController');
                }
            }
            $data = str_replace('{{{name}}}', ucfirst($this->argument('name')), $data);
            $data = str_replace('{{{section}}}', ucfirst($this->argument('section')), $data);
            $data = str_replace('{{{nameLower}}}', strtolower($this->argument('name')), $data);
            $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
            File::put(app_path('Http/Controllers/'.$this->controllerName.'.php'), $data);
        }


        $this->info('controller created successfully.');
    }

}
