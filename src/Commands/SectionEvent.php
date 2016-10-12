<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SectionEvent extends Command
{

    use SectionsTrait, \Illuminate\Console\AppNamespaceDetectorTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:event {section : The name of the section}  {name : The name of the controller} {--queued}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new event class in section';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->makeDirectory($this->argument('section'), 'Events');

        if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/Events/'.ucfirst($this->argument('name')).'.php'))) {
            $this->error('event already exists.');
        } else {
            $data = File::get(__DIR__.'/Template/event/event');

            $data = str_replace('{{{name}}}', ucfirst($this->argument('name')), $data);
            $data = str_replace('{{{section}}}', ucfirst($this->argument('section')), $data);
            $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
            File::put(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/Events/'.ucfirst($this->argument('name')).'.php'),
                      $data);
            $this->info('event created successfully.');
        }


        $this->makeDirectory($this->argument('section'), 'Listeners');

        if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/Listeners/'.ucfirst($this->argument('name')).'Listener.php'))) {
            $this->error('listener already exists.');
        } else {
            if ($this->option('queued')) {
                $data = File::get(__DIR__.'/Template/event/listener-queued');
            } else {
                $data = File::get(__DIR__.'/Template/event/listener');
            }

            $data = str_replace('{{{name}}}', ucfirst($this->argument('name')), $data);
            $data = str_replace('{{{section}}}', ucfirst($this->argument('section')), $data);
            $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
            File::put(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/Listeners/'.ucfirst($this->argument('name')).'Listener.php'),
                      $data);
            $this->info('listener created successfully.');
        }

        if (!File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/events.php'))) {

            $data = File::get(__DIR__.'/Template/eventLoader');

            $data = str_replace('{{{name}}}', ucfirst($this->argument('name')), $data);
            $data = str_replace('{{{section}}}', ucfirst($this->argument('section')), $data);
            $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
            File::put(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/events.php'), $data);
        }
    }
}
