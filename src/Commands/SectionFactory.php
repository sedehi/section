<?php

namespace Sedehi\Section\Commands;

use Illuminate\Database\Console\Factories\FactoryMakeCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Sedehi\Section\SectionOption;

class SectionFactory extends FactoryMakeCommand
{

    use SectionOption;

    public function __construct(Filesystem $files){

        parent::__construct($files);
    }

    protected function getPath($name){

        $name = str_replace(['\\', '/'], '', $this->argument('name'));
        if(!is_null($this->option('section'))) {
            return app_path("Http/Controllers/".Str::studly($this->option('section'))."/database/factories/{$name}.php");
        }

        return $this->laravel->databasePath()."/factories/{$name}.php";
    }

}
