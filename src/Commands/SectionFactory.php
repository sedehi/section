<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use File;

class SectionFactory extends Command
{

    use SectionsTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:factory {section : The name of the section}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a factory file in section';

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
        $this->makeDirectory(ucfirst($this->argument('section')), 'database/');
        if (File::exists(app_path('Http/Controllers/'.$this->argument('section').'/database/factory.php'))) {
            $this->error('factory already exists.');
        } else {
            $data = File::get(__DIR__.'/Template/factory');
            File::put(app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/database/factory.php'), $data);
            $this->info('factory created successfully.');
        }
    }
}
