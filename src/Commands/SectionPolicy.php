<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use File;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\PolicyMakeCommand;
use Illuminate\Support\Str;
use Sedehi\Section\SectionOption;

class SectionPolicy extends PolicyMakeCommand
{

    use SectionOption;

    public function __construct(Filesystem $files){

        parent::__construct($files);
    }

    protected function getDefaultNamespace($rootNamespace){

        $namespace = $rootNamespace.'\Http';
        if(!is_null($this->option('section'))) {
            $namespace = $namespace.'\Controllers\\'.Str::studly($this->option('section'));
        }

        return $namespace.'\Policies';
    }
}
