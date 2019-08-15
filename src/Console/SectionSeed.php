<?php

namespace Sedehi\Section\Console;

use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Illuminate\Support\Str;
use Sedehi\Section\SectionOption;

class SectionSeed extends SeederMakeCommand
{

    use SectionOption;

    /**
     * Get the destination class path.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getPath($name){

        $name = str_replace(['\\', '/'], '', $this->argument('name'));
        if(!is_null($this->option('section'))) {
            return app_path("Http/Controllers/".Str::studly($this->option('section'))."/database/seeds/{$name}.php");
        }

        return $this->laravel->databasePath().'/seeds/'.$name.'.php';
    }

}
