<?php

namespace Sedehi\Section\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\MailMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class SectionMail extends MailMakeCommand
{

    public function __construct(Filesystem $files){

        parent::__construct($files);
    }

    protected function getOptions(){

        $options = parent::getOptions();
        $options = array_merge($options, [
            ['section', 's', InputOption::VALUE_OPTIONAL, 'The name of the section'],
        ]);

        return $options;
    }

    protected function getDefaultNamespace($rootNamespace){

        $namespace = $rootNamespace.'\Http';
        if(!is_null($this->option('section'))) {
            $namespace = $namespace.'\Controllers\\'.Str::studly($this->option('section'));
        }

        return $namespace.'\Mail';
    }
}
