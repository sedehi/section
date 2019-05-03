<?php

namespace Sedehi\Section\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\NotificationMakeCommand;
use Illuminate\Support\Str;
use Sedehi\Section\SectionOption;

class SectionNotification extends NotificationMakeCommand
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

        return $namespace.'\Notifications';
    }
}
