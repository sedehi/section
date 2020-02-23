<?php

namespace Sedehi\Section\Console;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Support\Str;

class SectionMigration extends MigrateMakeCommand
{
    public function __construct($creator, $composer)
    {
        $this->signature .= '{--section= : The name of the section}';
        parent::__construct($creator, $composer);
    }

    protected function getMigrationPath()
    {
        $section = (Str::studly($this->input->getOption('section')));
        if ($section !== '') {
            $path = $this->laravel->basePath().'/app/Http/Controllers/'.$section.'/database/migrations';
            $this->makeDirectory($path);

            return $path;
        }
        if (($targetPath = $this->input->getOption('path')) !== null) {
            return !$this->usingRealPath() ? $this->laravel->basePath().'/'.$targetPath : $targetPath;
        }

        return parent::getMigrationPath();
    }

    protected function makeDirectory($path)
    {
        if (!app('files')->isDirectory($path)) {
            app('files')->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }
}
