<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Console\DetectsApplicationNamespace;


class SectionJob extends Command
{

    use DetectsApplicationNamespace, SectionsTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:job {section : The name of the section}  {name : The name of the model} {--sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new job class in section';

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
        $this->makeDirectory($this->argument('section'), 'Jobs/');

        if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/Jobs/'.ucfirst($this->argument('name')).'.php'))) {
            $this->error('job already exists.');
        } else {
            if ($this->option('sync')) {
                $data = File::get(__DIR__.'/Template/job/sync');
            } else {
                $data = File::get(__DIR__.'/Template/job/queued');
            }

            $data = str_replace('{{{name}}}', ucfirst($this->argument('name')), $data);
            $data = str_replace('{{{section}}}', ucfirst($this->argument('section')), $data);
            $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
            File::put(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/Jobs/'.ucfirst($this->argument('name')).'.php'),
                      $data);
            $this->info('job created successfully.');
        }
    }
}
