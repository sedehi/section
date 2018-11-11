<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SectionEvent extends GeneratorCommand
{
    use SectionsTrait;

    private $stubType;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'section:event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new event and it\'s listener in section';

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
        $this->buildEventClass();

        $this->buildListenerClass();
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
            ['event-name', InputArgument::REQUIRED, 'The name of the event'],
            ['listener-name', InputArgument::OPTIONAL, 'The name of the listener'],
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
            ['queued', null, InputOption::VALUE_NONE, 'Generate queued listener'],
        ];
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->stubType == 'event') {
            return $this->files->get(__DIR__.'/Template/event/event.stub');
        }

        if ($this->stubType == 'listener') {

            if ($this->option('queued')) {
                return $this->files->get(__DIR__.'/Template/event/listener-queued.stub');
            }

            return $this->files->get(__DIR__.'/Template/event/listener.stub');
        }
    }

    /**
     * Get event path.
     *
     * @return string
     */
    protected function getEventPath()
    {
        return app_path('Http/Controllers/'.$this->getSectionName().'/Events/'.$this->getEventName().'.php');
    }

    /**
     * Get event name.
     *
     * @return string
     */
    protected function getEventName()
    {
        return studly_case($this->argument('event-name'));
    }

    /**
     * Get listener path.
     *
     * @return string
     */
    protected function getListenerPath()
    {
        return app_path('Http/Controllers/'.$this->getSectionName().'/Listeners/'.$this->getListenerName().'.php');
    }

    /**
     * Get listener name.
     *
     * @return string
     */
    protected function getListenerName()
    {
        if (is_null($this->argument('listener-name'))) {
            return $this->getEventName().'Listener';
        }

        return studly_case($this->argument('listener-name'));
    }

    /**
     * Build event class.
     */
    protected function buildEventClass()
    {
        $this->createDirectory('Events');

        $eventPath = $this->getEventPath();

        if ($this->files->exists($eventPath)) {
            $this->error('event already exists.');
            return false;
        }

        $this->stubType = 'event';

        $stub = $this->getStub();

        $stub = str_replace(['{{{RootNamespace}}}','{{{section}}}','{{{name}}}'],
            [$this->rootNamespace(),$this->getSectionName(),$this->getEventName()],
            $stub
        );

        $this->files->put(
            $eventPath,
            $stub
        );

        $this->info('event created successfully.');
    }

    /**
     * Build listener class.
     */
    protected function buildListenerClass()
    {
        $this->createDirectory('Listeners');

        $listenerPath = $this->getListenerPath();

        if ($this->files->exists($listenerPath)) {
            $this->error('listener already exists.');
            return false;
        }

        $this->stubType = 'listener';

        $stub = $this->getStub();

        $stub = str_replace(['{{{RootNamespace}}}','{{{section}}}','{{{EventName}}}','{{{name}}}'],
            [$this->rootNamespace(),$this->getSectionName(),$this->getEventName(),$this->getListenerName()],
            $stub
        );

        $this->files->put(
            $listenerPath,
            $stub
        );

        $this->info('listener created successfully.');
    }
}
