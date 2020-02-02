<?php

namespace Sedehi\Section\Console;

use Illuminate\Foundation\Console\ListenerMakeCommand;
use Illuminate\Support\Str;
use Sedehi\Section\SectionOption;

class SectionListener extends ListenerMakeCommand
{
    use SectionOption;

    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Http';
        if ($this->option('section') !== null) {
            $namespace .= '\Controllers\\' . Str::studly($this->option('section'));
        }

        return $namespace.'\Listeners';
    }
}
