<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SectionRequest extends GeneratorCommand
{

    use SectionsTrait;

    private $namespace;
    private $directoryPath;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'section:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new request class in section';

     /**
     * Create a new command instance.
     * @return void
     */
    public function __construct(){

        parent::__construct(app()->files);
    }

    private function init()
    {
        $this->createDirectory('Requests');
        $this->directoryPath    = $this->getBaseDirectory();
        $this->namespace        = $this->getDefaultNamespace($this->rootNamespace());

        if ($this->option('site')) {
            $this->createDirectory('Requests/Site/');
            $this->namespace    .= '\Site';
            $this->directoryPath    .= 'Site';
        }

        if ($this->option('admin')) {
            $this->createDirectory('Requests/Admin/');
            $this->namespace   .= '\Admin';
            $this->directoryPath   .= 'Admin';
        }

        if ($this->option('api')) {

            $this->createDirectory('Requests/Api/');

            if ($this->option('v')) {
                $this->createDirectory('Requests/Api/'.ucfirst($this->option('v')));
                $this->namespace   .= '\Api\\'.ucfirst($this->option('v'));
                $this->directoryPath   .= 'Api/'.ucfirst($this->option('v'));
            } else {
                $this->namespace   .= '\Api';
                $this->directoryPath   .= 'Api';
            }
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->init();

        $filePath = $this->getFilePath();

        if ($this->files->exists($filePath)) {
            $this->error('Form Request already exists.');
            return false;
        }

        $this->files->put(
            $filePath,
            $this->buildClass($this->namespace)
        );

        $this->info('Form Request created successfully.');
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($namespace)
    {
        $stub = $this->files->get($this->getStub());

        return str_replace([
            '{{{namespace}}}',
            '{{{section}}}',
            '{{{lowerSection}}}',
            '{{{RootNamespace}}}',
            '{{{className}}}'
        ],[
            $namespace,
            $this->getSectionName(),
            strtolower($this->getSectionName()),
            $this->rootNamespace(),
            studly_case($this->argument('name'))

        ],$stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('crud')) {
            return __DIR__.'/Template/requests/admin.stub';
        }
        if ($this->option('api')) {
            return __DIR__.'/Template/requests/api.stub';
        }
        return __DIR__.'/Template/requests/site.stub';
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
            ['name', InputArgument::REQUIRED, 'The name of the request'],
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
            ['crud', null, InputOption::VALUE_NONE, 'Generate crud request'],
            ['admin', null, InputOption::VALUE_NONE, 'Generate request for admin'],
            ['site', null, InputOption::VALUE_NONE, 'Generate request for site'],
            ['api', null, InputOption::VALUE_NONE, 'Generate request for api'],
            ['v', null, InputOption::VALUE_REQUIRED, 'Set api version'],
        ];
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'Http\Controllers\\'.$this->getSectionName().'\Requests';
    }

    /**
     * Get directory path.
     *
     * @return string
     */
    protected function getBaseDirectory()
    {
        return app_path('Http/Controllers/'.$this->getSectionName().'/Requests/');
    }

    /**
     * Get file path to generate.
     *
     * @return string
     */
    protected function getFilePath()
    {
        return $this->directoryPath.'/'.studly_case($this->argument('name')).'.php';
    }
}
