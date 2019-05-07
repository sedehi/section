<?php

namespace Sedehi\Section;

use Illuminate\Foundation\Providers\ArtisanServiceProvider;
use Illuminate\Routing\Console\ControllerMakeCommand;
use Sedehi\Section\Commands\SectionAdd;
use Sedehi\Section\Commands\SectionAuth;
use Sedehi\Section\Commands\SectionController;
use Sedehi\Section\Commands\SectionEvent;
use Sedehi\Section\Commands\SectionFactory;
use Sedehi\Section\Commands\SectionJob;
use Sedehi\Section\Commands\SectionMail;
use Sedehi\Section\Commands\SectionMigration;
use Sedehi\Section\Commands\SectionModel;
use Sedehi\Section\Commands\SectionNotification;
use Sedehi\Section\Commands\SectionPolicy;
use Sedehi\Section\Commands\SectionRequest;
use Sedehi\Section\Commands\SectionResource;
use Sedehi\Section\Commands\SectionRule;
use Sedehi\Section\Commands\SectionSeed;
use Sedehi\Section\Commands\SectionTest;
use Sedehi\Section\Commands\SectionView;

class SectionServiceProvider extends ArtisanServiceProvider
{

    /**
     * Perform post-registration booting of services.
     * @return void
     */
    public function boot(){

        $this->commands([
                            SectionAdd::class,
                            SectionView::class,
                        ]);
    }

    protected function registerResourceMakeCommand(){

        $this->app->singleton('command.resource.make', function($app){

            return new SectionResource($app['files']);
        });
    }

    protected function registerMailMakeCommand(){

        $this->app->singleton('command.mail.make', function($app){

            return new SectionMail($app['files']);
        });
    }

    protected function registerNotificationMakeCommand(){

        $this->app->singleton('command.notification.make', function($app){

            return new SectionNotification($app['files']);
        });
    }

    protected function registerRequestMakeCommand(){

        $this->app->singleton('command.request.make', function($app){

            return new SectionRequest($app['files']);
        });
    }

    protected function registerJobMakeCommand(){

        $this->app->singleton('command.job.make', function($app){

            return new SectionJob($app['files']);
        });
    }

    protected function registerPolicyMakeCommand(){

        $this->app->singleton('command.policy.make', function($app){

            return new SectionPolicy($app['files']);
        });
    }

    protected function registerFactoryMakeCommand(){

        $this->app->singleton('command.factory.make', function($app){

            return new SectionFactory($app['files']);
        });
    }

    protected function registerEventMakeCommand(){

        $this->app->singleton('command.event.make', function($app){

            return new SectionEvent($app['files']);
        });
    }

    protected function registerSeederMakeCommand(){

        $this->app->singleton('command.seeder.make', function($app){

            return new SectionSeed($app['files'], $app['composer']);
        });
    }

    protected function registerMigrateMakeCommand(){

        $this->app->singleton('command.migrate.make', function($app){

            $creator  = $app['migration.creator'];
            $composer = $app['composer'];

            return new SectionMigration($creator, $composer);
        });
    }

    protected function registerModelMakeCommand(){

        $this->app->singleton('command.model.make', function($app){

            return new SectionModel($app['files']);
        });
    }

    protected function registerTestMakeCommand(){

        $this->app->singleton('command.test.make', function($app){

            return new SectionTest($app['files']);
        });
    }

    protected function registerRuleMakeCommand(){

        $this->app->singleton('command.rule.make', function($app){

            return new SectionRule($app['files']);
        });
    }

    protected function registerControllerMakeCommand(){

        $this->app->singleton('command.controller.make', function($app){

            return new SectionController($app['files']);
        });
    }
}
