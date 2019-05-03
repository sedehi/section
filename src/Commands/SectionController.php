<?php

namespace Sedehi\Section\Commands;

use Artisan;
use File;
use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;


class SectionController extends Command
{

    use DetectsApplicationNamespace;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:controller {section : The name of the section}  {name : The name of the controller} {--resource} {--upload} {--site} {--api} {--admin} {--crud} {--v= : Set api version} {--model= : Set model name}';

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
        $this->namespace      = $this->getAppNamespace().'Http\Controllers\\'.ucfirst($this->argument("section")).'\Controllers';

        if ($this->option('site')) {
            $this->makeDirectory($this->argument('section'), 'Controllers/Site/');
            $this->controllerName = ucfirst($this->argument('section')).'/Controllers/Site/'.ucfirst($this->argument('name'));
            $this->namespace      = $this->getAppNamespace().'Http\Controllers\\'.ucfirst($this->argument("section")).'\Controllers\Site';
            $this->type           = '.site';
        }

        if ($this->option('admin')) {
            $this->makeDirectory($this->argument('section'), 'Controllers/Admin/');
            $this->controllerName = ucfirst($this->argument('section')).'/Controllers/Admin/'.ucfirst($this->argument('name'));
            $this->namespace      = $this->getAppNamespace().'Http\Controllers\\'.ucfirst($this->argument("section")).'\Controllers\Admin';
            $this->type           = '.admin';
        }

        if ($this->option('api')) {
            $this->makeDirectory($this->argument('section'), 'Controllers/Api/');

            if ($this->option('v')) {
                $this->controllerName = ucfirst($this->argument('section')).'/Controllers/Api/'.ucfirst($this->option('v')).'/'.ucfirst($this->argument('name'));
                $this->namespace      = $this->getAppNamespace().'Http\Controllers\\'.ucfirst($this->argument("section")).'\Controllers\Api\\'.ucfirst($this->option('v'));
            } else {
                $this->controllerName = ucfirst($this->argument('section')).'/Controllers/Api/'.ucfirst($this->argument('name'));
                $this->namespace      = $this->getAppNamespace().'Http\Controllers\\'.ucfirst($this->argument("section")).'\Controllers\Api';
            }

            $this->type = '.api';
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
            $type = '';
            switch ($this->type) {
                case '.site':
                    $type = 'Site\\';
                    break;
                case '.api':
                    $type = 'Api\\';
                    break;
                case '.admin':
                    $type = 'Admin\\';
                    break;
            }

            $data = str_replace('{{{name}}}', ucfirst($this->argument('name')), $data);
            $data = str_replace('{{{onlyName}}}', str_replace("Controller", "", ucfirst($this->argument('name'))), $data);
            $data = str_replace('{{{type}}}', $type, $data);
            $data = str_replace('{{{viewType}}}', $this->type, $data);
            $data = str_replace('{{{section}}}', ucfirst($this->argument('section')), $data);

            if ($this->option('model')) {
                $data = str_replace('{{{model}}}', studly_case($this->option('model')), $data);
            } else {
                $data = str_replace('{{{model}}}', ucfirst($this->argument('section')), $data);
            }

            $data = str_replace('{{{sectionLower}}}', strtolower($this->argument('section')), $data);
            $data = str_replace('{{{nameLower}}}', strtolower($this->argument('name')), $data);
            $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
            $data = str_replace('{{{namespace}}}', $this->namespace, $data);
            File::put(app_path('Http/Controllers/'.$this->controllerName.'.php'), $data);
        }

        $this->info('controller created successfully.');
    }

}
