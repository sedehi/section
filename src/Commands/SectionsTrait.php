<?php
/**
 * Created by PhpStorm.
 * User: navid
 * Date: 5/3/16
 * Time: 1:20 PM
 */

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
}