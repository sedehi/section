<?php

namespace Sedehi\Section\Console;

use Illuminate\Foundation\Console\MailMakeCommand;
use Illuminate\Support\Str;
use Sedehi\Section\SectionOption;

class SectionMail extends MailMakeCommand
{
    use SectionOption;

    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Http';
        if (!is_null($this->option('section'))) {
            $namespace = $namespace.'\Controllers\\'.Str::studly($this->option('section'));
        }

        return $namespace.'\Mail';
    }
}
