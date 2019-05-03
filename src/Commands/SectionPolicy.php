<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use File;
use Illuminate\Console\DetectsApplicationNamespace;


class SectionPolicy extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:policy {section : The name of the section}  {name : The name of the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Policy class in section';

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
        $this->makeDirectory($this->argument('section'), 'Policies/');

        if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/Policies/'.ucfirst($this->argument('name')).'.php'))) {
            $this->error('Policy already exists.');
        } else {
            $data = File::get(__DIR__.'/stubs/policy');

            $data = str_replace('{{{name}}}', ucfirst($this->argument('name')), $data);
            $data = str_replace('{{{section}}}', ucfirst($this->argument('section')), $data);
            $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
            File::put(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/Policies/'.ucfirst($this->argument('name')).'.php'),
                      $data);
            $this->info('Policy created successfully.');
        }
    }
}
