<?php

namespace Sedehi\Section\Console;

use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;
use Sedehi\Section\SectionOption;

class SectionModel extends ModelMakeCommand
{

    use SectionOption;

    protected function getDefaultNamespace($rootNamespace){

        if(!is_null($this->option('section'))) {
            return $rootNamespace.'\Http\Controllers\\'.Str::studly($this->option('section')).'\\Models';
        }

        return $rootNamespace;
    }

    protected function createFactory(){

        $factory = Str::studly(class_basename($this->argument('name')));
        $this->call('make:factory', [
            'name'      => "{$factory}Factory",
            '--model'   => $this->qualifyClass($this->getNameInput()),
            '--section' => Str::studly($this->option('section')),
        ]);
    }

    protected function createMigration(){

        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));
        if($this->option('pivot')) {
            $table = Str::singular($table);
        }
        $this->call('make:migration', [
            'name'      => "create_{$table}_table",
            '--create'  => $table,
            '--section' => Str::studly($this->option('section')),
        ]);
    }
}
