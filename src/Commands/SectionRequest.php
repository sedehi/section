<?php

namespace Sedehi\Section\Commands;

use File;
use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;


class SectionRequest extends Command
{

    use DetectsApplicationNamespace, SectionsTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:request {section : The name of the section}  {name : The name of the request} {--crud} {--admin} {--api} {--site} {--v= : Set api version}';

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

    private function init()
    {

        $this->makeDirectory($this->argument('section'), 'Requests');

        $this->requestsName = ucfirst($this->argument('section')).'/Requests';
        $this->namespace    = $this->getAppNamespace().'Http\Controllers\\'.ucfirst($this->argument("section")).'\Requests';

        if ($this->option('site')) {
            $this->makeDirectory($this->argument('section'), 'Requests/Site/');
            $this->requestsName = ucfirst($this->argument('section')).'/Requests/Site';
            $this->namespace    = $this->getAppNamespace().'Http\Controllers\\'.ucfirst($this->argument("section")).'\Requests\Site';
        }

        if ($this->option('admin')) {
            $this->makeDirectory($this->argument('section'), 'Requests/Admin/');
            $this->requestsName = ucfirst($this->argument('section')).'/Requests/Admin';
            $this->namespace    = $this->getAppNamespace().'Http\Controllers\\'.ucfirst($this->argument("section")).'\Requests\Admin';
        }

        if ($this->option('api')) {

            $this->makeDirectory($this->argument('section'), 'Requests/Api/');

            if ($this->option('v')) {
                $this->makeDirectory($this->argument('section'), 'Requests/Api/'.ucfirst($this->option('v')));
                $this->requestsName = ucfirst($this->argument('section')).'/Requests/Api/'.ucfirst($this->option('v'));
                $this->namespace    = $this->getAppNamespace().'Http\Controllers\\'.ucfirst($this->argument("section")).'\Requests\Api\\'.ucfirst($this->option('v'));
            } else {
                $this->requestsName = ucfirst($this->argument('section')).'/Requests/Api';
                $this->namespace    = $this->getAppNamespace().'Http\Controllers\\'.ucfirst($this->argument("section")).'\Requests\Api';
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

        if (File::exists(app_path('Http/Controllers/'.$this->requestsName.'/'.ucfirst($this->argument('name')).'.php'))) {
            $this->error('Form Request already exists.');
        } else {
            if ($this->option('crud')) {
                $data = File::get(__DIR__.'/Template/requests/admin');
            } else {
                if ($this->option('api')) {
                    $data = File::get(__DIR__.'/Template/requests/api');
                } else {
                    $data = File::get(__DIR__.'/Template/requests/site');
                }
            }
            $data = str_replace('{{{name}}}', ucfirst($this->argument('name')), $data);
            $data = str_replace('{{{namespace}}}', $this->namespace, $data);
            $data = str_replace('{{{section}}}', ucfirst($this->argument('section')), $data);
            $data = str_replace('{{{lowerSection}}}', strtolower($this->argument('section')), $data);
            $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
            $data = str_replace('{{{className}}}', ucfirst($this->argument('name')), $data);
            File::put(app_path('Http/Controllers/'.$this->requestsName.'/'.ucfirst($this->argument('name')).'.php'),
                      $data);
            $this->info('Form Request created successfully.');
        }
    }
}
