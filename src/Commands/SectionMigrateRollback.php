<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SectionMigrateRollback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:migrate-rollback {--step=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback last migrations with same batch';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (is_null($this->option('step'))) {

            $batch = DB::table(config('database.migrations'))->max('batch');

            if (!is_null($batch)) {

                $migrations = DB::table(config('database.migrations'))->where('batch',$batch)->latest('id')->pluck('migration')->toArray();

                $this->rollback($migrations);

                $this->info('Last batch migrations rolled back successfully.');

                return;
            }

            $this->info('No migrations to rollback.');

            return;
        }

        if (is_numeric($this->option('step')) && ($this->option('step') > 0)) {

            $migrations = DB::table(config('database.migrations'))
                            ->orderBy('batch', 'dsc')
                            ->latest('id')
                            ->pluck('migration')
                            ->take($this->option('step'))->toArray();

            if (count($migrations)) {
                $this->rollback($migrations);
                $this->info('Last '.count($migrations).' migrations rolled back successfully.');
                return;
            } else {
                $this->info('Nothing to do.');
                return;
            }
        }

        $this->info('Nothing to do.');
    }

    private function rollback($migrations)
    {
        Schema::disableForeignKeyConstraints();

        $directoryData = array_sort_recursive(File::directories(app_path('Http/Controllers')));

        foreach ($migrations as $migration) {
            foreach ($directoryData as $directory) {
                if (File::isDirectory($directory.'/database/migrations')) {
                    foreach (File::files($directory.'/database/migrations') as $file) {
                        if (File::name($file) == $migration) {
                            require_once $file;

                            $className = Str::studly(implode('_', array_slice(explode('_', File::name($file)), 4)));
                            $class = new $className;
                            $class->down();

                            DB::table(config('database.migrations'))->where('migration',File::name($file))->delete();

                            $this->info('RolledBack: '.File::name($file));
                        }
                    }
                }
            }
        }

        Schema::enableForeignKeyConstraints();
    }
}
