<?php

namespace Sedehi\Section\Commands;

use File;
use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SectionModel extends GeneratorCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'section:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent model class in section';

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
        $this->createDirectory('Models/');

        $filePath = $this->getFilePath();

        if ($this->files->exists($filePath)) {
            return $this->error('Model already exists.');
        }

        $this->files->put(
            $filePath,
            $this->buildClass($this->getParentClassFullName())
        );

        $this->info('Model created successfully.');

        if ($this->option('all')) {
            $this->input->setOption('factory', true);
            $this->input->setOption('migration', true);
            $this->input->setOption('controller', true);
        }

        if ($this->option('factory')) {
            $this->createFactory();
        }

        if ($this->option('migration')) {
            $this->createMigration();
        }

        if ($this->option('controller')) {
            $this->createController();
        }
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/model.stub';
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
            ['name', InputArgument::REQUIRED, 'The name of the model'],
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
            ['all', 'a', InputOption::VALUE_NONE, 'Generate a migration, factory, and resource controller for the model'],
            ['controller', 'c', InputOption::VALUE_NONE, 'Create a new controller for the model'],
            ['factory', 'f', InputOption::VALUE_NONE, 'Create a new factory for the model'],
            ['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the model.'],
            ['pivot', 'p', InputOption::VALUE_NONE, 'Indicates if the generated model should be a custom intermediate table model.'],
        ];
    }

    /**
     * Get file path to generate.
     *
     * @return string
     */
    protected function getFilePath()
    {
        return app_path('Http/Controllers/'.$this->getSectionName().'/Models/'.studly_case($this->getNameInput()).'.php');
    }

    /**
     * Get model parent namespace.
     *
     * @return string
     */
    protected function getParentClassFullName()
    {
        if ($this->option('pivot')) {
            return 'Illuminate\Database\Eloquent\Relations\Pivot';
        }

        return 'Illuminate\Database\Eloquent\Model';
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
            '{{{RootNamespace}}}',
            '{{{section}}}',
            '{{{ParentFullName}}}',
            '{{{ParentName}}}',
            '{{{name}}}',
            '{{{table}}}'
        ],[
            $this->rootNamespace(),
            $this->getSectionName(),
            $this->getParentClassFullName(),
            class_basename($this->getParentClassFullName()),
            studly_case($this->getNameInput()),
            snake_case($this->getNameInput())
        ],$stub);
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createFactory()
    {
        $this->call('section:factory', [
            'section' => $this->getSectionName(),
            '--model' => $this->getNameInput(),
        ]);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createMigration()
    {
        $table = Str::plural(Str::snake($this->getNameInput()));

        $this->call('section:migration', [
            'section' => $this->getSectionName(),
            'name' => "create_{$table}_table",
            '--create' => $table,
        ]);
    }

    /**
     * Create a controller for the model.
     *
     * @return void
     */
    protected function createController()
    {
        $modelName = studly_case($this->getNameInput());

        $this->call('section:controller', [
            'section' => $this->getSectionName(),
            'name' => "{$modelName}Controller",
            '--model' => $modelName
        ]);
    }
}
