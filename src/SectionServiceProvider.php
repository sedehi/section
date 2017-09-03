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
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('sedehi::command.section.make', function ($app) {
            return new SectionAdd();
        });
        $this->app->bind('sedehi::command.section.controller', function ($app) {
            return new SectionController();
        });
        $this->app->bind('sedehi::command.section.event', function ($app) {
            return new SectionEvent();
        });
        $this->app->bind('sedehi::command.section.factory', function ($app) {
            return new SectionFactory();
        });
        $this->app->bind('sedehi::command.section.job', function ($app) {
            return new SectionJob();
        });
        $this->app->bind('sedehi::command.section.rule', function ($app) {
            return new SectionRule();
        });
        $this->app->bind('sedehi::command.section.migrate', function ($app) {
            return new SectionMigrate();
        });
        $this->app->bind('sedehi::command.section.migrate-fresh', function ($app) {
            return new SectionMigrateFresh();
        });
        $this->app->bind('sedehi::command.section.migrate-refresh', function ($app) {
            return new SectionMigrateRefresh();
        });
        $this->app->bind('sedehi::command.section.migrate-reset', function ($app) {
            return new SectionMigrateReset();
        });
        $this->app->bind('sedehi::command.section.migrate-rollback', function ($app) {
            return new SectionMigrateRollback();
        });
        $this->app->bind('sedehi::command.section.migration', function ($app) {
            return new SectionMigration();
        });
        $this->app->bind('sedehi::command.section.model', function ($app) {
            return new SectionModel();
        });
        $this->app->bind('sedehi::command.section.policy', function ($app) {
            return new SectionPolicy();
        });
        $this->app->bind('sedehi::command.section.request', function ($app) {
            return new SectionRequest();
        });
        $this->app->bind('sedehi::command.section.resource', function ($app) {
            return new SectionResource();
        });
        $this->app->bind('sedehi::command.section.seed', function ($app) {
            return new SectionSeed();
        });
        $this->app->bind('sedehi::command.section.test', function ($app) {
            return new SectionTest();
        });
        $this->app->bind('sedehi::command.section.auth', function ($app) {
            return new SectionAuth();
        });
        $this->app->bind('sedehi::command.section.notification', function ($app) {
            return new SectionNotification();
        });
        $this->app->bind('sedehi::command.section.mail', function ($app) {
            return new SectionMail();
        });
        $this->app->bind('sedehi::command.section.view', function ($app) {
            return new SectionView();
        });
        $this->app->bind('sedehi::section:update-roles', function ($app) {
            return new SectionUpdateRoles();
        });
        $this->app->bind('sedehi::section:picture', function ($app) {
            return new SectionPicture();
        });

        $this->commands([
                            'sedehi::command.section.make',
                            'sedehi::command.section.controller',
                            'sedehi::command.section.event',
                            'sedehi::command.section.factory',
                            'sedehi::command.section.job',
                            'sedehi::command.section.rule',
                            'sedehi::command.section.migrate',
                            'sedehi::command.section.migrate-fresh',
                            'sedehi::command.section.migrate-refresh',
                            'sedehi::command.section.migrate-reset',
                            'sedehi::command.section.migrate-rollback',
                            'sedehi::command.section.migration',
                            'sedehi::command.section.model',
                            'sedehi::command.section.policy',
                            'sedehi::command.section.request',
                            'sedehi::command.section.resource',
                            'sedehi::command.section.seed',
                            'sedehi::command.section.test',
                            'sedehi::command.section.auth',
                            'sedehi::command.section.notification',
                            'sedehi::command.section.mail',
                            'sedehi::command.section.view',
                            'sedehi::command.section.view',
                            'sedehi::section:update-roles',
                            'sedehi::section:picture',
                        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
