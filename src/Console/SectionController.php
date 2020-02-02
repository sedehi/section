<?php

namespace Sedehi\Section\Console;

use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class SectionController extends ControllerMakeCommand
{
    protected function getOptions()
    {
        $options = parent::getOptions();
        $options = array_merge($options, [
            ['section', null, InputOption::VALUE_OPTIONAL, 'The name of the section'],
            ['crud', null, InputOption::VALUE_NONE, 'Generate a crud controller class'],
            ['upload', null, InputOption::VALUE_NONE, 'Generate a upload controller class'],
            ['site', null, InputOption::VALUE_NONE, 'Generate a site controller class'],
            ['admin', null, InputOption::VALUE_NONE, 'Generate a admin controller class'],
            ['controller-version', null, InputOption::VALUE_OPTIONAL, 'Set version'],
            ['custom-views', null, InputOption::VALUE_NONE, 'Generate views from old stubs'],
        ]);

        return $options;
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Http\Controllers';
        if ($this->option('section') !== null) {
            $namespace .= '\\'.Str::studly($this->option('section')).'\\Controllers';
        }
        if ($this->option('site')) {
            $namespace .= '\\Site';
        }
        if ($this->option('admin')) {
            $namespace .= '\\Admin';
        }
        if ($this->option('api')) {
            $namespace .= '\\Api';
        }
        if (!is_null($this->option('controller-version'))) {
            $namespace .= '\\'.Str::studly($this->option('controller-version'));
        }

        return $namespace;
    }

    protected function getStub()
    {
        $stub = null;
        if ($this->option('crud') && $this->option('model')) {
            if ($this->option('custom-views')) {
                return __DIR__.'/stubs/controller-crud.stub';
            }
            return __DIR__.'/stubs/controller-crud-dynamic.stub';
        }
        if ($this->option('upload') && $this->option('model')) {
            if ($this->option('custom-views')) {
                return __DIR__.'/stubs/controller-upload.stub';
            }
            return __DIR__.'/stubs/controller-upload-dynamic.stub';
        }
        if ($this->option('parent')) {
            $stub = '/stubs/controller.nested.stub';
        } elseif ($this->option('model')) {
            $stub = '/stubs/controller.model.stub';
        } elseif ($this->option('invokable')) {
            $stub = '/stubs/controller.invokable.stub';
        } elseif ($this->option('resource')) {
            $stub = '/stubs/controller.stub';
        }
        if ($this->option('api') && is_null($stub)) {
            $stub = '/stubs/controller.api.stub';
        } elseif ($this->option('api') && !is_null($stub) && !$this->option('invokable')) {
            $stub = str_replace('.stub', '.api.stub', $stub);
        }
        $stub = $stub ?? '/stubs/controller.plain.stub';

        return base_path('vendor/laravel/framework/src/Illuminate/Routing/Console/').$stub;
    }

    protected function buildClass($name)
    {
        $controllerNamespace = $this->getNamespace($name);
        $replace = [];
        if ($this->option('parent')) {
            $replace = $this->buildParentReplacements();
        }
        if ($this->option('model')) {
            $replace = $this->buildModelReplacements($replace);
        }
        if ($this->option('section')) {
            $replace = $this->buildSectionReplacements($replace);
        }
        $replace = $this->buildRequestReplacements($replace);
        $replace = $this->buildViewsReplacements($replace);
        $replace = $this->buildActionReplacements($replace);
        $replace["use {$controllerNamespace}\Controller;\n"] = '';

        return str_replace(array_keys($replace), array_values($replace), parent::buildClass($name));
    }

    protected function buildModelReplacements(array $replace)
    {
        $modelClass = $this->parseModel($this->option('model'));
        if ($this->option('section')) {
            $modelClass = $this->laravel->getNamespace().'Http\\Controllers\\'.Str::studly($this->option('section')).'\\Models\\'.Str::studly($this->option('model'));
        }
        if (!class_exists($modelClass)) {
            if ($this->confirm("A {$modelClass} model does not exist. Do you want to generate it?", true)) {
                if ($this->option('section')) {
                    $this->call('make:model', [
                        'name'      => $this->option('model'),
                        '--section' => $this->option('section'),
                    ]);
                } else {
                    $this->call('make:model', ['name' => $modelClass]);
                }
            }
        }

        return array_merge($replace, [
            'DummyFullModelClass' => $modelClass,
            'DummyModelClass'     => class_basename($modelClass),
            'DummyModelVariable'  => lcfirst(class_basename($modelClass)),
        ]);
    }

    protected function buildSectionReplacements($replace)
    {
        $section = Str::studly($this->option('section'));

        return array_merge($replace, [
            'DummySectionNormal' => $section,
            'DummySectionLower'  => strtolower($section),
        ]);
    }

    protected function buildViewsReplacements($replace)
    {
        if ($this->option('section')) {
            $path = Str::studly($this->option('section')).'.views.'.str_replace('\\', '', strtolower($this->type())).'.'.strtolower($this->option('section'));
        } else {
            $path = 'views.'.strtolower($this->nameWithoutController());
        }

        return array_merge($replace, [
            'DummyViewPath' => $path,
        ]);
    }

    protected function buildActionReplacements($replace)
    {
        if ($this->option('section')) {
            $path = Str::studly($this->option('section')).'\\Controllers\\'.Str::studly($this->type()).Str::studly($this->argument('name'));
        } else {
            $path = Str::studly($this->argument('name'));
        }

        return array_merge($replace, [
            'DummyAction' => $path,
        ]);
    }

    protected function buildRequestReplacements($replace)
    {
        if ($this->option('section')) {
            $requestClass = $this->getRequestClass();
            if (!class_exists($requestClass)) {
                if ($this->confirm("A {$requestClass} Request does not exist. Do you want to generate it?", true)) {
                    if ($this->option('section')) {
                        $this->call('make:request', [
                            'name'              => Str::studly($this->nameWithoutController()).'Request',
                            '--section'         => $this->option('section'),
                            '--admin'           => $this->option('admin'),
                            '--site'            => $this->option('site'),
                            '--api'             => $this->option('api'),
                            '--request-version' => 'V1',
                        ]);
                    } else {
                        $this->call('make:request', ['name' => Str::studly($this->nameWithoutController()).'Request']);
                    }
                }
            }
        }

        return array_merge($replace, [
            'DummyFullRequestClass' => ($this->option('section')) ? $requestClass : 'Illuminate\Http\Request',
            'DummyRequestClass'     => ($this->option('section')) ? Str::studly($this->nameWithoutController()).'Request' : 'Request',
            'DummyTypeLower'        => str_replace('\\', '', strtolower($this->type())),
            'DummyTypeNormal'       => $this->type(),
        ]);
    }

    protected function type()
    {
        if ($this->option('api')) {
            return 'Api\\';
        } elseif ($this->option('site')) {
            return 'Site\\';
        } elseif ($this->option('admin')) {
            return 'Admin\\';
        } else {
            return '';
        }
    }

    protected function nameWithoutController()
    {
        return str_replace('Controller', '', $this->argument('name'));
    }

    protected function getRequestClass()
    {
        $class = $this->laravel->getNamespace().'Http\\Controllers\\'.Str::studly($this->option('section')).'\\Requests\\';

        if ($this->option('api')) {
            $class .= 'Api\\V1\\';
        } elseif ($this->option('site')) {
            $class .= 'Site\\';
        } elseif ($this->option('admin')) {
            $class .= 'Admin\\';
        }

        $class .= Str::studly($this->nameWithoutController()).'Request';

        return $class;
    }
}
