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
    protected $signature = 'section:controller {section : The name of the section}  {name : The name of the controller} {--resource} {--upload} {--site} {--api} {--admin} {--crud}';

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
    protected $namespace;
    protected $type;

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
        $this->namespace      = $this->getAppNamespace().'Http/Controllers/'.ucfirst($this->argument("section")).'/Controllers';

        if ($this->option('site')) {
            $this->makeDirectory($this->argument('section'), 'Controllers/Site/');
            $this->controllerName = ucfirst($this->argument('section')).'/Controllers/Site/'.ucfirst($this->argument('name'));
            $this->namespace      = $this->getAppNamespace().'Http/Controllers/'.ucfirst($this->argument("section")).'/Controllers/Site';
            $this->type           = '.site';
        }

        if ($this->option('admin')) {
            $this->makeDirectory($this->argument('section'), 'Controllers/Admin/');
            $this->controllerName = ucfirst($this->argument('section')).'/Controllers/Admin/'.ucfirst($this->argument('name'));
            $this->namespace      = $this->getAppNamespace().'Http/Controllers/'.ucfirst($this->argument("section")).'/Controllers/Admin';
            $this->type           = '.admin';
        }

        if ($this->option('api')) {
            $this->makeDirectory($this->argument('section'), 'Controllers/Api/');
            $this->controllerName = ucfirst($this->argument('section')).'/Controllers/Api/'.ucfirst($this->argument('name'));
            $this->namespace      = $this->getAppNamespace().'Http/Controllers/'.ucfirst($this->argument("section")).'/Controllers/Api';
            $this->type           = '.api';
        }
    }

    public function handle()
    {
        $this->init();

        if (File::exists(app_path('Http/Controllers/'.$this->controllerName.'.php'))) {
            return $this->error('controller already exists.');
        }


        if ($this->option('upload')) {
            if (!File::isDirectory(public_path('uploads/'.strtolower($this->argument('section')).'/'))) {
                File::makeDirectory(public_path('uploads/'.strtolower($this->argument('section')).'/'), 0775, true);
            }
        }

        $data = null;
        if ($this->option('resource')) {
            if ($this->option('upload')) {
                $data = File::get(__DIR__.'/Template/controller/resource/AdminController-upload');
            } else {
                if ($this->option('crud')) {
                    $data = File::get(__DIR__.'/Template/controller/resource/AdminController');
                } else {
                    Artisan::call('make:controller', [
                        'name'       => $this->controllerName,
                        '--resource' => true,
                    ]);
                }
            }
        } else {

            Artisan::call('make:controller', ['name' => $this->controllerName]);
        }

        if (!is_null($data)) {
            $data = str_replace('{{{name}}}', ucfirst($this->argument('name')), $data);
            $data = str_replace('{{{viewType}}}', $this->type, $data);
            $data = str_replace('{{{section}}}', ucfirst($this->argument('section')), $data);
            $data = str_replace('{{{sectionLower}}}', strtolower($this->argument('section')), $data);
            $data = str_replace('{{{nameLower}}}', strtolower($this->argument('name')), $data);
            $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
            $data = str_replace('{{{namespace}}}', $this->namespace, $data);
            File::put(app_path('Http/Controllers/'.$this->controllerName.'.php'), $data);
        }


        $this->info('controller created successfully.');
    }

}
