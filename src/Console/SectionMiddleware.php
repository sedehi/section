<?php

namespace Sedehi\Section\Console;

use Illuminate\Support\Str;
use Sedehi\Section\SectionOption;
use Illuminate\Routing\Console\MiddlewareMakeCommand;

class SectionMiddleware extends MiddlewareMakeCommand
{
    use SectionOption;

    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Http';
        if (!is_null($this->option('section'))) {
            $namespace = $namespace.'\Controllers\\'.Str::studly($this->option('section'));
        }

        return $namespace.'\Middlewares';
    }
}
