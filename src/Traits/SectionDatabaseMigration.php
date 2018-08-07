<?php

namespace Sedehi\Section\Traits;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

trait SectionDatabaseMigration
{

    /**
     * Define hooks to migrate the database before and after each test.
     * @return void
     */
    public function runDatabaseMigrations(){

        $this->artisan('section:migrate-fresh');
        $this->app[Kernel::class]->setArtisan(null);
        $this->beforeApplicationDestroyed(function(){

            \Schema::dropAllTables();

            //$this->artisan('section:migrate-rollback');
            RefreshDatabaseState::$migrated = false;
        });
    }
}