<?php

namespace Sedehi\Section;

use Illuminate\Database\MigrationServiceProvider as LaravelMigrationServiceProvider;
use Sedehi\Section\Console\SectionMigration;

class MigrationServiceProvider extends LaravelMigrationServiceProvider
{
    protected function registerMigrateMakeCommand()
    {
        $this->app->singleton('command.migrate.make', function ($app) {
            $creator = $app['migration.creator'];
            $composer = $app['composer'];

            return new SectionMigration($creator, $composer);
        });
    }
}
