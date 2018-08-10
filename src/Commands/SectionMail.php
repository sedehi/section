<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SectionMail extends GeneratorCommand
{
    use SectionsTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'section:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new mail class in section';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($files)
    {
        parent::__construct($files);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->createDirectory('Mail/');

        $filePath = $this->getFilePath();

        if ($this->files->exists($filePath)) {
            $this->error('mail already exists.');
            return false;
        }

        $this->files->put(
            $filePath,
            $this->buildClass($this->getNamespace($this->rootNamespace()))
        );

        if ($this->option('markdown')) {
            $this->writeMarkdownTemplate();
        }

        $this->info('mail created successfully.');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->option('markdown')
            ? __DIR__.'/Template/mail/markdown-mail.stub'
            : __DIR__.'/Template/mail/mail.stub';
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
            ['name', InputArgument::REQUIRED, 'The name of the mail class'],
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
            ['markdown', 'm', InputOption::VALUE_OPTIONAL, 'Create a new Markdown template for the mailable.'],
        ];
    }

    /**
     * Get file path to generate.
     *
     * @return string
     */
    protected function getFilePath()
    {
        return app_path('Http/Controllers/'.$this->getSectionName().'/Mail/'.studly_case($this->getNameInput()).'.php');
    }

    /**
     * Get namespace.
     *
     * @return string
     */
    protected function getNamespace($rootNamespace)
    {
        return $rootNamespace.'Http\Controllers\\'.$this->getSectionName().'\Mail';
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

        $stub = str_replace([
            '{{{namespace}}}',
            '{{{name}}}',
        ],[
            $this->getNamespace($this->rootNamespace()),
            studly_case($this->getNameInput()),
        ],$stub);

        if ($this->option('markdown')) {
            $stub = str_replace('{{{markdownView}}}', $this->option('markdown'), $stub);
        }

        return $stub;
    }

    /**
     * Write the Markdown template for the mailable.
     *
     * @return void
     */
    protected function writeMarkdownTemplate()
    {
        $path = app_path('Http/Controllers/views/emails/'.str_replace('.', '/', $this->option('markdown'))).'.blade.php';

        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0755, true);
        }

        $this->files->put($path, file_get_contents(__DIR__.'/Template/mail/markdown.stub'));
    }
}
