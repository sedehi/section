<?php

namespace Sedehi\Section;

use Illuminate\Support\ServiceProvider;
use Sedehi\Section\Commands\SectionAdd;
use Sedehi\Section\Commands\SectionAuth;
use Sedehi\Section\Commands\SectionController;
use Sedehi\Section\Commands\SectionEvent;
use Sedehi\Section\Commands\SectionFactory;
use Sedehi\Section\Commands\SectionJob;
use Sedehi\Section\Commands\SectionMail;
use Sedehi\Section\Commands\SectionMigrate;
use Sedehi\Section\Commands\SectionMigrateFresh;
use Sedehi\Section\Commands\SectionMigrateRefresh;
use Sedehi\Section\Commands\SectionMigrateReset;
use Sedehi\Section\Commands\SectionMigrateRollback;
use Sedehi\Section\Commands\SectionMigration;
use Sedehi\Section\Commands\SectionModel;
use Sedehi\Section\Commands\SectionNotification;
use Sedehi\Section\Commands\SectionPicture;
use Sedehi\Section\Commands\SectionPolicy;
use Sedehi\Section\Commands\SectionRequest;
use Sedehi\Section\Commands\SectionResource;
use Sedehi\Section\Commands\SectionRule;
use Sedehi\Section\Commands\SectionSeed;
use Sedehi\Section\Commands\SectionTest;
use Sedehi\Section\Commands\SectionUpdateRoles;
use Sedehi\Section\Commands\SectionView;

class SectionServiceProvider extends ServiceProvider
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
                            SectionJob::class,
                            SectionMigration::class,
                            SectionModel::class,
                            SectionPolicy::class,
                            SectionRequest::class,
                            SectionResource::class,
                            SectionSeed::class,
                            SectionTest::class,
                            SectionAuth::class,
                            SectionNotification::class,
                            SectionMail::class,
                            SectionView::class,
                        ]);
    }

    /**
     * Register any package services.
     * @return void
     */
    public function register(){
        //
    }
}
