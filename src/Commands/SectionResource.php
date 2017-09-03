<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Console\DetectsApplicationNamespace;


class SectionResource extends Command
{
    use DetectsApplicationNamespace, SectionsTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:resource {section : The name of the section}  {name : The name of the resource} {--collection : Create a resource collection.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new api resource in section';

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
        $this->makeDirectory($this->argument('section'), 'Resources/');

        if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/Resources/'.$this->argument('name').'.php'))) {
            $this->error('Resource already exists.');
        } else {
            if ($this->option('collection')) {
                $data = File::get(__DIR__.'/Template/resource/collection');
            } else {
                $data = File::get(__DIR__.'/Template/resource/single');
            }

            $data = str_replace('{{{name}}}', ucfirst($this->argument('name')), $data);
            $data = str_replace('{{{section}}}', ucfirst($this->argument('section')), $data);
            $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
            File::put(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/Resources/'.$this->argument('name').'.php'),
                      $data);
            $this->info('Resource created successfully.');
        }
    }
}
