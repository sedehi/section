<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SectionJob extends GeneratorCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'section:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new job class in section';

     /**
     * Create a new command instance.
     * @return void
     */
    public function __construct(){

        parent::__construct(app()->files);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->createDirectory('Jobs/');

        $filePath = $this->getFilePath();

        if ($this->files->exists($filePath)) {
            return $this->error('job already exists.');
        }

        $this->files->put(
            $filePath,
            $this->buildClass($this->getNamespace($this->rootNamespace()))
        );

        $this->info('job created successfully.');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->option('sync')
            ? __DIR__.'/Template/job/sync.stub'
            : __DIR__.'/Template/job/queued.stub';
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
            ['name', InputArgument::REQUIRED, 'The name of the job'],
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
            ['sync', null, InputOption::VALUE_NONE, 'Indicates that job should be synchronous.'],
        ];
    }

    /**
     * Get file path to generate.
     *
     * @return string
     */
    protected function getFilePath()
    {
        return app_path('Http/Controllers/'.$this->getSectionName().'/Jobs/'.studly_case($this->getNameInput()).'.php');
    }

    /**
     * Get namespace.
     *
     * @return string
     */
    protected function getNamespace($rootNamespace)
    {
        return $rootNamespace.'Http\Controllers\\'.$this->getSectionName().'\Jobs';
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
            '{{{name}}}',
        ],[
            $this->getNamespace($this->rootNamespace()),
            studly_case($this->getNameInput()),
        ],$stub);
    }
}
