<?php

namespace Sedehi\Section\Console;

use Illuminate\Foundation\Console\TestMakeCommand;
use Illuminate\Support\Str;

class SectionTest extends TestMakeCommand
{
    public function __construct($files)
    {
        $this->signature .= '
        {--section= : The name of the section}
        {--test-version= : Set test version}
        ';
        parent::__construct($files);
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace;
        if ($this->option('section') !== null) {
            $namespace .= '\Http\Controllers\\'.Str::studly($this->option('section'));
        }
        if ($this->option('section') !== null) {
            $namespace .= '\Tests';
        }
        if ($this->option('unit')) {
            $namespace .= '\Unit';
        } else {
            $namespace .= '\Feature';
        }
        if ($this->option('test-version') !== null) {
            $namespace .= '\\'.Str::studly($this->option('test-version'));
        }

        return $namespace;
    }

    protected function rootNamespace()
    {
        if ($this->option('section') !== null) {
            return app()->getNamespace();
        }

        return 'Tests';
    }

    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $name = str_replace('\\Tests\\', '\\tests\\', $name);
        if ($this->option('section') !== null) {
            return app_path().'/'.str_replace('\\', '/', $name).'.php';
        }

        return base_path('tests').str_replace('\\', '/', $name).'.php';
    }
}
