<?php

namespace Sedehi\Section\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SectionMigrateRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:migrate-refresh {section? : The name of the section}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh one section or all migrations';

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
//        if (!is_null($this->argument('section'))) {
//
//            Artisan::call('migrate:refresh', [
//                '--path'   => 'app/Http/Controllers/'.ucfirst($this->argument('section')).'/database/migrations/',
//            ]);
//
//            $this->info(ucfirst($this->argument('section')).' migrations refreshed successfully.');
//            return;
//        }
//
//        $this->info('All migrations refreshed successfully.');

//        if (Schema::hasTable(config('database.migrations'))) {
//            $data = DB::table(config('database.migrations'))->pluck('migration')->toArray();
//        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $directoryData = array_sort_recursive(File::directories(app_path('Http/Controllers')));

        foreach ($directoryData as $directory) {
            if (File::isDirectory($directory.'/database/migrations')) {

                $fileCount = count(File::files($directory.'/database/migrations'));

                if ($fileCount) {
                    Artisan::call('migrate:rollback', [
                        '--path'   => strstr($directory.'/database/migrations/','app'),
                        '--step'   => $fileCount
                    ]);
                }
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        dd('done');

    }
}
