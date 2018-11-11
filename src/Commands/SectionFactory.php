<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SectionFactory extends GeneratorCommand
{

    use SectionsTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'section:factory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a factory file in section';

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
        $this->createDirectory('database');

        $filePath = $this->getFilePath();

        if ($this->files->exists($filePath)) {
            $this->error('factory already exists.');
            return;
        }

        $this->files->put(
            $filePath,
            $this->buildClass($this->getFullModelName())
        );

        $this->info('factory created successfully.');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/Template/factory.stub';
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
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The name of the model'],
        ];
    }

    /**
     * Get file path to generate.
     *
     * @return string
     */
    protected function getFilePath()
    {
        return app_path('Http/Controllers/'.$this->getSectionName().'/database/factory.php');
    }

    /**
     * Get full model name.
     *
     * @return string
     */
    protected function getFullModelName()
    {
        $modelName = is_null($this->option('model')) ? $this->getSectionName() : studly_case($this->option('model'));

        return 'Http\\Controllers\\'.$this->getSectionName().'\\Models\\'.$modelName;
    }
}
