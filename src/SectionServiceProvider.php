<?php

namespace Sedehi\Section;

use Illuminate\Foundation\Providers\ArtisanServiceProvider;
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
                            SectionController::class,
                            SectionEvent::class,
                            SectionFactory::class,
                            SectionMigration::class,
                            SectionModel::class,
                            SectionPolicy::class,
                            SectionSeed::class,
                            SectionTest::class,
                            SectionAuth::class,
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
}
