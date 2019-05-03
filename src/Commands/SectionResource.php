<?php

namespace Sedehi\Section\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ResourceMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class SectionResource extends ResourceMakeCommand
{

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files){

        parent::__construct($files);
    }

    protected function getOptions(){

        $options = parent::getOptions();
        $options = array_merge($options, [
            ['section', 's', InputOption::VALUE_OPTIONAL, 'The name of the section'],
            ['api-version', 'av', InputOption::VALUE_OPTIONAL, 'Set api version']
        ]);

        return $options;
    }

    protected function getDefaultNamespace($rootNamespace){

        $namespace = $rootNamespace.'\Http';
        if(!is_null($this->option('section'))) {
            $namespace = $namespace.'\Controllers\\'.Str::studly($this->option('section'));
        }
        if(!is_null($this->option('api-version'))) {
            return $namespace.'\Resources\\'.Str::studly($this->option('api-version'));
        }

        return $namespace.'\Resources';
    }
}
