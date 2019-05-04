<?php

namespace Sedehi\Section\Commands;

use Illuminate\Foundation\Console\TestMakeCommand;
use Illuminate\Support\Str;

class SectionTest extends TestMakeCommand
{

    public function __construct($files){

        $this->signature .= '
        {--section= : The name of the section}
        {--test-version= : Set test version}
        ';
        parent::__construct($files);
    }

    protected function getDefaultNamespace($rootNamespace){

        $namespace = $rootNamespace;
        if(!is_null($this->option('section'))) {
            $namespace = $namespace.'\Http\Controllers\\'.Str::studly($this->option('section'));
        }
        if(!is_null($this->option('section'))) {
            $namespace .= '\Tests';
        }
        if($this->option('unit')) {
            $namespace .= '\Unit';
        }else {
            $namespace .= '\Feature';
        }
        if(!is_null($this->option('test-version'))) {
            $namespace .= '\\'.Str::studly($this->option('test-version'));
        }

        return $namespace;
    }

    protected function rootNamespace(){

        if(!is_null($this->option('section'))) {
            return app()->getNamespace();
        }

        return 'Tests';
    }

    protected function getPath($name){

        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $name = str_replace('\\Tests\\','\\tests\\',$name);
        if(!is_null($this->option('section'))) {
            return app_path().'/'.str_replace('\\', '/', $name).'.php';
        }

        return base_path('tests').str_replace('\\', '/', $name).'.php';
    }

}
