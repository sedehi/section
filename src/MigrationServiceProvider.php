<?php
namespace Sedehi\Section;

use Sedehi\Section\Console\SectionMigration;
use Illuminate\Database\MigrationServiceProvider as LaravelMigrationServiceProvider;
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
