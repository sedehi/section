<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use File;
use Illuminate\Console\DetectsApplicationNamespace;


class SectionMail extends Command
{

    use DetectsApplicationNamespace, SectionsTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:mail {section : The name of the section}  {name : The name of the mail}';

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
        $this->makeDirectory($this->argument('section'), 'Mail/');

        if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/Mail/'.ucfirst($this->argument('name')).'.php'))) {
            $this->error('mail already exists.');
        } else {
            $data = File::get(__DIR__.'/Template/mail.stub');

            $data = str_replace('{{{name}}}', ucfirst($this->argument('name')), $data);
            $data = str_replace('{{{section}}}', ucfirst($this->argument('section')), $data);
            $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
            File::put(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/Mail/'.ucfirst($this->argument('name')).'.php'),
                      $data);
            $this->info('mail created successfully.');
        }
    }
}
