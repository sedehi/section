<?php

namespace Sedehi\Section\Console;

use Illuminate\Routing\Console\MiddlewareMakeCommand;

class SectionDefineGatesMiddlewareCommand extends MiddlewareMakeCommand
{
    protected $name = 'section:define-gates-middleware';

    protected $hidden = true;

    protected $description = 'Create define gates middleware class';

    protected function getStub()
    {
        return __DIR__.'/stubs/define-gates-middleware.stub';
    }
}
