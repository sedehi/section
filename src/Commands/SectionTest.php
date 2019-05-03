<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Console\DetectsApplicationNamespace;

class SectionTest extends Command
{

    use DetectsApplicationNamespace;
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'section:test {section : The name of the section}  {name : The name of the test} {--unit : Create a unit test}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Create a new test class in section';

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct(){

        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle(){

        if($this->option('unit')) {
            $this->makeDirectory($this->argument('section'), 'tests/Unit/');
            $path = app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/tests/Unit/'.$this->argument('name').'.php');
            $data = File::get(__DIR__.'/stubs/unit-test');
        }else {
            $this->makeDirectory($this->argument('section'), 'tests/Feature/');
            $path = app_path('Http/Controllers/'.ucfirst($this->argument('section')).'/tests/Feature/'.$this->argument('name').'.php');
            $data = File::get(__DIR__.'/stubs/test');
        }
        if(File::exists($path)) {
            $this->error('tests already exists.');
        }else {
            $data = str_replace('{{{name}}}', ucfirst($this->argument('name')), $data);
            $data = str_replace('{{{section}}}', ucfirst($this->argument('section')), $data);
            $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
            File::put($path, $data);
            $this->info('tests created successfully.');
        }
    }
}
