<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SectionAuth extends Command
{

    use SectionsTrait, \Illuminate\Console\AppNamespaceDetectorTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create auth controllers and views';

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

    private function init()
    {

        $this->makeDirectory($this->argument('section'), 'Controllers/');

        $this->controllerName = ucfirst($this->argument('section')).'/Controllers/'.ucfirst($this->argument('name'));
        $this->namespace      = $this->getAppNamespace().'Http/Controllers/'.ucfirst($this->argument("section")).'/Controllers';

        if ($this->option('site')) {
            $this->makeDirectory($this->argument('section'), 'Controllers/Site/');
            $this->controllerName = ucfirst($this->argument('section')).'/Controllers/Site/'.ucfirst($this->argument('name'));
            $this->namespace      = $this->getAppNamespace().'Http/Controllers/'.ucfirst($this->argument("section")).'/Controllers/Site';
            $this->type           = '.site';
        }

        if ($this->option('admin')) {
            $this->makeDirectory($this->argument('section'), 'Controllers/Admin/');
            $this->controllerName = ucfirst($this->argument('section')).'/Controllers/Admin/'.ucfirst($this->argument('name'));
            $this->namespace      = $this->getAppNamespace().'Http/Controllers/'.ucfirst($this->argument("section")).'/Controllers/Admin';
            $this->type           = '.admin';
        }

        if ($this->option('api')) {
            $this->makeDirectory($this->argument('section'), 'Controllers/Api/');
            $this->controllerName = ucfirst($this->argument('section')).'/Controllers/Api/'.ucfirst($this->argument('name'));
            $this->namespace      = $this->getAppNamespace().'Http/Controllers/'.ucfirst($this->argument("section")).'/Controllers/Api';
            $this->type           = '.api';
        }
    }

    public function handle()
    {
        $this->info('');
        $this->info('Available Choices :');
        $this->info('');
        $this->info('1 => Email Only');
        $this->info('2 => Email And Username');
        $this->info('3 => Email And Mobile');

        $type = null;

        while (!in_array($type,[1,2,3]))
        {
            $type = $this->ask('What type of auth do you want ? ');
        }
//        dd($this->getAppNamespace());

        switch ($type) {
            case 1:

                // Create Controller
                if (File::exists(app_path('Http/Controllers/Auth/Controllers/SiteController.php'))) {
                    $this->warn('SiteController already exists.');
                } else {
                    $this->makeDirectory('Auth','Controllers');
                    $data = File::get(__DIR__.'/Template/auth/email-only/controller/SiteController');
                    $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
                    File::put(app_path('Http/Controllers/Auth/Controllers/SiteController.php'),$data);
                    $this->info('SiteController created successfully.');
                }

                // Create Mail
                if (File::exists(app_path('Http/Controllers/Auth/Mail/PasswordReminderMail.php'))) {
                    $this->warn('PasswordReminderMail already exists.');
                } else {
                    $this->makeDirectory('Auth','Mail');
                    $data = File::get(__DIR__.'/Template/auth/email-only/mail/PasswordReminder');
                    $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
                    File::put(app_path('Http/Controllers/Auth/Mail/PasswordReminderMail.php'),$data);
                    $this->info('PasswordReminderMail created successfully.');
                }

                // Create SiteRequest
                if (File::exists(app_path('Http/Controllers/Auth/Requests/SiteRequest.php'))) {
                    $this->warn('SiteRequest already exists.');
                } else {
                    $this->makeDirectory('Auth','Requests');
                    $data = File::get(__DIR__.'/Template/auth/email-only/request/SiteRequest');
                    $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
                    File::put(app_path('Http/Controllers/Auth/Requests/SiteRequest.php'),$data);
                    $this->info('SiteRequest created successfully.');
                }

                // Create views
                if (File::isDirectory(app_path('Http/Controllers/Auth/views/site'))) {
                    $this->warn('views/site folder already exists.');
                } else {
                    $this->makeDirectory('Auth','views/site');

                    $data = File::get(__DIR__.'/Template/auth/email-only/view/login');
                    File::put(app_path('Http/Controllers/Auth/views/site/login.blade.php'),$data);
                    $this->info('Login view created successfully.');

                    $data = File::get(__DIR__.'/Template/auth/email-only/view/reminder');
                    File::put(app_path('Http/Controllers/Auth/views/site/reminder.blade.php'),$data);
                    $this->info('Reminder view created successfully.');

                    $data = File::get(__DIR__.'/Template/auth/email-only/view/reset-password');
                    File::put(app_path('Http/Controllers/Auth/views/site/reset-password.blade.php'),$data);
                    $this->info('Password reset view created successfully.');

                }

                // Create Route
                if (!File::exists(app_path('Http/Controllers/Auth/route.php'))) {
                    $data = File::get(__DIR__.'/Template/auth/email-only/route');
                    File::put(app_path('Http/Controllers/Auth/route.php'),$data);
                    $this->info('Route file created successfully.');
                }

                break;
        }

        $this->info('Auth files created successfully.');
    }

}
