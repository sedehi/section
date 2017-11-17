<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use File;

class SectionExtend extends Command
{

    use \Illuminate\Console\AppNamespaceDetectorTrait, SectionsTrait;
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'section:extend {section : The name of the sections} {name : The name of the extend}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Extend a section ';

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

        if($this->confirm('Do you want create model ? [y|n]', true)) {
            $this->makeModel();
        }
        if($this->confirm('Do you want create admin controller ? [y|n]', true)) {
            if($this->confirm('Do you want upload picture in admin ? [y|n]', true)) {
                $this->makeAdminControllerWithUpload();
            }else {
                $this->makeAdminController();
            }
        }
        if($this->confirm('Do you want create form request ? [y|n]', true)) {
            $this->makeRequest();
        }
    }

    private function makeModel(){

        $this->call('section:model', ['section' => $this->argument('section'), 'name' => $this->argument('name')]);
    }

    private function makeAdminController(){

        $this->call('section:controller', [
            'section'    => $this->argument('section'),
            'name'       => ucfirst($this->argument('name')).'Controller',
            '--admin'    => true,
            '--crud'     => true,
            '--resource' => true,
        ]);
        $this->call('section:view', [
            'section'    => $this->argument('section'),
            'name'       => strtolower($this->argument('name')),
            'controller' => ucfirst($this->argument('name')).'Controller',
            '--admin'    => true,
        ]);
    }

    private function makeAdminControllerWithUpload(){

        $this->call('section:controller', [
            'section'    => $this->argument('section'),
            'name'       => ucfirst($this->argument('name')).'Controller',
            '--upload'   => true,
            '--resource' => true,
            '--admin'    => true,
        ]);
        $this->call('section:view', [
            'section'    => $this->argument('section'),
            'name'       => strtolower($this->argument('name')),
            'controller' => ucfirst($this->argument('name')).'Controller',
            '--upload'   => true,
            '--admin'    => true,
        ]);
    }

    private function makeRequest(){

        $this->call('section:request', [
            'section' => $this->argument('section'),
            'name'    => ucfirst($this->argument('name')).'Request',
            '--admin' => true,
            '--crud'  => true,
        ]);
    }

}
