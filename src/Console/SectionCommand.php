<?php

namespace Sedehi\Section\Console;

use Illuminate\Foundation\Console\ConsoleMakeCommand;
use Illuminate\Support\Str;
use Sedehi\Section\SectionOption;

class SectionCommand extends ConsoleMakeCommand
{
    use SectionOption;

    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Http';
        if ($this->option('section') !== null) {
            $namespace .= '\Controllers\\' . Str::studly($this->option('section'));
        }

        return $namespace.'\Commands';
    }
}
