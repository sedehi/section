<?php

namespace Sedehi\Section\Commands;

use File;
use Illuminate\Console\Command;

class SectionRequest extends Command
{

    use \Illuminate\Console\AppNamespaceDetectorTrait, SectionsTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:request {section : The name of the section}  {name : The name of the controller} {--admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new request class in section';

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
        $this->makeDirectory($this->argument('section'), 'Requests/');

        if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/Requests/'.ucfirst($this->argument('name')).'.php'))) {
            $this->error('Form Request already exists.');
        } else {
            if ($this->option('admin')) {
                $data = File::get(__DIR__.'/Template/requests/admin');
            } else {
                $data = File::get(__DIR__.'/Template/requests/site');
            }
            $data = str_replace('{{{name}}}', ucfirst($this->argument('name')), $data);
            $data = str_replace('{{{section}}}', ucfirst($this->argument('section')), $data);
            $data = str_replace('{{{lowerSection}}}', strtolower($this->argument('section')), $data);
            $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
            $data = str_replace('{{{className}}}', ucfirst($this->argument('name')), $data);
            File::put(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/Requests/'.ucfirst($this->argument('name')).'.php'),
                      $data);
            $this->info('Form Request created successfully.');
        }
    }
}
