<?php

namespace Sedehi\Section\Console;

use Illuminate\Routing\Console\MiddlewareMakeCommand;

class SectionPermissionMiddlewareCommand extends MiddlewareMakeCommand
{
    protected $name = 'section:permission-middleware';

    protected $hidden = true;

    protected $description = 'Create permission middleware class';

    protected function getStub()
    {
        return __DIR__.'/stubs/permission-middleware.stub';
    }
}
