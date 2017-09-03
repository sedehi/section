<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Console\DetectsApplicationNamespace;


class SectionRule extends Command
{
    use DetectsApplicationNamespace, SectionsTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:rule {section : The name of the section}  {name : The name of the rule}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new validation rule in section';

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
        $this->makeDirectory($this->argument('section'), 'Rules/');

        if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/Rules/'.$this->argument('name').'.php'))) {
            $this->error('Rule already exists.');
        } else {
            $data = File::get(__DIR__.'/Template/rule');

            $data = str_replace('{{{name}}}', ucfirst($this->argument('name')), $data);
            $data = str_replace('{{{section}}}', ucfirst($this->argument('section')), $data);
            $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
            File::put(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/Rules/'.$this->argument('name').'.php'),
                      $data);
            $this->info('Rule created successfully.');
        }
    }
}
