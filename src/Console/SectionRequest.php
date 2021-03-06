<?php

namespace Sedehi\Section\Console;

use Illuminate\Foundation\Console\RequestMakeCommand;
use Illuminate\Support\Str;
use Sedehi\Section\SectionOption;
use Symfony\Component\Console\Input\InputOption;

class SectionRequest extends RequestMakeCommand
{
    use SectionOption;

    protected function getStub()
    {
        if ($this->option('admin')) {
            return __DIR__.'/stubs/admin-request.stub';
        }

        return parent::getStub();
    }

    protected function getOptions()
    {
        $options = parent::getOptions();
        $options = array_merge($options, [
            ['section', null, InputOption::VALUE_OPTIONAL, 'The name of the section'],
            ['request-version', 'av', InputOption::VALUE_OPTIONAL, 'Set request version'],
            ['admin', null, InputOption::VALUE_NONE, 'Generate request for admin'],
            ['site', null, InputOption::VALUE_NONE, 'Generate request for site'],
            ['api', null, InputOption::VALUE_NONE, 'Generate request for api'],
        ]);

        return $options;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Http';
        if ($this->option('section') !== null) {
            $namespace .= '\Controllers\\'.Str::studly($this->option('section'));
        }
        $namespace .= '\Requests';
        if ($this->option('admin')) {
            $namespace .= '\Admin';
        }
        if ($this->option('site')) {
            $namespace .= '\Site';
        }
        if ($this->option('api')) {
            $namespace .= '\Api';
            if ($this->option('request-version') !== null) {
                $namespace .= '\\'.Str::studly($this->option('request-version'));
            }
        }

        return $namespace;
    }
}
