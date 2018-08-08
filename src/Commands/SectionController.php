<?php

namespace Sedehi\Section\Commands;

use Artisan;
use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SectionController extends GeneratorCommand
{
    use SectionsTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'section:controller';

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

    public function __construct($files)
    {
        parent::__construct($files);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    private function init()
    {
        // only init method completed
        dd('not completed yet');

        $this->createDirectory('Controllers/');

        $this->controllerName = $this->getControllerBasePath().$this->getControllerName();
        $this->namespace        = $this->getDefaultNamespace($this->rootNamespace());

        if ($this->option('site')) {
            $this->createDirectory('Controllers/Site/');
            $this->controllerName = $this->getControllerBasePath().'Site/'.$this->getControllerName();
            $this->namespace      .= '\Site';
            $this->type           = 'site';
        }

        if ($this->option('admin')) {
            $this->createDirectory('Controllers/Admin/');
            $this->controllerName = $this->getControllerBasePath().'Admin/'.$this->getControllerName();
            $this->namespace      .= '\Admin';
            $this->type           = 'admin';
        }

        if ($this->option('api')) {
            $this->createDirectory('Controllers/Api/');
            if ($this->option('v')) {
                $this->controllerName = $this->getControllerBasePath().'Api/'.ucfirst($this->option('v')).'/'.$this->getControllerName();
                $this->namespace      .= '\Api\\'.ucfirst($this->option('v'));
            } else {
                $this->controllerName = $this->getControllerBasePath().'Api/'.$this->getControllerName();
                $this->namespace      .= '\Api';
            }

            $this->type = 'api';
        }
    }

    public function handle()
    {
        $this->init();

        if ($this->files->exists($this->controllerName.'.php')) {
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
                $data = File::get(__DIR__.'/Template/controller/resource/AdminController-upload.stub');
            } else {
                if ($this->option('crud')) {
                    $data = File::get(__DIR__.'/Template/controller/resource/AdminController.stub');
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

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['section', InputArgument::REQUIRED, 'The name of the section'],
            ['name', InputArgument::REQUIRED, 'The name of the controller'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_REQUIRED, 'Set model name.'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Generate a resource controller class.'],
            ['upload', null, InputOption::VALUE_NONE, 'Generate a resource controller class from upload template.'],
            ['site', null, InputOption::VALUE_NONE, 'Generate a site controller class'],
            ['admin', null, InputOption::VALUE_NONE, 'Generate an admin controller class'],
            ['api', null, InputOption::VALUE_NONE, 'Generate an api controller class'],
            ['crud', null, InputOption::VALUE_NONE, 'Generate a crud controller class'],
            ['v', null, InputOption::VALUE_REQUIRED, 'Set api version'],
        ];
    }

    /**
     * Get controller input name.
     *
     * @return string
     */
    protected function getControllerName()
    {
        return studly_case($this->argument('name'));
    }

    /**
     * Get controller path.
     *
     * @return string
     */
    protected function getControllerBasePath()
    {
        return $this->getSectionName().'/Controllers/';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'Http\Controllers\\'.$this->getSectionName().'\Controllers';
    }
}
