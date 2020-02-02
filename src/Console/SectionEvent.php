<?php

namespace Sedehi\Section\Console;

use Illuminate\Foundation\Console\EventMakeCommand;
use Illuminate\Support\Str;
use Sedehi\Section\SectionOption;

class SectionEvent extends EventMakeCommand
{
    use SectionOption;

    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Http';
        if ($this->option('section') !== null) {
            $namespace .= '\Controllers\\' . Str::studly($this->option('section'));
        }

        return $namespace.'\Events';
    }
}
