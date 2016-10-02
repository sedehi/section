<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SectionMigrateReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:migrate-reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all section migrations';

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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $directoryData = array_sort_recursive(File::directories(app_path('Http/Controllers')));

        foreach ($directoryData as $directory) {
            if (File::isDirectory($directory.'/database/migrations')) {
                foreach (File::files($directory.'/database/migrations') as $file) {

                    $migration = DB::table(config('database.migrations'))->where('migration',File::name($file))->first();

                    if (!is_null($migration)) {

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

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('All migrations rolled back successfully.');
    }
}
