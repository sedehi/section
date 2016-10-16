<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use File;

class SectionNotification extends Command
{

    use SectionsTrait, \Illuminate\Console\AppNamespaceDetectorTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:notification {section : The name of the section}  {name : The name of the notification}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new notification class in section';

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
        $this->makeDirectory($this->argument('section'), 'Notifications/');

        if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/Notifications/'.ucfirst($this->argument('name')).'.php'))) {
            $this->error('notification already exists.');
        } else {
            $data = File::get(__DIR__.'/Template/notification.stub');

            $data = str_replace('{{{name}}}', ucfirst($this->argument('name')), $data);
            $data = str_replace('{{{section}}}', ucfirst($this->argument('section')), $data);
            $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
            File::put(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/Notifications/'.ucfirst($this->argument('name')).'.php'),
                      $data);
            $this->info('notification created successfully.');
        }
    }
}
