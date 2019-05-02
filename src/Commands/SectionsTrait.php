<?php


namespace Sedehi\Section\Commands;
use File;

trait SectionsTrait
{
    public function makeDirectory($section,$folder = '')
    {
        if (!File::isDirectory(app_path('Http/Controllers/'.ucfirst($section).'/'.$folder))) {
            File::makeDirectory(app_path('Http/Controllers/'.ucfirst($section).'/'.$folder), 0775, true);
        }
    }

    public function createDirectory($folder, $section = null)
    {
        if (is_null($section)) {
            $section = $this->getSectionName();
        }

        if (!File::isDirectory(app_path('Http/Controllers/'.ucfirst($section).'/'.$folder))) {
            File::makeDirectory(app_path('Http/Controllers/'.ucfirst($section).'/'.$folder), 0775, true);
        }
    }

    protected function getSectionName()
    {
        return studly_case($this->argument('section'));
    }
}
